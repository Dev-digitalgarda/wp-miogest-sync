<?php
/*
* Plugin Name: Wp Miogest Sync
* Description: Integration with miogest for automatically synchronize posts
* Text Domain: wp-miogest-sync
* Domain Path: /languages
* Version: 3.4.2
* Author: s4web
* Author URI: http://s4web.it
*/

define('WP_MIOGEST_SYNC_WORDPRESS_DIR', ABSPATH);
define('WP_MIOGEST_SYNC_PLUGIN_DIR', WP_PLUGIN_DIR . '/wp-miogest-sync');
require_once __DIR__ . '/src/autoload.php';

use WpMiogestSync\Utils\Logger;
use WpMiogestSync\Modules\Syncer;

function create_plugin_database_table()
{
  Logger::$logger->info('Initializing plugin');
  Logger::$logger->debug('Nothing to do');
  
  Logger::$logger->info('Plugin initialized');
}
register_activation_hook(__FILE__, 'create_plugin_database_table');

function uninstall_and_remove_everything()
{
  Logger::$logger->info('Uninstalling plugin');
  $syncer = new Syncer();
  $syncer->getAnnunciIds();
  $syncer->resetOldAnnunci();
  $syncer->resetOldAnnunciThumbs();
  $syncer->resetOldTermsAndTaxonomies();
  Logger::$logger->info('Plugin uninstalled');
}
register_uninstall_hook(__FILE__, 'uninstall_and_remove_everything');
