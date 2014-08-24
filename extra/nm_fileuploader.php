<?php

/*

Plugin Name: Nmedia File Uploader Plugin

Plugin URI: http://www.najeebmedia.com/nmedia-file-uploader-pro/

Description: This Plugin is developed by NajeebMedia.com

Version: 2.3

Author: Najeeb Ahmad

Author URI: http://www.najeebmedia.com/

*/
require_once(dirname( __FILE__ ).'/google-api-php-client/src/Google_Client.php');
require_once(dirname( __FILE__ ).'/google-api-php-client/src/contrib/Google_DriveService.php');
require_once(dirname( __FILE__ ).'/getTotalPageNumberWithoutExtension.php');

//include_once(str_replace("/", "\\", $_SERVER['DOCUMENT_ROOT']).'wp\wp-load.php');
class nmFileUploader {



	var $fileupload_db_version = "2.0";



	/*

	data vars

	*/



	static $title;

	static $file_name;

	static $author;

	//static $desc;

	static $file_type;

	static $file_size;

	static $pathUploads;

	static $fileGoogleID;

	static $fileUrlDownload;

	static $datePublished;

	static $category;

	static $genre;

	static $language;

	static $characters;

	static $originalName;

	static $translator;





	static $tblName = 'userfiles';



	static $short_name = 'nm_file_uploadersave';


	/*

	 ** pagination vars

	*/



	static $uploader_row_count;

	static $files_per_page = 5;

	static $total_pages;

	static $total_files;



	/**
	* constructor
	*/

	function nmFileUploader() {

		//$this -> loadJS();

	}



	function renderUserArea(){

		global $wpdb ;

		global $user_ID;

		global $current_user;

		get_currentuserinfo();



		if ( is_user_logged_in() ){

			ob_start();

	         nmFileUploader::renderForm();

			$output_string = ob_get_contents();

			ob_end_clean();

			return $output_string;

		}

		else

		{



			//wp_redirect( home_url() ); exit;*

			echo '<script type="text/javascript">

			window.location = "'.wp_login_url( get_permalink() ).'"

			</script>';

		}



	}



	/*

	This function is making directory in follownig path

	wp-content/uploads/user_uploads

	*/



	function makeUploadDirectory()

	{

		global $current_user;

		get_currentuserinfo();



		$upload_dir = wp_upload_dir();

		nmFileUploader::$pathUploads = $upload_dir['basedir'].'/user_uploads/'.$current_user -> user_nicename.'/';





		if(!is_dir(nmFileUploader::$pathUploads))

		{

			if(mkdir(nmFileUploader::$pathUploads, 0777, true))

				return true;

			else

				return false;

		}

		else

		{

			return true;

		}

	}





	/*

	Getting file extension

	*/

	function getFileExtension($file_name)

	{

		//echo substr(strrchr($file_name,'.'),1);

	 	return substr(strrchr($file_name,'.'),1);

	}



