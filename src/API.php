<?php

namespace Sobi;

use GuzzleHttp\Client;

/**
* This class is responsible for communication with SocialBicycles API
*/
class API
{
	protected $uri = 'https://app.socialbicycles.com/api/';
	protected $client;
	protected $hash;

	function __construct($hash)
	{
		$this->hash = $hash;
		$this->client = new Client(['base_uri' => $this->uri]);
	}

	protected function request($type, $uri, $query = [], $form = [])
	{
		$response = $this->client->request($type, $uri . '.json', [
			'headers' => [
				'Authorization' => 'Basic ' . $this->hash
			],
			'query' => $query,
			'form_params' => $form
		]);
		return json_decode($response->getBody()->getContents());
	}

	// AREAS

	public function areas($query = [])
	{
		return $this->request('GET', 'areas', $query);
	}

	// BIKES

	public function bikes($query = [])
	{
		return $this->request('GET', 'bikes', $query);
	}

	public function bike($bike_id)
	{
		return $this->request('GET', "bikes/{$bike_id}");
	}

	public function bookBike($bike_id, $ble_uuid = '')
	{
		return $this->request('POST', "bikes/{$bike_id}/book_bike", [], compact('ble_uuid'));
	}

	public function reportIssue($bike_id, $problems)
	{
		return $this->request('POST', "bikes/{$bike_id}/report_issue", [], compact('problems'));
	}

	// FRIENDS

	public function friends($page = 1, $per_page = null)
	{
		return $this->request('GET', 'friends', compact('page', 'per_page'));
	}

	// HUBS

	public function hubs($query = [])
	{
		return $this->request('GET', 'hubs', $query);
	}

	public function bookBikeFromHub($hub_id, $ble_uuid = '')
	{
		return $this->request('POST', "hubs/{$hub_id}/book_bike", [], compact('ble_uuid'));
	}

	public function hub($hub_id, $query = [])
	{
		return $this->request('GET', "hubs/{$hub_id}", $query);
	}

	// NETWORKS

	public function networks($subscribed = false)
	{
		return $this->request('GET', "networks", compact('subscribed'));
	}

	public function networkAreas($network_id, $query = [])
	{
		return $this->request('GET', "networks/{$network_id}/areas", $query);
	}

	public function networkSpecialAreas($network_id, $query = [])
	{
		return $this->request('GET', "networks/{$network_id}/special_areas", $query);
	}

	public function networkHubs($network_id, $query = [])
	{
		return $this->request('GET', "networks/{$network_id}/hubs", $query);
	}

	public function networkBikes($network_id, $query = [])
	{
		return $this->request('GET', "networks/{$network_id}/bikes", $query);
	}

	public function networkUnsubscribe($network_id)
	{
		return $this->request('DELETE', "networks/{$network_id}/subscription");
	}

	public function networkSubscription($network_id)
	{
		return $this->request('GET', "networks/{$network_id}/subscription");
	}

	public function networkSystemHours($network_id)
	{
		return $this->request('GET', "networks/{$network_id}/system_hours");
	}

	public function network($network_id)
	{
		return $this->request('GET', "networks/{$network_id}");
	}

	// RENTALS

	public function rentals($query = [])
	{
		return $this->request('GET', 'rentals', $query);
	}

	public function rentalCancel()
	{
		return $this->request('DELETE', 'rentals/cancel');
	}

	public function rental($rental_id)
	{
		return $this->request('GET', "rentals/{$rental_id}");
	}

	// RFIDS

	public function rfids($query = [])
	{
		return $this->request('GET', 'rfids', $query);
	}

	public function rfidCreate($uid, $name)
	{
		return $this->request('POST', 'rfids', [], compact('uid', 'name'));
	}

	public function rfidUpdate($rfid_id, $name)
	{
		return $this->request('PATCH', "rfids/{$rfid_id}", compact('$name'));
	}

	public function rfidDelete($rfid_id)
	{
		return $this->request('DELETE', "rfids/{$rfid_id}");
	}

	// ROUTES

	public function routes($query = [])
	{
		return $this->request('GET', 'routes', $query);
	}

	public function route($route_id)
	{
		return $this->request('GET', "routes/{$route_id}");
	}

	public function routeUpdate($route_id, $query = [])
	{
		return $this->request('PATCH', "routes/{$route_id}", $query);
	}

	// RTM

	public function rtm()
	{
		return $this->request('GET', 'rtm/token');
	}

	// SEARCH

	public function search($query, $all_networks = false)
	{
		return $this->request('GET', 'search', compact('query', 'all_networks'));
	}

	// SUBSCRIPTIONS

	public function subscriptions()
	{
		return $this->request('GET', 'subscriptions');
	}

	// USERS

	public function userUpdateInfo($query = [])
	{
		return $this->request('PATCH', 'users/update_info', $query);
	}

	public function userUpdateEmail($email, $password)
	{
		return $this->request('PATCH', 'users/update_email', compact('email', 'users/password'));
	}

	public function userUpdatePassword($password, $old_assword)	
	{
		return $this->request('PATCH', 'users/update_password', compact('password', 'old_password'));
	}

	public function me($query = [])
	{
		return $this->request('GET', 'users/me', $query);
	}

	// GPX

	public function gpx($user_id, $route_id)
	{
		$this->client->get("https://app.socialbicycles.com/users/{$user_id}/routes/{$route_id}/download_gpx", [
			'sink' => 'storage/file.gpx',
			'headers' => [
				'Authorization' => 'Basic ' . $this->hash
			]
		]);
		$gpx = $this->request('GET', "https://app.socialbicycles.com/users/{$user_id}/routes/{$route_id}/download_gpx");
		return $gpx;
	}

}