<?php

namespace Sobi;

/**
* Point Class
*/
class Point
{
	protected $lon, $lat;

	function __construct($raw)
	{
		$this->lon = $raw->coordinates[0];
		$this->lat = $raw->coordinates[1];
	}

	public function getLon()
	{
		return $this->lon;
	}

	public function getLat()
	{
		return $this->lat;
	}
}