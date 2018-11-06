<?php
/**
 * @author Doctor <doctor.netpeak@gmail.com>
 */

namespace App\Interfaces;


use App\Exceptions\CliCommandException;

interface ICliCommand
{
    /**
     * @return string
     */
    public static function getCommandName() :string;

    /**
     * @return string
     */
    public static function getCommandDesc() :string;

    /**
     * @param array $params
     * @throws CliCommandException
     * @return void
     */
    public function run(array $params = []) :void;
}