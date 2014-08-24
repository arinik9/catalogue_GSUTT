
<div id="choice">
<table>
<tr>
<!-- <td><input type="submit" value="Hepsini goster" id="all"></td> -->
<td><input type="submit" value="<" id="left"></td>
<td><input type="submit" value=">" id="right"></td>
</tr>
</table>


</div>

<div id="ajaxrequest">

<?php
  global $wpdb;
  $step = 3;

  $myrows = $wpdb->get_results( "SELECT fileName, author FROM ".$wpdb->prefix . nmFileUploader::$tblName." ORDER BY fileUploadedOn DESC");
  $echo = "";
  $echo .= '<table id="showFiles">';
  foreach ($myrows as $key => $file) {
    if($key<$step){
      $echo .= '<tr id="tr'.($key+1).'"><td style="width:83%;">' . $file->fileName . '</td><td>' . $file->author . '</td></tr>';
    }
  }
  $echo .= '</table>';

  echo $echo;
?>

</div>

<script>
$(document).ready(function(){
/*$("#all").click(function(){
  txt=$("#all").val();
  $.ajax({url: "<?php echo admin_url('admin-ajax.php'); ?>",
  data:{action: 'show_all_files', name: txt},
  success: function(ajaxresult){  $("#ajaxrequest").html(ajaxresult);},
  error:  function(xhr){   alert("An error occured: " + xhr.status + " " + xhr.statusText);}
});
});*/

$("#left").click(function(){
  id=$("#showFiles tr:first-child").attr("id");
  if("tr1" != id){// yani listenin en basindaysak sola tiklama butonu bir ise yaramasin
      txt=$("#left").val();
      $.ajax({url: "<?php echo admin_url('admin-ajax.php'); ?>",
          data:{action: 'show_all_files', name: txt, lastFileNumberOnList: id},
          success: function(ajaxresult){  $("#ajaxrequest").html(ajaxresult);},
          error:  function(xhr){   alert("An error occured: " + xhr.status + " " + xhr.statusText);}
      });
  }
});

$("#right").click(function(){
    id=$("#showFiles tr:last-child").attr("id");
    total = "<?php global  $wpdb; $row = $wpdb->get_row('SELECT total FROM '.$wpdb->prefix.'totalFilesUploaded WHERE id = 1'); echo $row->total; ?>";
    if(id != "tr"+total){//yani sonuncuysa saga tiklama butonu bir ise yaramasin
        txt=$("#right").val();
        $.ajax({url: "<?php echo admin_url('admin-ajax.php'); ?>",
            data:{action: 'show_all_files', name: txt, lastFileNumberOnList: id},
            success: function(ajaxresult){  $("#ajaxrequest").html(ajaxresult);},
            error:  function(xhr){   alert("An error occured: " + xhr.status + " " + xhr.statusText);}
        });
    }
});

});
</script>



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