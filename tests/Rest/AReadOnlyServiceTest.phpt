<?php
namespace {

	require __DIR__ . '/../bootstrap.php';
}
namespace Fakes {

	use Pipas\Rest\AService;
	use Pipas\Rest\Result\Contract;

	class FakeCompanyService extends AService
	{
		protected function getContractName()
		{
			return Contract::class;
		}
	}
}

namespace Tests {

	use Mockery;
	use Nette\Caching\IStorage;
	use Pipas\Rest\AContext;
	use Pipas\Rest\AService;
	use Pipas\Rest\IConnection;
	use Pipas\Rest\IContext;
	use Pipas\Rest\Result\Contract;
	use Tester\Assert;
	use Tester\TestCase;


	class FakeContext extends AContext
	{

		function __construct(IConnection $driver, IStorage $cacheStorage)
		{
			parent::__construct($driver, $cacheStorage);
			$this->addServiceMapping("*Service");
		}

		public function find($serviceName, $id)
		{

		}

		public function findAll($serviceName)
		{

		}

		public function findBy($serviceName, array $query)
		{

		}

		public function findOneBy($serviceName, array $query = array())
		{

		}

		public function validateToken($token)
		{

		}

		public function create($serviceName, array $entity)
		{

		}

		public function delete($uri)
		{

		}

		public function update($serviceName, array $entity)
		{

		}

	}

	class FakeCompanyService extends AService
	{
		protected function getContractName()
		{
			return Contract::class;
		}
	}

	class FakeService extends AService
	{

		const SERVICE_NAME = "person";

		public function getName()
		{
			return self::SERVICE_NAME;
		}

		protected function getContractName()
		{
			return Contract::class;
		}
	}

	class AReadOnlyServiceTest extends TestCase
	{

		/** @var Mockery\MockInterface|IContext */
		private $context;

		function setUp()
		{
			parent::setUp();
			$this->context = Mockery::mock(FakeContext::class);
		}

		private function createRepository()
		{
			return new FakeService($this->context);
		}

		function test_getContext()
		{
			$rep = $this->createRepository();
			Assert::same($this->context, $rep->getContext());
		}

		function test_getServiceNameByClassName()
		{
			$rep = new FakeCompanyService($this->context);
			Assert::equal("fakeCompany", $rep->getName());
		}

		function test_getServiceNameByClassNameUnderNamespace()
		{
			$rep = new \Fakes\FakeCompanyService($this->context);
			Assert::equal("fakeCompany", $rep->getName());
		}

		function test_find_delegateToContext()
		{
			$id = 1;
			$this->context->shouldReceive("find");
			$rep = $this->createRepository();
			$rep->find($id);
			$this->context->shouldHaveReceived("find", array(FakeService::SERVICE_NAME, $id));
			Assert::true(true);
		}

		function test_findAll_delegateToContext()
		{
			$this->context->shouldReceive("findAll");
			$rep = $this->createRepository();
			$rep->findAll();
			$this->context->shouldHaveReceived("findAll", array(FakeService::SERVICE_NAME));
		}

		function test_findBy_delegateToContext()
		{
			$id = array("key" => "value");
			$this->context->shouldReceive("findBy");
			$rep = $this->createRepository();
			$rep->findBy($id);
			$this->context->shouldHaveReceived("findBy", array(FakeService::SERVICE_NAME, $id));
		}

		function test_findOneBy_delegateToContext()
		{
			$id = array("key" => "value");
			$this->context->shouldReceive("findOneBy");
			$rep = $this->createRepository();
			$rep->findOneBy($id);
			$this->context->shouldHaveReceived("findOneBy", array(FakeService::SERVICE_NAME, $id));
		}

	}

	$test = new AReadOnlyServiceTest();
	$test->run();
}
