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
 ** Reference Documentation: http://support.trafficspaces.com/kb/api/api-ads
 **/
class TrafficspacesAd extends TrafficspacesResource {
	//******************************
	//** INPUT & OUTPUT VARIABLES **
	//******************************
	var $name;
	var $width;
	var $height;
	var $status;
	var $format;
	var $creative;
	var $linked_user;
	var $linked_contact;
	var $linked_targeting_plan;

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
	
	const LINKED_USER_RESOURCE_NAME				= "linked_user";
	const LINKED_CONTACT_RESOURCE_NAME			= "linked_contact";
	const LINKED_TARGETING_PLAN_RESOURCE_NAME	= "linked_targeting_plan";
	
	public function __construct(SimpleXMLElement $ad_xml = null) {
		if ($ad_xml) {
	    	// Load object dynamically and convert SimpleXMLElements into strings
	    	foreach ($ad_xml as $key => $element) {
				if ($key == "creative") {
		  			$this->creative = new TrafficspacesAdCreative($element);
			  	} else if ($key == TrafficspacesAd::LINKED_USER_RESOURCE_NAME) {
		  			$this->linked_user = new TrafficspacesLinkedResource($element, $key);
			  	} else if ($key == TrafficspacesAd::LINKED_CONTACT_RESOURCE_NAME) {
		  			$this->linked_contact = new TrafficspacesLinkedResource($element, $key);
			  	} else if ($key == TrafficspacesAd::LINKED_TARGETING_PLAN_RESOURCE_NAME) {
		  			$this->linked_targeting_plan = new TrafficspacesLinkedResource($element, $key);
			  	} else {	
	    			$this->$key = (string) $element;
	    		}
		    }
		}
	}
	
	public static function createAd($name, $width, $height, $format, $creative, 
			$linked_user = null, $linked_contact = null, $linked_targeting_plan = null) {
		$ad = new TrafficspacesAd();
		$ad->name = $name;
		$ad->width = $width;
		$ad->height = $height;
		$ad->format = $format;
		$ad->creative = $creative;
		$ad->linked_user = $linked_user;
		$ad->linked_contact = $linked_contact;
		$ad->linked_targeting_plan = $linked_targeting_plan;
		return $ad;
	}
	

	protected function getName() {
  		return "ad";
  	}
}

class TrafficspacesAdCreative extends TrafficspacesResource {
	//******************************
	//** INPUT & OUTPUT VARIABLES **
	//******************************
	var $flash_url;
	var $video_url;
	var $audio_url;
	var $image_url;
	var $title;
	var $caption;
	var $anchor;
	var $raw;
	var $target_url;
	
	public function __construct(SimpleXMLElement $creative_xml = null) {
		if ($creative_xml) {
	    	// Load object dynamically and convert SimpleXMLElements into strings
	    	foreach ($creative_xml as $key => $element) {
    			$this->$key  = (string) $element;
		    }
		}
	}

	public static function createTextCreative($title, $caption, $anchor, $image_url, $target_url) {
		$creative = new TrafficspacesAdCreative();
		$creative->title = $title;
		$creative->caption = $caption;
		$creative->anchor = $anchor;
		$creative->image_url = $image_url;
		$creative->target_url = $target_url;
		return $creative;
	}

	public static function createImageCreative($image_url, $target_url) {
		$creative = new TrafficspacesAdCreative();
		$creative->image_url = $image_url;
		$creative->target_url = $target_url;
		return $creative;
	}
		
	public static function createFlashCreative($flash_url, $target_url) {
		$creative = new TrafficspacesAdCreative();
		$creative->flash_url = $flash_url;
		$creative->target_url = $target_url;
		return $creative;
	}
		
	public static function createAudioCreative($audio_url, $target_url) {
		$creative = new TrafficspacesAdCreative();
		$creative->audio_url = $audio_url;
		$creative->target_url = $target_url;
		return $creative;
	}

	public static function createVideoCreative($video_url, $target_url) {
		$creative = new TrafficspacesAdCreative();
		$creative->video_url = $video_url;
		$creative->target_url = $target_url;
		return $creative;
	}

	public static function createRawCreative($raw, $target_url) {
		$creative = new TrafficspacesAdCreative();
		$creative->raw = raw;
		$creative->target_url = $target_url;
		return $creative;
	}
	
	protected function getName() {
  		return "creative";
  	}
}
?>