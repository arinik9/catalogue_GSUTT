<?php
$nmf_name = "NajeebMedia File Uploader Plugin";
$nmf_shortname = "nm_file";
$nmf_page_name = 'nm-file-uploader';


// Create Plugin nmf_options

$nmf_options = array (

array( "name" => $nmf_name." Options",
	"type" => "title"),

array( 	"name" => __("General Settings", "nm_file_uploader_pro"),
		"type" => "section"),
		array( "type" => "open"),

		array(  "name" => __("Files per Page Limit", "nm_file_uploader_pro"),
				"desc" => __("How many Files do you want to list on a page/view", "nm_file_uploader_pro"),
				"id" => $nmf_shortname."_file_limit",
				"type" => "text",
				"std"	=> 5),

        array(  "name" => __("Google Client ID", "nm_file_uploader_pro"),
                "desc" => __("Please enter google client ID. For help, go to documentation", "nm_file_uploader_pro"),
                "id" => $nmf_shortname."_clientID",
                "type" => "text",
                "std"   => 5),

        array(  "name" => __("Google Secret Key", "nm_file_uploader_pro"),
                "desc" => __("Please enter google secret key. For help, go to documentation", "nm_file_uploader_pro"),
                "id" => $nmf_shortname."_secretKey",
                "type" => "text",
                "std"   => 5),

		array( 	"name" => __("Sent Message", "nm_file_uploader_pro"),
		  		"desc" => __("Type a message here, it will be shown when user will submit the file", "nm_file_uploader_pro"),
				"id" => $nmf_shortname."_uploaded_msg",
				"type" => "textarea",
				"std" => ""),

		array( 	"name" => __("Delete Message", "nm_file_uploader_pro"),
		  		"desc" => __("Type a message here, it will be shown when user will delete the file", "nm_file_uploader_pro"),
				"id" => $nmf_shortname."_deleted_msg",
				"type" => "textarea",
				"std" => ""),


		array( "type" => "close"),

);	//end of nmf_options array



function nm_file_add_admin() {

    global $nmf_name, $nmf_shortname, $nmf_options, $nmf_page_name;

    if ( @$_GET['page'] == $nmf_page_name ) {

         if ( 'save' == $_REQUEST['action'] ) {

                foreach ($nmf_options as $value) {
                    update_option( $value['id'], $_REQUEST[ $value['id'] ] ); }

                foreach ($nmf_options as $value) {
                    if( isset( $_REQUEST[ $value['id'] ] ) ) { update_option( $value['id'], $_REQUEST[ $value['id'] ]  ); } else { delete_option( $value['id'] ); } }

                header("Location: plugins.php?page=$nmf_page_name&saved=true");
                die;

        } else if( 'reset' == $_REQUEST['action'] ) {

            foreach ($nmf_options as $value) {
                delete_option( $value['id'] ); }

            header("Location: plugins.php?page=$nmf_page_name&reset=true");
            die;

        }
    }

   add_menu_page($nmf_name,
  				"Nmedia File Upload",
				'edit_plugins',
				$nmf_page_name,
				'nm_file_admin',
				plugin_dir_url(__FILE__ ).'images/option.png');

	add_submenu_page( $nmf_page_name,
					  'Files List',
					  'Files List',
					  'manage_options',
					  'nm-user-files',
					  array('nmFileUploader', 'listUserFiles'));

}


function nm_file_add_init() {
  	wp_register_style('nmf_option_style', plugins_url('options.css', __FILE__));
	wp_enqueue_style( 'nmf_option_style');

	wp_enqueue_script("nmf_script", plugins_url('js/nm_plugin_option.js', __FILE__), false, "1.0");

}


