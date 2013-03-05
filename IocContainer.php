<?php

/**
 * IocContainer is base class to inversion of control
 *
 * @author Jeferson Amorim <amorimjj@gmail.com>
 */

Yii::import('ext.IocContainer.IocValidators');
Yii::import('ext.IocContainer.exceptions.*');

class IocContainer extends CApplicationComponent
{
    private $_registers = array();
    private $_registeredInstances = array();
    
    /**
     * 
     * @param string $interfaceName Name of interface
     * @param string $className Name of class
     * @throws InvalidInterfaceException
     * @throws InvalidClassException
     * @throws InvalidClassToInterfaceException
     */
    protected function validateRegister($interfaceName, $className)
    {
        if ( !IocValidators::isInterface($interfaceName))
            throw new InvalidInterfaceException($interfaceName);
        
        if ( !IocValidators::isValidClass($className))
            throw new InvalidClassException($className);
        
        if ( !IocValidators::isInterfaceImplementedByClass($interfaceName, $className) )
                throw new InvalidClassToInterfaceException($className, $interfaceName);
    }
    
    protected function getInstanceToInterface($interfaceName)
    {
        $className = $this->getClassTo($interfaceName);
        return $this->getInstanceToClass($className);
    }
    
    protected function buildParameterArgument(ReflectionParameter $parameter)
    {
        if ( !$parameter->getClass() )
            return null;
        
        return $this->getInstance($parameter->getClass()->name);
    }
    
    protected function getConstructorArguments(ReflectionMethod $constructor)
    {
        $constructorArguments = array();

       foreach ($constructor->getParameters() as $parameter)
        {
            $constructorArguments[] = $this->buildParameterArgument($parameter);
        }
        
        return $constructorArguments;
    }
    
    protected function getInstanceToClass($className)
    {
        if ( !IocValidators::isValidClass($className))
            throw new InvalidClassException($className);
        
        $class = new ReflectionClass($className);
        $constructor = $class->getConstructor();
        
        if ( ! $constructor = $class->getConstructor() )
            return $class->newInstance();
        
        return $class->newInstanceArgs($this->getConstructorArguments($constructor));
    }
    
    public function register($interfaceName, $className)
    {
        $this->validateRegister($interfaceName, $className);
        $this->_registers[$interfaceName] = $className;
    }
    
    public function registerInstance($object, $instance)
    {
        if ( ! $instance instanceof $object )
            throw new InvalidInstanceException($object);
        
        $this->_registeredInstances[$object] = $instance;
    }
    
    public function getClassTo($interfaceName)
    {
        if ( !isset($this->_registers[$interfaceName]))
            throw new UnregisteredInterfaceException($interfaceName);
        
        return $this->_registers[$interfaceName];
    }
    public function getInstance($object)
    {
        if ( isset($this->_registeredInstances[$object]) )
            return $this->_registeredInstances[$object];
        
        if ( IocValidators::isInterface($object))
            return $this->getInstanceToInterface($object);
        
        return $this->getInstanceToClass($object);
    }
    
    public function setRegisters($registers)
    {
        if (!is_array($registers))
            throw new InvalidArgumentException('Argument registers should be an array');
        
        foreach ( $registers as $interface => $completeClassName)
        {
            Yii::import($completeClassName);
            $this->register($interface, $this->getClassFromCompleteClassName($completeClassName));            
        }
    }
    
    protected function getClassFromCompleteClassName($completeClassName)
    {
        $data = explode(".",$completeClassName);
        return array_pop($data);
    }
}
?>