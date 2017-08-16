<?php

namespace Sobi;

class User extends BaseModel
{
	public function name()
	{
		return $this->first_name . ' ' . $this->last_name;
	}

	public function full_name()
	{
		return $this->name();
	}
}