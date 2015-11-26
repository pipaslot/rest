<?php

namespace Pipas\Rest;

/**
 * Description of RestException
 *
 * @author Petr Štipek <p.stipek@email.cz>
 */
class RestException extends \Exception
{

    const CODE_NOT_IMPLEMENTED = 1,
        CODE_RESULTSET_NOT_SUCCESFULLY_LOADED = 2,
        CODE_NULL_VALUE = 3,
        CODE_NO_API_RESULT = 4,
        CODE_RECORD_NOT_FOUND = 5,
        CODE_NOT_INHERITED_FROM = 6,
        CODE_PROPERTY_MUST_BE_PROTECTED = 7;

    /*	 * ********* Static constructors ************* */

    /**
     * @return \self
     */
    public static function notImplemented()
    {
        return new self("Not implemented code", self::CODE_NOT_IMPLEMENTED);
    }

    /**
     * @return \self
     */
    public static function resultSetNotSuccesfullyLoaded($url)
    {
        return new self("Can not successfuly load data from query to: " . $url, self::CODE_RESULTSET_NOT_SUCCESFULLY_LOADED);
    }

    /**
     * @return \self
     */
    public static function nullValue($varName = null)
    {
        return new self("Variable " . ($varName != null ? "'$varName' " : "") . "cannot be NULL", self::CODE_NULL_VALUE);
    }

    /**
     * REST Api nevrací výsledek
     * @return \self
     */
    public static function noApiResult($message = "No API result")
    {
        return new static($message, self::CODE_NO_API_RESULT);
    }

    /**
     * Požadovaný záznam nebyl nalezen
     * @return \self
     */
    public static function recordNotFound($message = "Record not found")
    {
        return new static($message, self::CODE_RECORD_NOT_FOUND);
    }

    /**
     * Class must be inherited from parent
     * @return \self
     */
    public static function notInheritedForm($className, $expectedParent)
    {
        return new static("Class '$className' must by inherited from '$expectedParent'", self::CODE_NOT_INHERITED_FROM);
    }

    /**
     * Property must be protected
     * @return \self
     */
    public static function notProtectedProperty($className, $property)
    {
        return new static("Property '$property' defined in class '$className' must be protected", self::CODE_PROPERTY_MUST_BE_PROTECTED);
    }

}
