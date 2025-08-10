<?php
require_once("top.php");

class Link {

  var $link_no;
  var $name;
  var $url;
  var $description;
  var $submitted_by;
  var $approved;
  
  function Link($linkno = -1) {
    $this->link_no = $linkno;
    $this->approved = 'N';
  }
  
  function Fetch($linkno = -1) {
    if ($linkno != -1) {
      $this->link_no = $linkno;
    }
    if ($this->link_no != -1) {
      $sql = "SELECT * FROM link WHERE link_no=" . $this->link_no;
      $result = mysql_query($sql);
      if ($result) {
        if ($row = mysql_fetch_object($result)) {
          $this->name = $row->name;
          $this->url = $row->url;
          $this->description = $row->description;
          $this->approved = $row->approved;
          $this->submitted_by = $row->submitted_by;
          
        }
      }
      else
        return mysql_error() . "\n<pre>\n" . $sql . "\n</pre>\n";
    }
  }

  function UpdateSQL() {
    $sql = sprintf("UPDATE link SET name='%s', url='%s', description='%s', 
                    submitted_by='%s', approved='%s'
                    WHERE link_no=%d",
                    $this->name, $this->url, $this->description, 
                    $this->submitted_by, $this->approved,
                    $this->link_no);
    return $sql;
  }

  function InsertSQL() {
    $sql = sprintf("INSERT INTO link (name, url, description, submitted_by, approved)
                    VALUES ('%s', '%s', '%s', '%s', '%s')",
		   $this->name, $this->url, $this->description, 
		   $this->submitted_by, $this->approved);
    return $sql;
  }
  
  function Save() {
    $sql = "";
    if ($this->link_no == -1) 
      $sql = $this->InsertSQL();
    else
      $sql = $this->UpdateSQL();
      
    mysql_query($sql);
    
    if ($this->link_no == -1)
      $this->link_no = mysql_insert_id();

    return mysql_error();    
  }

  function Delete() {
    $sql = sprintf("DELETE FROM link WHERE link_no=%d", $this->link_no);
    mysql_query($sql);
    return mysql_error();                    
  }

}