	public function fileuploader_install() {

		global $wpdb;

		global $fileupload_db_version;



		$table_name = $wpdb->prefix . nmFileUploader::$tblName;



		/* $sql = "CREATE TABLE `$table_name`

				(`fileID` INT( 9 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,

				`userID` INT( 7 ) NOT NULL ,

				`fileTitle` VARCHAR( 250 ) NULL ,

				`fileName` VARCHAR( 250 ) NULL ,

				`fileSize` INT( 15 ) NULL ,

				`fileDescription` MEDIUMTEXT NULL ,

				`fileType` VARCHAR( 15 ) NULL ,

				`fileUploadedOn` DATETIME NOT NULL )ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;"; */



		$sql = "CREATE TABLE `$table_name`

		(`fileID` INT( 9 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,

		`userID` INT( 7 ) NOT NULL ,

		`datePublished` VARCHAR( 4 ) DEFAULT '---' NOT NULL ,

		`author` VARCHAR( 140 ) DEFAULT '---' NOT NULL ,

		`translator` VARCHAR( 150 ) DEFAULT '---' NOT NULL ,

		`originalName` VARCHAR( 250 ) DEFAULT '---' NOT NULL ,

		`fileName` VARCHAR( 250 ) NULL ,

		`fileSize` INT(22) NULL ,

		`fileType` VARCHAR( 15 ) NULL ,

		`totalPages` INT( 9 ) NULL ,

		`googleFileID` VARCHAR( 200 ) NULL ,

		`characters` INT(2) DEFAULT 0 ,

		`category` VARCHAR( 250 ) NULL ,

		`genre` VARCHAR( 120 ) NOT NULL ,

		`language` VARCHAR( 25 ) NOT NULL ,

		`urlDownload` VARCHAR( 300 ) NOT NULL ,

		`fileUploadedOn` DATETIME NOT NULL )ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";



	   require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

	   dbDelta($sql);



	   add_option("fileupload_db_version", $fileupload_db_version);

	}





	/*

	 * unistalling table

	*/



	function fileuploaderUninstall(){



		global $wpdb;

		global $fileupload_db_version;





		//Delete any options thats stored also?

		delete_option($fileupload_db_version);

		$wpdb->query("DROP TABLE IF EXISTS ".$wpdb->prefix . nmFileUploader::$tblName);



	}





	/*

	form uploader

	*/

	public function renderForm()

	{

		$file = dirname(__FILE__).'/upload-form.php';
		//$file = dirname(__FILE__).'/index.php';

		include($file);

	}





	/*

	Listing user files in admin

	*/

	public function renderListings()

	{

		$file = dirname(__FILE__).'/listings.php';

		include($file);

	}


/*** YENI EKLENEN FONKSIYON  BEGIN***/
/*
	 Input: None
	Output: Google Docs'a yeni eklenen dosyanin id'si

	Dosyanin id'sini veritabanina kaydedip daha sonra indirme linki icin kullanicaz

*/
	public function saveFileToGoogleDrive() { // gsuttdocuments@gmail.com
		global $current_user, $wpdb;
		$returnData = array();
		$user_name = '';
		$user_id = '';
		$fileUploaded = "";
		$totalPages = 0;
		$category = 'Ana Dizin';

		get_currentuserinfo();

		if(is_user_logged_in()){
			$user_name = $current_user -> user_nicename;
			$user_id = $current_user -> ID;
		}

		$uploads = wp_upload_dir();
		$pathFileUploadedDir = $uploads['basedir'] . '/user_uploads/';

		$client = new Google_Client();

		// Get your credentials from the console
		$clientID = get_option( 'nm_file_clientID');
		$secretKey = get_option( 'nm_file_secretKey');
		echo '<br>'.$clientID;
		echo '<br>'.$secretKey;
		$client->setClientId($clientID);
		$client->setClientSecret($secretKey);
		$client->setRedirectUri('urn:ietf:wg:oauth:2.0:oob');
		$client->setScopes(array('https://www.googleapis.com/auth/drive'));

		$client->setAccessType('offline');

		$accessToken = get_option('refresh_token');
		$google_token= json_decode($accessToken);
		$client->refreshToken($google_token->refresh_token);
		echo '<br>'.$google_token->refresh_token;
		$service = new Google_DriveService($client);

		//Insert a file
		$file = new Google_DriveFile();

		$FileNameLength = strlen(nmFileUploader::$file_name) - strlen(nmFileUploader::$file_type);
		$fileTitle = substr(nmFileUploader::$file_name, 0, $FileNameLength);
		$file->setTitle($fileTitle);
		//$file->setDescription();

		if(nmFileUploader::$category != 'home'){//TO DO update gerekiyo --> bu haliyle de calisir tabi ki
	//https://developers.google.com/drive/v2/reference/files/insert
			$folderParentsId['teorik']  = "0B8v9utDj7Gt1c3VLT2h3bkFaOGM";
			$folderParentsId['oyunlar'] = "0B8v9utDj7Gt1c2FRaFhqT2MxdUE";
			//$folderParentsId['gsutt']  = "0B54VYLYKk7FBTE1ibnhDMVdXTzg";

		/*	$parent = new Google_ParentReference();
			$parent->setId($folderParentsId['gsutt']);//nmFileUploader::$category
			$file->setParents(array($parent));*/
		}
		//else'e gerek yok cunku kategori = 'home' ise zaten eklencek drive'a.
//echo '<script type="text/javascript"> alert("'.utf8_decode(nmFileUploader::$file_name).'"); </script>';
//echo '<script type="text/javascript"> alert("'.nmFileUploader::$file_name.'"); </script>';
//echo '<script type="text/javascript"> alert("'.utf8_encode(nmFileUploader::$file_name).'"); </script>';
//echo '<script type="text/javascript"> alert("'.urlencode(nmFileUploader::$file_name).'"); </script>';
		if(file_exists($pathFileUploadedDir.$user_name.'/'. nmFileUploader::$file_name)){
			//$data = file_get_contents( $pathFileUploadedDir.$user_name.'/' . nmFileUploader::$file_name);
			$fileUploaded = $pathFileUploadedDir.$user_name.'/'. nmFileUploader::$file_name;
		}
		else if(file_exists($pathFileUploadedDir. nmFileUploader::$file_name )){//Bunu kosulu koymamin sebebi, plugin'in kendi SAVE
				echo '<script type="text/javascript"> alert("icerde laaa 2"); </script>';																				//fonksiyonunda da olmasi. Baska sebebi yok
				$data = file_get_contents($pathFileUploadedDir. nmFileUploader::$file_name);
				$fileUploaded = $pathFileUploadedDir. nmFileUploader::$file_name;
		}
		else{
			array_push($returnData, false, "Dosya duzgun yuklenmedi. Lutfen Belgeler sayfasina tiklayip ardindan tekrar Yeni Dokuman ekle sayfasina geri donunuz ve dosyayi tekrar yuklemeyi deneyiniz. Sorun devam ederse lutfen ilgili kisilere haber veriniz");
			return $returnData;
		}

		$mimeTypes = array(
			".pdf" => 'application/pdf',
			".doc" => 'application/msword',
			".docx" => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
			".odt" => 'application/vnd.oasis.opendocument.text',
			".txt" => 'text/plain',
			".jpeg" => 'image/jpeg',
			".jpg" => 'image/jpeg',
			".jpe" => 'image/jpeg',
			".png" => 'image/png',
			".xls" => 'application/vnd.ms-excel',
			".xlsx" => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
			".zip" => 'application/zip',
			".rar" => 'application/x-rar-compressed',
			".tar" => 'application/x-tar',
			".tar.gz" => 'application/x-gzip',
			".tgz" => 'application/x-gzip',
			".gz" => 'application/x-gzip',
			".xml" => 'text/xml'
		);

		if($fileUploaded != ""){ // if file exist / was uploaded

			$file->setMimeType($mimeTypes[nmFileUploader::$file_type]);
			//$file->setMimeType($dataType);

			if(filesize($fileUploaded) > 15272624){ //eger 14-15mb'den buyukse file, parca parca yukluyoruz dosyayi. Cunku file_get_contents() belli bir limit koyuyo dosya boyutuna

				  $uploadStatus = array();
				  $filePath = $fileUploaded;
				  $chunkSizeBytes = 1 * 1024 * 1024;

				  $media = new Google_MediaFileUpload($mimeTypes[nmFileUploader::$file_type], null, true, $chunkSizeBytes);
				  $media->setFileSize(filesize($filePath));

				  $result = $service->files->insert($file, array('mediaUpload' => $media));

				  $status = false;
				  $handle = fopen($filePath, "rb");
				  while (!$status && !feof($handle)) {
				    $chunk = fread($handle, $chunkSizeBytes);
				    $uploadStatus = $media->nextChunk($result, $chunk);
				  }

				  fclose($handle);
				  $createdFile = $uploadStatus;

			}
			else{
				$data = file_get_contents($fileUploaded);

				$createdFile = $service->files->insert($file, array(
			      'data' => $data,
			      //'mimeType' => $dataType,
			      'mimeType' => $mimeTypes[nmFileUploader::$file_type],
				));
			}

			$fileSizeTotal = filesize($fileUploaded);

			//Google Docs'a yuklenen dosyayi public yapiyoruz ki kullanici indirebilsin -- Kullanici logout olunca bu dosyayi tekrar private yapabiliriz
			$permission = new Google_Permission();
			$permission->setRole( 'reader' );//or 'writer'
			$permission->setType( 'anyone' );
			$permission->setValue( 'me' );
			$permission->setWithLink(true);
			$service->permissions->insert( $createdFile['id'], $permission );

			//TO DO  google drive'a duzgun yuklenip yuklenilmedigini kontrol et

			if(nmFileUploader::$file_type == '.pdf'){
				$totalPages = getNumPagesInPDF($fileUploaded);			//Bu fonksiyonu require_once(dirname( __FILE__ ).'/getTotalPageNumberWithoutExtension.php');
															//yaparak kullandik
				unlink($fileUploaded);
			}
			else if(nmFileUploader::$file_type == '.docx'){//substr($fileUploaded,-5) == ".docx"
			    rename($fileUploaded, substr($fileUploaded, 0, -4).'zip');
			    $zip = new ZipArchive;
			    $res = $zip->open(substr($fileUploaded, 0, -4).'zip');
			    if ($res === TRUE) {
			        $zip->extractTo($pathFileUploadedDir.$user_name.'/', array('docProps/app.xml'));
			        $zip->close();
			    } else {
			        echo 'failed, code:' . $res;
			    }

			    $totalPages = getTotalPagesDocx($pathFileUploadedDir.$user_name.'/'.'docProps/app.xml');//require_once(dirname( __FILE__ ).'/getTotalPageNumberWithoutExtension.php');
			    unlink(substr($fileUploaded, 0, -4).'zip');
			    unlink($pathFileUploadedDir.$user_name.'/'.'docProps/app.xml');
			    rmdir($pathFileUploadedDir.$user_name.'/'.'docProps');
			}
			else if(nmFileUploader::$file_type == '.odt'){//substr($fileUploaded,-4) == ".odt"
			    rename($fileUploaded, substr($fileUploaded, 0, -3).'zip');
			    $zip = new ZipArchive;
			    $res = $zip->open(substr($fileUploaded, 0, -3).'zip');
			    if ($res === TRUE) {
			        $zip->extractTo($pathFileUploadedDir.$user_name.'/', array('meta.xml'));
			        $zip->close();
			    } else {
			        echo 'failed, code:' . $res;
			    }

			    $totalPages = getTotalPagesOdt($pathFileUploadedDir.$user_name.'/'.'meta.xml');//require_once(dirname( __FILE__ ).'/getTotalPageNumberWithoutExtension.php');
			    unlink(substr($fileUploaded, 0, -3).'zip');
			    unlink($pathFileUploadedDir.$user_name.'/'.'meta.xml');
			}
			else{
				$totalPages = 0;
				unlink($fileUploaded);
			}

			//upload edilip ama save edilmeyen dokumanlar varsa onlari silmek icin
			//herhangi bi dosya kataloga eklendiginde onu da kontrol ediyoruz
			$files1 = scandir($pathFileUploadedDir.$user_name);
			if($files1 !== false){
				foreach ($files1 as $key => $value) {
					if($key != 0 && $key != 1){
						unlink($pathFileUploadedDir.$user_name.'/'.$value);
					}
				}
			}



			nmFileUploader::$fileGoogleID = $createdFile['id'];
			nmFileUploader::$fileUrlDownload = 'https://drive.google.com/uc?export=download&id='. nmFileUploader::$fileGoogleID ;
			//return $createdFile['id'];

			$dt = array('userID'				=> $user_id,
						'datePublished' 		=> nmFileUploader::$datePublished,
						'author' 				=> nmFileUploader::$author,
						'translator' 			=> nmFileUploader::$translator,
						'originalName' 			=> nmFileUploader::$originalName,
						'fileName'				=> nmFileUploader::$file_name,
						'fileSize'				=> $fileSizeTotal,
						'fileType'				=> nmFileUploader::$file_type,
						'totalPages'			=> $totalPages,
						'googleFileID'			=> $createdFile['id'],
						'category'				=> nmFileUploader::$category,
						'genre'					=> nmFileUploader::$genre,
						'characters'			=> nmFileUploader::$characters,
						'language'				=> nmFileUploader::$language,
						'urlDownload'			=> nmFileUploader::$fileUrlDownload,
						'fileUploadedOn'		=> current_time('mysql')
					 );

/*	Ana table'a ekliyoruz	*/
			$wpdb -> insert($wpdb->prefix . nmFileUploader::$tblName, $dt,
				array('%d', '%s', '%s', '%s', '%s', '%s', '%d', '%s', '%d', '%s', '%s', '%s', '%d', '%s', '%s', '%s') );

			if($wpdb->insert_id){
				array_push($returnData, true);
			}
			else{
				array_push($returnData, false, "Dosya veritabanina kaydedilemedi. Lutfen ilgili kisilere haber veriniz");
			}

/*	toplam file sayisini 1 artiriyoruz	*/
			//simdilik gerek yok//$row = $wpdb->get_row("SELECT total FROM ".$wpdb->prefix . "totalFilesUploaded WHERE id = 1");
			//$total = $row->total;
			//$wpdb->update( $wpdb->prefix . "totalFilesUploaded", array('total' => ($total+1)), array( 'id' => 1 ), array( '%d' ), array( '%d' ) );

			//user_uploads/ klasorunun altina yuklenen dosyayi google drive'a ekledikten sonra siliyoruz

			return $returnData;
		}

  	}

  /*** YENI EKLENEN FONKSIYON  END ***/


	public function saveFile(){

		global $current_user, $wpdb;

		get_currentuserinfo();



		$user_name = '';

		$user_id = '';

		if(is_user_logged_in())

		{

			$user_name = $current_user -> user_nicename;

			$user_id = $current_user -> ID;

		}



		$upload_dir = wp_upload_dir();

		$filePath = $upload_dir['basedir'].'/user_uploads/'.$user_name.'/'.nmFileUploader::$file_name;





		$dt = array(	'fileName'			=> nmFileUploader::$file_name,

						'fileDescription'	=> 'Description'/*nmFileUploader::$desc*/,

						'userID'			=> $user_id,

						'fileType'			=> nmFileUploader::$file_type,

						'fileSize'			=> filesize($filePath),

						'fileUploadedOn'	=> current_time('mysql')

					 );





		//var_dump($dt);



		$wpdb -> insert($wpdb->prefix . nmFileUploader::$tblName,

						$dt,

				array('%s', '%s', '%d', '%s', '%d', '%s')

						);



		/* $wpdb->show_errors();

		$wpdb->print_error(); */



		if($wpdb->insert_id)

			return true;

		else

			return false;

	}



	/*

	deleting file

	*/

	public function deleteFile($fileid, $googleFileID = "")

	{

		global $wpdb;

		global $current_user;

		get_currentuserinfo();



	//Veritabanindan da siliyoruz
		$res = $wpdb->query("DELETE FROM ".$wpdb->prefix . nmFileUploader::$tblName." WHERE fileID = $fileid" );

		return $res;

	}





	/*

	** Get Files Detail

	*/



	function getUserFiles()

	{

		//echo "hello";

		global $wpdb;



		if(@$_REQUEST['user_id'] != '')

		{

			$userID = $_REQUEST['user_id'];

		}

		else

		{

			global $user_ID;

			$userID = $user_ID;

		}



		$myrows = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix . nmFileUploader::$tblName."

						  			   where userID = $userID

									   ORDER BY fileUploadedOn DESC");

	   return $myrows;

	}





	/*

	** Get All Files Detail

	*/



	function getAllUserFiles()

	{

		global $wpdb;



		$myrows = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix . nmFileUploader::$tblName."

						  			   ORDER BY fileUploadedOn DESC");

	   return $myrows;

	}





	function nm_user_upload_admin(){



		$user_id = $_REQUEST['user_id'];



		nmFileUploader::renderListings();



	}







	/*

	** listing all files uploaded by users

	*/



	function listUserFiles()

	{

		$file = dirname(__FILE__).'/listings-all.php';

		include($file);

	}





	/*

	 * upload file

	*/



	function uploadFile($username){





		$upload_dir = wp_upload_dir();

		$path_folder = $upload_dir['basedir'].'/user_uploads/'.$username.'/';


//echo '<script type="text/javascript"> alert("upload  fonksiyonu"); </script>';
		if (!empty($_FILES)) {

			$tempFile = $_FILES['Filedata']['tmp_name'];
			echo '<script type="text/javascript"> alert("'.$tempFile.'"); </script>';

			$targetPath = $path_folder;

			$targetFile = rtrim($targetPath,'/') . '/' . $_FILES['Filedata']['name'];//remove_accents


			if(move_uploaded_file($tempFile,$targetFile)){
				echo '<script type="text/javascript"> alert("'.$targetFile.'"); </script>';
				echo '1';
			}
			else{

				echo 'Error in file uploading';
			}

		}

	}



	/*

	 ** making file name with URL

	*/

	function makeFileDownloadable($files, $filesize, $user_dir='', $date)

	{
		//$uploads = wp_upload_dir();
		//$urlDownload = $uploads['baseurl'] . '/user_uploads/'. $f;

		global $wpdb;

		$myrows = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix . nmFileUploader::$tblName." WHERE fileName = '$files'");

		//$myrows = $wpdb->get_row( 'SELECT urlDownload FROM '.$wpdb->prefix . nmFileUploader::$tblName.' WHERE fileName = '.$files );

		$html = '';

		$html .= '<a href="'.$myrows[0]->urlDownload.'" title="'.$files.'" class="nm-link-title" target="_blank">'.$files.' ('.nmFileUploader::sizeInKB($filesize).')</a>';
		$html .= ' - <span class="nm-file-meta-more">'.nmFileUploader::time_difference($date).'</span>';

		return $html;

	}





