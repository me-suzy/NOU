<?php
require_once("textpage_class.php");

class News extends TextPage {

  var $publish_date;
  var $unpublish_date;
  var $author;
  var $author_email;
  var $author_no;

  function News($varticle_no = -1) {
    $this->brochure_no = $varticle_no;
    
    if ($this->brochure_no != -1) {
      $this->Fetch();
    }
  }

  function Fetch($varticle_no = -1) {
    
    if ($varticle_no != -1) {
      $this->brochure_no = $varticle_no;
    }
    
    if ($this->brochure_no != -1) {
      
      $sql = "SELECT * FROM article WHERE article_no=" . $this->brochure_no;
      
      $result = mysql_query($sql);
      
      if ($result) {
	if (mysql_num_rows($result) > 0) {
	  $row = mysql_fetch_object($result);
	  $this->keywords = $row->keywords;
	  $this->url = "";
	  $this->title = $row->title;
	  $this->rawbody = $row->body;
	  $this->description = $row->description;
	  $this->publish_date = $row->publish_date;
	  $this->unpublish_date = $row->unpublish_date;
	  $this->author_no = $row->author;
	  $this->menu_no = $row->menu_no;

	  $asql = "SELECT name, email FROM user WHERE user_no=" . $row->author;
	  $aresult = mysql_query($asql);

	  if ($aresult) {
	    if (mysql_num_rows($aresult)) {
	      $arow = mysql_fetch_object($aresult);
	      $this->author = $arow->name;
	      $this->author_email = $arow->email;
	    }
	  }

	  $this->body = $this->ParseText($this->rawbody);

	}
	else {
	  $this->body = "Could not find article " . $this->brochure_no;
	}
      }
      else {
	$this->body = mysql_error() . "\n<pre>\n" . $sql . "\n</pre>\n";
      }

    }

  }

  function Save() {

    if ($this->brochure_no == -1) {
      $format = "INSERT INTO article (publish_date, unpublish_date, author, description, keywords, body, title, menu_no)\n";
      $format .= "VALUES ('%s', '%s', %d, '%s', '%s', '%s', '%s', %d)";
      $sql = sprintf($format, $this->publish_date,
		     $this->unpublish_date, $this->author_no, 
		     $this->description, $this->keywords, $this->rawbody,
		     $this->title, $this->menu_no);
    }
    else {
      $format = "UPDATE article SET\n";
      $format .= "publish_date='%s',\nunpublish_date='%s',\nauthor=%d,\n";
      $format .= "description='%s',\nkeywords='%s',\nbody='%s',\n";
      $format .= "title='%s',\nmenu_no=%d\n";
      $format .= "WHERE article_no=%d";

      $sql = sprintf($format, $this->publish_date, $this->unpublish_date,
		     $this->author_no, $this->description, $this->keywords,
		     $this->rawbody, $this->title, $this->menu_no, 
		     $this->brochure_no);

    }

    mysql_query($sql);

    if (mysql_error() != "") return mysql_error();

    if ($this->brochure_no == -1) {
      $this->brochure_no = mysql_insert_id();
    }

  }

  function Delete($varticle_no = -1) {
    if ($varticle_no != -1) {
      $this->brochure_no = $varticle_no;
    }

    if ($this->brochure_no != -1) {
      if (file_exists("event_class.php")) {
	$sql = sprintf("UPDATE event SET article_no=0 WHERE article_no=%d",
		       $this->brochure_no);
	mysql_query($sql);
	if (mysql_error() != "")
	  return "Error updating events calendar:<br>\n" . mysql_error();
      }
      $sql = sprintf("DELETE FROM article WHERE article_no=%d", 
		     $this->brochure_no);
      mysql_query($sql);
      return mysql_error();
    }

  }
  

};

class Newslist {

  var $current_date;
  var $article_no;
  var $author;
  var $author_no;
  var $author_email;
  var $title;
  var $description;
  var $base;
  var $scrollsize;
  var $menuno;
  var $icon;

  function Newslist($vdate = "") {

    if ($vdate != "") {
      $this->current_date = $vdate;
    }
    else
      $this->current_date = "true";

    $this->base = 0;
    $this->scrollsize = 10;

    $this->title = array();
    $this->description = array();
    $this->article_no = array();
    $this->author = array();
    $this->author_no = array();
    $this->author_email = array();
    $this->icon = array();
    $this->menu_no = -1;
    
  }

  function Fetchlist() {

    $sql = "select article_no, publish_date, unpublish_date,\n";
    $sql .= "title, description, author, user.email, user.name, menu.graphic_2\n";
    $sql .= "from article\n";
    $sql .= "left outer join user\n";
    $sql .= "on user.user_no = article.author\n";
    $sql .= "left outer join menu\n";
    $sql .= "on menu.menu_no=article.menu_no\n";

    if ($this->current_date == "true" || $this->menu_no > 0)
      $sql .= "where ";

    if ($this->current_date == "true") {
      $sql .= "article.publish_date <= current_date\n";
      $sql .= "and article.unpublish_date >= current_date\n";
    }

    if ($this->menu_no > 0) {
      if ($this->current_date == "true") $sql .= "and ";
      $sql .= sprintf("article.menu_no=%d\n", $this->menu_no);
    }

    $sql .= "order by publish_date desc\n";

    if ($this->current_date == "false") {
      $sql .= "limit " . $this->base . ", " . ($this->scrollsize + 1);
    }

    $result = mysql_query($sql);
    if ($result) {
      while($row = mysql_fetch_object($result)) {
	$this->article_no[] = $row->article_no;
	$this->title[] = $row->title;
	$this->description[] = $row->description;
	$this->author[] = $row->name;
	$this->author_no[] = $row->author;
	$this->author_email[] = $row->email;
	$this->icon[] = $row->graphic_2;
      }
    }
    else {
      return  mysql_error() . "<br>\n<pre>\n" . $sql . "\n</pre>\n";
    }

  }

}

?>
