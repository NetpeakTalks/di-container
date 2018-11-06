<?php
/**
 * @author Doctor <doctor.netpeak@gmail.com>
 */

use App\DI\ParameterDIReference as PR;
use App\DI\ServiceDIReference as SR;
use App\DI\TagDIReference as TR;

return [
    // ----------- COMMANDS -----------

    "command.help" => [
        'class' => \App\Commands\HelpCommand::class,
        'arguments' => [
            new TR('allowed.command'),
            new PR('app.title'),
            new PR('app.version'),
            new PR('app.desc'),
        ],

        'tags' => ['cli.command']
    ],

    "command.test" => [
        'class' => \App\Commands\TestCommand::class,
        'tags' => ['cli.command', 'allowed.command']
    ],



    // ----------- SERVICES -----------

    "error.handler" => [
        'class' => \App\Services\ErrorHandler::class,
        'arguments' => [
            new SR('writer.cli'),
        ],
    ],


    "writer.cli" => [
        'class' => \App\Services\CliWriter::class,
    ],


];