	/*

	 * getting size in KBs

	 */

	function sizeInKB($size)

	{

		return round($size / 1024, 2) .' KiB';

	}





	/*

	 * time elapsed

	 */



	function time_difference($date)

	{

		if(empty($date)) {

			return "No date provided";

		}



		$periods         = array("second", "minute", "hour", "day", "week", "month", "year", "decade");

		$lengths         = array("60","60","24","7","4.35","12","10");



		$now             = time();

		$unix_date         = strtotime($date);



		// check validity of date

		if(empty($unix_date)) {

			return "Bad date";

		}



		// is it future date or past date

		if($now > $unix_date) {

			$difference     = $now - $unix_date;

			$tense         = "ago";



		} else {

			$difference     = $unix_date - $now;

			$tense         = "from now";

		}



		for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {

			$difference /= $lengths[$j];

		}



		$difference = round($difference);



		if($difference != 1) {

			$periods[$j].= "s";

		}



		return "$difference $periods[$j] {$tense}";

	}



	/*

	 ** to fix url re-occuring, written by Naseer sb

	*/



	function fixRequestURI($vars){

		$uri = str_replace( '%7E', '~', $_SERVER['REQUEST_URI']);

		$parts = explode("?", $uri);



		$qsArr = array();

		if(isset($parts[1])){	////// query string present explode it

			$qsStr = explode("&", $parts[1]);

			foreach($qsStr as $qv){

				$p = explode("=",$qv);

				$qsArr[$p[0]] = $p[1];

			}

		}



		//////// updatig query string

		foreach($vars as $key=>$val){

			if($val==NULL) unset($qsArr[$key]); else $qsArr[$key]=$val;

		}



		////// rejoin query string

		$qsStr="";

		foreach($qsArr as $key=>$val){

			$qsStr.=$key."=".$val."&";

		}

		if($qsStr!="") $qsStr=substr($qsStr,0,strlen($qsStr)-1);

		$uri = $parts[0];

		if($qsStr!="") $uri.="?".$qsStr;

		return $uri;

	}









}



