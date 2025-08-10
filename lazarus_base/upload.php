<?php

require_once("templatizer.php");
require_once("top.php");
require_once("menu.php");
require_once("fileupload.class");
require_once("security.php");

secRequire("Page");

$tp=new Templatizer();

$tp->Load_Template("standard.tpl");
$tp->Load_Template("upload.tpl");

#--------------------------------#
# Variables
#--------------------------------#
// The path to the directory where you want the 
// uploaded files to be saved. This MUST end with a 
// trailing slash unless you use $PATH = ""; to 
// upload to the current directory. Whatever directory
// you choose, please chmod 777 that directory.

//$dirname = $DOCUMENT_ROOT;
$dirname = "./";
if (substr($dirname, strlen($dirname) - 1, 1) != "/") {
  $dirname .= "/";
}
$dirname .= "images/";

	$PATH = $dirname;

// The name of the file field in your form.

	$FILENAME = "userfile";

// ACCEPT mode - if you only want to accept
// a certain type of file.
// possible file types that PHP recognizes includes:
//
// OPTIONS INCLUDE:
//  text/plain
//  image/gif
//  image/jpeg
//  image/png

$ACCEPT="image/gif image/jpeg image/pjpeg image/png text/plain";

// If no extension is supplied, and the browser or PHP
// can not figure out what type of file it is, you can
// add a default extension - like ".jpg" or ".txt"

	$EXTENSION = "";

// SAVE_MODE: if your are attempting to upload
// a file with the same name as another file in the
// $PATH directory
//
// OPTIONS:
//   1 = overwrite mode
//   2 = create new with incremental extention
//   3= do nothing if exists, highest protection

	$SAVE_MODE = 1;

#--------------------------------#
# PHP
#--------------------------------#

$upload = new uploader;
$upload->max_filesize(3000000);

if($upload->upload("$FILENAME", "$ACCEPT", "$EXTENSION")) {
	while(list($key, $var) = each($upload->file)){
		if ($key != "file")  
		  $tp->element["mainpage"].= $key . " = " . $var . "<br>";
	}
	if($upload->save_file("$PATH", $SAVE_MODE)) {
  	if($upload->new_file) {
		  if(ereg("image", $upload->file["type"])) {
			  $tp->element["show"]="<img src=\"/images/" . basename($upload->new_file) . "\" border=\"0\" alt=\"\">";
		  }
		  else {
			  $userfile = fopen($upload->new_file, "r");
			  while(!feof($userfile)) {
			  	$line = fgets($userfile, 255);
				  switch(2){
					  case 1:
						  $tp->element["show"] .= $line;
						  break;
					  case 2:
						  $tp->element["show"] .= nl2br(ereg_replace("\t", "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;", htmlentities($line)));
						  break;
				  }	
			  }
		  }
	  }
	}
}

if($upload->errors) {
	while(list($key, $var) = each($upload->errors)){
		$tp->element["mainpage"].= "<p>" . $var . "<br>";
	}
}


#--------------------------------#
# HTML FORM
#--------------------------------#
	if ($ACCEPT) {
		$tp->element["accept"]=$ACCEPT;
	}

$tp->Parse("upload","Pagebody",1);

$m = new Menu(2);
$tp->element["Pagemenu"]= $m->Load();
	
  $tp->Parse("page", "main");
  echo $tp->element["main"];

?>







