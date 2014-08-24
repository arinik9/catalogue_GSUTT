<?php
echo '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head><body style="background-color:#e9d9d9">';

require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php' );//wpdb global degiskenini kullanmak icin
//source for using wpdb outside wordpress: http://wordpress.org/support/topic/wpdb-outside-wordpress-again

echo '<h2>Bu script, gsutt.com\'daki katalog ile gsutt\'nin Google Drive hesabindaki <em>Oyunlar</em> ve <em>Teorik</em> klasorunun altindaki dokumanlari karsilastirmak icin yapilmistir</h2>';

/*** - WP - BEGIN ***/
global $wpdb;
$tblName = $wpdb->prefix.'userfiles';
$totalTeorikInWP=0;
$totalOyunlarInWP=0;

$sql = 'SELECT * FROM '.$tblName.' WHERE category="oyunlar"';
$filesOyunlarInWP = $wpdb->get_results($sql);
$totalOyunlarInWP=count($filesOyunlarInWP);

$sql = 'SELECT * FROM '.$tblName.' WHERE category="teorik"';
$filesTeorikInWP = $wpdb->get_results($sql);
$totalTeorikInWP=count($filesTeorikInWP);

/*** - WP - END ***/


/*** - Google Drive - BEGIN ***/
require_once $_SERVER['DOCUMENT_ROOT'].'/wp-content/plugins/nmedia-user-file-uploader/google-api-php-client/src/Google_Client.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/wp-content/plugins/nmedia-user-file-uploader/google-api-php-client/src/contrib/Google_DriveService.php';

$totalTeorikInDrive=0;
$totalOyunlarInDrive=0;

$client = new Google_Client();

// Get your credentials from the console
$client->setClientId('**********************.apps.googleusercontent.com');
$client->setClientSecret('********************');
$client->setScopes(array('https://www.googleapis.com/auth/drive'));

$client->setAccessType('offline');

$service = new Google_DriveService($client);


// Exchange authorization code for access token/refresh token
$accessToken = '{"access_token":"*****************IJtXlt*Tmss**********zpg","token_type":"Bearer",
"expires_in":3600,"refresh_token":"***********************","created":1405974101}';

$google_token= json_decode($accessToken);
$client->refreshToken($google_token->refresh_token);

//source: https://developers.google.com/drive/web/search-parameters

//$file = $service->files->listFiles(array(
  //    'q' => 'title contains "3. Richard"',
   // ));

$folderParentsId['teorik']  = "*******";
$folderParentsId['oyunlar'] = "0B8v9utDj7Gt1c2FRaFhqT2MxdUE";

$filesTeorikInDrive = $service->files->listFiles(array(
      'q' => $folderParentsId['teorik'].' in parents',//Teorik klasorunun icine bakiyoruz sadece
    ));

$totalTeorikInDrive=count($filesTeorikInDrive['items']);

$filesOyunlarInDrive = $service->files->listFiles(array(
      'q' => $folderParentsId['oyunlar'].' in parents',//Oyunlar klasorunun icine bakiyoruz sadece
    ));

$totalOyunlarInDrive=count($filesOyunlarInDrive['items']);

/*** - Google Drive - END***/

$flagTeorik = false;
$flagOyunlar = false;

echo '<p><fieldset style="background-color:yellowgreen;"><legend><strong>Eger Wordpress ile Drive senkronize degilse linke tiklayarak login olmak gerekiyor</strong></legend>login: gsuttdocuments<br>sifre: iki defa bitisik gsutt yazilarak<br></fieldset></p>';

if($totalTeorikInWP == $totalTeorikInDrive){
    $flagTeorik = true;
}
else{
    echo '<h3>Teorik</h3><p><span style="color:red;">Wordpress\'teki <strong>Teorik</strong> kategorisindeki metinler ile Google Drive\'deki Teorik klasorundeki metinler senkronize degil</span></p>';
    echo '<ul>';
    foreach ($filesTeorikInDrive['items'] as $key1 => $files1) {
        $createdDate = substr($files1['createdDate'],0,10);//$filesOyunlarInDrive['items'][$key1]
        $arraydDate = explode("-", $createdDate);
        if($arraydDate[0]>="2014" && $arraydDate[1]>="08" && $arraydDate[2]>="23"){
            $found = false;
            foreach ($filesTeorikInWP as $key2 => $files2) {
                if($files1['id'] == $files2->googleFileID){//$files1[$key1]['title']
                    $found = true;
                }
            }
            if(!$found){//false ise yani drive'da var ama gsutt.com'da yok ise
                echo '<li>Google Drive\'da <strong>Teorik</strong> klasorunde olup da gsutt.com\'daki katalogda olmayan metin: <a href="'.$files1['alternateLink'].'" target="_blank">'.$files1['title'].'</a></li>';
            }
        }
    }
    echo '</ul>';
}


if($totalOyunlarInWP == $totalOyunlarInDrive){
    $flagOyunlar = true;
}
else{
    echo '<h3>Oyunlar</h3><p><span style="color:red;">Wordpress\'teki <strong>Oyunlar</strong> kategorisindeki metinler ile Google Drive\'deki Oyunlar klasorundeki metinler senkronize degil</span></p>';
    echo '<ul>';
    foreach ($filesOyunlarInDrive['items'] as $key1 => $files1) {
        $createdDate = substr($files1['createdDate'],0,10);//$filesOyunlarInDrive['items'][$key1]
        $arraydDate = explode("-", $createdDate);
        if($arraydDate[0]>="2014" && $arraydDate[1]>="08" && $arraydDate[2]>="23"){
            $found = false;
            foreach ($filesOyunlarInWP as $key2 => $files2) {
                if($files1['id'] == $files2->googleFileID){//$files1[$key1]['title']
                    $found = true;
                }
            }
            if(!$found){//false ise yani drive'da var ama gsutt.com'da yok ise
                echo '<li>Google Drive\'da <strong>Oyunlar</strong> klasorunde olup da gsutt.com\'daki katalogda olmayan metin: <a href="'.$files1['alternateLink'].'" target="_blank">'.$files1['title'].'</a></li>';
            }
        }
    }
    echo '</ul>';
}


if($flagTeorik && $flagOyunlar){
    echo '<h1><span style="color:green;">Wordpress\'teki katalog ile Google Drive\'deki metinler senkronize. Bir problem yok! </span></h1>';
}

echo '</body></html>';
?>

