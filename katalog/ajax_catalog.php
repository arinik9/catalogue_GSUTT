<?php

/*

 ** This is for ajax   (nejat_arinik@yahoo.com)

*/


function show_all_files(){
  global $wpdb;
  global $user_ID;
  $post_name= (isset($_REQUEST["name"])) ? $_REQUEST["name"] : "";
  $step = 5;

if($post_name == "left"){
	$sql = (isset($_REQUEST["query"])) ? $_REQUEST["query"] : "";
	$sql = stripslashes($sql);

 	$strNumber = (isset($_REQUEST["lastFileNumberOnList"])) ? $_REQUEST["lastFileNumberOnList"] : "";
  	if($strNumber != ""){
	  	$start = ((int) substr($strNumber, 2) - $step);//sayfada gorunen listedeki pdf'in hemen bir oncekinin numarasini kaydediyoruz

		$myrows = $wpdb->get_results($sql);
		$echo = "";
		$echo .= '<input type="hidden" id="dataTable" value="'.count($myrows).'" name="'.$sql.'"><p id="hiddenSql" hidden>'.$sql.'</p><table id="showFiles"><caption><h2>Katalog</h2></caption>
		<tr><th id="table_th">Isim</th><th id="table_th">Yazar</th><th id="table_th">Yayım Tarihi</th><th id="table_th">Tür</th><th id="table_th">Çevirmen</th><th id="table_th">Karakter sayısı</th><th id="table_th">Indir</th><th id="table_th">Sil</th></tr>';

		for($y=$start; $y<$start+$step; $y++){//&& $y>0 bu kosulu kaldirdim ama emin degilim
			$car = ($myrows[$y-1]->characters == 0) ? "---" : $myrows[$y-1]->characters;
			$original = ($myrows[$y-1]->originalName == "---") ? "" : '('.$myrows[$y-1]->originalName.')';
			$echo .= '<tr id="tr'.$y.'" class="tr_catalog"><td class="td_fileName"><img src="'.plugins_url("images/" , __FILE__ ).$myrows[$y-1]->category.'.png" style="padding: 0px; border: 0px;" alt="kategori">  <a href="https://drive.google.com/file/d/'.$myrows[$y-1]->googleFileID.'/edit?usp=sharing" target="_blank">' . $myrows[$y-1]->fileName . '</a><br>'.$original.' ('.$myrows[$y-1]->totalPages.' sayfa)</td><td class="td_author">' . $myrows[$y-1]->author . '</td><td class="td_datePublished">' . $myrows[$y-1]->datePublished . '</td><td class="td_genre">' . $myrows[$y-1]->genre . '</td><td class="td_translator">' . $myrows[$y-1]->translator . '</td><td class="td_characters">' . $car . '</td><td class="td_urlDownload"><a href="' . $myrows[$y-1]->urlDownload . '"><img src="'.plugins_url("images/" , __FILE__ ).'down_32.png" style="padding: 0px; border: 0px;vertical-align: middle;" alt="indir"></a></td>';
			if($user_ID == $myrows[$y-1]->userID){
	            $echo .= '<td class="td_delete"><img src="'.plugins_url("images/" , __FILE__ ).'delete_32.png" class="delete" style="padding: 0px; border: 0px;vertical-align: middle;" alt="'.$myrows[$y-1]->fileID.'--'.$myrows[$y-1]->googleFileID.'"></td></tr>';
	        }
	        else{
	             $echo .= '<td class="td_delete">     ---</td></tr>';
	        }
		}
		$echo .= '</table><p>'.count($myrows).' sonuç bulundu</p><p>'.$start.'. ile '.($y-1).'. arası dökümanları gormektesiniz</p>';
		echo $echo;
	}
	else{
		echo "bir sorunla karsilasildi!";
	}
}
else if($post_name == "right"){
	$sql = (isset($_REQUEST["query"])) ? $_REQUEST["query"] : "";
	$sql = stripslashes($sql);

 	$strNumber = (isset($_REQUEST["lastFileNumberOnList"])) ? $_REQUEST["lastFileNumberOnList"] : "";

  	if($strNumber != ""){
	    $start = ((int) substr($strNumber, 2) + 1);//sayfada gorunen listedeki pdf'in hemen bir sonrakinin numarasini kaydediyoruz
		$myrows = $wpdb->get_results($sql);

		$echo = "";
		$echo .= '<input type="hidden" id="dataTable" value="'.count($myrows).'" name="'.$sql.'"><p id="hiddenSql" hidden>'.$sql.'</p><table id="showFiles"><caption><h2>Katalog</h2></caption>
		<tr><th id="table_th">Isim</th id="table_th"><th id="table_th">Yazar</th><th id="table_th">Yayım Tarihi</th id="table_th"><th id="table_th">Tür</th><th id="table_th">Çevirmen</th><th id="table_th">Karakter sayısı</th><th id="table_th">Indir</td><th id="table_th">Sil</th></tr>';

		for($y=$start; $y<=$start+$step-1 && $y<=count($myrows); $y++){
			$car = ($myrows[$y-1]->characters == 0) ? "---" : $myrows[$y-1]->characters;
			$original = ($myrows[$y-1]->originalName == "---") ? "" : '('.$myrows[$y-1]->originalName.')';
			$echo .= '<tr id="tr'.$y.'" class="tr_catalog"><td class="td_fileName"><img src="'.plugins_url("images/" , __FILE__ ).$myrows[$y-1]->category.'.png" style="padding: 0px; border: 0px;" alt="kategori">  <a href="https://drive.google.com/file/d/'.$myrows[$y-1]->googleFileID.'/edit?usp=sharing" target="_blank">' . $myrows[$y-1]->fileName . '</a><br>'.$original.' ('.$myrows[$y-1]->totalPages.' sayfa)</td><td class="td_author">' . $myrows[$y-1]->author . '</td><td class="td_datePublished">' . $myrows[$y-1]->datePublished . '</td><td class="td_genre">' . $myrows[$y-1]->genre . '</td><td class="td_translator">' . $myrows[$y-1]->translator . '</td><td class="td_characters">' . $car . '</td><td class="td_urlDownload"><a href="' . $myrows[$y-1]->urlDownload . '"><img src="'.plugins_url("images/" , __FILE__ ).'down_32.png" style="padding: 0px; border: 0px;vertical-align: middle;" alt="indir"></a></td>';
			if($user_ID == $myrows[$y-1]->userID){
	            $echo .= '<td class="td_delete"><img src="'.plugins_url("images/" , __FILE__ ).'delete_32.png" class="delete" style="padding: 0px; border: 0px;vertical-align: middle;" alt="'.$myrows[$y-1]->fileID.'--'.$myrows[$y-1]->googleFileID.'"></td></tr>';
	        }
	        else{
	             $echo .= '<td class="td_delete">     ---</td></tr>';
	        }
		}

		if(($y-1) == $start){
			$echo .= '</table><p>'.count($myrows).' sonuç bulundu</p><p>'.$start.'. sonucu gormektesiniz</p>';
		}
		else{
			$echo .= '</table><p>'.count($myrows).' sonuç bulundu</p><p>'.$start.'. ile '.($y-1).'. arası dökümanları gormektesiniz</p>';
		}
		echo $echo;
	}
	else{
		echo "bir sorunla karsilasildi!";
	}
}
else if($post_name == "author" || $post_name == "category" || $post_name == "genre" || $post_name == "totalPages" || $post_name == "characters"
 || $post_name == "language" || $post_name == "orderAuthor" || $post_name == "orderDate" || $post_name == "clearAuthorBox"){
	$options['genre'] = (isset($_REQUEST["value1"])) ? $_REQUEST["value1"] : "";
    $options['category'] = (isset($_REQUEST["value2"])) ? $_REQUEST["value2"] : "";
    $options['characters'] = (isset($_REQUEST["value3"])) ? $_REQUEST["value3"] : "";
    $options['totalPages'] = (isset($_REQUEST["value4"])) ? $_REQUEST["value4"] : "";
    $options['language'] = (isset($_REQUEST["value5"])) ? $_REQUEST["value5"] : "";
    $options['author'] = (isset($_REQUEST["value6"])) ? $_REQUEST["value6"] : "";
    $orderBy = (isset($_REQUEST["value7"])) ? $_REQUEST["value7"] : "";



		 $sql = 'SELECT * FROM '.$wpdb->prefix . nmFileUploader::$tblName;

		 $first = 0;
		foreach ($options as $key => $value) {

			if($value !== "Kriter yok"){

				if($key == "totalPages"){
					$options['totalPages'] = (int)$value;
					$queryPages = '';
					if($value == 35){
						$queryPages .= $key.' < '. $value;
					}
					else if($value == 70){
						$queryPages .= '35 < '.$key. ' AND '.$key.' < '. $value;
					}
					else if($value == 120){
						$queryPages .= '70 < '.$key. ' AND '.$key.' < '. $value;
					}
					else{
						$queryPages .= $key.' > 120';
					}
				}//fin if

				if($key == "characters"){
					$options['characters'] = (int)$value;
					$queryCharacters = '';
					if($value == 1 || $value == 2 || $value == 3 || $value == 4){
						$queryCharacters .= $key. ' = '.$value .' ';
					}
					else if($value == 10){
						$queryCharacters .= '4 < '.$key. ' AND '.$key.' < '. $value;
					}
					else if($value == 15){
						$queryCharacters .= '9 < '.$key. ' AND '.$key.' < '. $value;
					}
					else if($value == 20){
						$queryCharacters .= '14 < '.$key. ' AND '.$key.' < '. $value;
					}
					else{
						$queryCharacters .= $key.' > 20';
					}
				}//fin if

				if($first == 0){
					$first++;
					if($key == "totalPages"){
						$sql .= ' WHERE '.$queryPages;
					}
					else if($key == "characters"){
						$sql .= ' WHERE '.$queryCharacters;
					}
					else if($key == "author"){
						$sql .= ' WHERE '.$key.' LIKE \''.$value.'%\'';
					}
					else{
						$sql .= ' WHERE '.$key.' = \''.$value.'\'';
					}
				}
				else if($first != 0){
					if($key == "totalPages"){
						$sql .= ' AND '.$queryPages;
					}
					else if($key == "characters"){
						$sql .= ' AND '.$queryCharacters;
					}
					else if($key == "author"){
						$sql .= ' AND '.$key.' LIKE \''.$value.'%\'';
					}
					else{
						$sql .= ' AND '.$key.' = \''.$value.'\'';
					}
				}
		 	}

		}
//$echo .= '<script type="text/javascript"> console.log('.$sql.'); </script>';
		//kosolda gormek icin echo yaparsan json data'larinin duzenini bozmus oluyoruz

	$sql .= ' '.$orderBy;//checkbox'lardan elde ettgimiz bilgilere gore

	$myrows = $wpdb->get_results($sql);
	if($post_name != "author"){//author inputu ile diger inputlarin calisma sistemleri farkli oldugu icin, if-else ile ayirdik

		if(count($myrows) != 0){
			$echo .= '<input type="hidden" id="dataTable" value="'.count($myrows).'" name="'.$sql.'"><table id="showFiles"><caption><h2>Katalog</h2></caption>
			<tr><th id="table_th">Isim</th><th id="table_th">Yazar</th><th id="table_th">Yayım Tarihi</th><th id="table_th">Tür</th><th id="table_th">Çevirmen</th><th id="table_th">Karakter sayısı</th><th id="table_th">Indir</th><th id="table_th">Sil</th></tr>';
			foreach ($myrows as $key => $file) {
			    if($key<$step){
			    	$car = ($file->characters == 0) ? "---" : $file->characters;
			    	$original = ($file->originalName == "---") ? "" : '('.$file->originalName.')';
			        $echo .= '<tr id="tr'.($key+1).'" class="tr_catalog"><td class="td_fileName"><img src="'.plugins_url("images/" , __FILE__ ).$file->category.'.png" style="padding: 0px; border: 0px;" alt="kategori">  <a href="https://drive.google.com/file/d/'.$file->googleFileID.'/edit?usp=sharing" target="_blank">' . $file->fileName . '</a><br>'.$original.' ('.$file->totalPages.' sayfa)</td><td class="td_author">' . $file->author . '</td><td class="td_datePublished">' . $file->datePublished . '</td><td class="td_genre">' . $file->genre . '</td><td class="td_translator">' . $file->translator . '</td><td class="td_characters">' . $car . '</td><td class="td_urlDownload"><a href="' . $file->urlDownload . '"><img src="'.plugins_url("images/" , __FILE__ ).'down_32.png" style="padding: 0px; border: 0px;vertical-align: middle;" alt="indir"></a></td>';
			    	if($user_ID == $file->userID){
			            $echo .= '<td class="td_delete"><img src="'.plugins_url("images/" , __FILE__ ).'delete_32.png" class="delete" style="padding: 0px; border: 0px;vertical-align: middle;" alt="'.$file->fileID.'--'.$file->googleFileID.'"></td></tr>';
			        }//
			        else{
			             $echo .= '<td class="td_delete">     ---</td></tr>';
			        }
			    }
			}
		}

		$echo .= "</table><p>".count($myrows)." sonuç bulundu</p>";
		if(count($myrows) == 1){
			$echo .= "<p>1. dökümanı görmektesiniz</p>";
		}
		else if(count($myrows) <= $step && count($myrows) != 0){
			$echo .= "<p>1. ile ".count($myrows).". arası dökümanları görmektesiniz</p>";
		}
		else if(count($myrows) > $step){
			$echo .= "<p>1. ile ".$step.". arası dökümanları görmektesiniz</p>";
		}
		echo $echo;
	}
	else if($post_name == "author"){
		$myrows[count($myrows)] = $sql;
		echo json_encode($myrows);
	}
	//}
	//else{
	//	echo Options'lari alirken bir sorunla karsilasildi!";
	//}
}
else if($post_name == "fileName"){

	$request = (isset($_REQUEST["fileName"])) ? $_REQUEST["fileName"] : "";
	$sql = 'SELECT * FROM '.$wpdb->prefix . nmFileUploader::$tblName;
	$sql .= ' WHERE fileName LIKE "'. $request.'%"';
	//echo '<script type="text/javascript"> console.log('.$sql.'); </script>';
	$myrows = $wpdb->get_results($sql);
	echo json_encode($myrows);
}
else if($post_name == "delete"){
	$sql = (isset($_REQUEST["query"])) ? $_REQUEST["query"] : "";
	$googleFileID = (isset($_REQUEST["googleFileID"])) ? $_REQUEST["googleFileID"] : "";
	$fileID = (isset($_REQUEST["fileID"])) ? $_REQUEST["fileID"] : "";
	$sql = stripslashes($sql);

	$deleted = false; //flag
	$res = 0;
//GoogleDrive'dan siliyoruz
		$client = new Google_Client();

		// Get your credentials from the console
		$client->setClientId('*************************.apps.googleusercontent.com');
		$client->setClientSecret('***********************');
		$client->setRedirectUri('urn:ietf:wg:oauth:2.0:oob');
		$client->setScopes(array('https://www.googleapis.com/auth/drive'));

		$client->setAccessType('offline');

		$service = new Google_DriveService($client);

		// Exchange authorization code for access token/refresh token
		$accessToken = '{"access_token":"***********************************","token_type":"Bearer",
		"expires_in":3600,"refresh_token":"*******************************","created":1405974101}';

		$google_token= json_decode($accessToken);
		$client->refreshToken($google_token->refresh_token);

		try {
		    $service->files->delete($googleFileID);
		    $deleted = true;
		} catch (Exception $e) {
		    print "<span class='error'>While deleting file in Google Drive, an error occurred: " . $e->getMessage()."</span>";
		    $deleted = false;
		}


	//Veritabanindan da siliyoruz
		if($deleted){
			$res = $wpdb->query("DELETE FROM ".$wpdb->prefix . nmFileUploader::$tblName." WHERE fileID = $fileID" );
		}

	//katalogu guncelliyoruz sayfada, file silindikten sonra
		if($res){
			$myrows = $wpdb->get_results($sql);

			if(count($myrows) != 0){
				$echo .= '<input type="hidden" id="dataTable" value="'.count($myrows).'" name="'.$sql.'"><table id="showFiles"><caption><h2>Katalog</h2></caption>
				<tr><th id="table_th">Isim</th><th id="table_th">Yazar</th><th id="table_th">Yayım Tarihi</th><th id="table_th">Tür</th><th id="table_th">Çevirmen</th><th id="table_th">Karakter sayısı</th><th id="table_th">Indir</th><th id="table_th">Sil</th></tr>';

				foreach ($myrows as $key => $file) {
				    if($key<$step){
				    	$car = ($file->characters == 0) ? "---" : $file->characters;
				    	$original = ($file->originalName == "---") ? "" : '('.$file->originalName.')';
				        $echo .= '<tr id="tr'.($key+1).'" class="tr_catalog"><td class="td_fileName"><img src="'.plugins_url("images/" , __FILE__ ).$file->category.'.png" style="padding: 0px; border: 0px;" alt="kategori">  <a href="https://drive.google.com/file/d/'.$file->googleFileID.'/edit?usp=sharing" target="_blank">' . $file->fileName . '</a><br>'.$original.' ('.$file->totalPages.' sayfa)</td><td class="td_author">' . $file->author . '</td><td class="td_datePublished">' . $file->datePublished . '</td><td class="td_genre">' . $file->genre . '</td><td class="td_translator">' . $file->translator . '</td><td class="td_characters">' . $car . '</td><td class="td_urlDownload"><a href="' . $file->urlDownload . '"><img src="'.plugins_url("images/" , __FILE__ ).'down_32.png" style="padding: 0px; border: 0px;vertical-align: middle;" alt="indir"></a></td>';
				    	if($user_ID == $file->userID){
				            $echo .= '<td class="td_delete"><img src="'.plugins_url("images/" , __FILE__ ).'delete_32.png" class="delete" style="padding: 0px; border: 0px;vertical-align: middle;" alt="'.$file->fileID.'--'.$file->googleFileID.'"></td></tr>';
				        }//
				        else{
				             $echo .= '<td class="td_delete">     ---</td></tr>';
				        }
				    }
				}
			}

			$echo .= "</table><p>".count($myrows)." sonuç bulundu</p>";
			if(count($myrows) == 1){
				$echo .= "<p>1. dökümanı görmektesiniz</p>";
			}
			else if(count($myrows) <= $step && count($myrows) != 0){
				$echo .= "<p>1. ile ".count($myrows).". arası dökümanları görmektesiniz</p>";
			}
			else if(count($myrows) > $step){
				$echo .= "<p>1. ile ".$step.". arası dökümanları görmektesiniz</p>";
			}
			echo $echo;

		}
		else{
			echo '<span class="error">Dosya veritabanindan silinirken hata meydana geldi!</span>';
		}
}

// 'userID'	 'fileID' 'datePublished' 'author' 'fileName'  'fileSize'  'fileType'	'totalPages'   'googleFileID'	'characters'  'category'	'genre'	'urlDownload'
  die(0);
}





?>