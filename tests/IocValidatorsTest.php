<?php

require_once '../IocValidators.php';
require_once 'fakes/SubjectFake.php';

class IocValidatorsTest extends PHPUnit_Framework_TestCase {

    public function testIsInterface_WhenReceiveAnInvalidInterface_ShouldReturnFalse()
    {
        $this->assertFalse(IocValidators::isInterface('InvalideClass'));
    }
    
    public function testIsInterface_WhenReceiveAValidInterface_ShouldReturnTrue()
    {
        $this->assertTrue(IocValidators::isInterface('SplSubject'));
    }
    
    public function testIsValidClass_WhenReceiveAnInvalidClass_ShouldReturnFalse()
    {
        $this->assertFalse(IocValidators::isValidClass('SplSubject'));
    }
    
    public function testIsValidClass_WhenReceiveAValidClass_ShouldReturnTrue()
    {
        $this->assertTrue(IocValidators::isValidClass('IocValidators'));
    }
    
    public function testIsInterfaceImplementedByClass_WhenInterfaceAndClassIsValidButClassDoesntImplmentsInterface_ShouldReturnFalse()
    {
        $this->assertFalse(IocValidators::isInterfaceImplementedByClass('SplSubject', 'IocValidators'));
    }
    
    public function testIsInterfaceImplementedByClass_WhenInterfaceAndClassIsValidAndInterfaceIsImplmentedByClass_ShouldReturnTrue()
    {
        $this->assertTrue(IocValidators::isInterfaceImplementedByClass('SplSubject', 'SubjectFake'));
    }
    
    public function testIsValidYiiNamespace_WhenReceiveAValidNamespace_ShouldReturnTrue()
    {
        $this->assertTrue(IocValidators::isValidYiiNamespace('application.controller.MyController'));
    }
    
    public function testIsValidYiiNamespace_WhenReceiveAnInvalidNamespace_ShouldReturnFalse()
    {
        $this->assertFalse(IocValidators::isValidYiiNamespace('\Namespace\lib\ClassName'));
    }
} 
