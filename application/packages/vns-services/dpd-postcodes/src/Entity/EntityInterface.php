<?php

namespace VnsServices\DpdPostcodes\Entity;

/**
 * Interface EntityInterface
 *
 * @package VnsServices\DpdPostcodes\Entity
 */
interface EntityInterface
{
    public function addRow($row);

    public function parseScheme($row);

    public function getScheme();

    public function getRows();
}

