<?php

function showAllFiles(){
  ob_clean();
  global wpdb;
  $post_name=$_REQUEST["name"];

  echo $post_name;
  die();
}
add_action('wp_ajax_showAllFiles', 'showAllFiles_callback');
add_action('wp_ajax_nopriv_showAllFiles', 'showAllFiles_callback');

/*
if($post_name == "Hepsini goster"){
  $myrows = $wpdb->get_results( "SELECT fileName, author FROM ".$wpdb->prefix . nmFileUploader::$tblName." ORDER BY fileUploadedOn DESC");
  $echo = "";
  $echo .= '<table>';
  foreach ($myrows as $key => $file) {
    $echo .= '<tr><td>' . $file->fileName . '</td><td>' . $file->author . '</td></tr>';
  }
  $echo .= '</table>';

  echo $echo;
}*/

?>
