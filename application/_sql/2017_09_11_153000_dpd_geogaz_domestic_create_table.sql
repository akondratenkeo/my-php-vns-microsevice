
CREATE TABLE IF NOT EXISTS `dpd_geogaz_domestic` (
 `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
 `postcode_sector` VARCHAR(128) DEFAULT NULL,
 `dpd_depot` VARCHAR(128) DEFAULT NULL,
 `dpd_services_group` VARCHAR(128) DEFAULT NULL,
 `dpd_offshore_zone` VARCHAR(128) DEFAULT NULL,
 `timeslots_code` VARCHAR(128) DEFAULT NULL,
 `cluster` VARCHAR(128) DEFAULT NULL,
 `ilk_depot` VARCHAR(128) DEFAULT NULL,
 `ilk_services_group` VARCHAR(128) DEFAULT NULL,
 `ilk_offshore_zone` VARCHAR(128) DEFAULT NULL,
 `ilk_alternate_service` VARCHAR(128) DEFAULT NULL,
 `dpd_alternate_service` VARCHAR(128) DEFAULT NULL,
 `new_postcode` VARCHAR(128) DEFAULT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;