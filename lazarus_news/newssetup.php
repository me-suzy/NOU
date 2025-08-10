<?php

require_once("top.php");
require_once("templatizer.php");

$tp = new Templatizer();
$tp->Load_Template("standard.tpl");
$tp->element["Pagetitle"] = "News Setup";
$tp->element["Title"] = "News Setup";

$data = file("news.sql");

while (list($line_num, $line) = each($data)) {

  $sql .= trim($line);

  // See if we've reached the end of an SQL statement
  if (substr($sql, strlen($sql) - 1, 1) == ';') {
    mysql_query($sql);
    if (mysql_error() != "") {

      $tp->element["Pagebody"] = 

"There has been an error loading the sql data for the news system.  The
SQL statment
<pre>" . $sql . "</pre> generated the following error:\n<pre>" . 
	mysql_error();

      $tp->Parse("page", "main");
      echo $tp->element["main"];
      exit();

    }
    $sql = "";
  }
  else
    $sql .= "\n";

}

$hasnewsystem = 0;
$sysfile = file("index.php");
while (list($line_num, $line) = each($sysfile)) {
  if (strstr($line, "newsfeeder.php")) $hasnewsystem = 1;
}

if (!$hasnewsystem) {

  $sysfile = file("docs");
  while (list($line_num, $line) = each($sysfile)) {
    if (strstr($line, "newsfeeder.php")) $hasnewsystem = 1;
  }

}

if ($hasnewsystem) {
  $tp->element["Message"] = "Your system will <em>not</em> need software modifications to use the news system";
}
else {
  $tp->element["Message"] = "You have an early version of the base package.  Please see section 4 below for further instructions.";
}
  

$readme = file("README.news");

$tp->element["Pagebody"] = "<pre>\n";
while (list($line_num, $line) = each($readme)) {
  $tp->element["Pagebody"] .= $line;
}
$tp->element["Pagebody"] .= "</pre>";

$tp->Parse("page", "main");
echo $tp->element["main"];

?>

