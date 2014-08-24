
<?php

//echo '<script type="text/javascript"> alert("index.php"); </script>';
if(isset($_POST['search'])){
  addButtons('insert');
  $file = dirname(__FILE__).'/catalog.php';
  include($file);
}
else if(isset($_POST['insert']) || isset($_POST['nm-submit']) || (isset($_GET['fid']) && isset($_GET['googleFileID']))){
  addButtons('search');
  $file = dirname(__FILE__).'/upload-form.php';
  include($file);
}
else{
  addButtons('insert', 'search');
}


function addButtons($button1, $button2=""){
  $echo='';
  if($button1 == 'insert'){
    $echo .= '<div id="choice">  <form method="post"  id="form"> <ul> <li> <input type="submit" id="insert" value="Yeni pdf ekle!" name="insert"> </li><br>';
  }
  else{
     $echo .= '<div id="choice">  <form method="post"  id="form"> <ul> <li> <input type="submit" id="search" value="Katalogda ara!" name="search"> </li>';
  }

  if($button2 != ""){
    if($button2 == 'insert'){
      $echo .= '<li> <input type="submit" id="insert" value="Yeni pdf ekle!" name="insert"> </li>';
    }
    else{
      $echo .= '<li> <input type="submit" id="search" value="Katalogda ara!" name="search"> </li>';
    }
  }

  $echo .= '<br> </ul> </form> </div>';
  echo $echo;
}

?>


<!--
input icine  onclick="showCatalog"

<div id="operationArea" ><!--style="height:450px;"



</div>

<script>
function showUploadForm()
{
var xmlhttp;

if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function(){
  if (xmlhttp.readyState==4 && xmlhttp.status==200){
    document.getElementById("operationArea").innerHTML=xmlhttp.responseText;
  }
}

xmlhttp.open("GET","upload-form.php?val=1",true);
xmlhttp.send();


}

</script>
-->