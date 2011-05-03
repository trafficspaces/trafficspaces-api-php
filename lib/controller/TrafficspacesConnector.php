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

	public function list($params) {
	    $xml = $this->sendRequest("{$this->resource_path}?{$this->toQueryString($params)}", "XML");
  		$all_resources = new SimpleXMLElement($xml);
	    
	    $resource_objects = array();
	    foreach ($all_resources as $resource_xml) {
			array_push($resource_objects, $this->resource_class->newInstance($resource_xml));
	    }
	    return $resource_objects;
	}

	public function find($id) {
		$xml = $this->sendRequest("{$this->resource_path}/{$id}.xml", "XML");
		return $this->resource_class->newInstance(new SimpleXMLElement($xml));
	}

	public function create($resource) {
		$xml = $this->sendRequest("{$this->resource_path}", "XML", "POST", $resource->getXML());
		return $this->resource_class->newInstance(new SimpleXMLElement($xml));
	}

	public function update($resource) {
		$xml = $this->sendRequest("{$this->resource_path}/{$resource->id}.xml", "XML", "PUT", $resource->getXML());
		return $this->resource_class->newInstance(new SimpleXMLElement($xml));
	}
	
	public function delete($id) {
		$this->sendRequest("{$this->resource_path}/{$id}.xml",  "XML", "DELETE");
		return true;
	}

	/****************************************************
	 **********       UTILITY FUNCTIONS       ***********
	 ****************************************************/
	
	private function sendRequest($uri, $format = 'XML', $method = 'GET', $data = '') {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->end_point->base_uri . $uri);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $contentType = $format == 'XML' ? "application/xml" : "application/json";
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
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

		if ($result->code == 200 || $result->code == 201) {
        	return $result->response;
		} else {
	        if ($curl_error) {
	        
	            throw new TrafficspacesConnectionException('An error occurred while connecting to Trafficspaces: ' . $curl_error);
	        } else if ($xml->code == 403) { //FORBIDDEN
	        
				throw new TrafficspacesException('{$method} is not supported for this resource through the Trafficspaces API.',$result->code);
			} else if ($xml->code == 404) { //NOT FOUND
			
				$errors = new SimpleXMLElement($xml->response);
				throw new TrafficspacesNotFoundException($xml->code, $errors);
	        } else if ($result->code ==== 422) { //UNPROCESSABLE ENTITY
	        
				$errors = new SimpleXMLElement($result->response);
				throw new TrafficspacesValidationException($result->code, $errors);
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
?>
