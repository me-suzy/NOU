<?php

class Event {

  var $event_no;
  var $begin_year;
  var $begin_month;
  var $begin_day;
  var $end_year;
  var $end_month;
  var $end_day;
  var $name;
  var $location;
  var $article_no;
  var $event_begin;
  var $event_end;
  var $begin_hour;
  var $end_hour;
  var $article_no;

  function Event($veventno = -1) {
    
    $this->event_no = $veventno;
    if ($this->event_no > 0) 
      $this->Fetch();
    else {
      $this->begin_year = date("Y");
      $this->end_year = date("Y");
      $this->article_no = -1;
    }
    
  }
  
  function Fetch() {
    
    
    if ($this->event_no > 0) {
      $sql = "SELECT * FROM event WHERE event_no=" . $this->event_no;
      $result = mysql_query($sql);
      if ($result) {
	$row = mysql_fetch_object($result);
	$this->name = $row->name;
	$this->location = $row->location;
	$this->event_begin = $row->event_begin;
	$this->event_end = $row->event_end;
	$this->begin_hour = $row->begin_hour;
	$this->end_hour = $row->end_hour;
	$this->article_no = $row->article_no;

	$dp = split("-", $row->event_begin);
	$this->begin_year = $dp[0];
	$this->begin_month = $dp[1];
	$this->begin_day = $dp[2];

	$dp = split("-", $row->event_end);
	$this->end_year = $dp[0];
	$this->end_month = $dp[1];
	$this->end_day = $dp[2];

      }
      else
	return mysql_error();
    }

  }

  function Save() {

    $this->event_begin = sprintf("%d-%d-%d", $this->begin_year,
				 $this->begin_month, $this->begin_day);
    $this->event_end = sprintf("%d-%d-%d", $this->end_year, $this->end_month,
			       $this->end_day);
    $sql = "";

    if ($this->event_no <= 0)
      $sql = $this->Insert_SQL();
    else
      $sql = $this->Update_SQL();

    mysql_query($sql);
    if ($this->event_no <= 0) {
      $this->event_no = mysql_insert_id();
    }
    return mysql_error();

  }

  function Insert_SQL() {

    $res = sprintf("INSERT INTO event (event_begin, event_end, name, location, begin_hour, end_hour, article_no)
VALUES ('%s', '%s', '%s', '%s', '%s', '%s', %d)",
		   $this->event_begin, $this->event_end, $this->name,
		   $this->location, $this->begin_hour, $this->end_hour,
		   $this->article_no);
    return $res;

  }

  function Update_SQL() {

    $res = sprintf("UPDATE event SET event_begin='%s', event_end='%s',
name='%s', location='%s', begin_hour='%s', end_hour='%s', article_no=%d
WHERE event_no=%d",
		   $this->event_begin, $this->event_end, $this->name,
		   $this->location, $this->begin_hour, $this->end_hour,
		   $this->article_no, $this->event_no);
    return $res;

  }

  function Delete() {
    if ($this->event_no > 0) {
      if ($this->article_no > 0) {
	if (file_exists("news_class.php")) {
	  $sql = sprintf("DELETE FROM article WHERE article_no=%d", $this->article_no);
	  mysql_query($sql);
	}
      }
      $sql = sprintf("DELETE FROM event WHERE event_no=%d", $this->event_no);
      mysql_query($sql);
      return mysql_error();
    }
    else
      return "This record has not been saved.  Cannot delete.";
  }

  function Link_Article($varticle_no) {

    if (file_exists("news_class.php")) {
      if ($this->event_no > 0 && $varticle_no > 0) {

	$this->article_no = $varticle_no;
	$sql = sprintf("UPDATE event SET article_no=%d WHERE event_no=%d",
		       $this->article_no, $this->event_no);
	mysql_query($sql);
	return mysql_error();
      }
    else
      return "Must set event_no and article_no to link an article";
    }

  }

};

?>
