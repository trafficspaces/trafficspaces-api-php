<?php
/**
 ** Copyright (c) 2011 Trafficspaces Inc.
 ** 
 ** Permission is hereby granted, free of charge, to any person obtaining a copy
 ** of this software and associated documentation files (the "Software"), to deal
 ** in the Software without restriction, including without limitation the rights
 ** to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 ** copies of the Software, and to permit persons to whom the Software is
 ** furnished to do so, subject to the following conditions:
 ** 
 ** The above copyright notice and this permission notice shall be included in
 ** all copies or substantial portions of the Software.
 ** 
 ** THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 ** IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 ** FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 ** AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 ** LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 ** OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 ** THE SOFTWARE.
 ** 
 ** Reference Documentation: http://support.trafficspaces.com/kb/api/api-introduction
 **/
class TrafficspacesConnector {
	private $end_point;
	private $resource_path;
	private $resource_class;
	
	public function __construct(TrafficspacesEndPoint $end_point, $resource_path, $resource_class_name) {
		$this->end_point		= $end_point;
		$this->resource_path	= $resource_path;
		$this->resource_class	= new ReflectionClass($resource_class_name);
	}

	/****************************************************
	 ***********       CRUD FUNCTIONS       *************
	 ****************************************************/

	public function find($params) {
	    $xml = $this->sendRequest("{$this->resource_path}?{$this->toQueryString($params)}", "application/xml");
  		$all_resources = new SimpleXMLElement($xml);
	    
	    $resource_objects = array();
	    foreach ($all_resources as $resource_xml) {
			array_push($resource_objects, $this->resource_class->newInstance($resource_xml));
	    }
	    return $resource_objects;
	}

	public function read($id) {
		$xml = $this->sendRequest("{$this->resource_path}/{$id}.xml", "application/xml");
		return $xml != null ? $this->resource_class->newInstance(new SimpleXMLElement($xml)) : null;
	}

	public function create($resource) {
		$xml = $this->sendRequest("{$this->resource_path}", "application/xml", "POST", $resource->getXML());
		return $this->resource_class->newInstance(new SimpleXMLElement($xml));
	}

	public function update($resource) {
		$xml = $this->sendRequest("{$this->resource_path}/{$resource->id}.xml", "application/xml", "PUT", $resource->getXML());
		return $this->resource_class->newInstance(new SimpleXMLElement($xml));
	}
	
	public function delete($id) {
		$this->sendRequest("{$this->resource_path}/{$id}.xml",  "application/xml", "DELETE");
		return true;
	}

	/****************************************************
	 **********       UTILITY FUNCTIONS       ***********
	 ****************************************************/
	
	public function sendRequest($uri, $contentType = 'application/xml', $method = 'GET', $data = '') {
        $ch = curl_init();

        $headerWrapper = new ResponseHeaderWrapper();
        curl_setopt_array($ch, array( 
        		CURLOPT_URL => $this->end_point->base_uri . $uri,
        		CURLOPT_SSL_VERIFYPEER => false,
        		CURLOPT_SSL_VERIFYHOST => 2,
        		CURLOPT_FOLLOWLOCATION => true,
        		CURLOPT_MAXREDIRS => 1,
        		CURLOPT_RETURNTRANSFER => true,
        		//CURLOPT_BUFFERSIZE => 1048576,
        		CURLOPT_HEADERFUNCTION => array($headerWrapper, "readHeader")
        	)
        );
        
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        	"User-Agent: trafficspaces-api-php",
	    	"Content-Type: {$contentType}; charset=UTF-8",
	        "Accept: {$contentType}"
	    ));
		
		if (!empty($this->end_point->username)  && !empty($this->end_point->password)) {
        	curl_setopt($ch, CURLOPT_USERPWD, $this->end_point->username . ':' . $this->end_point->password);
        }

        $method = strtoupper($method);
        if($method == 'POST') {
        
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);	
        } else if ($method == 'PUT') {
        
	        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
	        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    	} else if($method != 'GET') {
    	
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        }

        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $result = new StdClass();
        $result->response = curl_exec($ch);
        $result->code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $result->meta = curl_getinfo($ch);

        $curl_error = ($result->code > 0 ? null : curl_error($ch) . ' (' . curl_errno($ch) . ')');

        curl_close($ch);
        
        $headers = $headerWrapper->getHeaders();
        
		if ($result->code == 200) {
        	return $result->response;
		} else if ($result->code == 201 && array_key_exists("Location", $headers)) {
        	return $this->sendRequest($headers["Location"], $contentType, "GET");
		} else {
	        if ($curl_error) {
	        
	            throw new TrafficspacesConnectionException('An error occurred while connecting to Trafficspaces: ' . $curl_error);
	        } else if ($result->code == 403) { //FORBIDDEN
	        
				throw new TrafficspacesException('{$method} is not supported for this resource through the Trafficspaces API.',$result->code);
			} else if ($result->code == 404) { //NOT FOUND
			
				//$errors = new SimpleXMLElement($result->response);
				//throw new TrafficspacesNotFoundException($result->code, $errors);
				return null;
	        } else if ($result->code === 422) { //UNPROCESSABLE ENTITY
	        
				$errors = new SimpleXMLElement($result->response);
				throw new TrafficspacesValidationException($result->code, $errors);
			} else if ($result->code >= 500) { // SERVER ERROR
	        
				throw new TrafficspacesException($result->response, $result->code);
			}
		}
	}
	
	private function toQueryString($paramsArray) {
		$queryString = "";
		foreach ($paramsArray as $key => $value) {
			$queryString .= urlencode($key) . "=" . urlencode($value) . "&";
		}
		return $queryString;
	}	
}
class ResponseHeaderWrapper {
	private $headerBuf = "";
	public function readHeader($ch, $header) {
		$this->headerBuf .= $header;
		return strlen($header);
	}
	public function getHeaders() {
		return http_parse_headers($this->headerBuf);
	}
}
?>
