<?php

require_once("top.php");
require_once("templatizer.php");
require_once("menu.php");

$tp = new Templatizer();
$tp->Load_Template("standard.tpl");
$tp->Load_Template("maillist.tpl");

$tp->element["Title"] = "Subscribe to our Newsletter";
$tp->element["Pagetitle"] = $tp->element["Title"];

$vMenuno = dc_fetch_menu_no("/maillist.php");
if ($vMenuno != -1) {
  $m = new Menu($vMenuno);
  $tp->element["Pagemenu"] = $m->Load();
}
else {
  $tp->element["Pagemenu"] = "&nbsp;";
}

if (isset($action) && isset($email)) {

  $tp->element["email"] = $email;
  $tp->element["state"] = $state;
  $tp->element["city"] = $city;

  if ($action == "subscribe") {
    $sql = sprintf("SELECT email FROM maillist WHERE email='%s'", $email);
    $result = mysql_query($sql);
    if ($result) {
      if (mysql_num_rows($result) > 0) {
        $tp->element["Message"] = $email . " is already subscribed.";
      }
      else {
        $sql = sprintf("INSERT INTO maillist (email, city, state) values ('%s', '%s', '%s')",
                        $email, $city, $state);
        mysql_query($sql);
        if (mysql_error() != "") {
          $tp->element["Message"] = mysql_error() . "\n<pre>\n" . $sql . "\n</pre>\n";
        }
        else {
          $tp->element["Message"] = $email . " has been added to the list.";
          $tp->Parse("subscribemessage", "outboundmail");
          $tp->Parse("subscribeadmin", "adminmail");
          mail($email, "Subscribed at Toyboxtoys.com", $tp->element["outboundmail"]);
          mail("clay@obrienscafe.com", "New Toyboxtoys.com subscriber", $tp->element["adminmail"]);
        }
      }
    }
    else {
      $tp->element["Message"] = mysql_error() . "\n<pre>\n" . $sql . "\n</pre>\n";
    }
  }
  if ($action == "remove") {
    $sql = "SELECT email FROM maillist WHERE email='" . $email . "'";
    $result = mysql_query($sql);
    if ($result) {
      if (mysql_num_rows($result) > 0) {
        $sql = "DELETE FROM maillist WHERE email='" . $email . "'";
        mysql_query($sql);
        if (mysql_error() != "") {
          $tp->element["Message"] = mysql_error() . "\n<pre>\n" . $sql . "\n</pre>\n";
        }
        else {
          $tp->element["Message"] = $email . " has been removed.";
          $tp->Parse("removemessage", "removemail");
          $tp->Parse("removeadmin", "adminmail");
          mail($email, "Removed from Toyboxtoys.com", $tp->element["removemail"]);
          mail("clay@obrienscafe.com", "Removed from Toyboxtoys.com", $tp->element["adminmail"]);
        }
      }
      else {
        $tp->element["Message"] = $email . " not subscribed.";
      }
    }
    else {
      $tp->element["Message"] = mysql_error() . "\n<pre>\n" . $sql . "\n</pre>\n";
    }  
  }
}

$tp->Parse("maillistform", "Pagebody");
$tp->Parse("page", "main");
echo $tp->element["main"];

?>
