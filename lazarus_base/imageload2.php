<html>
<head><title>Image Loader</title></head>

<script language="JavaScript">
function resize(imagename){
  var minwidth=130;
  var minheight=130;
  
  if (imagename.width>=minwidth-30 && imagename.height>=minheight-70){
    var width=imagename.width+30;
    var height=imagename.height+100;
  }
  else{
    var width=minwidth;
    var height=minheight;
  }
  window.resizeTo(width,height);
}
function backdir(){
  this.opener.focus();
  this.opener.navigate("http://toybox/imagedel.php#<?php echo $image?>");
  this.close();
}
function reset(imagefile){
  this.opener.opener.upform.delfile.value=imagefile;
  this.opener.opener.focus();
  this.opener.close();
  this.close();
}
</script>
<body>
<center><img src="/images/<?php echo $image?>" onLoad="resize(this);"></center>
<table cellspacing=0 cellpadding=0 width=100%>
<tr><td nowrap>
<center><input type="button" value="Delete" onClick="reset('<?php echo $image?>');">
<input type="button" value="Close" onClick="backdir();">
</center>
</td>
</tr>
</table>
</body>
</html>