register_activation_hook(__FILE__, array('nmFileUploader','fileuploader_install'));

register_deactivation_hook(__FILE__, array('nmFileUploader','fileuploaderUninstall'));









function load_fileuploader_script() {

	/*wp_deregister_script( 'jquery' );

    wp_register_script( 'jquery', plugins_url('js/jquery-1.4.4.min.js', __FILE__));

	wp_enqueue_script( 'jquery' );*/



	wp_enqueue_script("jquery");



	wp_register_script('swfobject_script', plugins_url('js/uploadify/swfobject.js', __FILE__));

	wp_enqueue_script('swfobject_script');



	wp_register_script('uploadify_script', plugins_url('js/uploadify/jquery.uploadify.v2.1.4.min.js', __FILE__));

	wp_enqueue_script('uploadify_script');


	wp_register_script('jquery-ui-1_11', 'https://code.jquery.com/ui/1.11.0/jquery-ui.js');

	wp_enqueue_script('jquery-ui-1_11');


	/*wp_register_script('jquery-1_10_2', 'https://code.jquery.com/jquery-1.10.2.js');

	wp_enqueue_script('jquery-1_10_2');*/





     wp_register_script( 'fileuploader_custom_script', plugins_url('js/fileuploader_custom.js', __FILE__),

	 					array('uploadify_script'));

	 wp_enqueue_script('fileuploader_custom_script');



	 $nonce= wp_create_nonce  ('fileuploader-nonce');



	wp_enqueue_script( 'fileuploader_ajax', plugin_dir_url( __FILE__ ) . 'js/ajax.js', array( 'jquery' ) );

	wp_localize_script( 'fileuploader_ajax', 'fileuploader_vars', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ),

			'fileuploader_token'	=> $nonce,

			'fileuploader_plugin_url' => plugin_dir_url( __FILE__ ),

			'current_user'	=> ''

	) );

}



