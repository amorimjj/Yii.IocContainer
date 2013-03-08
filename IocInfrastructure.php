<?php

/**
 * IocInfrastructure
 *
 * @author amorimjj
 */
class IocInfrastructure
{
    /**
     *
     * @param string Yii complete alias to class
     * @return string Name of class 
     */
    public static function getClassFromCompleteClassName($completeClassName)
    {
        $data = explode(".",$completeClassName);
        return array_pop($data);
    }
}

?>
