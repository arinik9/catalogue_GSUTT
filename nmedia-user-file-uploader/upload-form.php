<?php

global $wpdb;

global $current_user;
get_currentuserinfo();

nmFileUploader::makeUploadDirectory();

 /*
 save file
*/

if(isset($_POST['nm-submit'])){
    $_POST['file-name'] = stripslashes($_POST['file-name']);// eger isimde kesme isareti yani apostrof varsa diye kullaniyoruz

    $sql = 'SELECT * FROM '.$wpdb->prefix . nmFileUploader::$tblName;
    $sql .= ' WHERE fileName = "'. $_POST['file-name'].'"';
    //echo '<script type="text/javascript"> console.log('.$sql.'); </script>';
    $myrows = $wpdb->get_results($sql);

    if(count($myrows) == 0){
        /*
        $fileNameSanitazed = explode(".", $_POST['file-name']);
        $fileNameSanitazed[0] = trim($fileNameSanitazed[0]);
        $fileNameSanitazed[0] = str_replace("\t", " ", $fileNameSanitazed[0]);
        $fileNameSanitazed[0] = preg_replace('#[ ]+#'," ", $fileNameSanitazed[0]);
        $fileNameSanitazed[1] = trim($fileNameSanitazed[1]);

        $file_name_sanitazed = $fileNameSanitazed[0].'.'.$fileNameSanitazed[1];

        nmFileUploader::$file_name  = $file_name_sanitazed;
        */

        $fileNameSanitazed = trim($_POST['file-name']);
        $fileNameSanitazed = str_replace("\t", " ", $fileNameSanitazed);
        $fileNameSanitazed = preg_replace('#[ ]+#'," ", $fileNameSanitazed);

    	nmFileUploader::$file_name 	= $fileNameSanitazed;

        $authorSanitazed = trim($_POST['author']);
        $authorSanitazed = str_replace("\t", " ", $authorSanitazed);
        $authorSanitazed = preg_replace('#[ ]+#'," ", $authorSanitazed);

        nmFileUploader::$author     = $authorSanitazed;

    	nmFileUploader::$category 	= sanitize_text_field($_POST['category']);

    	nmFileUploader::$genre 	= sanitize_text_field($_POST['genre']);

    	nmFileUploader::$file_type	= "." . nmFileUploader::getFileExtension(sanitize_text_field($_POST['file-name']));

        nmFileUploader::$language  = sanitize_text_field($_POST['language']);

        if(isset($_POST['datePublished']) && $_POST['datePublished'] != ""){
            nmFileUploader::$datePublished = sanitize_text_field(trim($_POST['datePublished']));
        }
        else{
            nmFileUploader::$datePublished = "---";
        }

        if(isset($_POST['characters']) && $_POST['characters'] != ""){
            nmFileUploader::$characters = sanitize_text_field($_POST['characters']);
        }
        else{
            nmFileUploader::$characters = 0;
        }

        if(isset($_POST['originalName']) && $_POST['originalName'] != ""){
            $originalNameSanitazed = trim($_POST['originalName']);
            $originalNameSanitazed = str_replace("\t", " ", $originalNameSanitazed);
            $originalNameSanitazed = preg_replace('#[ ]+#'," ", $originalNameSanitazed);
            nmFileUploader::$originalName = $originalNameSanitazed;
        }
        else{
            nmFileUploader::$originalName = "---";
        }

        if(isset($_POST['translator']) && $_POST['translator'] != ""){
            $translatorSanitazed = trim($_POST['translator']);
            $translatorSanitazed = str_replace("\t", " ", $translatorSanitazed);
            $translatorSanitazed = preg_replace('#[ ]+#'," ", $translatorSanitazed);
            nmFileUploader::$translator = $translatorSanitazed;
        }
        else{
            nmFileUploader::$translator = "---";
        }


    	$saved = nmFileUploader::saveFileToGoogleDrive(); // Hem veritabanina hem Google drive'a kaydediyoruz

    	if($saved[0]){ //$saved[0] bize true ya da false donduruyor
    		echo "<div class=\"green\">". get_option('nm_file_uploaded_msg') ."</div>";
    		//echo '<p><a href="https://drive.google.com/uc?export=download&id='. nmFileUploader::$fileGoogleID .'"> Indirmek icin tiklayin</a></p>';
    	}
    	else{
    		echo '<span style="color: #f00;">'.$saved[1].'</span>'; //$saved[1] bize error type'ini gonderiyor
    	}

    }
    else{
        echo '<span style="color: #f00;">Dokuman yuklenemedi. Katalogda ayni isme sahip baska bir dokuman var</span><br><br><br>';
    }
}
//else if(isset($_POST['insert'])){
	//nothing
