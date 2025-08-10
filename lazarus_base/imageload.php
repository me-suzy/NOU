<html>
<head><title>Image Loader</title></head>
<script language="JavaScript">
function resize(imagename){
  var minwidth=130;
  var minheight=130;
  
  if (imagename.width>=minwidth-30){
    var width=imagename.width+30;
  }  
  else{
    var width=minwidth;
  }
  
  if (imagename.height>=minheight-70){  
    var height=imagename.height+100;
  }
  else{
    var height=minheight;
  }
  
  window.resizeTo(width,height);
}
function backdir(){
  this.opener.focus();
  this.opener.navigate("/imagedir.php?action=<?php echo $action?>#<?php echo $image?>");
  this.close();
}
function reset(imagefile,mode){
  if (mode=="Use"){
    this.opener.opener.item_detailform.image_file.value=imagefile;
  }
  else{
    this.opener.opener.upform.delfile.value=imagefile;
  }
  this.opener.opener.focus();
  this.opener.close();
  this.close();
}
</script>
<body>
<center><img src="/images/<?php echo $image ?>" onLoad="resize(this);"></center>
<table cellspacing=0 cellpadding=0 width=100%>
<tr><td nowrap>
<center><input type="button" value="<?php echo $action?>" onClick="reset('<?php echo $image?>','<?php echo $action?>');">
<input type="button" value="Close" onClick="backdir();">
</center>
</td>
</tr>
</table>
</body>
</html>