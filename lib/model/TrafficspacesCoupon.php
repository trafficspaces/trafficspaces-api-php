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
 ** Reference Documentation: http://support.trafficspaces.com/kb/api/api-coupons
 **/
class TrafficspacesCoupon extends TrafficspacesResource {
	//******************************
	//** INPUT & OUTPUT VARIABLES **
	//******************************
	var $name;
	var $code;
	var $base_value;
	var $discount_value;
	var $maximum_cumulative_discount;
	var $maximum_cumulative_uses;
	var $linked_user;
	
	//******************************
	//*** OUTPUT ONLY VARIABLES ****
	//******************************
	var $id;
	var $realm;
	var $type;
	var $cumulative_discount;
	var $cumulative_uses;
	var $creation_date;
	var $last_modified_date;
	var $expiration_date;

	//******************************
	//*** OTHER CONSTANTS 		****
	//******************************
	
	const LINKED_USER_RESOURCE_NAME			= "linked_user";
	
	public function __construct(SimpleXMLElement $coupon_xml = null) {
		if ($coupon_xml) {
	    	// Load object dynamically and convert SimpleXMLElements into strings
	    	foreach ($coupon_xml as $key => $element) {
			  	if ($key == TrafficspacesCoupon::LINKED_USER_RESOURCE_NAME) {
		  			$this->linked_user = new TrafficspacesLinkedResource($element, $key);
			  	} else {	
	    			$this->$key = (string) $element;
	    		}
		    }
		}
	}
	
	public static function createAbsoluteCoupon($name, $code, $base_value, $discount_value) {
		return TrafficspacesCoupon::createCoupon($name, $code, $base_value, $discount_value, "absolute");
	}
	
	public static function createRelativeCoupon($name, $code, $base_value, $discount_value) {
		return TrafficspacesCoupon::createCoupon($name, $code, $base_value, $discount_value, "relative");
	}
	
	public static function createCoupon($name, $code, $base_value, $discount_value, $type) {
		$coupon = new TrafficspacesCoupon();
		$coupon->name = $name;
		$coupon->code = $code;
		$coupon->base_value = $base_value;
		$coupon->discount_value = $discount_value;
		$coupon->type = $type;
		return $coupon;
	}
	
	protected function getName() {
  		return "coupon";
  	}
}

?>