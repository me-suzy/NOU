<?php
require_once("top.php");
require_once("security.php");

secRequire("User");

if ($function_no != "" && $user_no != "" && $do != "") {

  $sql = "";
  if ($do == "add") {
    $sql = "INSERT INTO user_function (user_no, function_no)\n";
    $sql .= sprintf("values(%d, %d)", $user_no, $function_no);
  }

  if ($do == "delete") {
    $sql = "DELETE FROM user_function\n";
    $sql .= sprintf("WHERE user_no=%d AND function_no=%d", 
		    $user_no, $function_no);
  }

  if ($sql != "") {
    mysql_query($sql);

    if (mysql_error() != "") {
      echo mysql_error();
      exit;
    }

  }

}

Header("Location: " . $HTTP_REFERER);

?>
