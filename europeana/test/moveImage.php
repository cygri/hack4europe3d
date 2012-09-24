<?php
		$file_source = $_GET['src'];
		$file_target = "/tmp/" + $_GET['dest'];
	  // prepare
      $file_source = str_replace(' ', '%20', html_entity_decode($file_source)); // fix url format
      if (file_exists($file_target)) chmod($file_target, 0777); // add write permission
 
      // opne files
	  $noErrors = true;
      if (($rh = fopen($file_source, 'rb')) === FALSE) {
		  $noErrors = false; 
		  echo "Error opening source!";
	  }
      if (($wh = fopen($file_target, 'wb')) === FALSE) {
		  $noErrors = false; 
		  echo $file_target;
	  }
 
      // read & write
	  if( $noErrors ) {
	      while (!feof($rh))
    	  {
        	    if (fwrite($wh, fread($rh, 1024)) === FALSE)
            	{
                	  // unable to write to file, possibly
	                  // because the harddrive has filled up
    	              fclose($rh);
        	          fclose($wh);
            	      //return false;
					  echo "Unable to write to file!";
					  break;
	            }
    	  }
		  // close files
	      fclose($rh);
    	  fclose($wh);
	  }
       
      //return true;
?>