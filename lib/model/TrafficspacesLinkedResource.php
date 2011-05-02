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
class TrafficspacesLinkedResource extends TrafficspacesResource {

	//******************************
	//****** OUTPUT VARIABLES ******
	//******************************
	var $id;
	var $name;

	//******************************
	//****** PRIVATE VARIABLES *****
	//******************************
	private $resource_name;
	
	public function __construct(SimpleXMLElement $linked_resource_xml = null, $resource_name) {
		if ($linked_resource_xml) {
	    	// Load object dynamically and convert SimpleXMLElements into strings
	    	foreach ($linked_resource_xml as $key => $element) {
	    		$this->$key  = (string) $element;
		    }
		}
		$this->$resource_name = $resource_name;
	}

	protected function getName() {
  		return $resource_name;
  	}
}

?>