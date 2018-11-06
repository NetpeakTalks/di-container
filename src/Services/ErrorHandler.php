<?php
/**
 * @author Doctor <doctor.netpeak@gmail.com>
 */

namespace App\Services;


use App\Interfaces\IOutputWriter;

class ErrorHandler
{
    /**
     * @var IOutputWriter
     */
    protected $writer;

    /**
     * ErrorHandler constructor.
     * @param IOutputWriter $writer
     */
    public function __construct(IOutputWriter $writer)
    {
        $this->writer = $writer;
    }

    /**
     * @param \Throwable $e
     */
    public function handle(\Throwable $e)
    {
        $this->writer->write("ERROR: ");
        $this->writer->writeLn($e->getMessage());
        $this->writer->writeLn($e->getFile() .' Line: ' . $e->getLine());
    }

}