//}
else{

	//else koymamin nedeni, bir kere dosya sildik mi ?fid=40 gibi egerler url'de kaliyor. url"de kaldigi icin ve hemen usutne dosya eklemek istersek
	//dosyayi ekledikten sonra en son silinen dosyayi bir daha sikmeye calisiyor. Silemedigi icin error verdirtiyo

/*
 delete file

*/

	/*if(isset($_GET['fid'])){
		if(isset($_GET['googleFileID'])){
			nmFileUploader::deleteFile(intval($_GET['fid']), $_GET['googleFileID']);
		}
		else{
			nmFileUploader::deleteFile(intval($_GET['fid']));
		}
		echo "<div class=\"red\">". get_option('nm_file_deleted_msg') ."</div>";
	}*/
}



/*$wpdb->show_errors();

 $wpdb->print_error(); */


?>



<div id="nm-upload-container">

<p style="text-align:center">

<strong><?php _e('Upload file(s):', nmFileUploader::$short_name)?></strong>

<span style="font-style: italic"><?php _e('click button below and browse for your file(s), then click "Save"', nmFileUploader::$short_name)?></span>

</p>

<div id="error"></div>

<form method="post"  onSubmit="return validate('<?php echo plugins_url('', __FILE__);?>')" id="frm_upload">  <!--action="http://www.gsutt.com/belgeler/" -->

<input type="hidden" name="file-name" id="file-name">



<div class="nm-uploader-area">


<input id="file_upload" name="file_upload" type="file"/>
<!-- <div id="drop_zone" style="height:400px;">Drop files here</div>
<output id="list"></output> -->
<span id="upload-response"></span>
<!--
 <script>
  function handleFileSelect(evt) {
    evt.stopPropagation();
    evt.preventDefault();

    var files = evt.dataTransfer.files; // FileList object.

    // files is a FileList of File objects. List some properties.
    var output = [];
    for (var i = 0, f; f = files[i]; i++) {
      output.push('<li><strong>', escape(f.name), '</strong> (', f.type || 'n/a', ') - ',
                  f.size, ' bytes, last modified: ',
                  f.lastModifiedDate ? f.lastModifiedDate.toLocaleDateString() : 'n/a',
                  '</li>');
    }
    document.getElementById('list').innerHTML = '<ul>' + output.join('') + '</ul>';
  }

  function handleDragOver(evt) {
    evt.stopPropagation();
    evt.preventDefault();
    evt.dataTransfer.dropEffect = 'copy'; // Explicitly show this is a copy.
  }

  // Setup the dnd listeners.
  var dropZone = document.getElementById('drop_zone');
  dropZone.addEventListener('dragover', handleDragOver, false);
  dropZone.addEventListener('drop', handleFileSelect, false);
</script>
 -->
</div>

<?php
//first date published
//genre
//category
//
global $wpdb;

