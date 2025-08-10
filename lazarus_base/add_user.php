<?php
require_once("top.php");

class User {

  var $user_no;
  var $user_id;
  var $email;
  var $password;
  var $name;
  var $domain;

  function User($vuserno = -1) {
    $this->user_no = $vuserno;
    $this->domain = $dc_realm;
    if ($vuserno != -1) {
      $this->Fetch();
    }
    else {
      $this->user_id = "";
      $this->email = "";
      $this->password = "";
      $this->name = "New User";
      $this->domain = $dc_domain_name;
    }
  }

  function Fetch() {
    if ($this->user_no != -1) {
      $sql = "SELECT * FROM user WHERE user_no=" . $this->user_no;
      $sql .= " AND domain='" . $this->domain . "'";
      $result = mysql_user($sql);
      if ($result) {
	if (mysql_num_rows($result) > 0) {
	  $row = mysql_fetch_object($result);
	  $this->user_id = $row->user_id;
	  $this->email = $row->email;
	  $this->password = $row->password;
	  $this->name = $row->name;
	  $this->domain = $dc_domain_name;
	}
      }
      else {
	$this->name = mysql_error();
      }
    }
  }
    
  function Authenticate($vuserid, $vpassword) {
    $sql = "SELECT user_no FROM user WHERE user_id='" . $vuserid . "' AND ";
    $sql .= "password='" . $vpassword . "' AND domain='" . $this->domain . "'";
    $result = mysql_query($sql);
    if ($result) {
      if ($mysql_num_rows($result) > 0) {
	$row = mysql_fetch_object($result);
	$this->user_no = $row->user_no;
	$this->Fetch();

	return 1;
      }
      else {
	$this->user_no = -1;
	return 0;
      }
    }
    else {
      $this->user_no = -1;
      echo "<p>There was an error while trying to authenticate " . $vuserid . "\n";
      echo "for domain " . $dc_domain_name . ":</p>\n\n";
      echo "<p>" . mysql_error() . "\n<pre>\n" . $sql . "\n</pre>\n";

      return -1;

    }

  }

  // Returns error message if applicable, or blank string on success.
  function Save() {
    if ($this->user_no != -1) {
      $sql = sprintf("UPDATE user SET user_id='%s', name='%s',\n",
		     $this->user_id, $this->name);
      $sql .= sprintf("password='%s', email='%s', domain='%s'\n",
		      $this->password , $this->email, $this->domain);
      $sql .= sprintf("WHERE user_no=%d", $this->user_no);
    }
    else {

      $sqltest = "SELECT user_no FROM user WHERE user_id='" . $this->user_id;
      $sqltest .= "' AND domain='" . $this->domain . "'";
      $result = mysql_query($sqltest);
      if (mysql_num_rows($result) > 0) {
	$retstr = sprintf("User ID %s already exists for the %s domain.",
			  $this->user_id, $this->domain);
	return $retstr;
      }

      $sql = "INSERT INTO user (user_id, name, password, email, domain)\n";
      $sql .= sprintf("VALUES ('%s', '%s', '%s', '%s', '%s')",
		      $this->user_id, $this->name, $this->password,
		      $this->email, $this->domain);
    }

    mysql_query($sql);

    if (mysql_error() != "") {
      return mysql_error();
    }
    else
      return "";

  }

};

