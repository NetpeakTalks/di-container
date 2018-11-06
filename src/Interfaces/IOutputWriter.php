<?php
/**
 * @author Doctor <doctor.netpeak@gmail.com>
 */

namespace App\Interfaces;


interface IOutputWriter
{
    /**
     * @param string $msg
     */
    public function write(string $msg) : void;

    /**
     * @param string $msg
     */
    public function writeLn(string $msg) : void;
}