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

class TrafficspacesAPITest {

	private $factory;

	private $defaults;

	private $dateTimeFormat = "Y-m-d\TH:i:s";

	public function __construct($sub_domain, $api_key) {
		$this->factory = new TrafficspacesConnectorFactory($sub_domain, $api_key);
		$this->init();
	}

	private function init() {
		$this->defaults = array("page" => 1, "pagesize" => 10);
	}

	public function runTests() {
		$this->testUserAPI();
		$this->testContactAPI();
		$this->testZoneAPI();
		$this->testAdAPI();
		$this->testCampaignAPI();
		$this->testTargetingPlanAPI();
		$this->testFeedAPI();
		$this->testOrderAPI();
		$this->testCouponAPI();
		$this->testPlacementsAPI();
	}

	private function testUserAPI() {

		echo "--- Testing User API ---\n";

		$users = $this->factory->getUserConnector()->find($this->defaults);

		echo "List: Found " . count($users) . " users\n";
	}

	private function testContactAPI() {

		echo "--- Testing Contact API ---\n";

		$connector = $this->factory->getContactConnector();

		// 1. List
		$contacts = $connector->find($this->defaults);
		echo "List: Found " . count($contacts) . " contacts\n";

		// 2. Create
		$contact = TrafficspacesContact::createContact("John Doe",
				TrafficspacesContactProfile::createContactProfile("john@test.com", "Test Company", TrafficspacesContactProfile::TYPE_ADVERTISER),
				null);
		$contact = $connector->create($contact);
		echo "Create: " . ($contact != null ? "Succesful" : "Failed") . ($contact != null ? ". The new ID is " . $contact->id : "") . "\n";

		// 3. Update
		$contact->name = "Jane Smith";
		$contact->profile->email = "jane@test.com";
		$contact->profile->company_name = "Test Ad Agency";
		$contact->profile->contact_details->street = "1 Madison Avenue";
		$contact->profile->contact_details->city = "New York";
		$contact->profile->contact_details->state = "NY";
		$contact->profile->contact_details->country = "us";
		$updatedContact = $connector->update($contact);
		echo "Update: " . ($updatedContact != null && $updatedContact->id == $contact->id && $updatedContact->name == $contact->name ? "Successful" : "Failed") . "\n";

		// 4. Delete
		if (!$connector->delete($contact->id) || $connector->read($contact->id) != null) {
			echo "Delete: Failed\n";
		} else {
			echo "Delete: Successful\n";
		}
	}


	private function testZoneAPI() {

		echo "--- Testing Zone API ---\n";

		$connector = $this->factory->getZoneConnector();

		// 1. List
		$zones = $connector->find($this->defaults);
		echo "List: Found " . count($zones) . " zones\n";

		// 2. Create
		$zone = TrafficspacesZone::createZone("Test Zone", 300, 250, "text,image,flash", TrafficspacesZonePricing::createZonePricing("cpm", 5.0));
		$zone = $connector->create($zone);
		echo "Create: " . ($zone != null ? "Successful" : "Failed") . ($zone != null ? ". The new ID is " . $zone->id : "") . "\n";

		// 3. Update
		$zone->name = "Test Zone 2";
		$zone->formats = "text,image";
		$zone->description = "Just another test zone";
		$zone->default_ad_tag = "<!-- Insert Google Adsense Tag -->";
		$zone->position = "anywhere";
		$zone->channel = "blog";
		$updatedZone = $connector->update($zone);
		echo "Update: " . ($updatedZone != null && $updatedZone->id == $zone->id && $updatedZone->name == $zone->name ? "Successful" : "Failed") . "\n";

		// 4. Delete
		if (!$connector->delete($zone->id) || $connector->read($zone->id) != null) {
			echo "Delete: Failed\n";
		} else {
			echo "Delete: Successful\n";
		}
	}

