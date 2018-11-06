<?php
/**
 * @author Doctor <doctor.netpeak@gmail.com>
 */


namespace App\Exceptions;


class ServiceNotFoundException extends ContainerException
{
    public $message = 'Service Not Found';
}