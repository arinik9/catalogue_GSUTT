<?php

require_once str_replace("/", "\\", $_SERVER['DOCUMENT_ROOT']).'wp\wp-content\plugins\nmedia-user-file-uploader\google-api-php-client\src\Google_Client.php';
require_once str_replace("/", "\\", $_SERVER['DOCUMENT_ROOT']).'wp\wp-content\plugins\nmedia-user-file-uploader\google-api-php-client\src\contrib\Google_DriveService.php';
include_once(str_replace("/", "\\", $_SERVER['DOCUMENT_ROOT']).'wp\wp-load.php');
//include_once(str_replace("/", "\\", $_SERVER['DOCUMENT_ROOT']).'wp\wp-settings.php');

//include_once(str_replace("/", "\\", $_SERVER['DOCUMENT_ROOT']).'wp\wp-includes\functions.php');
//include_once(str_replace("/", "\\", $_SERVER['DOCUMENT_ROOT']).'wp\wp-includes\option.php');

echo '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head><body>';

$client = new Google_Client();

// Get your credentials from the console
$clientID = get_option( 'nm_file_clientID');
$secretKey = get_option( 'nm_file_secretKey');
$client->setClientId($clientID);
$client->setClientSecret($secretKey);
$client->setRedirectUri('urn:ietf:wg:oauth:2.0:oob');
$client->setScopes(array('https://www.googleapis.com/auth/drive'));

$service = new Google_DriveService($client);

if(!isset($_POST['kod'])){
    $authUrl = $client->createAuthUrl();
    echo '<br>'.$authUrl;
    //Request authorization
    echo '<p><a href="'.$authUrl.'" target="_blank"> Please click here for get refresh token </a></p>';
    echo '<form action="" method="POST">
      Kod: <input type="text" name="kod"><br>
      <input type="submit" value="Submit">
    </form>';
}
else{
    $authCode = stripslashes_deep($_POST['kod']);

    // Exchange authorization code for access token
    $accessToken = $client->authenticate($authCode);
    $google_token= json_decode($accessToken);

    $refresh_token = $google_token->refresh_token;

    update_option('refresh_token', $accessToken);

    echo '<p><span style="color:green;font-size:40px;">Succesfully</span></p>';
    //$client->setAccessToken($accessToken);

    //Insert a file
    /*$file = new Google_DriveFile();

    $file->setTitle('My document');
    $file->setDescription('A test document');
    $file->setMimeType('text/plain');

    $data = file_get_contents('document.txt');

    $createdFile = $service->files->insert($file, array(
          'data' => $data,
          'mimeType' => 'text/plain',
        ));

    print_r($createdFile);*/
}
echo '</body></html>';
?>