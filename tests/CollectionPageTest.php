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
		$page = $api->bikes(['per_page' => 2]);
		$collectionPage = new CollectionPage(Bike::class, $page);
		$areasPageRaw = new \stdClass;
		$areasPageRaw->items = $api->areas(['per_page' => 2]);
		$areasPage = new CollectionPage(Area::class, $areasPageRaw);
		$hubs = $api->hubs(['per_page' => 2]);
		$hubsPage = new CollectionPage(Hub::class, $hubs);
		d($hubsPage);
	}
}