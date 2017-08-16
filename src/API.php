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
		$result = json_decode($response->getBody()->getContents());
		return $result;
	}

	public function __call($name, $arguments)
	{
		$name = strtoupper($name);
		if (in_array($name, ['GET', 'POST', 'PATCH', 'DELETE'])) {
			return $this->request($name, ...$arguments);
		}
	}

	public function model($type = \stdClass::class, ...$arguments)
	{
		return new $type(...$arguments);
	}

	// AREAS

	public function areas($query = [])
	{
		// return $this->get('areas', $query);
		// return $this->get('areas', $query);
		$areas = $this->get('areas', $query);
		return $this->model(CollectionPage::class, Area::class, $areas);
	}

	// BIKES

	public function bikes($query = [])
	{
		$bikes = $this->get('bikes', $query);
		return $this->model(CollectionPage::class, Bike::class, $bikes);
	}

	public function bike($bike_id)
	{
		return $this->model(Bike::class, $this->get("bikes/{$bike_id}"));
	}

	public function bookBike($bike_id, $ble_uuid = '')
	{
		return $this->model(Bike::class, $this->post("bikes/{$bike_id}/book_bike", [], compact('ble_uuid')));
	}

	public function reportIssue($bike_id, $problems)
	{
		return $this->post("bikes/{$bike_id}/report_issue", [], compact('problems'));
	}

	// FRIENDS

	public function friends($page = 1, $per_page = null)
	{
		return $this->model(CollectionPage::class, Friend::class, $this->get('friends', compact('page', 'per_page')));
	}

	// HUBS

	public function hubs($query = [])
	{
		if (isset($query['network_id']))
		{
			return $this->networkHubs($query['network_id'], $query);
		}
		return $this->get('hubs', $query);
	}

	public function bookBikeFromHub($hub_id, $ble_uuid = '')
	{
		return $this->model(Bike::class, $this->post("hubs/{$hub_id}/book_bike", [], compact('ble_uuid')));
	}

	public function hub($hub_id, $query = [])
	{
		return $this->model(Hub::class, $this->get("hubs/{$hub_id}", $query));
	}

	// NETWORKS

	public function networks($subscribed = false)
	{
		return $this->model(CollectionPage::class, Network::class, $this->get("networks", compact('subscribed')));
	}

	public function networkAreas($network_id, $query = [])
	{
		return $this->model(CollectionPage::class, Area::class, $this->get("networks/{$network_id}/areas", $query));
	}

	public function networkSpecialAreas($network_id, $query = [])
	{
		return $this->model(CollectionPage::class, Area::class, $this->get("networks/{$network_id}/special_areas", $query));
	}

	public function networkHubs($network_id, $query = [])
	{
		return $this->model(CollectionPage::class, Hub::class, $this->get("networks/{$network_id}/hubs", $query));
	}

	public function networkBikes($network_id, $query = [])
	{
		return $this->model(CollectionPage::class, Bike::class, $this->get("networks/{$network_id}/bikes", $query));
	}

	public function networkUnsubscribe($network_id)
	{
		return $this->delete("networks/{$network_id}/subscription");
	}

	public function networkSubscription($network_id)
	{
		return $this->model(Subscription::class, $this->get("networks/{$network_id}/subscription"));
	}

	public function networkSystemHours($network_id)
	{
		return $this->get("networks/{$network_id}/system_hours");
	}

	public function network($network_id)
	{
		return $this->model(Network::class, $this->get("networks/{$network_id}"));
	}

	// RENTALS

	public function rentals($query = [])
	{
		return $this->model(CollectionPage::class, Rental::class, $this->get('rentals', $query));
	}

	public function rentalCancel()
	{
		return $this->delete('rentals/cancel');
	}

	public function rental($rental_id)
	{
		return $this->model(Rental::class, $this->get("rentals/{$rental_id}"));
	}

	// RFIDS

	public function rfids($query = [])
	{
		return $this->model(CollectionPage::class, RFID::class, $this->get('rfids', $query));
	}

	public function rfidCreate($uid, $name)
	{
		return $this->model(RFID::class, $this->post('rfids', [], compact('uid', 'name')));
	}

	public function rfidUpdate($rfid_id, $name)
	{
		return $this->model(RFID::class, $this->patch("rfids/{$rfid_id}", compact('$name')));
	}

	public function rfidDelete($rfid_id)
	{
		return $this->delete("rfids/{$rfid_id}");
	}

	// ROUTES

	public function routes($query = [])
	{
		return $this->model(CollectionPage::class, Route::class, $this->get('routes', $query));
	}

	public function route($route_id)
	{
		return $this->model(Route::class, $this->get("routes/{$route_id}"));
	}

	public function routeUpdate($route_id, $query = [])
	{
		return $this->model(Route::class, $this->patch("routes/{$route_id}", $query));
	}

	// RTM

	public function rtm()
	{
		return $this->model(RTM::class, $this->get('rtm/token'));
	}

	// SEARCH

	public function search($query, $all_networks = false)
	{
		return $this->get('search', compact('query', 'all_networks'));
	}

	// SUBSCRIPTIONS

	public function subscriptions()
	{
		return $this->model(CollectionPage::class, Subscription::class, $this->get('subscriptions'));
	}

	// USERS

	public function userUpdateInfo($query = [])
	{
		return $this->model(User::class, $this->patch('users/update_info', $query));
	}

	public function userUpdateEmail($email, $password)
	{
		return $this->model(User::class, $this->patch('users/update_email', compact('email', 'password')));
	}

	public function userUpdatePassword($password, $old_assword)	
	{
		return $this->model(User::class, $this->patch('users/update_password', compact('password', 'old_password')));
	}

	public function me($query = [])
	{
		return $this->model(User::class, $this->get('users/me', $query));
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
		$gpx = $this->get("https://app.socialbicycles.com/users/{$user_id}/routes/{$route_id}/download_gpx");
		return $gpx;
	}

}