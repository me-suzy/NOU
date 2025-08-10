<?php

require_once("top.php");
require_once("templatizer.php");

$tp = new Templatizer();
$tp->Load_Template("standard.tpl");


$data = file("event.sql");

while (list($line_num, $line) = each($data)) {

  $sql .= trim($line);

  // See if we've reached the end of an SQL statement
  if (substr($sql, strlen($sql) - 1, 1) == ';') {
    mysql_query($sql);
    if (mysql_error() != "") {
      $response = "There has been an error:<br>\n" . mysql_error();
      $tp->element["Message"] = $response;
      $tp->Parse("page", "main");
      echo $tp->element["main"];
      exit;
    }
    $sql = "";
  }
  else
    $sql .= "\n";

}

$readme = file("README.event");

$tp->element["Pagebody"] = "<pre>\n";
while (list($line_num, $line) = each($readme)) {
  $tp->element["Pagebody"] .= $line;
}
$tp->element["Pagebody"] .= "</pre>";

$tp->Parse("page", "main");
echo $tp->element["main"];


?>
