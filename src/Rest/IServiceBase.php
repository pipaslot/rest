<?php

namespace Pipas\Rest;

/**
 * @author Petr Štipek <p.stipek@email.cz>
 */
interface IServiceBase
{

	const NAME_SUFFIX = "Service";

	/**
	 * Returns target service name
	 * @return string
	 */
	function getName();
}