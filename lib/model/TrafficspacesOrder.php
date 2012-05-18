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
 ** Reference Documentation: http://support.trafficspaces.com/kb/api/api-orders
 **/
class TrafficspacesOrder extends TrafficspacesResource {
	//******************************
	//** INPUT & OUTPUT VARIABLES **
	//******************************
	var $priority;
	var $gross_purchase_price;
	var $net_purchase_price;
	var $maximum_bid_price;
	var $total_volume;
	var $daily_volume;	
	var $start_date;
	var $end_date;
	var $linked_user;
	var $linked_zone;
	var $linked_campaign;
	var $order_statistic;

	//******************************
	//*** OUTPUT ONLY VARIABLES ****
	//******************************
	var $id;
	var $name;
	var $model;
	var $status;
	var $filled_volume;
	var $average_bid_price;
	var $scheduled_dates;
	var $realm;
	var $last_modified_date;

	//******************************
	//*** OTHER CONSTANTS 		****
	//******************************
	
	const LINKED_ZONE_RESOURCE_NAME			= "linked_zone";
	const LINKED_CAMPAIGN_RESOURCE_NAME		= "linked_campaign";
	const LINKED_USER_RESOURCE_NAME			= "linked_user";
	
	public function __construct(SimpleXMLElement $order_xml = null) {
		if ($order_xml) {
	    	// Load object dynamically and convert SimpleXMLElements into strings
	    	foreach ($order_xml as $key => $element) {
				if ($key == "order_statistic") {
		  			$this->order_statistic = new TrafficspacesOrderStatistic($element);
			  	} else if ($key == TrafficspacesOrder::LINKED_USER_RESOURCE_NAME) {
		  			$this->linked_user = new TrafficspacesLinkedResource($element, $key);
			  	} else if ($key == TrafficspacesOrder::LINKED_ZONE_RESOURCE_NAME) {
		  			$this->linked_zone = new TrafficspacesLinkedResource($element, $key);
			  	} else if ($key == TrafficspacesOrder::LINKED_CAMPAIGN_RESOURCE_NAME) {
		  			$this->linked_campaign = new TrafficspacesLinkedResource($element, $key);
			  	} else {	
	    			$this->$key = (string) $element;
	    		}
		    }
		}
	}
	
	public static function createOrder($price, $total_volume, $daily_volume, $start_date, $end_date, 
			$linked_zone, $linked_campaign) {
		$order = new TrafficspacesOrder();
		$order->gross_purchase_price = $price;
		$order->net_purchase_price = $price;
		$order->maximum_bid_price = $price;
		$order->total_volume = $total_volume;
		$order->daily_volume = $daily_volume;
		$order->start_date = $start_date;
		$order->end_date = $end_date;
		$order->linked_zone = $linked_zone;
		$order->linked_campaign = $linked_campaign;
		return $order;
	}
	
	protected function getName() {
  		return "order";
  	}
}

class TrafficspacesOrderStatistic extends TrafficspacesResource {
	//******************************
	//**** OUTPUT ONLY VARIABLES ***
	//******************************
	var $hits;
	var $uniques;
	var $clicks;
	var $conversions;
	var $views;
	var $duration;
	var $average_conversion_amount;
	var $days;
	var $date;
	var $linked_order;

	//******************************
	//*** OTHER CONSTANTS 		****
	//******************************
	
	const LINKED_ORDER_RESOURCE_NAME		= "linked_order";
	
	public function __construct(SimpleXMLElement $order_statistic_xml = null) {
		if ($order_statistic_xml) {
	    	// Load object dynamically and convert SimpleXMLElements into strings
	    	foreach ($order_statistic_xml as $key => $element) {
				if ($key == TrafficspacesOrderStatistic::LINKED_ORDER_RESOURCE_NAME) {
		  			$this->linked_order = new TrafficspacesLinkedResource($element, $key);
			  	} else {	
	    			$this->$key = (string) $element;
	    		}
		    }
		}
	}
	
	protected function getName() {
  		return "order_statistic";
  	}
}
?>