<?php

/**
 * InvalidInterfaceException
 *
 * @author jamorim
 */
class InvalidInstanceException extends InvalidArgumentException 
{
    public function __construct($object)
    {
        parent::__construct('Instance is invalid to '.$object, null, null);
    }
}

?>
