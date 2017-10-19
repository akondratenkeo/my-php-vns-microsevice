<?php

namespace VnsServices\DpdPostcodes\Manager;

use VnsServices\DpdPostcodes\Loader\FileLoaderInterface;

/**
 * Class FileManager
 *
 * @package VnsServices\DpdPostcodes\Repository
 */
class FileManager
{
    /**
     * @var array
     */
    protected $options;

    /**
     * @var string
     */
    protected $suffix = 'local';

    /**
     * @var string
     */
    protected $local_file;

    /**
     * @var FileLoaderInterface
     */
    private $loader;

    /**
     * FileManager constructor.
     *
     * @param array $options
     * @param FileLoaderInterface $loader
     */
    public function __construct(array $options, FileLoaderInterface $loader)
    {
        $this->options = $options;

        $this->loader = $loader;

        $this->local_file = $this->buildLocalFileName();
    }

    /**
     * Download file from remote resource.
     */
    public function download()
    {
        $this->loader->init();

        $this->loader->download($this->options['remote_path'].$this->options['remote_filename'], $this->local_file);

        $this->loader->close();
    }

    /**
     * Get file from local directory.
     *
     * @return string
     */
    public function get()
    {
        if (!file_exists($this->local_file)) {
            $this->download();
        }

        return $this->local_file;
    }

    /**
     * Delete file from local directory.
     */
    public function delete()
    {
        unlink($this->local_file);
    }

    /**
     * Build filename and path for local file.
     *
     * @return string
     */
    protected function buildLocalFileName()
    {
        $parts = explode('.', strtolower($this->options['remote_filename']));

        $last_of_type = array_pop($parts);

        return $this->options['local_storage'] .''. implode('.', array_merge($parts, [$this->suffix, date('dmY'), $last_of_type]));
    }
}
