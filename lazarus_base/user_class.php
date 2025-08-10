<?php
require_once("common.php");
require_once("templatizer.php");

class User {

  var $user_no;
  var $user_id;
  var $email;
  var $password;
  var $name;
  var $functions;
  
  function User($vuserno = -1) {
    $this->user_no = $vuserno;
    if ($vuserno != -1) {
      $this->Fetch();
    }
    else {
      $this->user_id = "";
      $this->email = "";
      $this->password = "";
      $this->name = "New User";
    }
  }
  
  function Fetch() {
    if ($this->user_no != -1) {
      $sql = "SELECT * FROM user WHERE user_no=" . $this->user_no;
      $result = mysql_query($sql);
      if ($result) {
	if (mysql_num_rows($result) > 0) {
	  $row = mysql_fetch_object($result);
	  $this->user_id = $row->user_id;
	  $this->email = $row->email;
	  $this->password = $row->password;
	  $this->name = $row->name;
	  
	  $sql = "select name, IFNULL(uf.function_no, 0) AS 'has' FROM function f\n";
	  $sql .= "left outer join user_function uf\n";
	  $sql .= "on uf.function_no = f.function_no\n";
	  $sql .= "and uf.user_no = " . $this->user_no . "\n";
	  $result = mysql_query($sql);
	  if ($result) {
	    while ($row = mysql_fetch_object($result)) {
	      $this->functions[$row->name] = $row->has;
	    }
	  }
	}
      }
      else {
	$this->name = mysql_error();
      }
    }
  }
  
  function Authenticate($vuserid, $vpassword) {
    $sql = "SELECT user_no FROM user WHERE user_id='" . $vuserid . "' AND ";
    $sql .= "password='" . $vpassword . "'";
    $result = mysql_query($sql);
    if ($result) {
      if (mysql_num_rows($result) > 0) {
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
      echo "for " . $dc_realm . ":</p>\n\n";
      echo "<p>" . mysql_error() . "\n<pre>\n" . $sql . "\n</pre>\n";
      
      return -1;
      
    }
    
  }
  
  // Returns error message if applicable, or blank string on success.
  function Save() {
    if ($this->user_no != -1) {
      $sql = sprintf("UPDATE user SET user_id='%s', name='%s',\n",
		     $this->user_id, $this->name);
      $sql .= sprintf("password='%s', email='%s'\n", 
		      $this->password , $this->email);
      $sql .= sprintf("WHERE user_no=%d", $this->user_no);
    }
    else {
      
      $sqltest = "SELECT user_no FROM user WHERE user_id='" . $this->user_id . "'";
      $result = mysql_query($sqltest);
      if (mysql_num_rows($result) > 0) {
	$retstr = sprintf("User ID %s already exists.", $this->user_id);
	return $retstr;
      }
      
      $sql = "INSERT INTO user (user_id, name, password, email)\n";
      $sql .= sprintf("VALUES ('%s', '%s', '%s', '%s')",
		      $this->user_id, $this->name, $this->password,
		      $this->email);
      
      $tp = new Templatizer();

      /*
      if (FileExists("newusermessage.tpl")) {
	$tp->Load_Template("newusermessage.tpl");
	$tp->element["Name"] = $this->name;
	$tp->element["Userid"] = $this->user_id;
	$tp->element["Password"] = $this->password;
	
	$tp->Parse("mail", "message");
	
	mail($this->email, "Welcome to the Lazarus Internet Development Web System",
	     $tp->element["message"], "Reply-to: clay@lazarusid.com");
      }
      */
      
    }
    
    mysql_query($sql);
    
    if (mysql_error() != "") {
      return mysql_error();
    }
    else
      return "";
    
  }
  
  function Delete($vuserno = -1) {
    if ($vuserno != -1) 
      $this->user_no = $vuserno;
    
    $sql = sprintf("delete from user where user_no=%d", $this->user_no);
    mysql_query($sql);
    if (mysql_error() != "")
      return mysql_error();
    else {
      
      $sql = sprintf("delete from user_function where user_no=%d", $this->user_no);
      mysql_query($sql);
      
      if (mysql_error() != "")
	return mysql_error();
      else
	return "";
    }
  }
  
  // Return an array with all of the matching names in it.  The key will be
  // the user_no, the rest will appear in a | delimited string that can be
  // split out by the application.
  
  // If a single match is found, that single item is loaded into the 
  // user object. 
  function Find($vuserid, $vemail="", $vfirstname="", $vlastname="") {

    $vname = "";
    $vresult;

    $sql = "SELECT user_no, user_id, email, name FROM user WHERE\n";
    $sql .= sprintf("user_id LIKE '%s%%'\n", $vuserid);
    $sql .= sprintf("AND email LIKE '%s%%'", $vemail);
    if ($vfirstname != "") {
      $vname = $vfirstname . "%";
      if ($vlastname != "") {
	$vname .= " " . $vlastname . "%";
      }
      $sql .= " AND name LIKE '" . $vname . "'";
    }

    $result = mysql_query($sql);
    if ($result) {
      if (mysql_num_rows($result) == 1) {
	$row = mysql_fetch_object($result);
	$this->user_no = $row->user_no;
	$this->Fetch();
      }
      while ($row = mysql_fetch_object($result)) {
	$vresult[$row->user_no] = $row->user_id . "|" . $row->name . "|" . $row->email;
      }
    }
    else {
      $vresult[0] = mysql_error();
      $vresult[1] = $sql;
    }

    return $vresult;

  }
}

?>
