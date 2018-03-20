<?php
/**
 * Created by PhpStorm.
 * User: SI
 * Date: 2018/3/18
 * Time: 22:59
 */

namespace App\Exceptions;
class wxException extends \Exception
{
    function __construct($msg='', $code = 0)
    {
        parent::__construct($msg, $code );
    }
}