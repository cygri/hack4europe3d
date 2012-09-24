<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>European Test 5</title>
        
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
			var searchTermCount = 0;
			var searchTermList = new Array( "flower", "blue", "sculpture", "art", "yellow", "brown", "fire", "women", "heart", "red", "classical", "space", "hope", "white", "ireland", "eu", "war", "gun", "blood",	"seven", "march", "young", "brave", "zero", "number", "walk", "river", "grass", "purple", "men", "spring", "green", "winter", "wood" );
		
			var isSearchFinished = true;
			var isFirstDrawLoop = true;
			
			var int=self.setInterval(function(){loadNextImages()},5000);
			function loadNextImages() {
				if( isSearchFinished ) {
					isSearchFinished = false;
					isFirstDrawLoop = true;
					sendEuropeanaRequest(searchTermList[searchTermCount]);
					searchTermCount++;
					if( searchTermCount >= searchTermList.length )
						searchTermCount = 0;
				}
			}
			
			var images = new Array();
			var xVal = new Array();
			var yVal = new Array();
			var imageOn = new Array();
			var imageCount = 0;
			
			//NA.bg();
			// Store the current transformation matrix
			//ctx.save();
			// Use the identity matrix while clearing the canvas
			//ctx.setTransform(1, 0, 0, 1, 0, 0);
			//ctx.fillStyle="#FF0000";
			//ctx.clearRect(0, 0, canvas.width, canvas.height);
			// Restore the transform
			//ctx.restore();
			
			var int=self.setInterval(function(){renderLoop()},100);
			function renderLoop() {
				if( !isSearchFinished ) {
					if( searchStatus.indexOf("finished") >= 0 )
						isSearchFinished = true;
				} else if( isFirstDrawLoop ) {
					var c = 0;
					for( n in imageList ) {
						xVal[imageCount] = Math.random()*(canvas.width)-200;
						yVal[imageCount] = Math.random()*(canvas.height)-200;
						imageOn[imageCount] = false;
						images[imageCount] = new Image();
						images[imageCount].src = imageList[n].enclosure;
						images[imageCount].addEventListener('load', drawImage(imageCount));
						imageCount++;
						c++;
					}
					isFirstDrawLoop = false;
				}
				//for( n in images ) {
				//	if( imageOn[n] )
				//		ctx.drawImage(images[n],xVal[n],yVal[n]);
				//}
				// text for search
				ctx.fillStyle="#FFFFFF";
				ctx.fillRect(90, 60, 550, 50);
				ctx.fillStyle="#000000";
				ctx.font="30px Arial";
				ctx.fillText(searchStatus,100,100);
			}
			
			function drawImage( num ) {
				imageOn[num] = true;
				ctx.drawImage(images[num],xVal[num],yVal[num]);
			}
			
		</script>
	</body>
</html>