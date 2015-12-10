<?php
session_start();
$valid = $_SESSION['pin'];

if(isset($_POST['file-link'])) {

	function get_str_between($string, $start, $end){
	    $string = ' ' . $string;
	    $ini = strpos($string, $start);
	    if ($ini == 0) return '';
	    $ini += strlen($start);
	    $len = strpos($string, $end, $ini) - $ini;
	    return substr($string, $ini, $len);
	}

	if ($valid == 'totes') {
		$fileLink = $_POST['file-link'];
		$fileTitle = $_POST['file-title'];
		$fileFolder = $_POST['file-folder'];
		$fileType = $_POST['doctype'];
		$fileID = get_str_between($fileLink, '/d/', '/');
		$dir = 'list/';
		$folderName = $dir.$fileFolder.'.txt';
		$f = fopen($folderName, 'r');
		$line = fgets($f);
		$type = '0';


		switch ($fileType) {
			case 'drive':
				$type ='1';
				break;
			case 'docs':
				$type ='0';
				break;
		}
		if (trim($line) == '')
		{
			$string = $fileID.', '.$fileTitle.', '.$type;
		} else {
			$string = "\r\n".$fileID.', '.$fileTitle.', '.$type;
		}
		fclose($f);

		file_put_contents($folderName, $string, FILE_APPEND);

		session_destroy();
		header('location:index.php'); 

	} else {
		echo 'Nice Try!';
	}

} elseif (isset($_POST['folder-title'])) {
	if ($valid == 'totes') {

		$newFolder = $_POST['folder-title'];
		$folderText = 'list/'.$newFolder.'.txt';

		if (!file_exists($folderText)) {
			fopen($folderText, 'w');
			fclose($folderText);
		}

		$current = file_get_contents($folderText);
		file_put_contents($folderName, $current);

		session_destroy();
		header('location:index.php'); 
	} else {
		echo 'Nice Try!';	
	}

} else {
	echo 'No direct access';
}

?>