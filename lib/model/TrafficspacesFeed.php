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
 ** Reference Documentation: http://support.trafficspaces.com/kb/api/api-feeds
 **/
class TrafficspacesFeed extends TrafficspacesResource {
	//******************************
	//** INPUT & OUTPUT VARIABLES **
	//******************************
	var $name;
	var $width;
	var $height;
	var $channel;
	var $provider;
	var $ad_tag;
	var $linked_user;
	
	//******************************
	//*** OUTPUT ONLY VARIABLES ****
	//******************************
	var $id;
	var $realm;
	var $creation_date;
	var $last_modified_date;

	//******************************
	//*** OTHER CONSTANTS 		****
	//******************************
	
	const LINKED_USER_RESOURCE_NAME			= "linked_user";
	
	public function __construct(SimpleXMLElement $feed_xml = null) {
		if ($feed_xml) {
	    	// Load object dynamically and convert SimpleXMLElements into strings
	    	foreach ($feed_xml as $key => $element) {
			  	if ($key == TrafficspacesFeed::LINKED_USER_RESOURCE_NAME) {
		  			$this->linked_user = new TrafficspacesLinkedResource($element, $key);
			  	} else {	
	    			$this->$key = (string) $element;
	    		}
		    }
		}
	}

	public static function createFeed($name, $width, $height, $weight, $ad_tag) {
		$feed = new TrafficspacesFeed();
		$feed->name = $name;
		$feed->width = $width;
		$feed->height = $height;
		$feed->weight = $weight;
		$feed->ad_tag = $ad_tag;
		return $feed;
	}
	
	protected function getName() {
  		return "feed";
  	}
}

?>