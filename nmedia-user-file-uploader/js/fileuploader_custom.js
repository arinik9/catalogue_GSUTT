// JavaScript Document

var current_page = 1;
var total_pages;
var plugin_path;

String.prototype.trim = function() {
    return this.replace(/^\s+|\s+$/g,"");
}


/*
validate me
*/

function validate()
{
	jQuery("#working-area").show();
	
	var upload_file_name	= jQuery("#nm-upload-name").val();
	var file_name			= jQuery("#file-name").val();
	var notes				= jQuery("#nm-notes").val();
	
	var notices 			= jQuery("#error");
	notices.html('');
	
	var vFlag				= false;
	 
	if(upload_file_name == '')
	{
		notices.append('File Name cannot be empty<br>');
		vFlag = true;
	}
	
	if(file_name == '')
	{
		notices.append('Select any file first<br>');
		vFlag = true;
	}
	
	if(notes == '')
	{
		notices.append('Files Notes cannot be empty<br>');
		vFlag = true;
	}
	
	
	if(vFlag)
	{
		jQuery("#working-area").hide();
		return false;
		
	}
	else
	{
		return true;
	}
}
		
function confirmFirst(url)
{
	var a = confirm('Are you sure to delete this file?');
	if(a)
	{
		window.location = url;
	}
}


function showDetail(id)
{
	jQuery(".detail-all").hide();
	jQuery("#detail-all-"+id).fadeIn(1000);
	
}


function toggleDetail(id)
{
	jQuery(".file-detail").hide();
	jQuery("#file-detail-"+id).fadeToggle('fast');
	
}


/* pagination */
function loadUploaderPageNext()
{
	jQuery("ul#nmuploader-container li.nm-c-p-"+current_page++).hide();
	jQuery("ul#nmuploader-container li.nm-c-p-"+current_page).show();
	setUploaderPagination();
}

function loadUploaderPagePrev()
{
	jQuery("ul#nmuploader-container li.nm-c-p-"+current_page--).hide();
	jQuery("ul#nmuploader-container li.nm-c-p-"+current_page).show();
	setUploaderPagination();
}


/*function loadUploaderCurrentPage()
{
	
	jQuery("ul#nmuploader-container li.nm-c-p-"+current_page).show();
	setUploaderPagination();
	
}*/

function setUploaderPagination()
{
	if(total_pages == 1)
	{
		jQuery("#prev-page a").hide();
		jQuery("#next-page a").hide();
	}
	else if(total_pages == current_page)
	{
		jQuery("#next-page a").hide();
		jQuery("#prev-page a").show();
	}
	else if(total_pages > current_page && current_page > 1)
	{
		jQuery("#prev-page a").show();
	}
	else
	{
		jQuery("#prev-page a").hide();
		jQuery("#next-page a").show();
	}	
	
	//setting page couner lable
	jQuery("#page-count").html(current_page+" of "+total_pages);
	
}

	