	private function testAdAPI() {

		echo "--- Testing Ad API ---\n";

		$connector = $this->factory->getAdConnector();

		// 1. List
		$ads = $connector->find($this->defaults);
		echo "List: Found " . count($ads) . " ads\n";

		// 2. Create
		$ad = TrafficspacesAd::createAd("Test Ad", 300, 250, "text",
				TrafficspacesAdCreative::createTextCreative("My Ad Title", "My Ad Caption", "TestAd.com", null, "http://www.testad.com"));
		$ad = $connector->create($ad);
		echo "Create: " . ($ad != null ? "Successful" : "Failed") . ($ad != null ? ". The new ID is " . $ad->id : "") ."\n";

		// 3. Update
		$ad->name = "Test Ad 2";
		$ad->status = "approved";
		$ad->creative->title = "Another Ad Title";
		$ad->creative->caption = "Yet another caption";
		$ad->creative->target_url = "http://www.testads.com/landing_page/";
		$updatedAd = $connector->update($ad);
		echo "Update: " . ($updatedAd != null && $updatedAd->id == $ad->id && $updatedAd->name == $ad->name ? "Successful" : "Failed") . "\n";

		// 4. Delete
		if (!$connector->delete($ad->id) || $connector->read($ad->id) != null) {
			echo "Delete: Failed\n";
		} else {
			echo "Delete: Successful\n";
		}
	}

	private function testCampaignAPI() {

		echo "--- Testing Campaign API ---\n";

		$connector = $this->factory->getCampaignConnector();

		// 1. List
		$campaigns = $connector->find($this->defaults);
		echo "List: Found " . count($campaigns) . " campaigns\n";

		// 2. Create
		$campaign = TrafficspacesCampaign::createCampaign("Test Campaign", null);
		$campaign = $connector->create($campaign);
		echo "Create: " . ($campaign != null ? "Successful" : "Failed") . ($campaign != null ? ". The new ID is " . $campaign->id : "") . "\n";

		// 3. Update
		$ad = TrafficspacesAd::createAd("Test Ad", 300, 250, "text",
				TrafficspacesAdCreative::createTextCreative("My Ad Title", "My Ad Caption", "TestAd.com", null, "http://www.test.com"));
		$ad = $this->factory->getAdConnector()->create($ad);

		$campaign->name = "Test Campaign 2";
		$campaign->linked_ads = array(TrafficspacesLinkedResource::createLinkedResource($ad->id, $ad->name, TrafficspacesCampaign::LINKED_AD_RESOURCE_NAME));
		$updatedCampaign = $connector->update($campaign);
		echo "Update: " . ($updatedCampaign != null && $updatedCampaign->id == $campaign->id && $updatedCampaign->name == $campaign->name &&
				$updatedCampaign->linked_ads != null && count($updatedCampaign->linked_ads) == 1 ? "Successful" : "Failed") . "\n";

		// 4. Delete
		$this->factory->getAdConnector()->delete($ad->id);
		if (!$connector->delete($campaign->id) || $connector->read($campaign->id) != null) {
			echo "Delete: Failed\n";
		} else {
			echo "Delete: Successful\n";
		}
	}

	private function testTargetingPlanAPI() {

		echo "--- Testing Targeting Plan API ---\n";

		$connector = $this->factory->getTargetingPlanConnector();

		// 1. List
		$targetingPlans = $connector->find($this->defaults);
		echo "List: Found " . count($targetingPlans) . " targeting plans\n";

		// 2. Create
		$targetingPlan = TrafficspacesTargetingPlan::createTargetingPlan("Test Targeting Plan", null);
		$targetingPlan = $connector->create($targetingPlan);
		echo "Create: " . ($targetingPlan != null ? "Successful" : "Failed") . ($targetingPlan != null ? ". The new ID is " . $targetingPlan->id : "") . "\n";

		// 3. Update
		$targetingPlan->name = "Test Targeting Plan 2";
		$targetingPlan->targets = new TrafficspacesTargetingPlanTargets();
		$targetingPlan->targets->geographics = "us,ca";
		$targetingPlan->targets->keywords = "football,basketball,baseball,hockey";
		$updatedTargetingPlan = $connector->update($targetingPlan);
		echo "Update: " . ($updatedTargetingPlan != null && $updatedTargetingPlan->id == $targetingPlan->id && $updatedTargetingPlan->name == $targetingPlan->name &&
				$updatedTargetingPlan->targets != null &&
				count(array_diff(split(",", $updatedTargetingPlan->targets->geographics), split(",", $targetingPlan->targets->geographics))) == 0 &&
				count(array_diff(split(",", $updatedTargetingPlan->targets->keywords), split(",", $targetingPlan->targets->keywords)) == 0) ? "Successful" : "Failed") . "\n";

		// 4. Delete
		if (!$connector->delete($targetingPlan->id) || $connector->read($targetingPlan->id) != null) {
			echo "Delete: Failed\n";
		} else {
			echo "Delete: Successful\n";
		}
	}

