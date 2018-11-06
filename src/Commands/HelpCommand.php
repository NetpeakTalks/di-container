<?php
/**
 * @author Doctor <doctor.netpeak@gmail.com>
 */

namespace App\Commands;


use App\Exceptions\CliCommandException;
use App\Interfaces\ICliCommand;

class HelpCommand implements ICliCommand
{
    const NAME = 'help';

    /**
     * @var array
     */
    protected $allowedCommands = [];

    /**
     * @var string
     */
    protected $desc;

    /**
     * HelpCommand constructor.
     * @param array $allowedCommands
     * @param string $title
     * @param string $version
     * @param string $desc
     */
    public function __construct(array $allowedCommands, $title, $version, $desc)
    {
        $this->allowedCommands = $allowedCommands;
        $this->desc = $title . ' ver.' . $version . ': ' . $desc;
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
        return 'Print help message';
    }

    /**
     * @param array $params
     * @throws CliCommandException
     * @return void
     */
    public function run(array $params = []): void
    {
        $message[] =  $this->desc;
        $message[] = "";
        $message[] = "Allowed commands:";
        array_push($this->allowedCommands, $this);

        /**
         * @var ICliCommand $commandClass
         */
        foreach ($this->allowedCommands as $commandClass) {
            $message[] = sprintf(
                "%s - %s",
                $commandClass::getCommandName(),
                $commandClass::getCommandDesc()
            );
        }
        $message[] = "";

        echo implode(PHP_EOL, $message);
    }
}