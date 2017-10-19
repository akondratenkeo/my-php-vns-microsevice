<?php

namespace VnsServices\DpdPostcodes\Entity;

/**
 * Class Services
 *
 * @package VnsServices\DpdPostcodes\Entity
 */
class Services extends AbstractEntity
{
    /**
     * @var array
     */
    protected $active;

    /**
     * Services constructor.
     *
     * @param array $active
     */
    public function __construct(array $active)
    {
        $this->active = $active;
    }

    /**
     * @return array
     */
    public function getActive()
    {
        $services = [];
        foreach ($this->getRows() as $key => $value) {
            if (in_array($value['2_digit_service_code'], $this->active)) {
                $services[] = $value;
            }
        }

        return $services;
    }
}

