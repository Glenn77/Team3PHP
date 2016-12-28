<?php
/**
 * Created by PhpStorm.
 * User: Ludovic
 * Date: 09-11-16
 * Time: 20:55
 */

namespace App\Traits;

trait ExceptionTrait
{
    public function beautifyException(\Exception $e)
    {
        try
        {
            $errorInfo = ((array) $e)['errorInfo'];

            $errorInfo['SQLSTATE'] = $errorInfo[0];
            $errorInfo['Violation'] = $errorInfo[1];
            $errorInfo['Message'] = $errorInfo[2];

            unset($errorInfo[0]);
            unset($errorInfo[1]);
            unset($errorInfo[2]);
        }
        catch (\Exception $e)
        {
            $errorInfo['Violation'] = $e->getFile() . ' on line ' . $e->getLine();
            $errorInfo['Message'] = $e->getMessage();
        }

        return $errorInfo;
    }
}