	private function testFeedAPI() {

		echo "--- Testing Feed API ---\n";

		$connector = $this->factory->getFeedConnector();

		// 1. List
		$feeds = $connector->find($this->defaults);
		echo "List: Found " . count($feeds) . " feeds\n";

		// 2. Create
		$feed = TrafficspacesFeed::createFeed("Test Feed", 728, 90, 100.0, "<!-- Google AdSense Backfill -->");
		$feed = $connector->create($feed);
		echo "Create: " . ($feed != null ? "Successful" : "Failed") . ($feed != null ? ". The new ID is " . $feed->id : "") . "\n";

		// 3. Update
		$feed->name = "Test Feed 2";
		$feed->weight = 20.0;
		$feed->ad_tag = "<!-- Another 3rd party Ad Tag-->";
		$updatedFeed = $connector->update($feed);
		echo "Update: " . ($updatedFeed != null && $updatedFeed->id == $feed->id && $updatedFeed->name == $feed->name &&
				$updatedFeed->weight == $feed->weight && $updatedFeed->ad_tag == $feed->ad_tag ? "Successful" : "Failed") . "\n";

		// 4. Delete
		if (!$connector->delete($feed->id) || $connector->read($feed->id) != null) {
			echo "Delete: Failed\n";
		} else {
			echo "Delete: Successful\n";
		}
	}

	private function testOrderAPI() {

		echo "--- Testing Order API ---\n";

		$connector = $this->factory->getOrderConnector();

		// 1. List
		$orders = $connector->find($this->defaults);
		echo "List: Found " . count($orders) . " orders\n";

		// 2. Create
		$zone = TrafficspacesZone::createZone("Test Zone", 300, 250, "text", TrafficspacesZonePricing::createZonePricing("cpc", 0.5));
		$zone = $this->factory->getZoneConnector()->create($zone);

		$ad = TrafficspacesAd::createAd("Test Ad", 300, 250, "text", TrafficspacesAdCreative::createTextCreative("My Ad Title", "My Ad Caption", "TestAd.com", null, "http://www.testad.com"));
		$ad = $this->factory->getAdConnector()->create($ad);

		$campaign = TrafficspacesCampaign::createCampaign("Test Campaign", null);
		$campaign->linked_ads = array(TrafficspacesLinkedResource::createLinkedResource($ad->id, $ad->name, TrafficspacesCampaign::LINKED_AD_RESOURCE_NAME));
		$campaign = $this->factory->getCampaignConnector()->create($campaign);

		$order = TrafficspacesOrder::createOrder(0.5, 100000, 1000, date($this->dateTimeFormat), null,
				TrafficspacesLinkedResource::createLinkedResource($zone->id, $zone->name, TrafficspacesOrder::LINKED_ZONE_RESOURCE_NAME),
				TrafficspacesLinkedResource::createLinkedResource($campaign->id, $campaign->name, TrafficspacesOrder::LINKED_CAMPAIGN_RESOURCE_NAME));
		$order = $connector->create($order);
		echo "Create: " . ($order != null ? "Successful" : "Failed") . ($order != null ? ". The new ID is " . $order->id : "") . "\n";

		// 3. Update
		$order->maximum_bid_price = 0.75;
		$order->daily_volume = 5000;
		$updatedOrder = $connector->update($order);
		echo "Update: " . ($updatedOrder != null && $updatedOrder->id == $order->id && $updatedOrder->name == $order->name &&
				$updatedOrder->maximum_bid_price == $order->maximum_bid_price &&
				$updatedOrder->daily_volume == $order->daily_volume ? "Successful" : "Failed") . "\n";

		// 4. Process
		$connector->sendRequest("/resources/orders/process/?action=pause&orderid={$order->id}", "application/x-www-form-urlencoded", "POST");
		$updatedOrder = $connector->read($order->id);
		echo "Process: " . ($updatedOrder->id == $order->id && $updatedOrder->name == $order->name &&
				$updatedOrder->status != null && ($updatedOrder->status == "pausing" || $updatedOrder->status == "paused") ? "Successful" : "Failed") . "\n";

		// 4. Delete
		$this->factory->getAdConnector()->delete($ad->id);
		$this->factory->getCampaignConnector()->delete($campaign->id);
		$this->factory->getZoneConnector()->delete($zone->id);
		if (!$connector->delete($order->id) || $connector->read($order->id) != null) {
			echo "Delete: Failed\n";
		} else {
			echo "Delete: Successful\n";
		}
	}