add_action('wp_enqueue_scripts', 'load_fileuploader_script');



add_shortcode( 'nm-wp-file-uploader', array('nmFileUploader', 'renderUserArea'));

add_action('wp_print_styles', 'nm_fileuploader_style');



//edit user action

add_action( 'edit_user_profile', array('nmFileUploader', 'nm_user_upload_admin'));

add_action( 'show_user_profile', array('nmFileUploader', 'nm_user_upload_admin'));



/*

 * ajax action callback to upload file

* defined in js/ajax.js

*/

add_action( 'wp_ajax_fileuploader_file', 'fileuploader_post_file' );

add_action( 'wp_ajax_nopriv_fileuploader_file', 'fileuploader_post_file' );

function fileuploader_post_file(){
	nmFileUploader::uploadFile($_REQUEST['username']);

	die(0);

}




/*

* Enqueue style-file, if it exists.

*/



function nm_fileuploader_style() {

	//uploadify css

	wp_register_style('fileuploader_stylesheet', plugins_url('js/uploadify/uploadify.css', __FILE__));

    wp_enqueue_style( 'fileuploader_stylesheet');


	wp_register_style('plugin_fileuploader_stylesheet', plugins_url('nm_fileuploader_style.css', __FILE__));

    wp_enqueue_style( 'plugin_fileuploader_stylesheet');


    wp_register_style('jquery-ui-1_11_stylesheet', 'https://code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css');

    wp_enqueue_style( 'jquery-ui-1_11_stylesheet');


    //loading tempalte style

	wp_register_style('_uploader_stylesheet', plugins_url('css/styles.css', __FILE__));

	wp_enqueue_style( '_uploader_stylesheet');

}



$options_file = dirname(__FILE__).'/file-upload-options.php';

include ($options_file);



?>