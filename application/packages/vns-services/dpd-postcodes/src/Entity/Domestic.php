<?php

namespace VnsServices\DpdPostcodes\Entity;

/**
 * Class Domestic
 *
 * @package VnsServices\DpdPostcodes\Entity
 */
class Domestic extends AbstractEntity
{
    /**
     * @return array
     */
    public function getRows()
    {
        $this->beforeOutput();
        return $this->rows;
    }

    protected function beforeOutput()
    {
        foreach ($this->rows as &$value) {
            $value['postcode_sector'] = str_replace(' ', '' , $value['postcode_sector']);
            $value['new_postcode'] = str_replace(' ', '' , $value['new_postcode']);
        }
        unset($value);
    }
}

