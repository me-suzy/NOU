<?php

$TEMP_UNDEFINED = "";

class Templatizer {
  var $element;  // Array holding all of the currently used elements;
  var $template; // Array holding all of the templates we have loaded
  
  // Use the functions Load_Template or Import_Template to add a 
  // new template to our list.  Otherwise, default values will
  // not be set.


  function Templatizer() {
    if (isset($mysqllink)) {
      $sql = "SELECT entity, value FROM registry WHERE category='template-default'";
      $result = mysql_query($sql);
      if ($result) {
	while ($row = mysql_fetch_object($result)) {
	  $this->element[$row->entity] = $row->value;
	}
      }
    }
  }
  
  // Loads template(s) from a file.  If $strtemplatename is blank
  // individual templates in a file must be marked with 
  // <template name="template_name"> and </template> tags.
  function Load_Template($strfilename, $strtemplatename = "") {
    
    // If $strtemplatename is set, the whole file is one giant template.
    if ($strtemplatename != "") {
      $text = file($strfilename,1);
      $this->Import_Template($text, $strtemplatename);
    }
    else {
      $text = file($strfilename,1);
      $name = "";
      $content = array();
      for($i=0; $i < sizeof($text); $i++) {
	
	// End of the template.  Time to process it.
	if (stristr($text[$i], "</template>")) {
	  $this->Import_Template($content, $name);
	  $name = "";
	  $content = array();
	}
	
	//Accumulate the lines of the template.
	if ($name != "") {
	  array_push($content, $text[$i]);
	}
	else {
	  // Find the start of a template.  Set name if it's a real template.
	  if (stristr($text[$i], "<template")) {
	    $tag = stristr($text[$i], "<template");
	    $pos = strpos($tag, ">");
	    // echo htmlspecialchars($tag) . $pos . "<br>\n";
	    if ($pos) {
	      $tag = substr($tag, 10, $pos - 10);
	      // echo " -> " . htmlspecialchars($tag);
	      $parts = split("=", $tag);
	      if (strtolower($parts[0]) == "name") {
		$name = $parts[1];
		$name = trim(str_replace("\"","",$name));
	      }
	    }
	  }
	}
	
      }
      
    }
  }
  
  // Use this function for loading templates from non-disk sources.
  function Import_Template($strcontent, $strtemplatename) {
    for ($i = 0; $i < sizeof($strcontent); $i++) {
      $v = $strcontent[$i];
      $v = str_replace("\\{", "\\`", $v);
      $keylist = "";
      $pos = 0;
      $start = 0;
      $end = 0;
      while ($pos >= 0) {
	$pos = strpos($v, "{", $start);
	if ($pos === false) {
	  $pos = -1;
	}
	else {
	  $end = strpos($v, "}", $pos);
	  if ($end === false) {
	  	$pos = -1;  // We had a false positive.  Probably Javascript.
	  }
	  else {
	    $key = substr($v, $pos + 1, $end - $pos - 1);
	    $start = $end + 1;
	    
	    if ($start > strlen($v)) {
	      $pos = -1;
	    }
	    
	    // Set default value.
	    if (!isset($this->element[$key])) {
	      $this->element[$key] = $TEMP_UNDEFINED;
	    }
	    
	    // Append to the list of tokens for this line.
	    if ($keylist == "") {
	      $keylist = $key;
	    }
	    else {
	      $keylist = $keylist . Chr(2) . $key;
	    }
	  }
	}
      }
      
      // Unescape our bracket characters.
      $v = str_replace("\\`", "{", $v);
      
      // Prepend the list of keys to the front of each line.
      $strcontent[$i] = $keylist . Chr(1) . $v;
      //echo $v . "<br>\n";
      
    }
    
    // Stick our template into the big array
    $this->template[$strtemplatename] = $strcontent;
    
    //echo "<h3>$strtemplatename</h3>\n<pre>";
    //echo htmlspecialchars($strcontent) . "\n</pre>\n";
    
  }
  
  function Parse($strtemplatename, $strbecomeselement, $append=0) {
    $strvalue = "";
    $vtemplate = $this->template[$strtemplatename];
    
    for ($i=0; $i < sizeof($vtemplate); $i++) {
      $v = $vtemplate[$i];

      $parts = split(Chr(1), $v);
      $keylist = split(Chr(2), $parts[0]);
      $text = $parts[1];
      
      for ($j=0; $j < sizeof($keylist); $j++) {
	$text = str_replace("{" . $keylist[$j] . "}", stripslashes($this->element[$keylist[$j]]), $text);
      }

      $strvalue .= $text;
      
    }
    
    // Make the parsed template into an element.
    if ($append) {
      $this->element[$strbecomeselement] .= $strvalue;
    }
    else {
      $this->element[$strbecomeselement] = $strvalue;
    }
    
  }
  
}

?>
