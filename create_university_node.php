<?php

date_default_timezone_set('America/Los_Angeles');
define('DRUPAL_ROOT', getcwd());
// Bootstrap Drupal
require 'includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

$dbhost = 'localhost';
$dbuser = 'root'; //username
$dbpass = 'root'; //password
$dbname = 'universitydata'; //database_name


$connection = mysql_connect($dbhost, $dbuser, $dbpass);
mysql_select_db($dbname);

$query = "select * from university";
$result = mysql_query($query);

while ($row = mysql_fetch_object($result)) {
  $records[] = $row;
}

mysql_close($connection);

foreach($records as $record){
	create_node($record);
}

function create_node($record) {
  $nid = find_existing_node($record->id);
  if ($nid) {
    echo "Updating Node ".$record->name."\n";
    $node = node_load($nid);
  } else {
    echo "Creating New Node ".$record->name."\n";
    $node = new stdClass();
  }
  
  $node->type = 'university';   // Your specified content type
  node_object_prepare($node);
  $node->title = ucwords(strtolower($record->name));
  $node->created = time();
  $node->changed = $node->created;
  $node->status = 1;
  $node->promote = 0;
  $node->sticky = 0;
  $node->format = 1;       // Filtered HTML
  $node->uid = 1;          // UID of content owner
  $node->language = LANGUAGE_NONE;

  $node->field_website_link[$node->language] = array(0 => array('value' => $record->web_address));
  $node->field_price_calculator_link[$node->language] = array(0 => array('value' => $record->net_price_calculator_web_address));
  $node->field_financial_aid_link[$node->language] = array(0 => array('value' => $record->financial_aid_address));
  $node->field_application_link[$node->language] = array(0 => array('value' => $record->online_application_web_address));
  $node->field_admission_link[$node->language] = array(0 => array('value' => $record->admission_web_address));
  
  $node->field_url_state[$node->language] = array(0 => array('value' => $record->state_abbr));
  $node->field_url_city[$node->language] = array(0 => array('value' => $record->city));

  $node->field_university_id[$node->language] = array(0 => array('value' => $record->id));
  $node->field_location[$node->language] = array(0 => array('name' => $record->name,'street' => $record->street_address, 'city' => $record->city, 'province' => $record->state_abbr, 'postal_code' => $record->zip, 'phone' => $record->phone, 'fax' => $record->fax));

  //$node->taxonomy = array('69', '87');
  //$node->taxonomy[85] = array('tid' => '85', 'vid' => '4', 'name' => 'Private');
  //84 public
  //85 private

  node_save($node);
}

function find_existing_node($id) {
  $query = "SELECT entity_id FROM {field_data_field_university_id} WHERE field_university_id_value = :id";
  $results = db_query($query, array(':id' => $id))->fetchAll();
  
  if(!empty($results)){
  	return $results[0]->entity_id;
  }
  return false;
}

