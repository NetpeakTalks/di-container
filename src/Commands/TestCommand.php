<?php
/**
 * @author Doctor <doctor.netpeak@gmail.com>
 */

namespace App\Commands;


use App\Exceptions\CliCommandException;
use App\Interfaces\ICliCommand;

class TestCommand implements ICliCommand
{
    const NAME = 'test';

    public function __construct()
    {
    }

    /**
     * @return string
     */
    public static function getCommandName(): string
    {
        return static::NAME;
    }

    /**
     * @return string
     */
    public static function getCommandDesc(): string
    {
        return 'test command';
    }

    /**
     * @param array $params
     * @throws CliCommandException
     * @return void
     */
    public function run(array $params = []): void
    {
        echo 'This is TEST command' . PHP_EOL;
    }
}