=== Nmedia Users File Uploader Plugin ===
Contributors: nmedia
Donate link: http://www.najeebmedia.com/donate/
Tags: File uploader, User files, User files manager, File uploaders, User Desgins uploader, Image uploader, ajax based file uploader, progress bar
Requires at least: 3.2.1
Tested up to: 3.5.1
Stable tag: 2.3
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html
=== Nmedia Users File Uploader Plugin ===
Contributors: nmedia
Donate link: http://www.najeebmedia.com/donate/
Tags: File uploader, User files, User files manager, File uploaders, User Desgins uploader, Image uploader, ajax based file uploader, progress bar
Requires at least: 3.2.1
Tested up to: 3.4
Stable tag: 2.1
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

N-Media file uploader plugin allow site users to upload files and share with admin.

== Description ==
This plugin lets the wordpress site users to upload files for admin. Each file is saved in private directory so each user can download/delete their own files after login. For more control please see PRO feature below.

<h3>Features</h3>
<ol>
	<li>Flash Uploader</li>
	<li>Ajax based validation</li>
	<li>File Detail</li>
	<li>Download Files</li>
	<li>Delete File</li>
	<li>Customized Upload Message, Delete Message</li>
</ol>


<h3>Pro Features</h3>
Pro version gives you AWSOME control over this plugin on top of free version. You can control file upload behavior with following shortcode
<ul>
	<li><strong>multi</strong>: multiple upload</li>
	<li><strong>file_limit</strong>: control file limits</li>
	<li><strong>file_ext</strong>: restrict file extension</li>
	<li><strong>allow_delete</strong>: switch on/off delete control</li>
	<li><strong>allow_upload</strong>: switch on/ff to upload files</li>
	<li><strong>display_files</strong>: show/hide files</li>
	<li><strong>is_public:</strong> allow all users to see uploaded files</li>
	<li><strong>size_limit: </strong>control file size uploaded by users</li>
</ul>

<h3>File Meta</h3>
File meta is another set of shortcodes allow site admin to attach unlimited input fields. These are named as `File Meta`. Admin will receive email on every file upload
with `File Meta`. Following four types of input field can be attached:

<ul>
	<li><strong>Text</strong> - e.g: [nm-input-field type="text" label="Title"]</li>
	<li><strong>Textarea</strong> - e.g: [nm-input-field type="textarea" label="File notes"]</li>
	<li><strong>Select</strong> - e.g: [nm-input-field type="select" label="Select color" options="Red,Green,Blue"]</li>
	<li><strong>Checkbox</strong> - e.g: [nm-input-field type="checkbox" label="Shipping by" options="Regular, Air"]</li>
</ul>

<a href="http://www.najeebmedia.com/nmedia-file-uploader-pro/">More info with Demo</a>


== Installation ==

1. Upload plugin directory to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. After activation, you can set options from `NM FileUploader` menu

== Frequently Asked Questions ==

= Can I change the message when file uploaded or deleted? =

Yes you can.

= Can I see its working Demo? =
Yes, http://www.najeebmedia.com/nmedia-file-uploader-plugin/<br>
username: test<br>
password: password


= Does this uploader will show progressbar =
Yes nice progressbar with percentage

= Why I see HTTP Error message =
it is because of your server side settings, sometime php.ini does not allow to upload big files. You need to check following two settings in php.ini:<br>
1- post_max_size<br>
2- upload_max_filesize
<a href="http://www.najeebmedia.com/how-increase-file-upload-size-limit-in-wordpress/">check this tutorial</a>

== Screenshots ==

1. Option settings under `NM FileUploader` menu
2. using short code in page
3. Front end view
4. Admin area for user uploaded files

== Changelog ==

= 1.0 =
* It is first version, and working perfectly

= 1.1 =
* Just fixed the delete file bug.

= 1.2 =
* Fixed content placement issue when using shortcode in middle of post/page.

= 1.3 =
* Fixed the bug for php short code

= 1.4 =
* Change the Upload File button 

= 1.5 = 
* Physical file deleted from folder
* Every user will have its own upload directory as `user_name`
* File Name field is removed

= 1.6 =
* there was error sometimes when creating directory for users, not it is fixed.

= 1.7 =
* Admin can see the file uploade by users

= 1.8 =
* Some major security issues is being fixed, please update to this version

= 2.0 = 
* doupload.php and uploadify.php files have removed for best security practice
* front end design is replced with ul/li based structure 
* pagination control

= 2.1 =
* Some latin characters like �, �, � etc were not rendered in file upload button, it is fixed now.


== Upgrade Notice ==

= 1.0 =
Nothing for now.

= 1.1 =
Update to this version, Delete File issue is just fixed.

= 1.2 =
Update to this version, Content Placement issue is being fixed

= 1.3 =
Fixed the bug for php short code

= 1.4 =
Wrapped up the Upload File Button with some CSS.

= 1.5 =
This plugin has three major changes, please update to get these.

= 1.6 =
Upload directory was not creating due to some server side settings, now it is fixed

= 1.7 =
Admin can see the file uploade by users

= 1.8 =
Some major security issues is being fixed, please update to this version

= 2.0 = 
doupload.php and uploadify.php files have removed for best security practice
front end design is replced with ul/li based structure 
pagination control

