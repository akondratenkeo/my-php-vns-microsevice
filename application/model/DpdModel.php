<?php

use VnsServices\DpdPostcodes\Config\FtpLoaderConfig;
use VnsServices\DpdPostcodes\Loader\FtpFileLoader;
use VnsServices\DpdPostcodes\Entity\EntityInterface;
use VnsServices\DpdPostcodes\Geogaz;

/**
 * Class DpdPostcodesModel
 */
class DpdModel
{
    public static function getPostcodeInfo($postcode)
    {
        // TODO: Add support of another database abstraction

        if (!preg_match('/^([A-Za-z][0-9]{1,2}|[A-Za-z]{2}[0-9]{1,2}|[A-Za-z][0-9][A-Za-z]|[A-Za-z]{2}[0-9]?[A-Za-z])([0-9])([A-Za-z]{2})$/', $postcode, $matches)) {
            return [
                'error' => 'Format of your postcode doesn\'t match the template!',
            ];
        }

        $needle = $matches[1] .''. $matches[2];

        $db = DatabaseFactory::getFactory()->getConnection();
        $sql = "SELECT `dpd_geogaz_domestic`.`id`,
                       `dpd_geogaz_domestic`.`postcode_sector`,
                       `dpd_geogaz_domestic`.`dpd_depot`,
                       `dpd_geogaz_domestic`.`dpd_services_group`,
                       `dpd_geogaz_domestic`.`dpd_offshore_zone`,
                       `dpd_geogaz_domestic`.`new_postcode`,
                       `dpd_geogaz_groups`.`service_11` AS `dpd_two_day`,
                       `dpd_geogaz_groups`.`service_12` AS `dpd_next_day`
                FROM `dpd_geogaz_domestic` 
                LEFT JOIN `dpd_geogaz_groups` ON `dpd_geogaz_domestic`.`dpd_services_group` = `dpd_geogaz_groups`.`lookup_code` 
                WHERE `dpd_geogaz_domestic`.`postcode_sector` = :postcode OR `dpd_geogaz_domestic`.`new_postcode` = :postcode";
        $stmt = $db->prepare($sql);
        $stmt->execute([':postcode' => $needle]);

        if (!$result = $stmt->fetch()) {
            return [
                'error' => 'Sorry, no delivery to your address!',
            ];
        }

        self::checkOffshoreNoDelivery($matches[1], $result);

        return [
            'data' => $result,
        ];
    }

    /**
     * @return array
     */
    public static function updatePostcodesInfo()
    {
        $db = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT `value` FROM `dpd_settings` WHERE `name` = :param_name";
        $stmt = $db->prepare($sql);
        $stmt->execute([':param_name' => 'geogaz_last_export_version']);

        $geogaz_last_export_version = $stmt->fetchColumn();

        $options = self::prepareGeogazOptions();
        $loader_config = new FtpLoaderConfig((array) Config::get('DPD_POSTCODES_FTP'));

        $geogaz = new Geogaz($geogaz_last_export_version);
        $geogaz->initFileManager($options, new FtpFileLoader($loader_config));

        try {
            self::savePostcodesToDatabase($geogaz->getData());

            $sql = "UPDATE `dpd_settings` SET `value` = :val WHERE `name` = :param_name";
            $stmt = $db->prepare($sql);
            $stmt->execute([
                ':val' => $geogaz->getFileVersion(),
                ':param_name' => 'geogaz_last_export_version',
            ]);

        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage(),
            ];
        }

        return [
            'message' => 'Geogaz file successfully updated!'
        ];
    }

    /**
     * @param $data
     */
    protected static function savePostcodesToDatabase($data)
    {
        if(!$data) {
            throw new \InvalidArgumentException('Input variable should not be NULL!');
        }

        list($services, $groups, $domestic) = $data;
        unset($data);

        $db = DatabaseFactory::getFactory()->getConnection();

        $sql  = "TRUNCATE `dpd_geogaz_services`;";
        $sql .= "TRUNCATE `dpd_geogaz_groups`;";
        $sql .= "TRUNCATE `dpd_geogaz_domestic`;";
        $db->exec($sql);

        $sql = self::prepareMultipleInsertStatement($services, 'dpd_geogaz_services');
        $db->exec($sql);

        $sql = self::prepareMultipleInsertStatement($groups, 'dpd_geogaz_groups');
        $db->exec($sql);

        $sql = self::prepareMultipleInsertStatement($domestic, 'dpd_geogaz_domestic');
        $db->exec($sql);
    }

    /**
     * @param EntityInterface $entity
     * @param $table
     * @return string
     */
    protected static function prepareMultipleInsertStatement(EntityInterface $entity, $table)
    {
        $scheme = $entity->getScheme();
        array_unshift($scheme, "id");

        // TODO: Change statement for PDO support, divide requests on 50 items per request
        $sql = '';
        $sql .= "INSERT INTO $table (". implode(', ', $scheme) . ") VALUES";

        foreach ($entity->getRows() as $row) {
            $sql .= "(NULL, '" .implode("', '", array_values($row)) ."'),";
        }

        $sql = substr($sql, 0 , -1) .";";

        return $sql;
    }

    /**
     * @return array
     */
    protected static function prepareGeogazOptions()
    {
        return [
            'local_storage' => Config::get('PATH_STORAGE'),
            'remote_path' => Config::get('DPD_POSTCODES_FTP_FILE_OPTIONS')['dpd_path'],
            'remote_filename' => Config::get('DPD_POSTCODES_FTP_FILE_OPTIONS')['dpd_filename'],
        ];
    }

    /**
     * @param $postcode
     * @param $data
     */
    private static function checkOffshoreNoDelivery($postcode, $data)
    {
        if (in_array($postcode, Config::get('NO_DELIVERY_POSTCODES')) || in_array(substr($postcode, 0, 2), Config::get('NO_DELIVERY_POSTCODES'))) {
            $data->dpd_offshore_zone = '1';
        } else {
            $data->dpd_offshore_zone = '';
        }
    }
}
