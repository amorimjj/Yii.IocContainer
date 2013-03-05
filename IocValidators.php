<?php
/**
 * IocValidators is the validator class
 *
 * @author Jeferson Amorim <amorimjj@gmail.com>
 */
class IocValidators
{
    /**
     * @param string $interfaceName interface name to check
     * @return boolean
     */
    public static function isInterface($interfaceName)
    {
        return @interface_exists($interfaceName);
    }
    
    /**
     * @param type $className class name to check
     * @return boolean
     */
    public static function isValidClass($className)
    {
        return @class_exists($className);
    }
    
    public static function isInterfaceImplementedByClass($interfaceName, $className)
    {
        $class = new ReflectionClass($className);
        return $class->implementsInterface($interfaceName);
    }

}

?>
