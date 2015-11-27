<?php

use Pipas\Rest\Connection;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$instanceName = "instance";
$userEmail = "email";
$apiKey = "key";
$apiUrl = "url";
$conn = new Connection($instanceName, $userEmail, $apiKey, $apiUrl);

Assert::equal($instanceName, $conn->getInstanceName());
Assert::equal($userEmail, $conn->getUserName());
Assert::equal($apiKey, $conn->getApiKey());
Assert::equal($apiUrl, $conn->getApiUrl());
