<?php

namespace VnsServices\DpdPostcodes\Loader;

use VnsServices\DpdPostcodes\Config\FtpLoaderConfig;

/**
 * Class FtpFileLoader for loading GEOGAZUK.TXT file by FTP connection.
 *
 * @package VnsServices\DpdPostcodes\Loader
 */
class FtpFileLoader implements FileLoaderInterface
{
    /**
     * @var FtpLoaderConfig
     */
    protected $config;

    /**
     * @var resource
     */
    private $conn_id;

    /**
     * FtpFileLoader constructor.
     *
     * @param FtpLoaderConfig $config
     */
    public function __construct(FtpLoaderConfig $config)
    {
        $this->config = $config;
    }

    /**
     * Init connection to FTP server.
     *
     * @throws \Exception
     */
    public function init()
    {
        $this->conn_id = ftp_connect($this->config->server, $this->config->server_port);

        if (!$this->conn_id) {
            throw new \Exception('Connection to FTP Server has failed!');
        }

        if (!ftp_login($this->conn_id, $this->config->username, $this->config->password)) {
            throw new \Exception('Authentication has failed, use correct login credentials!');
        }

        ftp_pasv($this->conn_id, true);
    }

    /**
     * Copy file from external FTP resource.
     *
     * @param $remote_file
     * @param $local_file
     * @throws \Exception
     */
    public function download($remote_file, $local_file)
    {
        if (!$this->checkIsFileExisted($remote_file)) {
            throw new \Exception('Download has failed, file doesn\'t exist!');
        }

        if (!ftp_get($this->conn_id, $local_file, $remote_file, FTP_BINARY)) {
            throw new \Exception('Copy of file from external resource, has failed!');
        }
    }

    /**
     *  Close FTP connection
     */
    public function close()
    {
        ftp_close($this->conn_id);
    }

    /**
     * Check is file exists in specified location.
     *
     * @param string $remote_file
     * @return bool
     */
    protected function checkIsFileExisted($remote_file)
    {
        $contents = ftp_nlist($this->conn_id, $remote_file);

        if (!$contents || (is_array($contents) && count($contents) == 0)) {
            return false;
        }

        return true;
    }
}
