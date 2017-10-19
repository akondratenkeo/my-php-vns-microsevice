
CREATE TABLE IF NOT EXISTS `dpd_geogaz_services` (
 `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
 `2_digit_service_code` VARCHAR(128) DEFAULT NULL,
 `3_digit_service_code` VARCHAR(128) DEFAULT NULL,
 `dpd_product_desc` VARCHAR(128) DEFAULT NULL,
 `dpd_label_service_desc` VARCHAR(128) DEFAULT NULL,
 `ilk_product_desc` VARCHAR(128) DEFAULT NULL,
 `ilk_label_service_desc` VARCHAR(128) DEFAULT NULL,
 `ilk_alternative_service_desc` VARCHAR(128) DEFAULT NULL,
 `dpdpremium` VARCHAR(128) DEFAULT NULL,
 `old_dpd` VARCHAR(128) DEFAULT NULL,
 `old_ilk` VARCHAR(128) DEFAULT NULL,
 `ilk_max_parcels_per_con` VARCHAR(128) DEFAULT NULL,
 `ilk_max_weight_per_parcel` VARCHAR(128) DEFAULT NULL,
 `dpd_max_parcels_per_con` VARCHAR(128) DEFAULT NULL,
 `dpd_max_weight_per_parcel` VARCHAR(128) DEFAULT NULL,
 `dpd_alternative_service_desc` VARCHAR(128) DEFAULT NULL,
 `ilkpremium` VARCHAR(128) DEFAULT NULL,
 `sec_dpd` VARCHAR(128) DEFAULT NULL,
 `sec_ilk` VARCHAR(128) DEFAULT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;