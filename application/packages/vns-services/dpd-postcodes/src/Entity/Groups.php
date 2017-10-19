<?php

namespace VnsServices\DpdPostcodes\Entity;

/**
 * Class Groups
 *
 * @package VnsServices\DpdPostcodes\Entity
 */
class Groups extends AbstractEntity
{
    public function validateServiceAvailability(array $services)
    {
        foreach ($this->rows as $key => &$value) {

            foreach ($services as $service) {
                $service_position = (int)$service['2_digit_service_code'] - 1;
                $value['service_'. $service['2_digit_service_code']] = (int)$value['list_of_available_services'][$service_position];
            }
        }

        unset($value);
    }
}

