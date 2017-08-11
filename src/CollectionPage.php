<?php

namespace Sobi;

/**
* Collection Page Class
*/
class CollectionPage
{
	protected $current_page;
	protected $per_page;
	protected $total_entries;
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

	function __construct($class, $objectOrArray)
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
}