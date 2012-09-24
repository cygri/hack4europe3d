<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>European Test 2</title>
        
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
						images[x] = new Image();
						images[x].src = imageList[x].enclosure;
						images[x].addEventListener('load', drawImage(x));
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