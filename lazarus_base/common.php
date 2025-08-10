<?php
include("common.ini");

/* Troubleshoot code.  Leave commented out unless you know
 * what you're doing

echo "Database Server: $dc_db_server <br>\n";
echo "User: $dc_db_user <br>\n";
echo "Password: $dc_db_password <br>\n";
echo "Database: $dc_db_name <br>\n";
*/

  $mysqllink = mysql_connect($dc_db_server, $dc_db_user, $dc_db_password)
    or die("The database is not available at the moment.  Please try again later.");
  mysql_select_db($dc_db_name);


session_start();

function dc_option_list($rsResult, $strTarget, $strDisplay, $strValue = "") {

  $result = "";
  $selected = 0;

  while ($row = mysql_fetch_array($rsResult)) {

    $result .= "<option";

    if ($strValue == "") {
      if ($row[$strDisplay] == $strTarget) {
	$result .= " selected";
      }

      $result .= ">" . $row[$strDisplay] . "\n";

    }
    else {

      if ($row[$strValue] == $strTarget) {
	$result .= " selected";
      }

      $result .= " value=\"" . $row[$strValue] . "\">" . $row[$strDisplay] . "\n";

    }

  }

  return $result;

}

function dc_fetch_menu_no($target) {

  // This ugly little block of code makes sure that we put up the
  // right menu.
  
  $sql = "SELECT menu_no FROM menu_node";
  $sql .= sprintf(" WHERE link = '%s'", $target);
  $result = mysql_query($sql);
  if ($result) {
    
    if (mysql_num_rows($result) > 0) {

      $row = mysql_fetch_object($result);
      return $row->menu_no;

    }
    else {
      return -1;
    }
    
  }
  else {
    return -1;
  }

}

//  function dc_insert_menu($target_link = "") {
//    global $REQUEST_URI;

//    $vMenuno = dc_fetch_menu_no($REQUEST_URI);
//    if ($vMenuno != -1) {
//      $m = new Menu($vMenuno);
//      $tp->element["Pagemenu"] = $m->Load();
//    }
//    else {
//      $tp->element["Pagemenu"] = "&nbsp;";
//    }

//  }

function dc_directory_append($base, $newpart) {

  $vstr = $base;
  if (substr($vstr, strlen($vstr), 1) != "/") {
    $vstr .= "/";
  }
  $vstr .= $newpart;

  return $vstr;

}
?>
