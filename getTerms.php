<?php
include("database.inc");

$types = array("Property", "Description", "Location", "Static");
$type = $_REQUEST["type"];
$typeCode = array_search($type, $types);

$cats = explode(",", $_REQUEST["cats"]);

echo json_encode(getTerms($typeCode, $cats));
?>