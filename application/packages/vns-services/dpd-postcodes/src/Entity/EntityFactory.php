<?php

namespace VnsServices\DpdPostcodes\Entity;

/**
 * Class EntityFactory
 *
 * @package VnsServices\DpdPostcodes\Entity
 */
final class EntityFactory
{
    /**
     * @param string $type
     *
     * @return EntityInterface
     */
    public static function factory($type)
    {
        if ($type == 'services') {
            return new Services(['11', '12']);
        }

        if ($type == 'groups') {
            return new Groups();
        }

        if ($type == 'domestic') {
            return new Domestic();
        }

        throw new \InvalidArgumentException('Unknown format given');
    }
}
