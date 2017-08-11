<?php

namespace Sobi\Tests;

use PHPUnit\Framework\TestCase;
use Sobi\CollectionPage;
use Sobi\API;
use Sobi\Bike;
use Sobi\Area;
use Sobi\Hub;

/**
* Collection Page test
*/
class CollectionPageTest extends TestCase
{
	public function test_load_page()
	{
		$api = new API('dG9tYXN6YmVAaG90bWFpbC5jb206VG9tYXN6ZWsw');

		$bikes = $api->bikes(['per_page' => 2]);
		$bikesPage = new CollectionPage(Bike::class, $bikes);

		$areas = $api->areas(['per_page' => 2]);
		$areasPage = new CollectionPage(Area::class, $areas);

		$hubs = $api->hubs(['per_page' => 2]);
		$hubsPage = new CollectionPage(Hub::class, $hubs);

		$networkAreas = $api->networkAreas(105);
		$networkAreasPage = new CollectionPage(Area::class, $networkAreas);

		$this->assertContains('Wavelo', $networkAreasPage->items[0]->name);
	}
}