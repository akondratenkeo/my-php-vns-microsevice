<?php

namespace VnsServices\DpdPostcodes\Config;

/**
 * Class FtpLoaderConfig
 *
 * @package VnsServices\DpdPostcodes\Config
 */
class FtpLoaderConfig implements LoaderConfigInterface
{
    /**
     * @var string
     */
    public $server;

    /**
     * @var int
     */
    public $server_port = 21;

    /**
     * @var string
     */
    public $username;

    /**
     * @var string
     */
    public $password;

    /**
     * FtpLoaderConfig constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        // TODO: Change properties visibility to private

        foreach ($config as $key => $value) {
            $this->{$key} = $value;
        }
    }
}