function nm_file_admin() {

    global $nmf_name, $nmf_shortname, $nmf_options, $nm_bgs;
	//print_r($nmf_options);


    if ( $_REQUEST['saved'] ) echo '<div id="message" class="updated fade"><p><strong>'.$nmf_name.' '.__('Settings saved.','nm_file_uploader_pro').'</strong></p></div>';
    if ( $_REQUEST['reset'] ) echo '<div id="message" class="updated fade"><p><strong>'.$nmf_name.' '.__('Settings reset.','nm_file_uploader_pro').'</strong></p></div>';
    if ( $_REQUEST['reset_widgets'] ) echo '<div id="message" class="updated fade"><p><strong>'.$nmf_name.' '.__('Widgets reset.','nm_file_uploader_pro').'</strong></p></div>';

?>
<div class="wrap rm_wrap">
<h2><?php echo $nmf_name; ?> Settings</h2>


<div class="rm_opts">
<iframe src="//www.facebook.com/plugins/like.php?href=http://www.najeebmedia.com/nmedia-user-file-uploader-plugin/&amp;send=false&amp;layout=standard&amp;width=350&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;height=35&amp;appId=283225211712454" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:350px; height:35px;" allowTransparency="true"></iframe>

<div style="padding:10px; background-color:#CCC; border:#999 1px dashed; width:720px">
<h2>Use following Shortcode in page</h2>
[nm-wp-file-uploader]
</div>

<div style="padding:10px; background-color:#EEE; border:#999 1px dashed; width:720px">
<a href="http://localhost/wp/wp-content/plugins/nmedia-user-file-uploader/googledocsAcces.php" target="_blank"><h3>Please click link for get refresh token (necessary)</h3></a>
</div>

<div style="padding:10px; background-color:#EEE; border:#999 1px dashed; width:720px">
<?php echo get_option('refresh_token'); ?>
</div>

<br />
<form method="post">

<?php foreach ($nmf_options as $value) {
switch ( $value['type'] ) {

case "open":
?>

<?php break;

case "close":
?>

</div>
</div>
<br />

<?php break;

case "title":
?>

<?php break;

case 'text':
?>

<div class="rm_input rm_text">
	<label for="<?php echo $value['id']; ?>"><?php _e($value['name'], 'nm_file_uploader_pro') ?></label>
 	<input name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>" value="<?php if ( get_option( $value['id'] ) != "") { echo stripslashes(get_option( $value['id'])  ); } else { echo $value['std']; } ?>" />
 <small><?php _e($value['desc'], 'nm_file_uploader_pro') ?></small><div class="clearfix"></div>

 </div>
<?php
break;

case 'textarea':
?>

<div class="rm_input rm_textarea">
	<label for="<?php echo $value['id']; ?>"><?php _e($value['name'], 'nm_file_uploader_pro') ?></label>
 	<textarea name="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>" cols="" rows=""><?php if ( get_option( $value['id'] ) != "") { echo stripslashes(get_option( $value['id']) ); } else { echo $value['std']; } ?></textarea>
 <small><?php _e($value['desc'], 'nm_file_uploader_pro') ?></small><div class="clearfix"></div>

 </div>

<?php
break;

case 'bgs'		//custom field set by Najeeb
?>

<div class="rm_input">
	<div style="float:left; width:200px;">
	<label for="<?php echo $value['id']; ?>"><?php _e($value['name'], 'nm_file_uploader_pro') ?></label>
    </div>
    <div class="nm_bgs">
    <?php foreach($nm_bgs as $bg => $val):
	$bg_img_name = 'images/'.$val;
	?>
    <div class="item">
        	<img src="<?php echo plugins_url($bg_img_name, __FILE__)?>" alt="<?php echo $bg ?>" width="75" /><br />
			<input type="radio" name="<?php echo $value['id']; ?>" value="<?php echo $val?>" <?php if (get_option( $value['id'] ) == $val) { echo 'checked="checked"'; } ?> />
            <?php echo $bg ?>
        </div>
    <?php endforeach;?>

        <div class="clearfix"></div>
        </div>

    <small><?php _e($value['desc'], 'nm_file_uploader_pro') ?></small>
 	<div class="clearfix"></div>

 </div>

<?php
break;



case 'bgs_pro'		//custom field set by Najeeb for Pro Backgrounds
?>

<div class="rm_input">
	<div style="float:left; width:200px;">
	<label for="<?php echo $value['id']; ?>"><?php _e($value['name'], 'nm_file_uploader_pro') ?></label>
    </div>
    <div class="nm_bgs">
    <?php
	for($i=1; $i<=26; $i++):
	$bg_img_name = 'images/'.$i.'.jpg';
	$bg_title = 'Pro-'.$i;
	$val = $i.'.jpg';
	?>
    <div class="item">
        	<img src="<?php echo plugins_url($bg_img_name, __FILE__)?>" alt="<?php $bg_title?>" width="75" /><br />
			<input type="radio" name="<?php echo $value['id']; ?>" value="<?php echo $val?>" <?php if (get_option( $value['id'] ) == $val) { echo 'checked="checked"'; } ?> />
            <?php echo $bg_title ?>
        </div>
    <?php endfor;?>

        <div class="clearfix"></div>
        </div>

    <small><?php _e($value['desc'], 'nm_file_uploader_pro') ?></small>
 	<div class="clearfix"></div>

 </div>

<?php
break;

case 'select':
?>

<div class="rm_input rm_select">
	<label for="<?php echo $value['id']; ?>"><?php _e($value['name'], 'nm_file_uploader_pro') ?></label>

<select name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>">
<?php foreach ($value['nmf_options'] as $option) { ?>
		<option <?php if (get_option( $value['id'] ) == $option) { echo 'selected="selected"'; } ?>><?php echo $option; ?></option><?php } ?>
</select>

	<small><?php _e($value['desc'], 'nm_file_uploader_pro') ?></small><div class="clearfix"></div>
</div>
<?php
break;

case "checkbox":
?>

<div class="rm_input rm_checkbox">
	<label for="<?php echo $value['id']; ?>"><?php _e($value['name'], 'nm_file_uploader_pro') ?></label>

<?php if(get_option($value['id'])){ $checked = "checked=\"checked\""; }else{ $checked = "";} ?>
<input type="checkbox" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" value="true" <?php echo $checked; ?> />

	<small><?php _e($value['desc'], 'nm_file_uploader_pro') ?></small><div class="clearfix"></div>
 </div>
<?php break;
case "section":

$i++;
?>

<div class="rm_section">
<div class="rm_title"><h3><img src="<?php plugins_url('css/images/trans.gif', __FILE__)?>" class="inactive" alt="""><?php _e($value['name'], 'nm_file_uploader_pro') ?></h3><span class="submit"><input name="save<?php echo $i; ?>" type="submit" value="<?php _e('Save Changes', 'nm_file_uploader_pro')?>" />
</span><div class="clearfix"></div></div>
<div class="rm_options">

<?php break;

}
}
?>

<input type="hidden" name="action" value="save" />
</form>
<form method="post">
<p class="submit">
<input name="reset" type="submit" value="<?php _e('Reset', 'nm_file_uploader_pro')?>" />
<input type="hidden" name="action" value="reset" />
</p>
</form>


<div style="padding:10px; background-color:#CCC; border:#999 1px dashed; width:720px">
<h2>Need EXTRA Conrol Like:</h2>
<ul style="list-style:inside">
	<li>Admin can upload files for Roles or Public</li>
    <li><strong>Attach unlimited form input field using simple shortcodes</strong> <a href="http://www.najeebmedia.com/nmedia-file-uplaoder-wordpress-plugin-pro-v6/">Detail</a></li>
    <li><strong>multi</strong>: multiple upload</li>
    <li><strong>file_limit</strong>: control file limits</li>
    <li><strong>file_ext</strong>: restrict file extension</li>
    <li><strong>allow_delete</strong>: switch on/off delete control</li>
    <li><strong>allow_upload</strong>: switch on/ff to upload files</li>
    <li><strong>display_files</strong>: show/hide files</li>
    <li><strong>is_public</strong>: allow all users to see uploaded files</li>
</ul>

<p><a href="https://docs.google.com/open?id=0B2u2KiDYT-rnZUhfTDJjVG5xdWc">PRO User guide about all shortcodes</a></p>

Get PRO Version for Just $30.00 USD. <a href="http://www.najeebmedia.com/nmedia-file-uploader-pro/">Detail and Purchase Here</a>
</div>

<div style="font-size:9px; margin-bottom:10px;">2012 © <a href="http://www.najeebmedia.com">Nmedia</a></div>
 </div>

<?php
// get company ad
$file = dirname(__FILE__).'/nmedia-ad.php';
include($file);
}

add_action('admin_init', 'nm_file_add_init');
add_action('admin_menu' , 'nm_file_add_admin');
