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
 ** Reference Documentation: http://support.trafficspaces.com/kb/api/api-users
 **/
class TrafficspacesUser extends TrafficspacesResource {
	//******************************
	//** INPUT & OUTPUT VARIABLES **
	//******************************
	var $name;
	var $role;
	var $profile;

	//******************************
	//*** OUTPUT ONLY VARIABLES ****
	//******************************
	var $id;
	var $creation_date;
	var $last_modified_date;
	var $expiration_date;

	public function __construct(SimpleXMLElement $user_xml = null) {
		if ($user_xml) {
	    	// Load object dynamically and convert SimpleXMLElements into strings
	    	foreach ($user_xml as $key => $element) {
				if ($key == "profile") {
		  			$this->profile = new TrafficspacesUserProfile($element);
			  	} else {	
	    			$this->$key = (string) $element;
	    		}
		    }
		}
	}
	
	protected function getName() {
  		return "user";
  	}
}

class TrafficspacesUserProfile extends TrafficspacesResource {
	//******************************
	//** INPUT & OUTPUT VARIABLES **
	//******************************
	var $title;
	var $first_name;
	var $last_name;
	var $gender;
	var $date_of_birth;
	var $company_name;
	var $website;
	var $email;
	var $photo_url;
	var $locale;
	var $contact_details;

	public function __construct(SimpleXMLElement $profile_xml = null) {
		if ($profile_xml) {
	    	// Load object dynamically and convert SimpleXMLElements into strings
	    	foreach ($profile_xml as $key => $element) {
				if ($key == "contact_details") {
		  			$this->contact_details = new TrafficspacesUserProfileContactDetails($element);
			  	} else {	
	    			$this->$key  = (string) $element;
	    		}
		    }
		}
	}

	protected function getName() {
  		return "profile";
  	}

  	public function getFullName() { return $this->first_name . ' ' . $this->last_name; }
}

class TrafficspacesUserProfileContactDetails extends TrafficspacesResource {
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