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
 ** Reference Documentation: http://support.trafficspaces.com/kb/api/api-placements
 **/
class TrafficspacesPlacement extends TrafficspacesResource {
	//******************************
	//** INPUT & OUTPUT VARIABLES **
	//******************************
	var $handle;
	
	//******************************
	//*** INPUT ONLY VARIABLES  ****
	//******************************
	var $medium;
	var $count;
	var $useiframe;
	var $frame;
	var $title;

	//******************************
	//*** OUTPUT ONLY VARIABLES ****
	//******************************
	var $ads;
	
	public function __construct(SimpleXMLElement $placement_xml = null) {
		if ($placement_xml) {
	    	// Load object dynamically and convert SimpleXMLElements into strings
	    	foreach ($placement_xml as $key => $element) {
				if ($key == "ads") {
					$this->ads = array();
					foreach ($element->children() as $sub_element) {
						array_push($this->ads, new TrafficspacesPlacementAd($sub_element));
					}
			  	} else {	
	    			$this->$key = (string) $element;
	    		}
	    	}
		}
	}
	
	public static function createPlacement($handle, $medium = null, $count = 1) {
		$placement = new TrafficspacesPlacement();
		$placement->handle = $handle;
		$placement->medium = $medium;
		$placement->count = $count;
		return $placement;
	}
	
	protected function getName() {
  		return "placement";
  	}
}

class TrafficspacesPlacementAd extends TrafficspacesResource {
	//******************************
	//*** OUTPUT ONLY VARIABLES ****
	//******************************
	var $medium;
	var $width;
	var $height;
	var $creative;

	public function __construct(SimpleXMLElement $ad_xml = null) {
		if ($ad_xml) {
	    	// Load object dynamically and convert SimpleXMLElements into strings
	    	foreach ($ad_xml as $key => $element) {
				if ($key == "creative") {
		  			$this->creative = new TrafficspacesPlacementAdCreative($element);
			  	} else {	
	    			$this->$key = (string) $element;
	    		}
		    }
		}
	}

	protected function getName() {
  		return "ad";
  	}
}

class TrafficspacesPlacementAdCreative extends TrafficspacesResource {
	//******************************
	//*** OUTPUT ONLY VARIABLES ****
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

	protected function getName() {
  		return "creative";
  	}
}
?>