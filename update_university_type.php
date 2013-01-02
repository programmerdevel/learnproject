<?php
date_default_timezone_set('America/Los_Angeles');
define('DRUPAL_ROOT', getcwd());
// Bootstrap Drupal
require 'includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

$lines = file('source/public-private.csv');
foreach($lines as $line){
  $data = explode(',', $line);
  do_update($data);
}

function do_update(array $data) {
  $nid = find_existing_node($data[0]);
  if ($nid) {
    echo "Updating :: " . $data[1] . " :: " . $nid . "\n";
    $node = node_load($nid);

    node_object_prepare($node);
    $node->language = LANGUAGE_NONE;

    $node->field_actual_type[$node->language][0]['value'] = trim($data[3]);
    $node->field_university_type[$node->language][0]['value'] = trim($data[2]);
    node_save($node);
  }
  else {
    print_r($data);
  }
}

function find_existing_node($id) {
  $query = "SELECT entity_id FROM {field_data_field_university_id} WHERE field_university_id_value = :id";
  $results = db_query($query, array(':id' => $id))->fetchAll();
  
  if(!empty($results)){
  	return $results[0]->entity_id;
  }
  return false;
}
?>