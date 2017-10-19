<?php

namespace VnsServices\DpdPostcodes;

use VnsServices\DpdPostcodes\Entity\EntityFactory;
use VnsServices\DpdPostcodes\Entity\EntityInterface;
use VnsServices\DpdPostcodes\Manager\FileManager;
use VnsServices\DpdPostcodes\Loader\FileLoaderInterface;

/**
 * Class Geogaz
 * 
 * @package VnsServices\DpdPostcodes
 */
class Geogaz
{
    /**
     * @var FileManager
     */
    private $file_manager;

    /**
     * @var string
     */
    private $file_last_export_version;

    /**
     * @var string
     */
    private $file_version;

    /**
     * @var array
     */
    private $file_scheme = [];

    /**
     * @var array
     */
    public $data = [
        'services' => null,
        'groups' => null,
        'domestic' => null,
    ];

    /**
     * Geogaz constructor.
     * 
     * @param $file_last_export_version
     */
    public function __construct($file_last_export_version)
    {
        $this->file_last_export_version = $file_last_export_version;
    }

    /**
     * Geogaz local server's file manager initialization. 
     * 
     * @param array $options
     * @param FileLoaderInterface $loader
     */
    public function initFileManager(array $options, FileLoaderInterface $loader)
    {
        $this->file_manager = new FileManager($options, $loader);
    }

    /**
     * @return bool
     * @throws \Exception
     */
    protected function parse()
    {
        $file = $this->file_manager->get();

        $handle = fopen($file, 'r');
        $first_row = fgets($handle);

        $this->parseFileHeader($first_row);

        if (!$this->isFileHasNewerVersion()) {
            $this->file_manager->delete();
            throw new \Exception('Last export version of the file is the same with the remote one!');
        }

        $type = null;
        $is_row_readable = true;

        while (($row = fgets($handle)) !== false) {

            if (preg_match('/\#\-{13}\|[\|\-]+/', $row)) {
                $is_row_readable = false;
                continue;
            }

            if (preg_match('/\#([\w]+)\|[ \w\|]+/', $row, $matches)) {
                $type = strtolower($matches[1]);

                if (array_key_exists($type, $this->data)) {
                    $this->data[$type] = EntityFactory::factory($type);
                    $this->data[$type]->parseScheme($row);
                    $is_row_readable = true;
                    continue;
                }
            }

            if ($is_row_readable && $type && ($this->data[$type] instanceof EntityInterface)) {
                $this->data[$type]->addRow($row);
            }
        }

        if (!feof($handle)) {
            throw new \Exception('Unexpected file parse fail!');
        }

        $this->file_manager->delete();

        return true;
    }

    /**
     * @return array|bool
     */
    public function getData() {

        if (!$this->parse()) {
            return false;
        }

        $this->prepareData();

        return array_values($this->data);
    }

    /**
     * Prepare Geogaz file data for output
     */
    protected function prepareData()
    {
        $services_confirmed = $this->data['services']->getActive();

        $this->data['groups']->validateServiceAvailability($services_confirmed);
    }

    /**
     * @param $first_row
     * @throws \Exception
     */
    protected function parseFileHeader($first_row)
    {
        if (!$first_row) {
            throw new \Exception('Unexpected file parse fail!');
        }

        $cols = explode('|', $first_row);

        $this->setFileVersion($cols[1]);
        $this->setFileScheme('cols_number', count($cols));
    }

    /**
     * @param int|string $file_version
     */
    protected function setFileVersion($file_version)
    {
        $this->file_version = (int)$file_version;
    }

    /**
     * @return string
     */
    public function getFileVersion()
    {
        return $this->file_version;
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    protected function setFileScheme($key, $value)
    {
        $this->file_scheme[$key] = $value;
    }

    /**
     * @return bool
     */
    protected function isFileHasNewerVersion()
    {
        return ($this->file_version > $this->file_last_export_version);
    }

}
