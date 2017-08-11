<?php

namespace Sobi;

/**
* Base Model Class
*/
class BaseModel
{
	function __construct($raw)
	{
		foreach (get_object_vars($raw) as $key => $value) {
			if ( $value instanceOf \stdClass && class_exists( __NAMESPACE__ . '\\' . $value->type) ) {
				$class =  __NAMESPACE__ . '\\' . $value->type;
				$this->$key = new $class($value);
			} else {
				$this->$key = $value;
			}
		}
	}
}