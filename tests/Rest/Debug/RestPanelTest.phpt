<?php

use Nette\Http\Url;
use Pipas\Rest\Debug\Logger;
use Pipas\Rest\Debug\RestPanel;
use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';

//Without logs
test(function () {

	$panel = new RestPanel();

	Assert::false(empty($panel->getTab()));
	Assert::true(empty($panel->getPanel()));

	Logger::startGet(new Url("domain.com"));

	Assert::false(empty($panel->getTab()));
	Assert::false(empty($panel->getPanel()));
});

