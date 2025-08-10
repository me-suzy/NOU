<?php
require_once("top.php");
require_once("security.php");
require_once("menu.php");
require_once("templatizer.php");

$tp = new Templatizer();

$tp->Load_Template("standard.tpl");
$tp->Load_Template("user-search.tpl");

$vMenuno = dc_fetch_menu_no("/user-search.php");
if ($vMenuno != -1) {
  $m = new Menu($vMenuno);
  $tp->element["Pagemenu"] = $m->Load();
}
else {
  $tp->element["Pagemenu"] = "&nbsp;";
}

if ($REQUEST_METHOD == "POST") {
  
  $user_no = array();
  
// Need to put something here to kill any SQL miscreants.
  
  $sql = "SELECT user_no, name FROM user WHERE domain='" . $dc_domain_name . "' AND\n";
  $sql .= sprintf("(user_id LIKE '%%%s%%'\n", $search_term);
  $sql .= sprintf("OR name LIKE '%%%s%%'\n", $search_term);
  $sql .= sprintf("OR email LIKE '%%%s%%')", $search_term);
  
  $result = mysql_query($sql);
  if ($result) {
    if (mysql_num_rows($result) == 0) {
      $tp->Parse("nousers", "Usergrid");
    }
    else {
      while ($row = mysql_fetch_object($result)) {
	$user_no = $row->user_no;
	$user_name = $row->name;
	$tp->element["user_no"] = $user_no;
	$tp->element["user_name"] = $user_name;

	$ssql = "SELECT sorcerer_no, name FROM sorcerer WHERE user_no=" . $user_no;
	$sresult = mysql_query($ssql);
	if (mysql_num_rows($sresult) == 0) {
	  $tp->Parse("nosorcerers", "Sorcerergridrows");
	}
	else {
	  $tp->element["Sorcerergridrows"] = "";
	  while ($srow = mysql_fetch_object($sresult)) {
	    $tp->element["sorcerer_no"] = $srow->sorcerer_no;
	    $tp->element["sorcerer_name"] = $srow->name;

	    if (trim($tp->element["sorcerer_name"]) == "") {
	      $tp->element["sorcerer_name"] = "[Not Named]";
	    }

	    $tp->Parse("sorcerergridrow", "Sorcerergridrows", 1);
	  }
	}

	$dsql = "SELECT daemon_no, name FROM daemon WHERE user_no=" . $user_no;
	$dresult = mysql_query($dsql);
	if ($dresult) {
	  if (mysql_num_rows($dresult) == 0) {
	    $tp->Parse("nodemons", "Demongridrows");
	  }
	  else {
	    $tp->element["Demongridrows"] = "";
	    while ($drow = mysql_fetch_object($dresult)) {
	      $tp->element["demon_no"] = $drow->daemon_no;
	      $tp->element["demon_name"] = $drow->name;
	      
	      if (trim($tp->element["demon_name"]) == "") {
		$tp->element["demon_name"] = "[Not Named]";
	      }

	      $tp->Parse("demongridrow", "Demongridrows", 1);
	    }
	  }
	}
	else {
	  $tp->element["Message"] = mysql_error() . "\n<pre>\n" . $dsql . "\n</pre>\n";
	}

	$tp->Parse("usergrid", "Usergrid", 1);

      }
    }
  }
  else {
    $tp->element["Message"] = mysql_error() . "\n<pre>\n" . $sql . "\n</pre>\n";
  }
}

$tp->element["Searchterm"] = $search_term;
$tp->Parse("usersearch", "Pagebody");
$tp->Parse("page", "main");
echo $tp->element["main"];

?>
