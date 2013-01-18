<?php
include("../php/security.php");
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Add torrents</title>
		<link rel="stylesheet" type="text/css" href="../css/style.css"/>
		<link rel="stylesheet" type="text/css" href="../css/ui.css"/>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
		<script type="text/javascript" src="../js/jquery.filedrop.js"></script>
	</head>
	<body>
		<h1>Add Torrent</h1>
		<div id="dropbox">
			<b>Drop your files here to upload</b>
			<table id="torrent_list">
				<tr><td style="width:300px;">Name</td><td style="width:100px;">Progress</td></tr>
			</table>
		</div>
		<h1>Add URL/Magnet</h1>
		<input type="text" id="link" />
		<!--SCRIPT TO HANDLE DRAG AND DROP-->
		<script type="text/javascript">
			function rowID(){
				return document.getElementById('torrent_list').rows.length;
			}
			function clampName(name){
				return (name.length>45 ? name.substring(0,45) + "..." : name);
			}
			$("#link").keyup(function (e) {
				if (e.keyCode == 13) {
					var url = $(this).val();
					var id = rowID();
					$('#torrent_list > tbody:last').append("<tr><td>" + clampName(url) + "</td><td><div class=\"border_bottom\" style=\"width:100%;\"><div id=\"_" + id + "\" class=\"blue\" style=\"height:2px;\"></div></div></td></tr>");
					$.ajax({
					  url: '../php/util.php?cmd=load_link&href=' + url,
					  success: function(data) {
						if(data!=0){
							$('#' + id).attr('class','red');
							alert("Error loading link " + url);
						}else{
							$('#' + id).attr('class','green');
						}
					  }
					});
				}
			});
			$(function(){
				var dropbox = $('#dropbox');
				var files = new Array();
				dropbox.filedrop({
					paramname:'torrents',
					maxfiles: 20,
					maxfilesize: 2,
					url: '../php/upload.php',
					
					uploadFinished:function(i,file,response){
						var id = files.indexOf(file.name);
						if(response.status != '1'){
							$('#_' + id).attr('class','red');
							alert(response.status);
						}else{						
							$('#_' + id).attr('class','green');
						}
					},
					
					error: function(err, file) {
						switch(err) {
							case 'BrowserNotSupported':
								showMessage('Your browser does not support HTML5 file uploads!');
								break;
							case 'TooManyFiles':
								alert('Too many files! Please select 5 at most! (configurable)');
								break;
							case 'FileTooLarge':
								alert(file.name+' is too large! Please upload files up to 2mb (configurable).');
								break;
							default:
								break;
						}
					},
					
					// Called before each upload is started
					beforeEach: function(file){
						if(file.type != 'application/x-bittorrent'){
							alert("File type must be .torrent");
							return false;
						}
					},
					
					uploadStarted:function(i, file, len){
						var id = files.length;
						files[id] = file.name;
						var name = clampName(file.name);
						$('#torrent_list > tbody:last').append("<tr><td>" + name + "</td><td><div class=\"border_bottom\" style=\"width:100%;\"><div id=\"_" + id + "\" class=\"blue\" style=\"height:2px;\"></div></div></td></tr>");
					},
					
					progressUpdated: function(i, file, progress) {
						var id = files.indexOf(file.name);
						$('#_' + id).width(progress + '%');
					}
					 
				});
			});
		</script>
	</body>
</html>