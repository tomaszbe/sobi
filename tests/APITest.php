<?php

namespace Sobi\Tests;

use PHPUnit\Framework\TestCase;
use Sobi\API;
use Carbon\Carbon;

/**
* API testing class
*/
class APITest extends TestCase
{
	private function api()
	{
		return new API('dG9tYXN6YmVAaG90bWFpbC5jb206VG9tYXN6ZWsw');
	}

	public function test_can_instantiate_the_class()
	{
		$this->assertInstanceOf(API::class, $this->api());
	}

	public function test_lists_areas()
	{
		$areas = $this->api()->areas(['network_ids' => [105, 106]]);
		$this->assertNotCount(0, $areas);
	}

	public function test_lists_bikes()
	{
		$bikes = $this->api()->bikes(['network_id' => 105, 'per_page' => 1]);
		$this->assertInstanceOf('stdClass', $bikes);
	}

	public function test_gets_bike()
	{
		$bike = $this->api()->bike(1);
		$this->assertInstanceOf('stdClass', $bike);
	}

	public function test_friends()
	{
		$friends = $this->api()->friends();
		$this->assertEquals(1, $friends->current_page);
	}

	public function test_book_unbookable_bike_404()
	{
		try {
			$this->api()->bookBike(1);
		} catch (\GuzzleHttp\Exception\BadResponseException $exception) {
			$response = json_decode($exception->getResponse()->getBody()->getContents());
			$this->assertContains('Bike', $response->error);
			$this->assertEquals(404, $response->code);
		}
	}

	public function test_hubs()
	{
		$hubs = $this->api()->hubs(['network_id' => 105, 'per_page' => 1]);
		$this->assertGreaterThan('100', $hubs->total_entries);
		$hubId = $hubs->items[0]->id;
		$hub = $this->api()->hub($hubId);
		$this->assertEquals($hubId, $hub->id);
	}

	public function test_networks()
	{
		$networks = $this->api()->networks(true);
		$network = $networks[0];
		$this->assertEquals('Wavelo', $network->name);
		$areas = $this->api()->networkAreas($network->id, ['exclude_attributes' => 'polygon']);
		$this->assertEquals($areas[0]->network_id, $network->id);
		$specialAreas = $this->api()->networkSpecialAreas($network->id, ['exclude_attributes' => 'polygon']);
		$this->assertTrue(is_array($specialAreas));
		$hubs = $this->api()->networkHubs($network->id, ['per_page' => '1']);
		$this->assertEquals($hubs->items[0]->network_id, $network->id);
		$bikes = $this->api()->networkBikes($network->id, ['per_page' => '1']);
		$this->assertGreaterThan(10, $bikes->total_entries);
		$subscription = $this->api()->networkSubscription($network->id);
		$this->assertEquals($network->id, $subscription->network_id);
		$systemHours = $this->api()->networkSystemHours($network->id);
		$this->assertTrue(is_array($systemHours));
		$networkFromId = $this->api()->network($network->id);
		$this->assertEquals($networkFromId->id, $network->id);
	}

	public function test_unsubscribe_from_unsubscribed_405()
	{
		try {
			$this->api()->networkUnsubscribe(1);
		} catch (\GuzzleHttp\Exception\BadResponseException $exception) {
			$responseCode = $exception->getResponse()->getStatusCode();
			$this->assertEquals(405, $responseCode);
		}
	}

	public function test_rentals()
	{
		$rentals = $this->api()->rentals(['per_page' => 1]);
		$this->assertGreaterThan(10, $rentals->total_entries);
		try {
			$cancelledRental = $this->api()->rentalCancel();
			$this->assertGreaterThan(1, $cancelledRental->id);
		} catch (\GuzzleHttp\Exception\BadResponseException $exception) {
			$responseCode = $exception->getResponse()->getStatusCode();
			$this->assertEquals(403, $responseCode);
		}
		$rental = $this->api()->rental($rentals->items[0]->id);
		$this->assertEquals($rentals->items[0]->bike_id, $rental->bike_id);
	}

	public function test_rfids()
	{
		$rfids = $this->api()->rfids(['per_page' => '1']);
		$initialCount = $rfids->total_entries;
		$rfid = $this->api()->rfidCreate('82752401984385', 'Test name');
		$this->assertGreaterThan(1, $rfid->id);
		$rfids = $this->api()->rfids(['per_page' => '1']);
		$this->assertEquals($rfids->total_entries, $initialCount + 1);
		$deleteResponse = $this->api()->rfidDelete($rfid->id);
		$this->assertEquals('OK', $deleteResponse);
		$rfids = $this->api()->rfids(['per_page' => '1']);
		$this->assertEquals($rfids->total_entries, $initialCount);
	}

	public function test_routes()
	{
		$routes = $this->api()->routes(['per_page' => 1]);
		$oldType = $routes->items[0]->route_type;
		$oldPublic = $routes->items[0]->public;
		$updated = $this->api()->routeUpdate($routes->items[0]->id, ['route_type' => 'errand', 'public' => !$oldPublic]);
		$route = $this->api()->route($routes->items[0]->id);
		$this->api()->routeUpdate($routes->items[0]->id, ['route_type' => $oldType, 'public' => $oldPublic]);
		$this->assertEquals($route->route_type, 'errand');
	}

	public function test_rtm()
	{
		$rtm = $this->api()->rtm();
		$expiresAt = Carbon::createFromFormat('Y-m-d\TH:i:s\Z', $rtm->expires_at);
		$this->assertGreaterThan(Carbon::now(), $expiresAt);
	}

	public function test_search()
	{
		$results = $this->api()->search('Bagatela');
		$this->assertGreaterThan(1, count($results->hubs));
	}

	public function test_subscriptions()
	{
		$subscriptions = $this->api()->subscriptions();
		$this->assertTrue(is_array($subscriptions));
	}

	public function test_me()
	{
		$me = $this->api()->me(['latitude' => 20, 'longitude' => 50]);
		$this->assertGreaterThan(1, $me->id);
	}

	public function xtest_gpx()
	{
		$me = $this->api()->me();
		$routes = $this->api()->routes();
		$routeId = $routes->items[0]->id;
		$gpx = $this->api()->gpx($me->id, $routeId);
		d($gpx);
	}

}