<?php

/*

 ** This is main temlate for loading file list,

do not change until you are like me (ceo@najeebmedia.com)

*/



?>

<script type="text/javascript">

	plugin_path = '<?php echo dirname(__FILE__)?>';

</script>

<div id="nmuploader-wrapper">

	<h2>

		<?php _e('Download file(s)', nmFileUploader::$short_name)?>

	</h2>

	<p style="font-style: italic">

		<?php _e('(click on file name to download)', nmFileUploader::$short_name)?>

	</p>



	<?php

/* eskisi 	$arrFiles = nmFileUploader::getUserFiles();

*/

/* umut düzeltti. herkesin yüklediği dosyalar herkese görünsün diye

*/

	$arrFiles = nmFileUploader::getAllUserFiles();

	nmFileUploader::$files_per_page = ( get_option('nm_file_file_limit') == 0) ? 5 : get_option('nm_file_file_limit');



	nmFileUploader::$total_pages = ceil(count($arrFiles) / nmFileUploader::$files_per_page);



	?>









	<ul id="nmuploader-container">





		<form id="frm_nm_files" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI'])?>#download-zip" method="post">

			<input type="hidden" name="plugin_url" value="<?php echo plugins_url('', __FILE__)?>" />

			<?php

			$uploader_row_count = 0;

			foreach($arrFiles as $file):



			$urlDelete = nmFileUploader::fixRequestURI(array('fid'	=> $file -> fileID));



			$user_name = '';

			if($file -> userID != 0) 			//public upload

			{

				$user_info = get_userdata($file -> userID);

				$user_name = $user_info -> user_nicename;

			}



			$bg_color = '';

			$urlDwnld = nmFileUploader::makeFileDownloadable($file -> fileName,

					$file -> fileSize,

					$user_name,

					$file -> fileUploadedOn);



			$ajaxDownload = '<a href="javascript:downloadFile(\''.$file -> fileName.'\')">Download</a>';



			$uploader_row_count++;

			$page_number = ceil($uploader_row_count / nmFileUploader::$files_per_page);



			$extImage = strtolower(str_replace('.', '', $file -> fileType)). '.png';



			$urlExtImage = plugins_url('images/ext/48px/'.$extImage, __FILE__);

			?>





			<li style="display: none" class="nm-c-p-<?php echo $page_number?>">

				<ul class="nmuploader-row" id="nmuploader-<?php echo $file -> fileID?>">



					<li><img src="<?php echo $urlExtImage?>" title="<?php echo $file -> fileName?>" />

					</li>



					<li class="meta"><?php echo $urlDwnld?><br /> <span	class="nm-file-meta-more"> <?php echo stripcslashes($file -> fileDescription)?>

					</span>

					</li>



					<li class="tool"><a	href="javascript:confirmFirst('<?php echo $urlDelete.'&googleFileID='.$file->googleFileID ?>')" title="Delete">

					 <?php	echo '<img id="del-file-'.$file -> fileID.'" border="0" src="'.plugins_url( 'images/delete_16.png' , __FILE__).'" />';	?>

					</a>

					</li>







				</ul>

			</li>



			<div class="fix_height"></div>

			<?php endforeach;?>

		</form>

	</ul>



	<ul id="nmuploader-bottom">



		<li>

			<ul>

				<li id="prev-page"><a href="javascript:loadUploaderPagePrev()">

						&laquo; <?php _e('Previous', nmFileUploader::$short_name)?>

				</a></li>

				<li id="page-count">2 of 11</li>

				<li id="next-page"><a href="javascript:loadUploaderPageNext()"> <?php _e('Next', nmFileUploader::$short_name)?>

						&raquo;

				</a></li>

			</ul>

		</li>

	</ul>

	<script type="text/javascript">

			total_pages = <?php echo nmFileUploader::$total_pages?>;

			setUploaderPagination();

	</script>



	<div class="fix_height"></div>

</div>

