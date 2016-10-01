<?php


namespace Pipas\Rest\Result;

/**
 * @author Petr Štipek <p.stipek@email.cz>
 */
interface IContract extends \ArrayAccess, IToArrayConversion
{
	/**
	 * @param $id
	 * @return self
	 */
	function setId($id);

	/**
	 * @return int|null
	 */
	function getId();
}