<!DOCTYPE html>
<html>
<head>
	<title>docshare</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- iOS Web App -->
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="apple-mobile-web-app-title" content="docshare" />
	<meta name="apple-mobile-web-app-status-bar-style" content="default" />
	<!--<link rel="apple-touch-icon" href="img/profile.png">-->
	<!--<link rel="apple-touch-startup-image" href="img/temp-splash.png">-->
	
	<link rel="stylesheet" href="style.css">

</head>
<body>

<?php
$dir = 'list';
$folders = scandir($dir);
$folders = array_diff($folders, array('.', '..', '.DS_Store'));
?>
	<nav>
		<h1>
			docshare
		</h1>

		<h2 class="main">Public Documents</h2>

		<div class="add">+ Add New</div>
	</nav>
	<div class="shade"></div>
	<div class="add-new">
		<div class="first">

		<h4>Enter Your Passcode to Continue</h4>

		

		<form action="#">
			<input id="pass1" type="text" pattern="\d*" maxlength="1">
			<input id="pass2" type="text" pattern="\d*" maxlength="1">
			<input id="pass3" type="text" pattern="\d*" maxlength="1">
			<input id="pass4" type="text" pattern="\d*" maxlength="1">
			<h5 id="pass-msg">&nbsp;</h5>
			<button class="passcode-submit">Verify</button>
		</form>


		</div>

		<div class="second">

		
			<h4>Add New</h4>
			<div class="add-folder">Folder</div>
			<div class="add-file active">File</div>
			<div class="file-form">
				<form action="add.php" method="POST">
					<input id="file-link" name="file-link" type="text" placeholder="Paste Google Doc Shareable Link" />
					<input id="file-title" name="file-title" type="text" placeholder="Enter Title for Document" />
					<div class="doc-type">
						<img id="gdocs" src="img/doc-icon.png" alt="Google Docs">
						<img id="gdrive" src="img/drive-icon.png" alt="Google Drive">

						<fieldset>
							<label for="docs">Docs</label>
							<label for="drive">Drive</label>
							<span>
								<input id="docs" name="doctype" type="radio" value="docs" checked>
								<input id="drive" name="doctype" type="radio" value="drive">
							</span>
							

						</fieldset>
					</div>
					

					<span class="folder-select">
						
						<h5>Add to Which Folder?</h5>
						<select name="file-folder" id="file-folder">

	<?php
	foreach ($folders as $folder) {
		$folderName = preg_replace('/\\.[^.\\s]{3,4}$/', '', $folder);
		echo '<option value="'.$folderName.'" >'.$folderName.'</option>';
	}
	?>
						</select>
					</span>
					
					

					<button class="add-file-submit" type="submit">Add</button>
				</form>
			</div>
			<div class="folder-form">
				<form action="add.php" method="POST">
					<input id="folder-title" name="folder-title" type="text" placeholder="Enter Folder Title" />
					<button class="add-folder-submit" type="submit">Add</button>
				</form>
			</div>
		</div>
	</div>

	<div class="main-body">

<?php
foreach ($folders as $folder) {

	$file = $dir.'/'.$folder;
	$files = fopen($file, 'r') or die('<span class="msg">Unable to open file!</span>');
	$folderName = preg_replace('/\\.[^.\\s]{3,4}$/', '', $folder);
	
	echo '<h3>'.$folderName.'</h3>';
	echo '<ul>';

	while (($line = fgets($files)) !== false) {
		$info = explode(',', $line);
		$link = 'https://drive.google.com/file/d/'.$info[0].'/edit?usp=sharing';
		$fileType = trim($info[2]);
		if ($fileType == '0') {
			$pdf = 'https://docs.google.com/document/d/'.$info[0].'/export?format=pdf';
			echo '<li><img class="doc" src="img/doc-icon.png">'.$info[1].'<a href="'.$pdf.'">PDF</a><a target="blank" href="'.$link.'">Link</a></li>';
		} else if ($fileType == '1') {
			$pdf = 'https://drive.google.com/uc?export=download&id='.$info[0];
			echo '<li><img class="doc" src="img/drive-icon.png">'.$info[1].'<a href="'.$pdf.'">PDF</a><a target="blank" href="'.$link.'">Link</a></li>';
		} else {
			$pdf = 'https://docs.google.com/document/d/'.$info[0].'/export?format=pdf';
			echo '<li><img class="doc" src="img/doc-icon.png">'.$info[1].'<a href="'.$pdf.'">PDF</a><a target="blank" href="'.$link.'">Link</a></li>';
		}
		
	}
	echo '</ul>';
	fclose($files);
}
?>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script>
$(document).ready(function() {
	function respond(val) {
		if (val) {
			$('.first').hide();
			$('.second').show();
			$('#pass-msg').text();
		} else {
			$('#pass-msg').text('Passcode Incorrect, Try Again');
			$('#pass1, #pass2, #pass3, #pass4').val('');
			$('#pass1').focus();
		}
	}
	$('.add-folder, .add-file').click(function() {
		var thisClass = $(this).attr('class');
		var activate = thisClass.substr(4);
		var activeForm = activate + '-form';
		$('.active').removeClass('active');
		if (activate == 'folder') {
			$('.file-form').hide();
		} else {
			$('.folder-form').hide();
		}
		$('.' + activeForm).show();
		$(this).addClass('active');
	});

	$('.add').click(function() {
		$('.shade, .add-new').fadeIn();
		$('#pass1').focus();
	});
	$('.shade').click(function() {
		$('.shade, .add-new').fadeOut();
	});

	$('.passcode-submit').click(function(event) {
		event.preventDefault();
		var passcode = $('input[id^=pass').map(function() {return this.value;}).get().join('');
		$.ajax({
			type: "POST",
			url: 'passcode.php',
			data: {passcode: passcode},
			success: function (res) {
				if (res == true){
					respond(true);
				} else {
					respond(false);
				}	
        	},
        	error: function () {
            	respond(false);
        	}
        });
	});

	$('#pass1, #pass2, #pass3').keyup(function() {
		if ($(this).val().length > 0) {
			$(this).next().focus();
		} else {}
		
	});

	$('#gdocs, #gdrive').click(function() {
		var type = $(this).attr('id').substr(1);
		if (type == 'docs') {
			$('#drive').prop('checked', false);
			$('#docs').prop('checked', true);
		} else if (type == 'drive') {
			$('#docs').prop('checked', false);
			$('#drive').prop('checked', true);
		}
	});

	
});

</script>
</body>
</html>