<h2>File Uploaded by Users</h2>

<?php

global $wpdb;





/*

delete file

*/

/*if(isset($_GET['fid']))

{

	//echo 'file deleted '.nmFileUploader::deleteFile($_GET['fid']);

	if(nmFileUploader::deleteFile($_GET['fid']))

	{

		echo '<div class="updated">';

		echo "<p>". get_option('nm_file_deleted_msg') ."</p>";

		echo '</div>';

	}



}*/
//echo '<script type="text/javascript"> alert("listings-all"); </script>';
if(isset($_GET['fid'])){
    if(isset($_GET['googleFileID'])){
        nmFileUploader::deleteFile($_GET['fid'], $_GET['googleFileID']);
    }
    else{
        nmFileUploader::deleteFile($_GET['fid']);
    }

    echo '<div class="updated">';
    echo "<p>". get_option('nm_file_deleted_msg') ."</p>";
    echo '</div>';
}



/*$wpdb->show_errors();

$wpdb->print_error(); */





$arrFiles = nmFileUploader::getAllUserFiles();

?>

<div style="margin:5px;padding:5px;border:1px solid #CCC; background-color:#f5f5f5">

<h3>Total files: <?php echo count($arrFiles)?></h3>

<div class="user-uploaded-files">

<table width="100%" border="0" id="user-files" class="wp-list-table widefat fixed posts">

<thead>

	<tr>

        <th width="233" align="left" valign="middle"><strong>File Name</strong></th>

        <th width="516" align="left" valign="middle"><strong>Notes</strong></th>

        <th width="71" align="center" valign="middle"><strong>Date</strong></th>

        <th width="97" align="center" valign="middle"><strong>Action</strong></th>

      </tr>

</thead>





<tbody>

<?php foreach($arrFiles as $file):

//print_r(parse_url($_SERVER['HTTP_REFERER']));

$urlQuertyString = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_QUERY);



if($urlQuertyString == '')

	$urlDelete = str_replace( '%7E', '~', $_SERVER['REQUEST_URI']).'?fid='.$file -> fileID;

else

	$urlDelete = str_replace( '%7E', '~', $_SERVER['REQUEST_URI']).'&fid='.$file -> fileID;





$user_info = get_userdata($file -> userID);



$uploads = wp_upload_dir();

if(file_exists($uploads['basedir'] . '/user_uploads/'. $file -> fileName))

	$urlDwnld = $uploads['baseurl'] . '/user_uploads/'. $file -> fileName;

else

  	$urlDwnld = $uploads['baseurl'] . '/user_uploads/'.$user_info -> user_nicename.'/' . $file -> fileName;



$urlUserProfile = admin_url( 'profile.php?user_id='.$file->userID.'#nm-uploaded-files' );

?>

  <tr>

    <td><?php echo $file -> fileName?><br />

		uploaded by: <?php echo $user_info -> user_nicename?> | view all files by <a href="<?php echo $urlUserProfile?>"><?php echo $user_info -> user_nicename?></a></td>

    <td><?php echo $file -> fileDescription?></td>

    <td width="71" align="center"><?php echo date('d-M,y', strtotime($file -> fileUploadedOn))?></td>

    <td width="97" align="center">

    <a href="<?php echo $urlDwnld?>">

	<?php echo "<img border=\"0\" src=".plugins_url( 'images/down_16.png' , __FILE__)." />";	?>

    </a>


    <a href="javascript:confirmFirst('<?php echo $urlDelete.'&googleID='.$file -> googleFileID  ?>')">

	<?php echo "<img border=\"0\" src=".plugins_url( 'images/delete_16.png' , __FILE__)." />";	?>

    </a></td>

  </tr>

<?php endforeach;?>



</tbody>

</table>

</div>

<div style="clear:both"></div>

</div>





<script type="text/javascript">

	function confirmFirst(url)

	{

		var a = confirm('Are you sure to delete this file?');

		if(a)

		{

			window.location = url;

		}

	}



</script>