<?php
define('DRUPAL_ROOT', getcwd());
// This gets Drupal started.
require 'includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

// This gets Pathauto started updating aliases.
_pathauto_include() ;
node_pathauto_bulkupdate();