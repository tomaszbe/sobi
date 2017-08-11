<?php

namespace Sobi;

class LineString extends BaseModel
{
	protected $points = [];
	function __construct($raw)
	{
		foreach($raw->coordinates as $coordinates) {
			$rawPoint = new \stdClass;
			$rawPoint->coordinates = $coordinates;
			$this->points[] = new Point($rawPoint);
		}
	}
}