<?php

namespace Sobi;

/**
* Polygon Class
*/
class Polygon extends BaseModel
{
	protected $points = [];
	function __construct($raw)
	{
		foreach($raw->coordinates as $polygon) {
			foreach($polygon as $coordinates) {
				$rawPoint = new \stdClass;
				$rawPoint->coordinates = $coordinates;
				$this->points[] = new Point($rawPoint);
			}
		}
	}
}