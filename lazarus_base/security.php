<?php

require_once("user_class.php");
$user = new User();

function secAuthenticate () {
  global $dc_realm, $PHP_AUTH_USER, $PHP_AUTH_PW;

  Header("WWW-Authenticate: Basic realm=\"" . $dc_realm . "\"");
  Header("HTTP/1.0 401 Unauthorized");
  //  echo "User: $PHP_AUTH_USER <br>\nPassword: $PHP_AUTH_PW <br>\n";
  include("401.html");
  exit;
}

function secRequire($permission) {
  global $user, $PHP_AUTH_USER, $PHP_AUTH_PW;

  if ($PHP_AUTH_USER=="" || $PHP_AUTH_PW=="") {
    if ($permission != "")
      secAuthenticate();
  }
  else {
    $result = $user->Authenticate($PHP_AUTH_USER, $PHP_AUTH_PW);
    if ($result == 0) secAuthenticate();
  }

  if ($user->functions[$permission] != 0)
    return 1;
  else {
    Header("HTTP/1.0 403 Forbidden");
    include("403.html");

    // For debuggering only.

    //echo "<p>" . $user->name . " has these functions:\n";
    //echo "<ol>\n";

    /* 
    while (list ($key, $value) = each($user->functions)) {
      echo "  <li>" . $key . ": " . $value . "\n";
    }

    echo "</ol>\n";

    echo "<p>But you need <strong>" . $permission . "</strong>\n";
    */
    exit;
  }

}

function secRequireFunctionNo($function_no) {
  global $user, $PHP_AUTH_USER, $PHP_AUTH_PW;

  if ($PHP_AUTH_USER=="" || $PHP_AUTH_PW=="") {
    if ($function_no != 0)
      secAuthenticate();
  }
  else {
    $result = $user->Authenticate($PHP_AUTH_USER, $PHP_AUTH_PW);
    if ($result == 0) secAuthenticate();
  }


  $foundit = 0;
  while(list($Fname, $Fno) = each($user->functions)) {
    if ($Fno == $function_no) $foundit = 1;
  }

  if ($foundit == 0) {
    Header("HTTP/1.0 403 Forbidden");
    include("403.html");
    exit;
  }
  
}

?>
