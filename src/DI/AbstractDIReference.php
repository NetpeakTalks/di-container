<?php
/**
 * @author Doctor <doctor.netpeak@gmail.com>
 */

namespace App\DI;


abstract class AbstractDIReference
{
    /**
     * @var string
     */
    protected $name;

    /**
     * AbstractDIReference constructor.
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName() :string
    {
        return $this->name;
    }
}