//$selectFileName = '<select name="fileName"> <option value="kriter yok" selected>kriter yok</option>';
$selectAuthor = '<select name="author" id="authorSel" style="width:180px;"> <option value="Tıklayıp göz gezdirin" id="0" selected>Tıklayıp göz gezdirin</option>';
$selectGenre = '<select name="genre" id="genreSel" style="width:180px;"> <option value="Tıklayıp göz gezdirin" id="0" selected>Tıklayıp göz gezdirin</option>';
//$selectCategory = '<select name="category" id="categorySel"> <option value="Tıklayıp göz gezdirin" id="0" selected>Tıklayıp göz gezdirin</option>';
$selectDatePublished = '<select name="datePublished" id="datePublishedSel" style="width:180px;"> <option value="Tıklayıp göz gezdirin" id="0" selected>Tıklayıp göz gezdirin</option>';

//$arrayFileName = array();
$arrayAuthor = array();
$arrayGenre = array();
//$arrayCategory = array();
$arrayDatePublished = array();
//fileName, author, genre, category, datePublished
$myrows = $wpdb->get_results( "SELECT fileName, author, genre, category, datePublished FROM ".$wpdb->prefix . nmFileUploader::$tblName." ORDER BY fileName DESC");
foreach ($myrows as $key => $file) {
      // if (in_array($file->fileName, $arrayFileName)) { array_push($arrayFileName,$file->fileName); 	$selectFileName .= '<option id="opt'.($key+1).'" value="'.$file->fileName.'">'.$file->fileName.'</option>';}
       if (!in_array($file->author, $arrayAuthor)) { array_push($arrayAuthor,$file->author); 	/*$selectAuthor .= '<option id="opt'.($key+1).'" value="'.$file->author.'">'.$file->author.'</option>';*/}
       if (!in_array($file->genre, $arrayGenre)) { array_push($arrayGenre,$file->genre); /*	$selectGenre .= '<option id="opt'.($key+1).'" value="'.$file->genre.'">'.$file->genre.'</option>';*/}
       //if (!in_array($file->category, $arrayCategory)) { array_push($arrayCategory,$file->category); 	$selectCategory .= '<option id="opt'.($key+1).'" value="'.$file->category.'">'.$file->category.'</option>';}
       if (!in_array($file->datePublished, $arrayDatePublished) && $file->datePublished != "---") { array_push($arrayDatePublished,$file->datePublished); 	/*$selectDatePublished .= '<option id="opt'.($key+1).'" value="'.$file->datePublished.'">'.$file->datePublished.'</option>';*/}
}
//$selectFileName .= '</select>';

sort($arrayAuthor);
foreach ($arrayAuthor as $key => $author) {
  $selectAuthor .= '<option id="opt'.($key+1).'" value="'.$author.'">'.$author.'</option>';
}

sort($arrayGenre);
foreach ($arrayGenre as $key => $genre) {
  $selectGenre .= '<option id="opt'.($key+1).'" value="'.$genre.'">'.$genre.'</option>';
}

sort($arrayDatePublished);
foreach ($arrayDatePublished as $key => $date) {
  $selectDatePublished .= '<option id="opt'.($key+1).'" value="'.$date.'">'.$date.'</option>';
}
$selectAuthor .= '</select>';
$selectGenre .= '</select>';
//$selectCategory .= '</select>';
$selectDatePublished .= '</select>';

