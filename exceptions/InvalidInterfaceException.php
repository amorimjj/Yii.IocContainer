<?php

/**
 * InvalidInterfaceException
 *
 * @author jamorim
 */
class InvalidInterfaceException extends InvalidArgumentException 
{
    public function __construct($interfaceName)
    {
        parent::__construct('Invalid interface: ' . $interfaceName, null, null);
    }
}

?>
