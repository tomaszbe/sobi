<?php

namespace Sobi;

/**
* Point Class
*/
class Point
{
	protected $x, $y;

	function __construct($raw)
	{
		$this->x = $raw->coordinates[0];
		$this->y = $raw->coordinates[1];
	}

	public function getX()
	{
		return $this->x;
	}

	public function getY()
	{
		return $this->y;
	}
}