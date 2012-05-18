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
 ** Reference Documentation: http://support.trafficspaces.com/kb/api/api-contacts
 **/
class TrafficspacesContact extends TrafficspacesResource {
	//******************************
	//** INPUT & OUTPUT VARIABLES **
	//******************************
	var $name;
	var $profile;
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

	public function __construct(SimpleXMLElement $contact_xml = null) {
		if ($contact_xml) {
	    	// Load object dynamically and convert SimpleXMLElements into strings
	    	foreach ($contact_xml as $key => $element) {
				if ($key == "profile") {
		  			$this->profile = new TrafficspacesContactProfile($element);
			  	} else if ($key == TrafficspacesContact::LINKED_USER_RESOURCE_NAME) {
		  			$this->linked_user = new TrafficspacesLinkedResource($element, $key);
			  	} else {	
	    			$this->$key = (string) $element;
	    		}
		    }
		}
	}
	
	public static function createContact($name, $profile, $linked_user) {
		$contact = new TrafficspacesContact();
		$contact->name = $name;
		$contact->profile = $profile;
		$contact->linked_user = $linked_user;
		return $contact;
	}

	protected function getName() {
  		return "contact";
  	}
}

class TrafficspacesContactProfile extends TrafficspacesResource {
	
	const TYPE_ADVERTISER = 0;
		
	const TYPE_PUBLISHER = 1;
	
	//******************************
	//** INPUT & OUTPUT VARIABLES **
	//******************************
	var $reference;
	var $company_name;
	var $website;
	var $email;
	var $type;
	var $contact_details;

	public function __construct(SimpleXMLElement $profile_xml = null) {
		if ($profile_xml) {
	    	// Load object dynamically and convert SimpleXMLElements into strings
	    	foreach ($profile_xml as $key => $element) {
				if ($key == "contact_details") {
		  			$this->contact_details = new TrafficspacesContactProfileContactDetails($element);
			  	} else {	
	    			$this->$key  = (string) $element;
	    		}
		    }
		}
	}
	
	public static function createContactProfile($email, $company_name, $type) {
		$profile = new TrafficspacesContactProfile(); 
		$profile->email = $email;
		$profile->company_name = $company_name;
		$profile->type = $type;
		return $profile;
	}

	protected function getName() {
  		return "profile";
  	}
}

class TrafficspacesContactProfileContactDetails extends TrafficspacesResource {
	//******************************
	//** INPUT & OUTPUT VARIABLES **
	//******************************
	var $street;
	var $street2;
	var $city;
	var $state;
	var $zip;
	var $country;
	var $mobile;
	var $telephone;
	var $fax;

	public function __construct(SimpleXMLElement $contact_details_xml = null) {
		if ($contact_details_xml) {
	    	// Load object dynamically and convert SimpleXMLElements into strings
	    	foreach ($contact_details_xml as $key => $element) {
	    		$this->$key  = (string) $element;
		    }
		}
	}

	protected function getName() {
  		return "contact_details";
  	}
}

?>