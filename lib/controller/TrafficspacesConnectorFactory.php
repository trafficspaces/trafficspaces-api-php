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
class TrafficspaceConnectorFactory {
	private $ad_store_end_point;
	private $ad_server_end_point;
	
	public function __construct($sub_domain, $username = null, $password = null) {
		$this->ad_store_end_point	= new TrafficspacesEndPoint("https://{$this->sub_domain}.trafficspaces.com", $this->username, $this->password);
		$this->ad_server_end_point	= new TrafficspacesEndPoint("http://ads.trafficspaces.net");
	}
		
	public function getUserConnector() {
		return new TrafficspaceConnector($this->ad_store_end_point, "/resources/users", "TrafficspacesUser");
	}
	
	public function getContactConnector() {
		return new TrafficspaceConnector($this->ad_store_end_point, "/resources/contacts", "TrafficspacesContact");
	}
	
	public function getZoneConnector() {
		return new TrafficspaceConnector($this->ad_store_end_point, "/resources/zones", "TrafficspacesZone");
	}
	
	public function getZoneStatisticsConnector() {
		return new TrafficspaceConnector($this->ad_store_end_point, "/resources/zones/statistics", "TrafficspacesZoneStatistic");
	}
	
	public function getAdConnector() {
		return new TrafficspaceConnector($this->ad_store_end_point, "/resources/ads", "TrafficspacesAd");
	}
	
	public function getCampaignConnector() {
		return new TrafficspaceConnector($this->ad_store_end_point, "/resources/campaigns", "TrafficspacesCampaign");
	}
	
	public function getTargetingPlanConnector() {
		return new TrafficspaceConnector($this->ad_store_end_point, "/resources/targetingplans", "TrafficspacesTargetingPlan");
	}
	
	public function getFeedConnector() {
		return new TrafficspaceConnector($this->ad_store_end_point, "/resources/feeds", "TrafficspacesFeed");
	}
	
	public function getOrderConnector() {
		return new TrafficspaceConnector($this->ad_store_end_point, "/resources/orders", "TrafficspacesOrder");
	}
	
	public function getOrderStatisticsConnector() {
		return new TrafficspaceConnector($this->ad_store_end_point, "/resources/orders/statistics", "TrafficspacesOrderStatistic");
	}
	
	public function getCouponConnector() {
		return new TrafficspaceConnector($this->ad_store_end_point, "/resources/coupons", "TrafficspacesCoupon");
	}
	
	public function getPlacementConnector() {
		return new TrafficspaceConnector($this->ad_server_end_point, "/resources/placements", "TrafficspacesPlacement");
	}
}
?>
