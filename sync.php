<?php

define('WP_MIOGEST_SYNC_WORDPRESS_DIR', '../../..');
define('WP_MIOGEST_SYNC_PLUGIN_DIR', WP_MIOGEST_SYNC_WORDPRESS_DIR . '/wp-content/plugins/wp-miogest-sync');
require_once __DIR__ . '/src/autoload.php';

use WpMiogestSync\Utils\Logger;
use WpMiogestSync\Modules\Syncer;

$plugin_path = "wp-miogest-sync/wp-miogest-sync.php";
if (is_plugin_active($plugin_path)) {
    Logger::info("Initializing syncer");
    $syncer = new Syncer();
    Logger::info("Check that the call was made by an allowed address");
    $syncer->checkRemoteAddress();
    Logger::info("Creating table if it does not exist");
    $syncer->createTableIfNotExists();
    Logger::info("Fetching data from remote addresses");
    $syncer->fetchRemoteData();
    Logger::info("Get all the ids from the annunci table");
    $syncer->getAnnunciIds();
    Logger::info("Delete old annunci's data (actually erasing all the properties' posts");
    $syncer->resetOldAnnunci();
    Logger::info("Delete old annunci's thumbnails (actually erasing all of them)");
    $syncer->resetOldAnnunciThumbs();
    Logger::info("Delete old terms and taxonomies (actually erasing all of them)");
    $syncer->resetOldTermsAndTaxonomies();
    Logger::info("Insert new terms and taxonomies");
    $syncer->insertNewTermsAndTaxonomies();
    Logger::info("Insert new annunci");
    $syncer->insertNewAnnunci();
    Logger::info("Sync complete");
} else {
    Logger::warning('Plugin is not active');
}
