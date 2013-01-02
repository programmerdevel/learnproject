<?php

date_default_timezone_set('America/Los_Angeles');
define('DRUPAL_ROOT', getcwd());
// Bootstrap Drupal
require 'includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

$cities = get_cities();

foreach($cities as $city){
  $name = $city->city;
  $state = ucwords(str_replace('_', ' ', get_state_name($city->province)));
  create_node($name, $state);
}

function create_node($name, $state) {
  echo "Creating New City Page :: ".$name." :: ".$state."\n";
  $node = new stdClass();
  $node->type = 'city';   // Your specified content type
  node_object_prepare($node);
  $node->title = ucwords(strtolower($name));
  $node->created = time();
  $node->changed = $node->created;
  $node->status = 1;
  $node->promote = 0;
  $node->sticky = 0;
  $node->format = 1;       // Filtered HTML
  $node->uid = 1;          // UID of content owner
  $node->language = LANGUAGE_NONE;
  
  $node->field_state[$node->language][0]['value'] = $state;
  node_save($node);
}

function get_cities() {
  $query = "SELECT DISTINCT (`city`), `province` FROM location";
  $results = db_query($query)->fetchAll();
  
  return $results;
}


function get_state_name($abbr) {
  $abbr = strtolower($abbr);
  $states = array(
      'al' => 'alabama',
      'ak' => 'alaska',
      'az' => 'arizona',
      'ar' => 'arkansas',
      'ca' => 'california',
      'co' => 'colorado',
      'ct' => 'connecticut',
      'de' => 'delaware',
      'fl' => 'florida',
      'ga' => 'georgia',
      'hi' => 'hawaii',
      'id' => 'idaho',
      'il' => 'illinois',
      'in' => 'indiana',
      'ia' => 'iowa',
      'ks' => 'kansas',
      'ky' => 'kentucky',
      'la' => 'louisiana',
      'me' => 'maine',
      'md' => 'maryland',
      'ma' => 'massachusetts',
      'mi' => 'michigan',
      'mn' => 'minnesota',
      'ms' => 'mississippi',
      'mo' => 'missouri',
      'mt' => 'montana',
      'ne' => 'nebraska',
      'nv' => 'nevada',
      'nh' => 'new-hampshire',
      'nj' => 'new-jersey',
      'nm' => 'new-mexico',
      'ny' => 'new-york',
      'nc' => 'north-carolina',
      'nd' => 'north-dakota',
      'oh' => 'ohio',
      'ok' => 'oklahoma',
      'or' => 'oregon',
      'pa' => 'pennsylvania',
      'ri' => 'rhode-island',
      'sc' => 'south-carolina',
      'sd' => 'south-dakota',
      'tn' => 'tennessee',
      'tx' => 'texas',
      'ut' => 'utah',
      'vt' => 'vermont',
      'va' => 'virginia',
      'wa' => 'washington',
      'wv' => 'west-virginia',
      'wi' => 'wisconsin',
      'wy' => 'wyoming',
  );
  
  return $states[$abbr];
}


?>
