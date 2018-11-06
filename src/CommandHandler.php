<?php
/**
 * @author Doctor <doctor.netpeak@gmail.com>
 */

namespace App;


use App\Commands\HelpCommand;
use App\Exceptions\CliCommandException;
use App\Exceptions\ServiceNotFoundException;
use App\Interfaces\ICliCommand;
use App\Interfaces\IDIContainer;
use App\Interfaces\IOutputWriter;
use App\Services\ErrorHandler;

class CommandHandler
{
    /**
     * @var IDIContainer
     */
    protected $container;

    /**
     * @var array
     */
    protected $commandsInstances = [];

    /**
     * IDIContainer constructor.
     * @param ConfigHandler $container
     */
    public function __construct(IDIContainer $container)
    {
        $this->container = $container;
    }

    /**
     * @param $params
     * @throws CliCommandException
     * @throws ServiceNotFoundException
     */
    public function handle($params)
    {
        $command = HelpCommand::NAME;
        array_splice($params, 0, 1);

        if (false === empty($params)) {
            $command = current($params);
            array_splice($params, 0, 1);
        }


        try {
            /**
             * @var ICliCommand $service
             */
            $service = $this->container->get('command.' . $command);
            $service->run($params);
        } catch (\Exception $e) {
            /**
             * @var ErrorHandler $errorHandler
             */
            $errorHandler = $this->container->get('error.handler');
            $errorHandler->handle($e);
        }
    }
}