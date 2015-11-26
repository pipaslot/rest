<?php

namespace Pipas\Rest;

use Nette\Caching\Cache;
use Nette\Caching\IStorage;
use Pipas\Rest\Result\ResultMapper;

/**
 * Basic context for connection to API
 *
 * @author Petr Stipek <p.stipek@email.cz>
 */
abstract class AContext implements IContext
{

    private $repositories = array();

    /**
     * @var Cache
     */
    protected $cache;

    /**
     * @var IDriver
     */
    protected $driver;

    /** @var  ResultMapper */
    protected $resultMapper;

    function __construct(IDriver $driver, IStorage $cacheStorage)
    {
        $this->driver = $driver;
        $this->cache = new Cache($cacheStorage, get_called_class());
        $this->resultMapper = ResultMapper::get();
    }

    /**
     * Return drive for connection to the API via REST
     * @return IDriver
     */
    public function getDriver()
    {
        return $this->driver;
    }

    /**
     *
     * @param string $name
     * @return IService
     */
    public function getRepository($name)
    {
        if (!isset($this->repositories[$name])) {
            $class = get_called_class();
            $slashPos = strrpos($class, "\\");
            $repositoryClass = substr($class, 0, $slashPos) . "\\Repository\\" . ucfirst($name) . "Repository";
            $this->repositories[$name] = new $repositoryClass($this);
        }
        return $this->repositories[$name];
    }


    public function __get($name)
    {
        return $this->getRepository($name);
    }

}
