<?php

class registry {

  var $registry_no;
  var $category;
  var $entity;
  var $value;

  function registry($vregistryno = -1) {
    $this->registry_no = $vregistryno;
    if ($this->registry_no > 0) {
      $this->Fetch();
    }
  }

  function Fetch($vregistryno = -1) {
    if ($vregistryno != -1) {
      $this->registry_no = $vregistryno;
    }
    if ($this->registry_no > 0) {
      $sql = sprintf("SELECT category, entity, value FROM registry 
WHERE registry_no=%d",
		     $this->registry_no);
      $result = mysql_query($sql);
      if ($result) {
	$row = mysql_fetch_object($result);
	$this->category = $row->category;
	$this->entity = $row->entity;
	$this->value = $row->value;
      }
      else 
	return "registry::Fetch(): " . mysql_error();
    }
  }

  function insert_sql() {
    $sql = sprintf("INSERT INTO registry (category, entity, value)
VALUES ('%s', '%s', '%s')",
		   $this->category, $this->entity, $this->value);
    return $sql;
  }

  function update_sql() {
    $sql = sprintf("UPDATE registry SET category='%s', entity='%s', value='%s'
WHERE registry_no=%d",
		   $this->category, $this->entity, $this->value,
		   $this->registry_no);
    return $sql;
  }

  function Save() {
    $sql = "";
    if ($this->registry_no <= 0) 
      $sql = $this->insert_sql();
    else
      $sql = $this->update_sql();

    mysql_query($sql);
    if (mysql_error())
      return "registry::Save(): " . mysql_error();

    if ($this->registry_no <= 0) 
      $this->registry_no = mysql_insert_id();

  }

  function Delete() {
    if ($this->registry_no > 0) {
      $sql = sprintf("DELETE FROM registry WHERE registry_no=%d", 
		     $this->registry_no);
      mysql_query($sql);
      if (mysql_error() != "")
	return "registry::Delete() " . mysql_error();
    }
    else
      return "registry::Delete(): registry_no <= 0, cannot delete nonexistant record.";
  }

};

?>
