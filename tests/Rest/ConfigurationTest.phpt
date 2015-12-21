<?php

use Nette\Http\Url;
use Pipas\Rest\Configuration;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$instanceName = "instance";
$userEmail = "email";
$apiKey = "key";
$apiUrl = "http://my.domain.com/api";
$conn = new Configuration($instanceName, $userEmail, $apiKey, $apiUrl);

Assert::equal($instanceName, $conn->getInstanceName());
Assert::equal($userEmail, $conn->getUserName());
Assert::equal($apiKey, $conn->getApiKey());
Assert::true($conn->getApiUrl() instanceof Url);
Assert::equal($apiUrl, $conn->getApiUrl()->absoluteUrl);
