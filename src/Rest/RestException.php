<?php

namespace Pipas\Rest;

/**
 *
 * @author Petr Štipek <p.stipek@email.cz>
 */
class RestException extends \Exception
{
    const CODE_NOT_IMPLEMENTED = 1,
		CODE_RESULT_SET_NOT_SUCCESSFULLY_LOADED = 2,
        CODE_NULL_VALUE = 3,
        CODE_NO_API_RESULT = 4,
        CODE_RECORD_NOT_FOUND = 5,
        CODE_NOT_INHERITED_FROM = 6,
        CODE_PROPERTY_MUST_BE_PROTECTED = 7;

    /*	 * ********* Static constructors ************* */

	protected $data;

	/**
	 * @return mixed
	 */
	public function getData()
	{
		return $this->data;
	}

	/**
	 * @param mixed $data
	 * @return $this
	 */
	public function setData($data)
	{
		$this->data = $data;
		return $this;
	}

    /**
     * @return \self
     */
    public static function notImplemented()
    {
        return new self("Not implemented code", self::CODE_NOT_IMPLEMENTED);
    }

	/**
	 * @param string $url
	 * @param null|mixed $result
	 * @return RestException
	 */
	public static function resultSetNotSuccessfullyLoaded($url,$result = null)
    {
    	$e = new self("Can not successfully load data from query to: " . $url, self::CODE_RESULT_SET_NOT_SUCCESSFULLY_LOADED);
		$e->setData($result);
		return $e;
    }

	/**
	 * @param string|null $varName
	 * @return RestException
	 */
    public static function nullValue($varName = null)
    {
        return new self("Variable " . ($varName != null ? "'$varName' " : "") . "cannot be NULL", self::CODE_NULL_VALUE);
    }

	/**
	 * REST API does not returns result
	 * @param string $message
	 * @return RestException
	 */
    public static function noApiResult($message = "No API result")
    {
        return new static($message, self::CODE_NO_API_RESULT);
    }

	/**
	 * Required record was not found
	 * @param string $message
	 * @return RestException
	 */
    public static function recordNotFound($message = "Record not found")
    {
        return new static($message, self::CODE_RECORD_NOT_FOUND);
    }

	/**
	 * Class must be inherited from parent
	 * @param string $className
	 * @param string $expectedParent
	 * @return RestException
	 */
    public static function notInheritedForm($className, $expectedParent)
    {
        return new static("Class '$className' must by inherited from '$expectedParent'", self::CODE_NOT_INHERITED_FROM);
    }

	/**
	 * Property must be protected
	 * @param string $className
	 * @param string $property
	 * @return RestException
	 */
    public static function notProtectedProperty($className, $property)
    {
        return new static("Property '$property' defined in class '$className' must be protected", self::CODE_PROPERTY_MUST_BE_PROTECTED);
    }

}
