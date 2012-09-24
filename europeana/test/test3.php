<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>European Test 3</title>
        
	</head>

	<body>
    	<script language="javascript" src="europeanSearchV1.js"></script>
        <p id="searchStatusP">[searchStatus]</p>
        <script language="javascript">
			var int=self.setInterval(function(){checkSearchStatus()},1000);
			function checkSearchStatus() {
				if( document.getElementById("searchStatusP").textContent != searchStatus )
					document.getElementById("searchStatusP").textContent = searchStatus;
			}
		</script>
    
	    <script language="javascript" src="theNewAesthetic.js"></script>
    	<script language="javascript">
			sendEuropeanaRequest("lemon");
			
			var isSearchFinished = false;
			var isFirstDrawLoop = true;
			
			var images = new Array();
			var imageNewName = new Array();
			
			var int=self.setInterval(function(){renderLoop()},1000);
			function renderLoop() {
				if( !isSearchFinished ) {
					if( searchStatus.indexOf("finished") >= 0 )
						isSearchFinished = true;
				} else if( isFirstDrawLoop ) {
					// search is finished
					// DRAW ONCE
					NA.bg();
					
					for( x in imageList ) {
						imageNewName[x] = imageList[x].enclosure.substring( imageList[x].enclosure.lastIndexOf("/") + 1, imageList[x].enclosure.length );
						
						var query = "moveImage.php?src=" + imageList[x].enclosure + "&dest=" + imageNewName[x];
						var http_request = new XMLHttpRequest();
						http_request.open("GET", query, true);
						http_request.onreadystatechange = function () {
					   		var done = 4, ok = 200;
						    if (http_request.readyState == done && http_request.status == ok) {
								images[x] = new Image();
								images[x].src = "/tmp/" + imageNewName[x];
								images[x].addEventListener('load', drawImage(x));
						    }
						};
						http_request.send(null);
						
						
					}
					isFirstDrawLoop = false;
				}
				// else { //continuous drawing }
			}
			
			function drawImage( num ) {
				var x = Math.random()*(canvas.width-images[num].width);
				var y = Math.random()*(canvas.height-images[num].height);
				ctx.drawImage(images[num],x,y);
				//ctx.drawImage(images[num],0,0);
			}
			
		</script>
	</body>
</html>