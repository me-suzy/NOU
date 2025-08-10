<?php

require_once("common.php");

class TextPage {

  var $brochure_no;
  var $body;        // Read-only. Changes will not be saved.
  var $title;
  var $pagetitle;
  var $description;
  var $keywords;
  var $menu_no;
  var $url;
  var $url_prefix;
  var $function_no;
  var $rawbody;     // Set this to set the body text that is saved to the DB
  
  function TextPage($vbrochure_no = -1) {
    
    $this->brochure_no = $vbrochure_no;
    
  }
  
  function Find($vurl = "index.html") {
    $this->Textpage_Find($vurl);
  }

  function Textpage_Find($vurl = "index.html") {
    $sql = sprintf("SELECT brochure_no FROM brochure WHERE url='%s'", $vurl);
    $result = mysql_query($sql);
    if ($result) {
      $row = mysql_fetch_object($result);
      $this->brochure_no = $row->brochure_no;
      $this->Fetch();
      
      //      $this->published .= "<p>SQL to find the brochure:<br>\n";
      //      $this->published .= "<pre>\n" . $sql . "\n</pre>";
      
    }
    else {
      $this->title = "MySQL Error";
      $this->pagetitle = $this->title;
      $this->body = mysql_error() . "<br>\n" . $sql;      
    }
  }

  function ParseText($vtext) {
    $vbody = $vtext;
    $lines = split("\n", $vbody);
    
    $par = 0;
    for ($i=0; $i < sizeof($lines); $i++) {
      $lines[$i] = trim($lines[$i]);
      
      if ($lines[$i] == "") {
	$lines[$i] .= "</p>";
	$par = 1;
      }
      else {
	if ($par == 1) {
	  if (strtolower(substr($lines[i], 0, 1)) != "<") {
	    $lines[$i] = "<p>" . $lines[$i];
	    $par = 0;
	  }
	}
      }	  
    }
    return implode("\n", $lines);
  }

  function Fetch($vbrochure_no = -1) {
    $this->Textpage_Fetch($vbrochure_no);
  }
  
  function Textpage_Fetch($vbrochure_no = -1) {
    
    if ($vbrochure_no != -1) {
      $this->brochure_no = $vbrochure_no;
    }
    
    $sql = sprintf("SELECT * FROM brochure\nWHERE brochure_no=%d",
		   $this->brochure_no);
    
    $result = mysql_query($sql);
    if ($result) {
      
      if (mysql_num_rows($result)) {
	
      	$row = mysql_fetch_object($result);
	
        $this->rawbody = $row->body;  
      	$this->pagetitle = $row->pagetitle;
      	$this->title = $row->title;
      	$this->keywords = $row->keywords;
      	$this->description = $row->description;
      	$this->body = $this->ParseText($row->body);
      	$this->menu_no = $row->menu_no;
      	$this->function_no = $row->function_no;
      	$this->published = $row->published;
	$this->url = $row->url;
	
      }
      else {
      	header("HTTP/1.0 404 Not Found");
      	$this->title = "Not Found";
      	$this->function_no = -1;
      }
    }
    else {
      $this->body = mysql_error();
      $this->title = "Database Error";
      $this->pagetitle = "Database Error";
      $this->function_no = -2;
      $this->published = mysql_error() . "<br>\n" . $sql;
    }
  }
  
  function Save() {
    $this->rawbody = addslashes($this->rawbody);
    $this->description = addslashes($this->description);



    return $this->Textpage_Save();
  }

