<?php

// change the following paths if necessary
$yiit=dirname(__FILE__).'/../../../framework.yii/yiit.php';
$config=dirname(__FILE__).'/../core/presentation/config/test.php';

require_once($yiit);

Yii::setPathOfAlias('ext', dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR);
//require_once( Yii::getPathOfAlias('system.test.CTestCase').'.php' );
//require_once(dirname(__FILE__).'/WebTestCase.php');

//Yii::createWebApplication(array());
