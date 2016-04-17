<?php
namespace Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

class Functional extends \Codeception\Module
{
    /**
     * See exception is thrown
     * @param  mixed $exception
     * @param  mixed $function
     * @return boolean
     */
    public function seeExceptionThrown($exception, $function)
    {
        try
        {
            $function();
            return false;
        } catch (Exception $e) {            
            if( get_class($e) == $exception ){
                return true;
            }
            return false;
        }
    }
}
