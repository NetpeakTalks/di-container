<?php
/**
 * @author Doctor <doctor.netpeak@gmail.com>
 */

namespace App\Services;


use App\Interfaces\IOutputWriter;

class CliWriter implements IOutputWriter
{
    /**
     * @param string $msg
     */
    public function write(string $msg): void
    {
        echo $msg;
    }

    /**
     * @param string $msg
     */
    public function writeLn(string $msg): void
    {
        $this->write($msg . PHP_EOL);
    }
}