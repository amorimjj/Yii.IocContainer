<?php

/**
 * UnregisteredInterfaceException
 *
 * @author jamorim
 */
class UnregisteredInterfaceException extends InvalidArgumentException
{
    public function __construct($interfaceName)
    {
        parent::__construct('Interface '.$interfaceName.' was not registered', null, null);
    }
}

?>
