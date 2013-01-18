<?php
include("../php/security.php");
?>
<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="../css/style.css"/>
		<link rel="stylesheet" type="text/css" href="../css/ui.css"/>
	</head>
	<body>
	<?php
		$dir = new RecursiveDirectoryIterator($_DL . $_GET['base'],FilesystemIterator::SKIP_DOTS);
		$it  = new RecursiveIteratorIterator($dir,RecursiveIteratorIterator::SELF_FIRST);
		$it->setMaxDepth(0);
		$dirs = "";
		$files = "";
		
		//Add backup link
		$parent = substr($_GET['base'],0,strrpos($_GET['base'],'/'));
		$dirs .= "<a href='dir.php?base=" . $parent . "' ><img src=\"../icons/folder.png\"/> ..</a><br>";
		
		// Basic loop displaying different messages based on file or folder
		foreach ($it as $fileinfo) {
			if ($fileinfo->isDir()) {
				$dirs .= "<a href=\"dir.php?base=" . $_GET['base'] . '/' . $fileinfo->getFilename() . "\"><img src=\"../icons/folder.png\"/>" . $fileinfo->getFilename() . "</a><br>";
			} elseif ($fileinfo->isFile()) {
				if(isset($_ALIAS)){
					$url = substr($fileinfo->__toString(),strrpos($fileinfo->__toString(),$_ALIAS));
					$files .= "<a href=\"". $url . "\"><img src=\"../icons/page_white.png\"/>" . $fileinfo->getFilename() . "</a><br>";
				}else{
					$files .= "<a href=\"". $_URL . $fileinfo->__toString() . "\"><img src=\"../icons/page_white.png\"/>" . $fileinfo->getFilename() . "</a><br>";
				}
				
			}
		}
		echo $dirs . $files;
	?>
	</bodY>
</html>