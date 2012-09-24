<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>European Test 4</title>
        
	</head>

	<body>
    	<script language="javascript" src="europeanSearchV1.js"></script>
    	<script language="javascript">
			var style = document.createElement('style');
			style.type = 'text/css';
			  style.innerHTML = 'html{ height: 100% }body{ height: 100%; margin: 0; padding: 0 }';
			  document.getElementsByTagName('head')[0].appendChild(style);

			  //Create a canvas element [this one is used by .bg() & .glitchGif() ]
			  var canvas = document.createElement('canvas');
			  document.body.appendChild(canvas);
			  WIDTH = window.innerWidth,
			  HEIGHT = window.innerHeight,
			  canvas.height = HEIGHT;
			  canvas.width = WIDTH;
			  var ctx = canvas.getContext('2d');
			  canvas.style.position = 'absolute';
			  canvas.style.left = '0px';
			  canvas.style.top = '0px';
			  canvas.style.zIndex = '0';
			//<script language="javascript" src="theNewAesthetic.js">
			</script>
    	<script language="javascript">
			sendEuropeanaRequest("sculpture");
			
			var isSearchFinished = false;
			var isFirstDrawLoop = true;
			
			var images = new Array();
			var xVal = new Array();
			var yVal = new Array();
			var imageOn = new Array();
			
			//NA.bg();
			
			var int=self.setInterval(function(){renderLoop()},100);
			function renderLoop() {
				// Store the current transformation matrix
				ctx.save();
				// Use the identity matrix while clearing the canvas
				ctx.setTransform(1, 0, 0, 1, 0, 0);
				//ctx.fillStyle="#FF0000";
				ctx.clearRect(0, 0, canvas.width, canvas.height);
				// Restore the transform
				ctx.restore();
				
				// background
				//NA.bg();
				
				if( !isSearchFinished ) {
					if( searchStatus.indexOf("finished") >= 0 )
						isSearchFinished = true;
				} else if( isFirstDrawLoop ) {
					var c = 0;
					for( n in imageList ) {
						xVal[c] = Math.random()*(canvas.width);
						yVal[c] = Math.random()*(canvas.height);
						imageOn[c] = false;
						images[c] = new Image();
						images[c].src = imageList[c].enclosure;
						images[c].addEventListener('load', drawImage(c));
						c++;
					}
					isFirstDrawLoop = false;
				}
				else { 
					for( n in images ) {
						if( imageOn[n] )
							ctx.drawImage(images[n],xVal[n],yVal[n]);
					}
				}
				// text for search
				ctx.font="30px Arial";
				ctx.fillText(searchStatus,0,0);
			}
			
			function drawImage( num ) {
				imageOn[num] = true;
			}
			
		</script>
	</body>
</html>