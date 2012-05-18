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
class TrafficspacesResource {
	public function getXMLObject(&$xml = null) {
	  	if ($xml === null) {
			$xml = simplexml_load_string(sprintf("<?xml version='1.0' encoding='utf-8'?><%s></%s>", $this->getName(), $this->getName()));
	  	}
	  	foreach (get_object_vars($this) as $key=>$val) {
	  		if ($key != 'connector') {
		  		if (is_object($val) && method_exists($val, "getXMLObject")) {
		  			$node = $xml->addChild($key);
		  			$val->getXMLObject($node);
		  		} else if (is_array($val)) {
		  			$node = $xml->addChild($key);
		  			foreach ($val as $v) {
		  				if (method_exists($v, "getXMLObject") && method_exists($v, "getName")) {
		  					$sub_node = $node->addChild($v->getName());
		  					$v->getXMLObject($sub_node);
		  				}
		  			}
		  		} else if ($val !== null) {
		  			$xml->addChild($key, htmlentities($val, ENT_QUOTES));
		  		}
	  		}
	  	}
	  	return $xml;
	}

	public function getXML($include_header = true) {
		$xml = $this->getXMLObject()->asXML();
		if (!$include_header) {
			$xml = preg_replace('/^(<\?xml)(.)*(\?>)/', "", $xml);
		}
		return $xml;
	}

	public function getJSON() {
		return sprintf('{"%s":%s}', $this->getName(), json_encode($this->getXMLObject()));
	}
}
?>