<?php

namespace VnsServices\DpdPostcodes\Loader;

/**
 * Interface FileLoaderInterface
 *
 * @package VnsServices\DpdPostcodes\Loader
 */
interface FileLoaderInterface
{
    public function init();

    public function download($remote_file, $local_file);

    public function close();
}