  function Textpage_Save() {

    $sql = "";

    if ($this->brochure_no == -1) {
      $sql = "INSERT INTO brochure (body, title, pagetitle, description, keywords,";
      $sql .= "menu_no, url, published, function_no)\n";
      $sql .= sprintf("values ('%s', '%s', '%s', '%s', '%s', %d, '%s', '%s', %d)",
		      $this->rawbody, $this->title, $this->pagetitle, 
		      $this->description, $this->keywords, $this->menu_no, 
		      $this->url, $this->published, $this->function_no);
    }
    else {
      $sql = "UPDATE brochure SET\n";
      $sql .= sprintf("body = '%s',\ntitle = '%s',\npagetitle = '%s',\n",
                      $this->rawbody, $this->title, $this->pagetitle);
      $sql .= sprintf("description='%s',\nkeywords='%s',\nmenu_no=%d\n,",
                      $this->description, $this->keywords, $this->menu_no);
      $sql .= sprintf("url='%s',\npublished='%s',\nfunction_no=%d\n",
                      $this->url, $this->published, $this->function_no);
      $sql .= sprintf("WHERE brochure_no=%d",
                      $this->brochure_no);
    }    
    mysql_query($sql);
    if (mysql_error() != "") {
      echo mysql_error() . "\n<pre>\n" . $sql . "</pre>";
      return mysql_error() . "<br>\n" . $sql;
    }
    if ($this->brochure_no == -1) {
      //      Get the insert id for this record    
      $this->brochure_no = mysql_insert_id();
    }
    
    
  }

  function AddToMenu() {
    $target = dc_directory_append($this->url_prefix, $this->url);    
    
    $sql = "INSERT INTO menu_node (menu_no, link, label, function_no)\n";
    $sql .= sprintf("VALUES (%d, '%s', '%s', %d)", $this->menu_no,
		    $target, $this->title, $this->function_no);

    mysql_query($sql);
    return mysql_error();

  }

  function UpdateOnMenu($menu_node_no) {
    $target = dc_directory_append($this->url_prefix, $this->url);    
    $sql = sprintf("UPDATE menu_node SET link='%s', label='%s', menu_no=%d ",
		   $target, $this->title, $this->menu_no);
    $sql .= sprintf("WHERE menu_node_no=%d", $menu_node_no);
    mysql_query($sql);
    return mysql_error();
  }

  function RemoveFromMenu($menu_node_no) {
    $sql = sprintf("DELETE FROM menu_node WHERE AND menu_node_no=%d",
		   $menu_node_no);
    mysql_query($sql);
    return mysql_error();
  }
  
  function FindOnMenu() {
  
    $result = mysql_query("SELECT url FROM brochure WHERE brochure_no=" . $this->brochure_no);
    if ($result) {
      $row = mysql_fetch_object($result);
      $target = dc_directory_append($this->url_prefix, $row->url);
      
      $sql = "SELECT menu_node_no FROM menu_node ";
      $sql .= sprintf("WHERE link='%s'", $target);
      
      $result = mysql_query($sql);
      if ($result) {
	if (mysql_num_rows($result) > 0) {
	  $row2 = mysql_fetch_object($result);
	  return $row2->menu_node_no;
	}
	else {
	  return 0; // No records found, no error.
	}
      }
      else {
	return -1;
      }
      
    }
    else {
      return -2;
    }
  }

  function Populate_Menu() {

    // If it's published it needs to be added or updated
    if ($this->published == "Y") {
      $node_no = $this->FindOnMenu();

      if ($node_no > 0) {
	$this->UpdateOnMenu($node_no);
      }
      else {
	if ($node_no == 0) {
	  $this->AddToMenu();
	}
	else {
	  echo mysql_error();
	  exit();
	}
      }

    }
    else {  // It isn't published, so we can remove it from the menu
      $node_no = $this->FindOnMenu();
      if ($node_no > 0) {
	$this->RemoveFromMenu($node_no);
      }
      else {
	if ($node_no < 0) {  // Error is we got something besides 0 rows
	  echo mysql_error();
	  exit;
	}
      }
    }
  }  

  function Delete($vbrochure_no = -1) {
    if ($vbrochure_no != -1) {
      $this->brochure_no = $vbrochure_no;
    }

//      $node_no = $this->FindOnMenu();
//      if ($node_no > 0) {
//        $this->RemoveFromMenu($node_no);
//      }

    $sql = sprintf("DELETE FROM brochure WHERE brochure_no=%d", $this->brochure_no);
    mysql_query($sql);
    return mysql_error();
  }
  
}
?>
