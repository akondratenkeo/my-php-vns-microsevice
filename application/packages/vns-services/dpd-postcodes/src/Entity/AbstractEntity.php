<?php

namespace VnsServices\DpdPostcodes\Entity;

/**
 * Class AbstractEntity
 *
 * @package VnsServices\DpdPostcodes\Entity
 */
abstract class AbstractEntity implements EntityInterface
{
    /**
     * @var array
     */
    protected $entity_scheme;

    /**
     * @var array
     */
    protected $rows;

    /**
     * @param $row
     */
    public function addRow($row)
    {
        if (!$this->entity_scheme) {
            throw new \InvalidArgumentException(__CLASS__.'::$entity_scheme should not be empty!');
        }

        $values = explode('|', trim($row));
        array_shift($values);

        $assoc = [];
        for ($i = 0, $count = count($this->entity_scheme); $i < $count; $i++) {
            if ($this->entity_scheme[$i]) {
                $assoc[$this->entity_scheme[$i]] = $values[$i];
            }
        }

        $this->rows[] = $assoc;
    }

    /**
     * @param string $header
     */
    public function parseScheme($header)
    {
        $header = trim(str_replace(' ', '_' ,strtolower($header)));
        $keys = explode('|', $header);

        array_shift($keys);
        $this->entity_scheme = $keys;
    }

    public function getScheme()
    {
        return array_keys($this->rows[0]);
    }

    /**
     * @return array
     */
    public function getRows()
    {
        return $this->rows;
    }
}

