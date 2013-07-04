<?php
session_start();
if(!isset($_SESSION['wysystem']) && !isset($_SESSION['loggedip']) && !isset($_SESSION['wylevel']) && !isset($_SESSION['wydatelog']) 
|| $_SESSION['wysystem']=="" || $_SESSION['wysystem']==NULL && $_SESSION['loggedip']=="" || $_SESSION['loggedip']==NULL 
&& $_SESSION['wylevel']="" || $_SESSION['wylevel']==NULL && $_SESSION['wydatelog']="" || $_SESSION['wydatelog']==NULL){
	session_destroy();
	header('Location:../wy_login.php');
}
else{
?>
<!DOCTYPE html><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="icon" href="../wy_files/images/wy-icon.ico" type="image/x-icon" />
<title>Dashboard Admin</title>
<link rel="stylesheet" type="text/css" href="css/user.css"/>
<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/bootstrap-responsive.min.css">
<link rel="stylesheet" href="css/bootstrap-image-gallery.min.css">
<link rel="stylesheet" href="css/jquery.fileupload-ui.css">

<noscript><link rel="stylesheet" href="css/jquery.fileupload-ui-noscript.css"></noscript>
</head>
<body bgproperties="fixed">

	<!--header-->
    <div id="header">
    	<a href="index.php">
        	<img src="images/logo.png" alt="" style="margin:5px 10px 0 5px;float:left;"/>
        </a>
        <div class="sitetitle">
        Wayang CMS Administration
        </div>
        <div class="headermenu">
        Logged with user: <?php echo $_SESSION['wysystem']." as ".$_SESSION['wylevel'].", "; echo $_SESSION['wydatelog'];?>. <a href="../wy_controlls/wy_login.php?action=logout" >Log Out? <img src="images/super-mono-3d-part2-91.png"/></a>
        </div>
        <div id="siteposition" class="siteposition">
        Dashboard <img src="images/current.png"/> Manage Media
        </div>
    </div>
<div id="wrapper">
   <!--body-->
	<?php require_once('mod_menu.php');?>
	<div id="usercontent">
    	<div class="usercontenttitle"><strong>Dashboard Work Form</strong></div>
        <div class="listrecent">
			<div class="container">
				<!-- The file upload form used as target for the file upload widget -->
				<form id="fileupload" action="" method="POST" enctype="multipart/form-data">
					<!-- Redirect browsers with JavaScript disabled to the origin page -->
					<!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
					<div class="row fileupload-buttonbar">
						<div class="span7">
							<!-- The fileinput-button span is used to style the file input field as button -->
							<span class="btn btn-success fileinput-button">
								<i class="icon-plus icon-white"></i>
								<span>Add files...</span>
								<input type="file" name="files[]" multiple>
							</span>
							<button type="submit" class="btn btn-primary start">
								<i class="icon-upload icon-white"></i>
								<span>Start upload</span>
							</button>
							<button type="reset" class="btn btn-warning cancel">
								<i class="icon-ban-circle icon-white"></i>
								<span>Cancel upload</span>
							</button>
							<button type="button" class="btn btn-danger delete">
								<i class="icon-trash icon-white"></i>
								<span>Delete</span>
							</button>
							<input type="checkbox" class="toggle">
						</div>
						<!-- The global progress information -->
						<div class="span5 fileupload-progress fade">
							<!-- The global progress bar -->
							<div class="progress progress-success progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
								<div class="bar" style="width:0%;"></div>
							</div>
							<!-- The extended global progress information -->
							<div class="progress-extended">&nbsp;</div>
						</div>
					</div>
					<!-- The loading indicator is shown during file processing -->
					<div class="fileupload-loading"></div>
					<br>
					<!-- The table listing the files available for upload/download -->
					<table role="presentation" class="table table-striped"><tbody class="files" data-toggle="modal-gallery" data-target="#modal-gallery"></tbody></table>
				</form>
				<br>
			</div>
			<!-- modal-gallery is the modal dialog used for the image gallery -->
			<div id="modal-gallery" class="modal modal-gallery hide fade" data-filter=":odd" tabindex="-1">
				<div class="modal-header">
					<a class="close" data-dismiss="modal">&times;</a>
					<h3 class="modal-title"></h3>
				</div>
				<div class="modal-body"><div class="modal-image"></div></div>
				<div class="modal-footer">
					<a class="btn modal-download" target="_blank">
						<i class="icon-download"></i>
						<span>Download</span>
					</a>
					<a class="btn btn-success modal-play modal-slideshow" data-slideshow="5000">
						<i class="icon-play icon-white"></i>
						<span>Slideshow</span>
					</a>
					<a class="btn btn-info modal-prev">
						<i class="icon-arrow-left icon-white"></i>
						<span>Previous</span>
					</a>
					<a class="btn btn-primary modal-next">
						<span>Next</span>
						<i class="icon-arrow-right icon-white"></i>
					</a>
				</div>
			</div>
			<!-- The template to display files available for upload -->
			<script id="template-upload" type="text/x-tmpl">
			{% for (var i=0, file; file=o.files[i]; i++) { %}
				<tr class="template-upload fade">
					<td class="preview"><span class="fade"></span></td>
					<td class="name"><span>{%=file.name%}</span></td>
					<td class="size"><span>{%=o.formatFileSize(file.size)%}</span></td>
					{% if (file.error) { %}
						<td class="error" colspan="2"><span class="label label-important">Error</span> {%=file.error%}</td>
					{% } else if (o.files.valid && !i) { %}
						<td>
							<div class="progress progress-success progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="bar" style="width:0%;"></div></div>
						</td>
						<td>{% if (!o.options.autoUpload) { %}
							<button class="btn btn-primary start">
								<i class="icon-upload icon-white"></i>
								<span>Start</span>
							</button>
						{% } %}</td>
					{% } else { %}
						<td colspan="2"></td>
					{% } %}
					<td>{% if (!i) { %}
						<button class="btn btn-warning cancel">
							<i class="icon-ban-circle icon-white"></i>
							<span>Cancel</span>
						</button>
					{% } %}</td>
				</tr>
			{% } %}
			</script>
			<!-- The template to display files available for download -->
			<script id="template-download" type="text/x-tmpl">
			{% for (var i=0, file; file=o.files[i]; i++) { %}
				<tr class="template-download fade">
					{% if (file.error) { %}
						<td></td>
						<td class="name"><span>{%=file.name%}</span></td>
						<td class="size"><span>{%=o.formatFileSize(file.size)%}</span></td>
						<td class="error" colspan="2"><span class="label label-important">Error</span> {%=file.error%}</td>
					{% } else { %}
						<td class="preview">{% if (file.thumbnail_url) { %}
							<a href="{%=file.url%}" title="{%=file.name%}" data-gallery="gallery" download="{%=file.name%}"><img src="{%=file.thumbnail_url%}"></a>
						{% } %}</td>
						<td class="name">
							<a href="{%=file.url%}" title="{%=file.name%}" data-gallery="{%=file.thumbnail_url&&'gallery'%}" download="{%=file.name%}">{%=file.name%}</a>
						</td>
						<td class="size"><span>{%=o.formatFileSize(file.size)%}</span></td>
						<td colspan="2"></td>
					{% } %}
					<td>
						<button class="btn btn-danger delete" data-type="{%=file.delete_type%}" data-url="{%=file.delete_url%}"{% if (file.delete_with_credentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
							<i class="icon-trash icon-white"></i>
							<span>Delete</span>
						</button>
						<input type="checkbox" name="delete" value="1" class="toggle">
					</td>
				</tr>
			{% } %}
			</script>
			<script src="js/jquery-1.9.1.min.js"></script>
			<!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
			<script src="js/vendor/jquery.ui.widget.js"></script>
			<!-- The Templates plugin is included to render the upload/download listings -->
			<script src="js/tmpl.min.js"></script>
			<!-- The Load Image plugin is included for the preview images and image resizing functionality -->
			<script src="js/load-image.min.js"></script>
			<!-- The Canvas to Blob plugin is included for image resizing functionality -->
			<script src="js/canvas-to-blob.min.js"></script>
			<!-- Bootstrap JS and Bootstrap Image Gallery are not required, but included for the demo -->
			<script src="js/bootstrap.min.js"></script>
			<script src="js/bootstrap-image-gallery.min.js"></script>
			<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
			<script src="js/jquery.iframe-transport.js"></script>
			<!-- The basic File Upload plugin -->
			<script src="js/jquery.fileupload.js"></script>
			<!-- The File Upload file processing plugin -->
			<script src="js/jquery.fileupload-fp.js"></script>
			<!-- The File Upload user interface plugin -->
			<script src="js/jquery.fileupload-ui.js"></script>
			<!-- The main application script -->
			<script src="js/main.js"></script>
        </div>
    </div>  
    <!--footer-->
    <div id="footer">
    	<div class="copyright">
            <p>&copy; 2012. all right reserved
            <br/>
             powered by <a href="http://www.wayang-cms.org" class="copyright"/>Wayang CMS</a></p>
    	</div>
  	</div>
</div>   
</body>
</html>
<?php } ?>