<?php

require '../IocContainer.php';
require 'fakes/ITest.php';
require 'fakes/SubjectFake.php';
require 'fakes/ClassTest1.php';
require 'fakes/ClassTest2.php';
require 'fakes/ClassTest3.php';
require 'fakes/ClassTest4.php';
require 'fakes/ClassTest5.php';
require 'fakes/ClassTest6.php';

class IocContainerTest extends PHPUnit_Framework_TestCase {

    private $_ioc;
    
    public function setup()
    {
        $this->_ioc = new IocContainer();
    }
    
    public function testRegister_WhenTryRegisterAClassToAnInvalidInterface_ShouldThrowInvalidInterfaceException() {
        $this->setExpectedException('InvalidInterfaceException', 'Invalid interface: IInvalid');
        $this->_ioc->register('IInvalid', 'Class');
    }
    
    public function testRegister_WhenTryRegisterAClassToAValidInterfaceButClassIsInvalid_ShouldThrowInvalidClassException() {
        $this->setExpectedException('InvalidClassException', 'Invalid class: Class');
        $this->_ioc->register('SplSubject', 'Class');
    }
    
    public function testRegister_WhenTryRegisterAClassToAValidInterfaceButClassDoesntImplementsIt_ShouldThrowInvalidClassToInterfaceException()
    {
        $this->setExpectedException('InvalidClassToInterfaceException', 'Class IocValidators is invalid to interface SplSubject');
        $this->_ioc->register('SplSubject', 'IocValidators');
    }
    
    public function testRegister_WhenTryRegisterAClassToAValidInterfaceWhenImplementsIt_CantThrowException()
    {
        $this->_ioc->register('SplSubject', 'SubjectFake');
    }
    
    public function testRegister_WhenTryRegisterAClassToAValidInterfaceWhenImplementsIt_ShouldRegisterClassToInteface()
    {
        $this->_ioc->register('SplSubject', 'SubjectFake');
        $this->assertEquals('SubjectFake', $this->_ioc->getClassTo('SplSubject'));
    }
    
    public function testGetClassTo_WhenTryRetrieveANotRegisteredInterface_ShouldThrowUnregisteredInterfaceException()
    {
        $this->setExpectedException('UnregisteredInterfaceException', 'Interface SplObject was not registered');
        $this->_ioc->getClassTo('SplObject');
    }
    
    public function testGetInstance_WhenTryRetrieveAInstanceForAUnregisteredInterface_ShouldThrowUnregisteredInterfaceException()
    {
        $this->setExpectedException('UnregisteredInterfaceException','Interface SplObserver was not registered');
        $this->_ioc->getInstance('SplObserver');
    }
    
    public function testGetInstance_WhenTryRetrieveAInstanceForARegisteredInterface_ShouldReturnASolicitedInstance()
    {
        $this->_ioc->register('SplSubject', 'SubjectFake');
        $this->assertInstanceOf('SubjectFake', $this->_ioc->getInstance('SplSubject'));
    }
    
    public function testGetInstance_WhenTryRetrieveAnInstaceToAnInvalidClass_ShouldThrowInvalidClassException()
    {
        $this->setExpectedException('InvalidClassException', 'Invalid class: Class');
        $this->_ioc->getInstance('Class');
    }
    
    public function testGetInstance_WhenTryRetreiveAnInstanceToAValidClass_ShouldReturnAClassInstance()
    {
        $this->assertInstanceOf('ClassTest1', $this->_ioc->getInstance('ClassTest1'));
    }
    
    public function testGetInstance_WhenTryRetreiveAnInstanceToAValidClassWitchNeedParametersOnConstructor_ShouldReturnAClassInstance()
    {
        $this->_ioc->register('ITest', 'ClassTest2');
        $this->assertInstanceOf('ClassTest3', $this->_ioc->getInstance('ClassTest3'));
    }
    
    public function testGetInstance_WhenTryRetreiveAnInstanceToAValidClass_ShouldReturnAClassInstance1()
    {
        $this->assertInstanceOf('ClassTest4', $this->_ioc->getInstance('ClassTest4'));
    }
    
    public function testGetInstance_WhenTryRetreiveAnInstanceToAValidClass_ShouldReturnAClassInstance2()
    {
        $this->_ioc->register('ITest', 'ClassTest6');
        $this->assertInstanceOf('ClassTest5', $this->_ioc->getInstance('ClassTest5'));
    }
}