echo '<div style="background-color:hsla(290,60%,70%,0.3); color:#0000ff;"><p><strong>Not 1:</strong> Dosya yükleme işlemi bazen duruyor gibi gözükebilir. Kırmızı renkli bir uyarı çıkmadığı taktirde lütfen işlemin bitmesini bekleyiniz</p>
<p><span style="color:#C00000;"><strong>Not 2:</strong> Kataloga aynı isimde 2. bir döküman ekleyemezsiniz. Eğer yüklemek istediğiniz döküman katalogda bulunuyorsa ama başka bir versiyonuysa dosya isminin sonuna "versiyon 2" eklenebilir. Mesela "İçerdekiler" metni için "İçerdekiler versiyon 2" gibi</span></p>
<p><strong>Not 3:</strong> Word 2003 yani .doc uzantılı dosya yüklemeyiniz. Eger elinizde .doc uzantılı bir döküman varsa Office Word ile açıp .docx olarak ya da odt olarak kaydedebilirsiniz veya pdf formatına dönüştürebilirsiniz. Pdf, odt ya da docx dışında döküman yüklemezsek daha iyi olur</p>
<p><strong>Not 4:</strong> Eğer yükleyeceğiniz dökümanın türkçesi var ve siz başka bir dilde aynı dökümanı yüklemek istiyorsanız, yabancı metnin ismini orjinali gibi bırakıp <em>Eserin orjinal ismi</em> kutucuğuna eser isminin türkçe karşılığını yazabilirsiniz</p>
<p><strong>Not 5:</strong> Yüklemek istediğiniz döküman pdf formatındaysa ve sayfalar bir ters bir düz ise windows için <a style="color:#C00000;" href="http://www.gsutt.com/downloads/pdfsam-win-v2_2_1.exe">pdfsam</a> programını indirerek kolaylıkla sorunu çözebilirsiniz. Daha detaylı bilgiye <a style="color:#C00000;" href="http://www.gsutt.com/?p=1549">burdan</a> ulaşabilirsiniz</p>
<p><strong>Not 6:</strong> Boyutu 32 megabaytın üzerinde olan dökümanlar yüklenmez</p>
<p><strong>Not 7:</strong> Eğer elinizde scan edilmiş resim dosyaları varsa şu şekilde pdf haline getirebiliriz: Önce <em><a style="color:#C00000;" href="http://www.gsutt.com/downloads/iview438_setup.exe">IrfanView</a></em> programını indirerek resimlerin boytunu istediğimiz gibi ayarlayıp kaydedelim. Kaydedilirken otomatik olarak resimler sıkıştırılıyor yani boyutu azalıyor. Programsız/yüklemesiz olan <em><a style="color:#C00000;" href="http://www.convert-jpg-to-pdf.net/" target="_blank">www.convert-jpg-to-pdf.net</a></em> sitesini kullanabilirsiniz pdf formatına çevirmek için. Sitenin içinde kullanım videosu da var</p>
<p><strong>Not 8:</strong> Sayfayı yenilirken <em>Döküman yüklenemedi. Katalogda ayni isme sahip başka bir döküman var</em> yazısı çıkabilir, dikkate almayın</p></div>
<table id="uploadForm">
<tr>
    <td>
        <label for="originalName">Eserin orjinal ismi (varsa): </label>
    </td>
    <td style="width:200px;"><br></td>
    <td style="width:300px;">
        <input type="text" name="originalName" id="originalName" style="width:260px;">
    </td>
</tr>

<tr id="tr_input_translator_tr">
	<td>
		<label for="translator">Cevirmenin ismi (varsa):</label>
	</td>
	<td style="width:200px;"><br></td>
  	<td style="width:300px;">
  		<input type="text" name="translator" id="translator" style="width:260px;">
 	</td>
</tr>

<tr id="tr_input_author_tr">
    <td>
        <label for="author">yazar: (<em>soyisim</em> <em>isim</em> şeklinde)</label>
    </td>
    <td style="width:200px;">'.$selectAuthor.'</td>
    <td style="width:300px;">
        <input type="text" name="author" id="author" style="width:260px;" required>
    </td>
</tr>

<tr id="tr_input_category_tr">
	<td>
		<label for="category">Kategori: </label>
	</td>
	<td><br></td>
	<td>
		<select id="categorySel" name="category">
		  <!-- <option value="home" selected>Ana dizin</option> -->
		  <option value="oyunlar" selected>Oyunlar</option>
		  <option value="teorik">Teorik</option>
		</select>
	</td>
</tr>

