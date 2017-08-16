<?php

namespace Sobi;

/**
* Collection Page Class
*/
class CollectionPage extends \stdClass implements \Countable, \ArrayAccess 
{
	public $current_page;
	public $per_page;
	public $total_entries;
	public $items = [];

	protected $class;

	public function createFromObject($class, $object)
	{
		foreach (get_object_vars($object) as $key => $value) {
			if ($key == 'items') {
				foreach ($object->items as $item) {
					$this->items[] =  new $class($item);
				}
			} else {
				$this->{$key} = $value;
			}
		}
	}

	public function createFromArray($class, $array)
	{
		$object = new \stdClass;
		$object->current_page = 1;
		$count = count($array);
		$object->per_page = $count;
		$object->total_entries = $count;
		$object->items = $array;
		$this->createFromObject($class, $object);
	}

	function __construct($class, $objectOrArray = [])
	{
		if ($objectOrArray instanceOf \stdClass) {
			$this->createFromObject($class, $objectOrArray);
		} else if (is_array($objectOrArray)) {
			$this->createFromArray($class, $objectOrArray);
		}
	}

	public function getTotalEntries()
	{
		return $this->total_entries;
	}

	public function count()
	{
		return count($this->items);
	}

	public function offsetExists ( $offset )
	{
		return $offset < count($this);
	}

	public function offsetGet ( $offset )
	{
		return $this->items[$offset];
	}

	public function offsetSet ($offset, $value )
	{
		return false;
	}

	public function offsetUnset ($offset)
	{
		return false;
	}
}