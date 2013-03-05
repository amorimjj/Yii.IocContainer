<?php

/**
 * InvalidInterfaceException
 *
 * @author jamorim
 */
class InvalidClassToInterfaceException extends InvalidArgumentException 
{
    public function __construct($className, $interfaceName)
    {
        parent::__construct('Class '.$className.' is invalid to interface '.$interfaceName, null, null);
    }
}

?>