= 2.1 =
Some latin characters like �, �, � etc were not rendered in file upload button, it is fixed now.


1. It is very light plugin
2. We are working on more plugins to get our users more excited.
3. More options/controls will be given soon.
N-Media file uploader plugin allow site users to upload files and share with admin.

== Description ==
This plugin lets the wordpress site users to upload files for admin. Each file is saved in private directory so each user can download/delete their own files after login. For more control please see PRO feature below.

<h3>Features</h3>
<ol>
	<li>Flash Uploader</li>
	<li>Ajax based validation</li>
	<li>File Detail</li>
	<li>Download Files</li>
	<li>Delete File</li>
	<li>Customized Upload Message, Delete Message</li>
</ol>


<h3>Pro Features</h3>
Pro version gives you AWSOME control over this plugin on top of free version. You can control file upload behavior with following shortcode
<ul>
	<li><strong>HTML-5</strong>: based file upload script (iPhone/iPad supported)</li>
	<li><strong>multi</strong>: multiple upload</li>
	<li><strong>file_limit</strong>: control file limits</li>
	<li><strong>file_ext</strong>: restrict file extension</li>
	<li><strong>allow_delete</strong>: switch on/off delete control</li>
	<li><strong>allow_upload</strong>: switch on/ff to upload files</li>
	<li><strong>display_files</strong>: show/hide files</li>
	<li><strong>is_public:</strong> allow all users to see uploaded files</li>
	<li><strong>size_limit: </strong>control file size uploaded by users</li>
</ul>

<h3>File Meta</h3>
File meta is another set of shortcodes allow site admin to attach unlimited input fields. These are named as `File Meta`. Admin will receive email on every file upload
with `File Meta`. Following four types of input field can be attached:

<ul>
	<li><strong>Text</strong> - e.g: [nm-input-field type="text" label="Title"]</li>
	<li><strong>Textarea</strong> - e.g: [nm-input-field type="textarea" label="File notes"]</li>
	<li><strong>Select</strong> - e.g: [nm-input-field type="select" label="Select color" options="Red,Green,Blue"]</li>
	<li><strong>Checkbox</strong> - e.g: [nm-input-field type="checkbox" label="Shipping by" options="Regular, Air"]</li>
</ul>

<a href="http://www.najeebmedia.com/nmedia-file-uploader-pro/">More info with Demo</a>


== Installation ==

1. Upload plugin directory to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. After activation, you can set options from `NM FileUploader` menu

== Frequently Asked Questions ==

= Can I change the message when file uploaded or deleted? =

Yes you can.

= Can I see its working Demo? =
Yes, http://www.najeebmedia.com/nmedia-file-uploader-plugin/<br>
username: test<br>
password: password


= Does this uploader will show progressbar =
Yes nice progressbar with percentage

= Why I see HTTP Error message =
it is because of your server side settings, sometime php.ini does not allow to upload big files. You need to check following two settings in php.ini:<br>
1- post_max_size<br>
2- upload_max_filesize
<a href="http://www.najeebmedia.com/how-increase-file-upload-size-limit-in-wordpress/">check this tutorial</a>

== Screenshots ==

1. Option settings under `NM FileUploader` menu
2. using short code in page
3. Front end view
4. Admin area for user uploaded files

== Changelog ==

= 1.0 =
* It is first version, and working perfectly

= 1.1 =
* Just fixed the delete file bug.

= 1.2 =
* Fixed content placement issue when using shortcode in middle of post/page.

= 1.3 =
* Fixed the bug for php short code

= 1.4 =
* Change the Upload File button 

= 1.5 = 
* Physical file deleted from folder
* Every user will have its own upload directory as `user_name`
* File Name field is removed

= 1.6 =
* there was error sometimes when creating directory for users, not it is fixed.

= 1.7 =
* Admin can see the file uploade by users

= 1.8 =
* Some major security issues is being fixed, please update to this version

= 2.0 = 
* doupload.php and uploadify.php files have removed for best security practice
* front end design is replced with ul/li based structure 
* pagination control

= 2.1 =
* Some latin characters like �, �, � etc were not rendered in file upload button, it is fixed now.
= 2.3 =
* Some design changes have been made to make it more user friendly

== Upgrade Notice ==

= 1.0 =
Nothing for now.

= 1.1 =
Update to this version, Delete File issue is just fixed.

= 1.2 =
Update to this version, Content Placement issue is being fixed

= 1.3 =
Fixed the bug for php short code

= 1.4 =
Wrapped up the Upload File Button with some CSS.

= 1.5 =
This plugin has three major changes, please update to get these.

= 1.6 =
Upload directory was not creating due to some server side settings, now it is fixed

= 1.7 =
Admin can see the file uploade by users

= 1.8 =
Some major security issues is being fixed, please update to this version

= 2.0 = 
doupload.php and uploadify.php files have removed for best security practice
front end design is replced with ul/li based structure 
pagination control

= 2.1 =
Some latin characters like �, �, � etc were not rendered in file upload button, it is fixed now.

= 2.3 =
Some design changes have been made to make it more user friendly


1. It is very light plugin
2. We are working on more plugins to get our users more excited.
3. More options/controls will be given soon.