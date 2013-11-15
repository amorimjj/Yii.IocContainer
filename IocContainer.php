<?php

/**
 * IocContainer is base class to inversion of control
 *
 * @author Jeferson Amorim <amorimjj@gmail.com>
 */

require_once 'ioc/IocContainer.php';

class IocContainer extends CApplicationComponent
{
    /**
     * @var IocContainer 
     */
    private $_iocInstance = null;
    
    public function register($interfaceName, $className)
    {
        $this->_iocInstance->register($interfaceName, $className);
    }
    
    public function registerInstance($object, $instance)
    {
        return $this->_iocInstance->registerInstance($object, $instance);
    }
    
    public function getClassTo($interfaceName)
    {
        return $this->_iocInstance->getClassTo($interfaceName);
    }
    
    public function getInstance($object)
    {
        return $this->_iocInstance->getInstance($object);
    }
    
    public function getSingletonInstance($object)
    {
        return $this->_iocInstance->getSingletonInstance($object);        
    }
    
    public function setRegisters($registers)
    {
        $this->_iocInstance->setRegisters($registers);
    }
    
    public function __construct() {
        
        $this->_iocInstance = \ioc\IocContainer::getContainer();
        
        $loaderFunction = function($completeClassName){
            Yii::import($completeClassName);
            $data = explode(".",$completeClassName);
            return array_pop($data);
        };
        
        $this->_iocInstance->setExternalLoaderClass($loaderFunction);
    }

    public function init()
    {
        parent::init();
        $this->_iocInstance->reset();
    }
}
?>