<tr>
	<td>
		<label for="genre">Eserin türü:<br>(bilmiyorsanız <em>Tıklayıp göz gezdirin</em>\'daki 3 tireyi --- seçin)</label>
	</td>
	<td style="width:200px;">'.$selectGenre.'</td>
  	<td style="width:300px;">
  		<input type="text" name="genre" id="genre" style="width:260px;" required>
 	</td>
</tr>

<tr id="tr_input_datePublished_tr">
	<td>
		<label for="datePublished">Yayım Tarihi: (bulamadıysanız boş bırakın)</label>
	</td>
	<td style="width:200px;">'.$selectDatePublished.'</td>
  	<td style="width:300px;">
  		<!-- <input type="text" name="datePublished" id="datePublished" style="width:260px;"> -->
        <input type="number" name="datePublished" id="datePublished" min="1000" step="1" pattern="\d+" style="width:260px;">
  	</td>
</tr>

<tr id="tr_input_characters_tr">
    <td>
        <label for="characters">Karakter Sayısı:</label>
    </td>
    <td ><br></td>
    <td style="width:300px;">
        <input type="number" name="characters" id="characters" min="1" step="1" pattern="\d+" style="width:260px;" required>
    </td>
</tr>
<tr id="tr_input_language_tr">
    <td>
        <label for="language">Eserin dili: </label>
    </td>
    <td><br></td>
    <td>
        <select name="language" id="language">
          <option value="Turkce" selected>Türkçe</option>
          <option value="Fransizca">Fransızca</option>
          <option value="Ingilizce">Ingilizce</option>
        </select>
    </td>
</tr>';

?>

<script>

$(document).ready(function(){

//reset each input
$("#author").val("");
$("#categorySel").val("oyunlar");
$("#genre").val("");
$("#language").val("Turkce");
$("#datePublished").val("");
$("#characters").val("");

});



$("#authorSel").change(function(){
  	var objet = $(this).find("option:selected")
 	if(objet.attr("id") !== "0"){
  		$("#author").val(objet.val());
	}
	else{
		$("#author").val("");
	}
});

$("#categorySel").change(function(){
  	var objet = $(this).find("option:selected")//attr("id");
	$("#category").val(objet.val());
    if(objet.val() == "teorik"){
        $("#tr_input_characters_tr").remove();
        $("#tr_input_datePublished_tr").remove();
    }
    else{
        $( '<tr id="tr_input_datePublished_tr"><td><label for="datePublished">Yayım Tarihi: (bulamadıysanız boş bırakın)</label></td><td style="width:200px;">'+'<?php echo $selectDatePublished; ?>'+'</td><td style="width:300px;"><input type="text" name="datePublished" id="datePublished" style="width:260px;"></td></tr>').insertBefore("#tr_input_language_tr");
        $( '<tr id="tr_input_characters_tr"><td><label for="characters">Karakter Sayısı:</label></td><td ><br></td><td style="width:300px;"><input type="number" name="characters" id="characters" min="1" step="1" pattern="\d+" style="width:260px;"></td></tr>').insertBefore("#tr_input_datePublished_tr");
    }

});

$("#genreSel").change(function(){
  	var objet = $(this).find("option:selected")//attr("id");
 	if(objet.attr("id") !== "0"){
  		$("#genre").val(objet.val());
	}
	else{
		$("#genre").val("");
	}
});

$("#datePublishedSel").change(function(){
  	var objet = $(this).find("option:selected")//attr("id");
 	if(objet.attr("id") !== "0"){
  		$("#datePublished").val(objet.val());
	}
	else{
		$("#datePublished").val("");
	}
});

</script>

<!-- saving button -->
<tr>
	<td colspan="3" style="text-align:center;">
		<input class="nm-submit-button" type="submit" value="<?php _e('Save', 'nm_file_uploader_pro')?>" name="nm-submit" id="nm-upload">
	    <div id="working-area" style="display:none">
			<?php
				echo "<img src=".plugins_url( 'images/loading.gif' , __FILE__)." />";
			?>
	    </div>
	</td>
</tr>
</table>

</form>



<div style="clear:both"></div>

</div>



<script type="text/javascript">

		fileuploader_vars.current_user = '<?php echo $current_user -> user_nicename?>';

		setupUploader();

</script>





<?php

//file list

//check if displayFiles is True

	//$list_path = dirname(__FILE__).'/_template_uploader.php';

	//include ($list_path);



?>