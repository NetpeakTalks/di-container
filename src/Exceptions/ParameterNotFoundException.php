<?php
/**
 * @author Doctor <doctor.netpeak@gmail.com>
 */


namespace App\Exceptions;


class ParameterNotFoundException extends ContainerException
{
    public $message = 'Parameter Not Found';
}