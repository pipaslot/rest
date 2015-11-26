<?php

namespace Pipas\Rest;

use OutOfRangeException;

/**
 * Pomocné funkce pro REST nástroje
 *
 * @author Petr Štipek <p.stipek@email.cz>
 */
class Helpers {

	/**
	 * Převede hodnotu na boolean false/true, popřípadně 0/1, nebo "false"/"true"
	 * @param type $value
	 * @throws OutOfRangeException V případě že nelze hodnotu rozpoznat
	 * @return bool
	 */
	public static function toBoolean($value)
	{
		if($value == null) return false;
		if(is_bool($value)) return $value;
		elseif(is_string($value))
		{
			$lowercase = strtolower($value);
			if($lowercase == "true") return true;
			if($lowercase == "false") return false;
		}
		elseif(is_numeric($value))
		{
			return $value != 0;
		}
		throw new \OutOfRangeException("Can not convert value '$value' to bool. Use TRUE or FALSE.");
	}

}
