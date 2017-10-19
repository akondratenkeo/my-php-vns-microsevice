<?php
/**
 * Cron for DPD postcodes update
 */

require __DIR__.'/../vendor/autoload.php';

$log = new Monolog\Logger('cron');
$log->pushHandler(new Monolog\Handler\StreamHandler(__DIR__ .'/../storage/logs/cron.log', Monolog\Logger::DEBUG));

$data = DpdModel::updatePostcodesInfo();

$log->info(array_values($data)[0]);
