<?php

/*
   Plugin Name: Katalog
   Description: A plugin that show files from database
   Version: 1.0
   Author: Nejat ARINIK
   Author URI: http://nejatarinik.com
   */

function catalog(){

global $wpdb;
global $user_ID;

$step = 5;
$echo = "";
$echo .= '<form action="http://www.gsutt.com/yeni-dokuman/" method="get">
  <input type="submit" class="button-link" value="Yeni Döküman Eklemek Için Tiklayiniz"></form><br><br>';
$echo .= '<label for="autocomplete"><h2>Metin ismi girerek arama yapabilirsiniz: </h2></label>
<input name="fileName" style="width:500px;" id="autocomplete">
<div id="selectFile" style="height:70px;"><input type="hidden" id="autocompleteFileID" value="0"><br>(Otomatik arama icin en az 2 harf yazmis olmaniz gerekiyor)</div>';

$sql = "SELECT * FROM ".$wpdb->prefix . nmFileUploader::$tblName . " ORDER BY fileName";//ORDER BY fileUploadedOn DESC
$myrows = $wpdb->get_results($sql);

$selectGenre = '<select name="genre" class="genreSel" style="width:110px;"> <option value="Kriter yok" id="0" selected>Kriter yok</option>';
$arrayGenre = array();

foreach ($myrows as $key => $file) {
  if (!in_array($file->genre, $arrayGenre)) { array_push($arrayGenre,$file->genre);   $selectGenre .= '<option id="opt'.($key+1).'" value="'.$file->genre.'">'.$file->genre.'</option>';}
}
$selectGenre .= '</select>';


/*$tagsFileName = '[';
$tagsAuthor = '[';
$tagsFileName = substr($tagsFileName, 0, -2);//en sona ototmatik olarak  virgul ve bosluk geliyordu, onlari sildik
$tagsAuthor = substr($tagsAuthor, 0, -2);//en sona ototmatik olarak  virgul ve bosluk geliyordu, onlari sildik
$tagsFileName .= ']';
$tagsAuthor .= ']';*/



/*$echo .=  '<script>
var tags ='. $tagsFileName.';
$( "#autocomplete" ).autocomplete({
source: function( request, response ) {
var matcher = new RegExp( "^" + $.ui.autocomplete.escapeRegex( request.term ), "i" );
response( $.grep( tags, function( item ){
return matcher.test( item );
}) );
},

select: function (event, ui) {
 alert(ui.item.value);
 $.ajax({
        url:"<?php echo admin_url('admin-ajax.php'); ?>",
        data: {action: 'show_all_files', name: $(this).attr('name'),  author: ui.item.value, value1: options['genre'], value2: options['category'], value3: options['datePublished'], value4: options['totalPages'], value5: options['language']},
        success: function(ajaxresult){  $("#ajaxrequest").html(ajaxresult);},
        error:  function(xhr){   alert("An error occured: " + xhr.status + " " + xhr.statusText);}
    });
}

});

</script>';*/


$echo .= '<br><br><br><br><br><br><br><br><br><br><h2>Aramayi filtreleyebilirsiniz: </h2>
<table>
<tr class="tr_filtre">
    <td class="td_label_author">
        <label for="autocomplete2">Yazar: </label>
    </td>
    <td class="td_input_author"><input name="author" id="autocomplete2"><input type="submit" name="clearAuthorBox" value="clear" class="clearAuthorBox"> (En az 2 harf)<br><br></td>
    <td class="td_label_language">
        <label for="language">Dil: </label>
    </td>
    <td class="td_input_language">
        <select name="language" class="language">
          <option value="Kriter yok" id="0" selected>Kriter yok</option>
          <option value="Turkce" >Türkce</option>
          <option value="Fransizca">Fransizca</option>
          <option value="Ingilizce">Ingilizce</option>
        </select>
    </td>
    <td class="td_label_genre">
        <label for="genre">Tür: </label>
    </td>
    <td class="td_input_genre">'.$selectGenre.'</td>
</tr>
<tr class="tr_filtre">
    <td class="td_label_totalPages">
        <label for="totalPages">Uzunluk: </label>
    </td>
    <td class="td_input_totalPages">
        <select name="totalPages" class="totalPages">
            <option value="Kriter yok" id="0" selected>Kriter yok</option>
            <option value="35" >35 sayfaya kadar</option>
            <option value="70">35-70 sayfa arası</option>
            <option value="120">70-120 sayfa arası</option>
            <option value="121">120 sayfa ve üzeri</option>
        </select>
    </td>
    <td class="td_label_category">
        <label for="category">Kategori: </label>
    </td>
    <td class="td_input_category">
        <select name="category" class="category">
          <option value="Kriter yok" id="0" selected>Kriter yok</option>
          <option value="oyunlar" >Oyunlar</option>
          <option value="teorik">Teorik</option>
        </select>
    </td>
    <td class="td_label_characters">
        <label for="characters">Karakter Sayısı: </label>
    </td>
    <td class="td_input_characters">
        <select name="characters" class="characters" style="width:110px;">
            <option value="Kriter yok" id="0" selected>Kriter yok</option>
            <option value="1" >1 karakter</option>
            <option value="2">2 karakter</option>
            <option value="3" >3 karakter</option>
            <option value="4">4 karakter</option>
            <option value="10">5-10 karakter arası</option>
            <option value="15">10-15 karakter arası</option>
            <option value="20">15-20 karakter arası</option>
            <option value="21">20 karakter ve üzeri</option>
        </select>
    </td>
</tr>
<table>
<tr class="tr_filtre">
    <td class="td_orderAuthor">
        <input type="checkbox" class="orderAuthor" name="orderAuthor" value="orderAuthor">Yazar ismine göre sıralayın<br>
    </td>
    <td class="td_orderDate">
        <input type="checkbox" class="orderDate" name="orderDate" value="orderDate">Kataloga eklenme tarihine göre sıralayın
    </td>
</tr>
</table>
</table><br>';

$echo .= '<div id="dialog_delete_confirm"></div>';
$echo .= '<div id="ajaxrequest">';

$echo .= '<input type="hidden" id="dataTable" value="'.count($myrows).'" name="'.$sql.'">
<table id="showFiles"><caption><h2>Katalog</h2></caption><tr><th>Isim</th><th>Yazar</th><th>Yayım Tarihi</th><th>Tür</th><th>Çevirmen</th><th>Karakter sayısı</th><th>Indir</th><th>Sil</th></tr>';
foreach ($myrows as $key => $file) {
    if($key<$step){
        $car = ($file->characters == 0) ? "---" : $file->characters;
        $original = ($file->originalName == "---") ? "" : '('.$file->originalName.')';
        $echo .= '<tr id="tr'.($key+1).'" class="tr_catalog"><td class="td_fileName"><img src="'.plugins_url("images/" , __FILE__ ).$file->category.'.png" style="padding: 0px; border: 0px;" alt="kategori">  <a href="https://drive.google.com/file/d/'.$file->googleFileID.'/edit?usp=sharing" target="_blank">' . $file->fileName . '</a><br>'.$original.' ('.$file->totalPages.' sayfa)</td><td class="td_author">' . $file->author . '</td><td class="td_datePublished">' . $file->datePublished . '</td><td class="td_genre">' . $file->genre . '</td><td class="td_translator">' . $file->translator . '</td><td class="td_characters">' . $car . '</td><td class="td_urlDownload"><a href="' . $file->urlDownload . '"><img src="'.plugins_url("images/" , __FILE__ ).'down_32.png" style="padding: 0px; border: 0px;vertical-align: middle;" alt="indir"></a></td>';
        if($user_ID == $file->userID){
            $echo .= '<td class="td_delete"><img src="'.plugins_url("images/" , __FILE__ ).'delete_32.png" class="delete" style="padding: 0px; border: 0px;vertical-align: middle;" alt="'.$file->fileID.'--'.$file->googleFileID.'"></td></tr>';
        }
        else{
             $echo .= '<td class="td_delete">     ---</td></tr>';
        }
    }
}
$echo .= '</table><p>'.count($myrows).' sonuç bulundu</p><p>1. ile '.$step.'. arası dökümanları görmektesiniz</p></div>';

$echo .= '<div id="choice">
<table>
<tr>
<td><input type="submit" name="left" value="Previous" id="left"></td>
<td style="width:80px;"><input type="submit" name="right" value="Next" id="right"></td>
</tr>
</table>
</div>';

/*$echo .=  '<script>
var tags2 ='. $tagsAuthor.';
$( "#autocomplete2" ).autocomplete({
source: function( request, response ) {
var matcher = new RegExp( "^" + $.ui.autocomplete.escapeRegex( request.term ), "i" );
response( $.grep( tags2, function( item ){
return matcher.test( item );
}) );
}
});
</script>';*/

echo $echo;

?>


<script>
$("#left").hide();
var step = 5;

$(document).ready(function(){

//reset each input
$(".genreSel").val("Kriter yok");
$(".category").val("Kriter yok");
$(".characters").val("Kriter yok");
$(".totalPages").val("Kriter yok");
$(".language").val("Kriter yok");
$(".orderAuthor").attr('checked', false);
$(".orderDate").attr('checked', false);
$('#autocomplete2').val("");



/* Click functions*/

$("#left").click(function(){
    id=$("#showFiles tr:first-child").next().attr("id");
    sql = $("#dataTable").attr("name");

    var current = parseInt(id.substring(2)) -1;//first child'i buluyoruz
    var totalInt =1;
    var flagHideLeft = false;

    var number1 = parseInt(totalInt / step);
    var number2 = parseInt(current / step);

    if( (number2 - number1) == 1){
        flagHideLeft = true;
    }

    if("tr1" != id && $("#showFiles").children().children().length != 0){// yani listenin en basindaysak sola tiklama butonu bir ise yaramasin
      txt=$("#left").attr('name');
      $.ajax({url: "<?php echo admin_url('admin-ajax.php'); ?>",
          data:{action: 'show_all_files', name: txt, lastFileNumberOnList: id, query: sql},
          success: function(ajaxresult){
            $("#right").show();

            if(flagHideLeft){
                $("#left").hide();
            }

            $("#ajaxrequest").html(ajaxresult);
        },
          error:  function(xhr){   alert("An error occured: " + xhr.status + " " + xhr.statusText);}
      });
  }
});

$("#right").click(function(){
    id=$("#showFiles tr:last-child").attr("id");
    total = $("#dataTable").attr("value");

    var current = parseInt(id.substring(2)) -(step-1);//first child'i buluyoruz
    var totalInt = parseInt(total)-1;
    var flagHideRight = false;

    var number1 = parseInt(totalInt / step);
    var number2 = parseInt(current / step);

    if( (number1 - number2) == 1){
        flagHideRight = true;
    }

    sql = $("#dataTable").attr("name");

    if(id != "tr"+total && $("#showFiles").children().children().length != 0){//yani sonuncuysa saga tiklama butonu bir ise yaramasin
        txt=$("#right").attr('name');
        $.ajax({url: "<?php echo admin_url('admin-ajax.php'); ?>",
            data:{action: 'show_all_files', name: txt, lastFileNumberOnList: id, query: sql},
            success: function(ajaxresult){
                $("#left").show();

                if(flagHideRight){
                    $("#right").hide();
                }

              $("#ajaxrequest").html(ajaxresult);
            },
            error:  function(xhr){   alert("An error occured: " + xhr.status + " " + xhr.statusText);}
        });
    }
});

$(document).on("click",".clearAuthorBox", function(){
//check clear button for author
    if($('#autocomplete2').val() != ''){
        $('#autocomplete2').val("");

        var options = [];
        var orderBy;

        //configuration for checkbox
        if($(this).attr('name') == "orderDate"){
             $(".orderAuthor").attr('checked', false);
        }
        else if($(this).attr('name') == "orderAuthor"){
            $(".orderDate").attr('checked', false);
        }

        if($(".orderAuthor").is(":checked")){
            $(".orderDate").attr('checked', false);
            orderBy = "ORDER BY author ASC";
        }
        else if($(".orderDate").is(":checked")){
            $(".orderAuthor").attr('checked', false);
            orderBy = "ORDER BY fileUploadedOn DESC";
        }
        else{
            orderBy = "ORDER BY fileName ASC";
        }

        options['genre'] = $(".genreSel").find("option:selected").val();
        options['category'] = $(".category").find("option:selected").val();
        options['characters'] = $(".characters").find("option:selected").val();
        options['totalPages'] = $(".totalPages").find("option:selected").val();
        options['language'] = $(".language").find("option:selected").val();
        options['author'] = "Kriter yok";


        $.ajax({url: "<?php echo admin_url('admin-ajax.php'); ?>",
            //traditional: true,
            data:{action: 'show_all_files', name: $(this).attr('name'), value1: options['genre'], value2: options['category'], value3: options['characters'], value4: options['totalPages'], value5: options['language'], value6: options['author'], value7: orderBy},
            success: function(ajaxresult){
                $("#ajaxrequest").html(ajaxresult);

            //Next ve Previous butonlarini duzenleme
                $("#left").hide();
                total = $("#dataTable").attr("value");
                totalInt = parseInt(total);
                if(totalInt <= step){
                    $("#right").hide();
                }
                else{
                    $("#right").show();
                }
            //end buton
            },
            error:  function(xhr){   alert("An error occured: " + xhr.status + " " + xhr.statusText);}
        });
    }
});


$(document).on("click",".delete", function(){
    //https://stackoverflow.com/questions/17715274/jquery-click-function-doesnt-work-after-ajax-call
    $("#dialog_delete_confirm").html("Dökümani gercekten silmek istiyor musunuz?");
    IDs=$(this).attr('alt').split('--');//image tag'inin alt atrtribut'sune fileID ve GoogleFileId'yi koymustuk, onu geri cagiriyoruz tiklanan file icin
   $("#dialog_delete_confirm").dialog({
        resizable: false,
        draggable: false,
        modal: true,
        title: "Konfirmasyon",
        height: 200,
        width: 300,
        open: function(event, ui) {
                $(".ui-dialog-titlebar-close").hide();
                $('body').addClass('stop-scrolling');
        },
        buttons: {
            "Yes": function () {
                sql = $("#dataTable").attr("name");

                $.ajax({url: "<?php echo admin_url('admin-ajax.php'); ?>",
                    data:{action: 'show_all_files', name: 'delete', query: sql, fileID: IDs[0], googleFileID: IDs[1]},
                    success: function(ajaxresult){
                      $("#ajaxrequest").html(ajaxresult);

                      if($("#autocompleteFileID").val() == IDs[0]){//en yukardaki tekli file bulma alanini kontrol etmek lazim. Eger asigadaki listede sildigin dokiman o yukardaki alanda belirmisse, onu da silmek gerekir
                          $("#selectFile").html('<br>(Otomatik arama icin en az 2 harf yazmis olmaniz gerekiyor)');
                      }

                    //Next ve Previous butonlarini duzenleme
                        $("#left").hide();
                        total = $("#dataTable").attr("value");
                        totalInt = parseInt(total);
                        if(totalInt <= step){
                            $("#right").hide();
                        }
                        else{
                            $("#right").show();
                        }
                    //end buton
                    },
                    error:  function(xhr){   alert("Döküman silinirken bir sorun olustu: " + xhr.status + " " + xhr.statusText);}
                });

                $('body').removeClass('stop-scrolling');
                $(this).dialog('close');
            },
            "No": function () {
                $('body').removeClass('stop-scrolling');
                $(this).dialog('close');
            }
        }
    });
});

});

/* Change functions */


$(".genreSel, .category, .characters, .totalPages, .language, .orderAuthor, .orderDate").change(function(){
    var options = [];
    var orderBy;

    //configuration for checkbox
    if($(this).attr('name') == "orderDate"){
         $(".orderAuthor").attr('checked', false);
    }
    else if($(this).attr('name') == "orderAuthor"){
        $(".orderDate").attr('checked', false);
    }

    if($(".orderAuthor").is(":checked")){
        $(".orderDate").attr('checked', false);
        orderBy = "ORDER BY author ASC";
    }
    else if($(".orderDate").is(":checked")){
        $(".orderAuthor").attr('checked', false);
        orderBy = "ORDER BY fileUploadedOn DESC";
    }
    else{
        orderBy = "ORDER BY fileName ASC";
    }

    options['genre'] = $(".genreSel").find("option:selected").val();
    options['category'] = $(".category").find("option:selected").val();
    options['characters'] = $(".characters").find("option:selected").val();
    options['totalPages'] = $(".totalPages").find("option:selected").val();
    options['language'] = $(".language").find("option:selected").val();
    //console.log($('#autocomplete2').val());
    options['author'] = ($('#autocomplete2').val() != "") ? $('#autocomplete2').val() : "Kriter yok";


    $.ajax({url: "<?php echo admin_url('admin-ajax.php'); ?>",
        //traditional: true,
        data:{action: 'show_all_files', name: $(this).attr('name'), value1: options['genre'], value2: options['category'], value3: options['characters'], value4: options['totalPages'], value5: options['language'], value6: options['author'], value7: orderBy},
        success: function(ajaxresult){
            $("#ajaxrequest").html(ajaxresult);

        //Next ve Previous butonlarini duzenleme
            $("#left").hide();
            total = $("#dataTable").attr("value");
            totalInt = parseInt(total);
            if(totalInt <= step){
                $("#right").hide();
            }
            else{
                $("#right").show();
            }
        //end buton

        },
        error:  function(xhr){   alert("An error occured: " + xhr.status + " " + xhr.statusText);}
    });
});


//kaynak: http://fr.openclassrooms.com/informatique/cours/decouvrez-la-puissance-de-jquery-ui/l-autocompletion-1
var json;
$('#autocomplete').autocomplete({
    source : function(requete, reponse){ // les deux arguments représentent les données nécessaires au plugin
    $.ajax({
            url : "<?php echo admin_url('admin-ajax.php'); ?>", // on appelle le script JSON
            dataType : 'json', // on spécifie bien que le type de données est en JSON
            data : {
                action: 'show_all_files', name: $('#autocomplete').attr('name'), fileName: $('#autocomplete').val() // on donne la chaîne de caractère tapée dans le champ de recherche
            },

            success : function(donnee){
                //console.log(donnee[0].fileName);
                console.log(donnee.length);
                json = donnee;
                reponse($.map(donnee, function(objet){
                    return objet.fileName;
                    //return objet.fileName + ', ' + objet.author; // on retourne cette forme de suggestion
                }));
            }
        });
    },
    minLength: 2,
    autoFocus: true,
    select: function( event, ui ) {
        var row = '<br><table><tr><th id="table_th">Isim</th><th id="table_th">Yazar</th><th id="table_th">Yayım Tarihi</th><th id="table_th">Tür</th><th id="table_th">Çevirmen</th><th id="table_th">Karakter sayısı</th><th id="table_th">Indir</th><th id="table_th">Sil</th></tr>';
        $.each(json, function (key, data) {
            if(ui.item.value == data.fileName){
                var car = "";
                if(data.characters == 0){ car = "---"; }
                else{ car = data.characters; }

                var original = "";
                if(data.originalName == "---"){ original = ""; }
                else{ original = data.originalName; }

                row += '<input type="hidden" id="autocompleteFileID" value="'+data.fileID+'"><tr class="tr_catalog"><td class="td_fileName"><img src="'+'<?php echo plugins_url("images/" , __FILE__ ); ?>'+data.category+'.png" style="padding: 0px; border: 0px;" alt="kategori">  <a href="https://drive.google.com/file/d/'+data.googleFileID+'/edit?usp=sharing" target="_blank">'+data.fileName+'</a><br>'+original+' ('+data.totalPages+' sayfa)</td><td class="td_author">'+data.author+'</td><td class="td_datePublished">'+data.datePublished+'</td><td class="td_genre">'+data.genre+'</td><td class="td_translator">'+data.translator+'</td><td class="td_characters">'+car+'</td><td class="td_urlDownload"><a href="'+data.urlDownload+'"><img src="'+'<?php echo plugins_url("images/" , __FILE__ ); ?>'+'down_32.png" style="padding: 0px; border: 0px;vertical-align: middle;" alt="indir"></a></td>';
                if("<?php global $user_ID; echo $user_ID; ?>" == data.userID){
                    row += '<td class="td_delete"><img src="'+'<?php echo plugins_url("images/" , __FILE__ ); ?>'+'delete_32.png" class="delete" style="padding: 0px; border: 0px;vertical-align: middle;" alt="'+data.fileID+'--'+data.googleFileID+'"></td></tr></table><br>';
                }
                else{
                     row += '<td class="td_delete">     ---</td></tr></table><br>';
                }

                $("#selectFile").html(row);
            }
        })

      }
});

var json2;
var sqlData;
var countRow;
$('#autocomplete2').autocomplete({
    source : function(requete, reponse){
        var options = [];
        var step=5;
        options['language'] = $(".language").find("option:selected").val();
        options['category'] = $(".category").find("option:selected").val();
        options['characters'] = $(".characters").find("option:selected").val();
        options['totalPages'] = $(".totalPages").find("option:selected").val();
        options['genre'] = $(".genreSel").find("option:selected").val();
        options['author'] = $('#autocomplete2').val();

        $.ajax({
                url : "<?php echo admin_url('admin-ajax.php'); ?>", // on appelle le script JSON
                dataType : 'json', // on spécifie bien que le type de données est en JSON
                data : {
                    action: 'show_all_files', name: $('#autocomplete2').attr('name'), value1: options['genre'], value2: options['category'], value3: options['characters'], value4: options['totalPages'], value5: options['language'], value6: options['author']
                },

                success : function(donnee){
                    sqlData = donnee[donnee.length-1];
                    donnee.pop();//sql bilgisini siliyoruz, pop() kullandik cunku array'in en sonuna koymustuk sql bilgisini
                    json2 = donnee;
                    countRow = donnee.length;

                    reponse($.unique($.map(donnee, function(objet){
                        return objet.author;
                        //return objet.fileName + ', ' + objet.author; // on retourne cette forme de suggestion
                    })) );
                },
                error:  function(xhr){   alert("An error occured: " + xhr.status + " " + xhr.statusText);}
            });
        },
    minLength: 2,
    autoFocus: true,
    select: function( event, ui ) {
            var table='<input type="hidden" id="dataTable" value="'+countRow+'" name="'+sqlData+'"><table id="showFiles"><caption><h2>Katalog</h2></caption><tr><th id="table_th">Isim</th><th id="table_th">Yazar</th><th id="table_th">Yayım Tarihi</th><th id="table_th">Tür</th><th id="table_th">Çevirmen</th><th id="table_th">Karakter sayısı</th><th id="table_th">Indir</th><th id="table_th">Sil</th></tr>';
            var i=1;
            var step=5;
            $.each(json2, function (key, data) {
                if(ui.item.value == data.author && i<=step){
                    var car = "";
                    if(data.characters == 0){ car = "---"; }
                    else{ car = data.characters; }

                    var original = "";
                    if(data.originalName == "---"){ original = ""; }
                    else{ original = data.originalName; }

                    table+='<tr id="tr'+i+'" class="tr_catalog"><td class="td_fileName"><img src="'+'<?php echo plugins_url("images/" , __FILE__ ); ?>'+data.category+'.png" style="padding: 0px; border: 0px;" alt="kategori">  <a href="https://drive.google.com/file/d/'+data.googleFileID+'/edit?usp=sharing" target="_blank">'+data.fileName+'</a><br>'+original+' ('+data.totalPages+' sayfa)</td><td class="td_author">'+data.author+'</td><td class="td_datePublished">'+data.datePublished+'</td><td class="td_genre">'+data.genre+'</td><td class="td_translator">'+data.translator+'</td><td class="td_characters">'+car+'</td><td class="td_urlDownload"><a href="'+data.urlDownload+'"><img src="'+'<?php echo plugins_url("images/" , __FILE__ ); ?>'+'down_32.png" style="padding: 0px; border: 0px;vertical-align: middle;" alt="indir"></a></td>';
                    if("<?php global $user_ID; echo $user_ID; ?>" == data.userID){
                        table += '<td class="td_delete"><img src="'+'<?php echo plugins_url("images/" , __FILE__ ); ?>'+'delete_32.png" class="delete" style="padding: 0px; border: 0px;vertical-align: middle;" alt="'+data.fileID+'--'+data.googleFileID+'"></td></tr>';
                    }
                    else{
                         table += '<td class="td_delete">     ---</td></tr>';
                    }

                    i++;
                }
            })

            table += '</table><p>'+countRow+' sonuç bulundu</p><p>1. ile '+(i-1)+'. arası dökümanları görmektesiniz</p>';
            $("#ajaxrequest").html(table);

        //Next ve Previous butonlarini duzenleme
            $("#left").hide();
            total = $("#dataTable").attr("value");
            totalInt = parseInt(total);
            if(totalInt <= step){
                $("#right").hide();
            }
            else{
                $("#right").show();
            }
        //end buton

          }
});


</script>

<?php
}//end function



//add_action("wp_head", "catalog")

add_shortcode( 'katalog', 'catalog');


/**
* FOR AJAX
*/

add_action('wp_ajax_show_all_files', 'show_all_files');
add_action('wp_ajax_nopriv_show_all_files', 'show_all_files');

$ajax_catalog = dirname(__FILE__).'/ajax_catalog.php';

include ($ajax_catalog);  //the function "show_all_files" is in ajax_catalog.php

/**
 * Enqueue plugin style-file
 */

add_action( 'wp_enqueue_scripts', 'my_stylesheet_catalog' );

function my_stylesheet_catalog() {
    // Respects SSL, Style.css is relative to the current file
    wp_register_style( 'style_catalog', plugins_url('style_catalog.css', __FILE__) );
    wp_enqueue_style( 'style_catalog' );
}





?>
