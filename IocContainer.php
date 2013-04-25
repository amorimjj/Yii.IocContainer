<?php

/**
 * IocContainer is base class to inversion of control
 *
 * @author Jeferson Amorim <amorimjj@gmail.com>
 */

Yii::import('ext.IocContainer.IocValidators');
Yii::import('ext.IocContainer.IocInfrastructure');
Yii::import('ext.IocContainer.exceptions.*');

class IocContainer extends CApplicationComponent
{
    private $_registers = array();
    private $_registeredInstances = array();
    private $_initRegisters = array();
    private $_singletonInstances = array();
    
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
    
    protected function getParametersToInstance($instance)
    {
        foreach ($this->_initRegisters as $value)
        {
            if (is_array($value) && $value['class'] == get_class($instance) )
                return $value;
        }
        
        return array();
    }
    
    protected function setInstanceProperty($instance, $property, $value)
    {
       if ( ! property_exists($instance, $property ) )
           throw new InvalidArgumentException('Class \'' .  get_class($instance) . '\' doesn\'t have a property called \''. $property . '\'');
     
       $instance->{$property} = $value;
    }


    protected function startParameters($instance)
    {
        foreach( $this->getParametersToInstance($instance) as $key => $value )
            if ( $key !== 'class') $this->setInstanceProperty($instance, $key, $value);
            
        return $instance;
    }


    protected function getInstanceToClass($className)
    {
        if ( !IocValidators::isValidClass($className))
            throw new InvalidClassException($className);
        
        $class = new ReflectionClass($className);
        
        if ( ! $constructor = $class->getConstructor() )
            return $class->newInstance();
        
        return $class->newInstanceArgs($this->getConstructorArguments($constructor));
    }
    
    protected function registerFromInitIfFound($object)
    {
        if (isset($this->_initRegisters[$object]) && !isset($this->_registeredInstances[$object]))
        {
            $completeClassName = is_array($this->_initRegisters[$object]) ? $this->_initRegisters[$object]['class'] : $this->_initRegisters[$object];
            Yii::import($completeClassName);
            
            if ( IocValidators::isInterface($object))
                $this->register($object, IocInfrastructure::getClassFromCompleteClassName($completeClassName));
            else 
                $this->registerInstance($object, $this->getInstance(IocInfrastructure::getClassFromCompleteClassName($completeClassName)));
        }   
    }
    
    protected function getInstanceFromRegisters($object)
    {
        $this->registerFromInitIfFound($object);
        
        if ( isset($this->_registeredInstances[$object]) )
            return $this->_registeredInstances[$object];
    }

    public function register($interfaceName, $className)
    {
        $this->validateRegister($interfaceName, $className);
        $this->_registers[$interfaceName] = $className;
    }
    
    public function registerInstance($object, $instance)
    {
        if ( ! IocValidators::isInstanceValidToObject($object, $instance) )
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
        if ( ($instance = $this->getInstanceFromRegisters($object)) )
            return $instance;
       
       $instance =  IocValidators::isInterface($object) ? $this->getInstanceToInterface($object) : $this->getInstanceToClass($object);
       $this->startParameters($instance);
       
       return $instance;
    }
    
    public function getSingletonInstance($object)
    {
        if ( ! isset($this->_singletonInstances[$object]))
            $this->_singletonInstances[$object] = $this->getInstance($object);
            
        return $this->_singletonInstances[$object];
        
    }
    
    public function setRegisters($registers)
    {
        if (!IocValidators::isValidRegister($registers))
            throw new InvalidArgumentException('Argument registers should be an array');
        
        $this->_initRegisters = $registers;
    }
    
    public function init()
    {
        parent::init();
        $this->_registers = array();
        $this->_registeredInstances = array();
        $this->_singletonInstances = array();
    }
}
?>