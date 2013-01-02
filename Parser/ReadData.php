<?php
$content = file_get_contents('serialize/data.txt');
$data = unserialize($content);
print_r($data);
?>
