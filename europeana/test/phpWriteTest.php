<?php
	$my_file = 'file.txt';
	$handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file); //open file for writing ('w','r','a')...
	$data = 'This is the data';
	echo fwrite($handle, $data);
	fclose($handle);
	
	$handle = fopen($my_file, 'r') or die('Cannot open file:  '.$my_file);;
	$newdata = fread($handle,filesize($my_file));
	echo $newdata;
	fclose($handle);
?>