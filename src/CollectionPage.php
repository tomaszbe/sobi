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

	protected $itemClass;

	function __construct($itemClass, $raw)
	{
		foreach (get_object_vars($raw) as $key => $value) {
			if ($key == 'items') {
				foreach ($raw->items as $item) {
					$this->items[] =  new $itemClass($item);
				}
			} else {
				$this->{$key} = $value;
			}
		}
	}

	public function getTotalEntries()
	{
		return $this->total_entries;
	}
}