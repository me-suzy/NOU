<?php

require_once("top.php");

$data = file("base.sql");

while (list($line_num, $line) = each($data)) {

  $sql .= trim($line);

  // See if we've reached the end of an SQL statement
  if (substr($sql, strlen($sql) - 1, 1) == ';') {
    mysql_query($sql);
    if (mysql_error() != "") {
      echo mysql_error();
      exit;
    }
    $sql = "";
  }
  else
    $sql .= "\n";

}

echo "<p>Completed loading SQL data.</p>";

?>