	private function testCouponAPI() {

		echo "--- Testing Coupon API ---\n";

		$connector = $this->factory->getCouponConnector();

		// 1. List
		$coupons = $connector->find($this->defaults);
		echo "List: Found " . count($coupons) . " coupons\n";

		// 2. Create
		$couponCode = "HALFPRICE";
		// Remove the coupon if it already exists
		$coupon = $this->readCouponByCode($connector, $couponCode);
		if ($coupon != null) {
			echo "Coupon already exists. Deleting..\n";
			$connector->delete($coupon->id);
		}
		$coupon = TrafficspacesCoupon::createRelativeCoupon("Test Coupon", "HALFPRICE", 0, 50.0);
		$coupon = $connector->create($coupon);
		echo "Create: " . ($coupon != null ? "Successful" : "Failed") . ($coupon != null ? ". The new ID is " . $coupon->id : "") . "\n";

		// 3. Update
		$coupon->name = "Test Coupon 2";
		$coupon->base_value = 100.0;
		$coupon->maximum_cumulative_discount = 1000.0;
		$coupon->maximum_cumulative_uses = 10;
		$updatedCoupon = $connector->update($coupon);
		echo "Update: " . ($updatedCoupon != null && $updatedCoupon->id == $coupon->id && $updatedCoupon->name == $coupon->name &&
				$updatedCoupon->base_value == $coupon->base_value &&
				$updatedCoupon->maximum_cumulative_discount == $coupon->maximum_cumulative_discount &&
				$updatedCoupon->maximum_cumulative_uses == $coupon->maximum_cumulative_uses ? "Successful" : "Failed") . "\n";

		// 4. Process
		$discount = 50;
		$connector->sendRequest("/resources/coupons/process/?action=use&couponcode={$coupon->code}&discount={$discount}", "application/x-www-form-urlencoded", "POST");
		$updatedCoupon = $connector->read($coupon->id);
		echo "Process: " . ($updatedCoupon->id == $coupon->id && $updatedCoupon->name == $coupon->name &&
				$updatedCoupon->cumulative_discount == ($coupon->cumulative_discount + $discount) &&
				$updatedCoupon->cumulative_uses == ($coupon->cumulative_uses + 1) ? "Successful" : "Failed") . "\n";

		// 5. Delete
		if (!$connector->delete($coupon->id) || $connector->read($coupon->id) != null) {
			echo "Delete: Failed\n";
		} else {
			echo "Delete: Successful\n";
		}
	}

	private function testPlacementsAPI() {

		echo "--- Testing Placements API ---\n";

		$connector = $this->factory->getPlacementConnector();

		// 1. List
		$params = array("pagesize" => 3, "status" => "playing");

		echo "Fetching a live insertion order\n";
		$orders = $this->factory->getOrderConnector()->find($params);
		if (count($orders) == 0) {
			echo "There are no available insertion orders\n";
			return;
		}

		foreach ($orders as $order) {

			$zone = $this->factory->getZoneConnector()->read($order->linked_zone->id);

			echo "Fetching live ads for zone: {$zone->name}\n";

			$startTime = microtime(true);
			$placements = $connector->find(array(TrafficspacesPlacement::createPlacement($zone->handle)), null);

			$adCount = 0;
			if ($placements != null) {
				foreach ($placements as $placement) {
					$adCount += ($placement->ads != null)  ? count($placement->ads) : 0;
				}
			}
			echo "Got ads in " . (microtime(true) - $startTime) . " (msecs)\n";
			echo "Found  " . count($placements) . " placements\n";
			echo "Found  " . $adCount . " ads\n";
		}
	}


	private function readCouponByCode($connector, $couponCode) {
		$coupon = null;

		$params = array("couponcode" => $couponCode);
		$coupons = $connector->find($params);
		if ($coupons != null && count($coupons) == 1) {
			$coupon = $coupons[0];
		}

		return $coupon;
	}
}
?>