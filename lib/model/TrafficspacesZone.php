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
class TrafficspacesZone extends TrafficspacesResource {
	//******************************
	//** INPUT & OUTPUT VARIABLES **
	//******************************
	var $name;
	var $width;
	var $height;
	var $formats;
	var $language;
	var $channel;
	var $position;
	var $scope;
	var $info_url;
	var $preview_url;
	var $default_ad_tag;
	var $description;
	var $linked_user;
	var $pricing;
	var $zone_statistic;

	//******************************
	//*** OUTPUT ONLY VARIABLES ****
	//******************************
	var $id;
	var $realm;
	var $creation_date;
	var $last_modified_date;

	public function __construct(SimpleXMLElement $zone_xml = null) {
		if ($zone_xml) {
	    	// Load object dynamically and convert SimpleXMLElements into strings
	    	foreach ($zone_xml as $key => $element) {
				if ($key == "pricing") {
		  			$this->pricing = new TrafficspacesZonePricing($element);
			  	} else if ($key == "zone_statistic") {
		  			$this->zone_statistic = new TrafficspacesZoneStatistic($element);
			  	} else if ($key == "linked_user") {
		  			$this->linked_user = new TrafficspacesLinkedResource($element, "linked_user");
			  	} else {	
	    			$this->$key = (string) $element;
	    		}
		    }
		}
	}
	
	protected function getName() {
  		return "zone";
  	}
}

class TrafficspacesZonePricing extends TrafficspacesResource {
	//******************************
	//** INPUT & OUTPUT VARIABLES **
	//******************************
	var $model;
	var $accept_bids;
	var $price;
	var $volume_minimum;
	var $volume_maximum;
	var $volume_minimum;
	var $order_concurrency;
	var $discounts;

	public function __construct(SimpleXMLElement $pricing_xml = null) {
		if ($pricing_xml) {
	    	// Load object dynamically and convert SimpleXMLElements into strings
	    	foreach ($pricing_xml as $key => $element) {
				if ($key == "discounts") {
					$this->discounts = array();
					foreach ($element->children() as $sub_element) {
						array_push($this->discounts, new TrafficspacesZonePricingDiscount($sub_element));
					}
			  	} else {	
	    			$this->$key = (string) $element;
	    		}
		    }
		}
	}
	
	protected function getName() {
  		return "pricing";
  	}
}

class TrafficspacesZonePricingDiscount extends TrafficspacesResource {
	//******************************
	//** INPUT & OUTPUT VARIABLES **
	//******************************
	var $discount_rate;
	var $volume_minimum;

	public function __construct(SimpleXMLElement $discount_xml = null) {
		if ($discount_xml) {
	    	// Load object dynamically and convert SimpleXMLElements into strings
	    	foreach ($discount_xml as $key => $element) {
	    		$this->$key = (string) $element;
		    }
		}
	}
	
	protected function getName() {
  		return "discount";
  	}
}

class TrafficspacesZoneStatistic extends TrafficspacesResource {
	//******************************
	//**** OUTPUT ONLY VARIABLES ***
	//******************************
	var $hits;
	var $uniques;
	var $clicks;
	var $conversions;
	var $views;
	var $duration;
	var $very_short_stay_uniques;
	var $short_stay_uniques;
	var $medium_stay_uniques;
	var $long_stay_uniques;
	var $very_long_stay_uniques;
	var $date;
	var $linked_zone;

	public function __construct(SimpleXMLElement $zone_statistic_xml = null) {
		if ($zone_statistic_xml) {
	    	// Load object dynamically and convert SimpleXMLElements into strings
	    	foreach ($zone_statistic_xml as $key => $element) {
				if ($key == "linked_zone") {
		  			$this->linked_zone = new TrafficspacesLinkedResource($element, "linked_zone");
			  	} else {	
	    			$this->$key = (string) $element;
	    		}
		    }
		}
	}
	
	protected function getName() {
  		return "zone_statistic";
  	}
}
?>