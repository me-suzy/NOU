<?php

function Store_Upload($dest_dir, $given_name, $tmp_name, $tmp_size, $tmp_type)
{

  $dest = $DOCUMENT_ROOT . "/" . $dest_dir;
  $fname = strtolower($given_name);
  $dest .= "/" . $fname;
  
  copy($tmp_name, $dest);
  
  echo "File stored as " . $dest_dir . " <br>\n";
  echo $tmp_size . " bytes of type " . $tmp_type;

}

?>
