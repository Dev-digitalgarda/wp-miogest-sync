<?php

define('WP_MIOGEST_SYNC_WORDPRESS_DIR', '../../..');
define('WP_MIOGEST_SYNC_PLUGIN_DIR', WP_MIOGEST_SYNC_WORDPRESS_DIR . '/wp-content/plugins/wp-miogest-sync');
require_once __DIR__ . '/src/autoload.php';

use WpMiogestSync\Utils\Logger;
use WpMiogestSync\Modules\Syncer;

Logger::$logger->info('Initializing syncer');
$syncer = new Syncer();
Logger::$logger->info('Check that the call was made by an allowed address');
$syncer->checkRemoteAddress();
Logger::$logger->info('Fetching data from remote addresses');
$syncer->fetchRemoteData();
Logger::$logger->info('Get all the ids from the annunci table');
$syncer->getAnnunciIds();
Logger::$logger->info('Delete old annunci\'s data (actually erasing all the properties\' posts');
$syncer->deleteOldAnnunci();
Logger::$logger->info('Delete old annunci\'s thumbnails');
$syncer->deleteOldAnnunciThumbs();
Logger::$logger->info('Insert new annunci');
$syncer->insertNewAnnunci();
Logger::$logger->info('Sync complete');
