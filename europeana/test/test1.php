<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Europeana Test 1</title>
        <script language="javascript" src="theNewAesthetic.js"></script>
		<script language="javascript">
			var imageUrlList = null;
			var returnedJSON = {};
			function sendEuropeanaRequest() {
				var  searchTerms = document.getElementById("searchTerms");
				var  jsonText = document.getElementById("jsonText");
				jsonText.textContent = "Performing search of '" + searchTerms.value + "' ...";
				// to do: validate search
				var url = "http://api.europeana.eu/api/opensearch.json?searchTerms="+searchTerms.value+"&wskey=ROAMXHDBDQ";
				var http_request = new XMLHttpRequest();
				http_request.open("GET", url, true);
				http_request.onreadystatechange = function () {
		    		var done = 4, ok = 200;
				    if (http_request.readyState == done && http_request.status == ok) {
						returnedJSON = JSON.parse( http_request.responseText );
						if( returnedJSON == null )
							alert( "no JSON returned!" );
						jsonText.textContent = "Returned!";
						parseResponse();
				    }
				};
				http_request.send(null);
			}
			
			function parseResponse() {
				var resultDiv = document.getElementById("resultDiv");
				resultDiv.innerHTML = "<h1>Result</h1><p>" + returnedJSON.description + "</p>";
				for( x in returnedJSON.items ) {
					var obj = returnedJSON.items[x];
					var type = obj['europeana:type'];
					//if( type == "IMAGE" ) {
					if( returnedJSON.items[x].enclosure.indexOf(".jpg") > -1
							|| returnedJSON.items[x].enclosure.indexOf(".png") > -1
							|| returnedJSON.items[x].enclosure.indexOf(".gif") > -1
							|| returnedJSON.items[x].enclosure.indexOf(".tif") > -1
							|| returnedJSON.items[x].enclosure.indexOf(".jpeg") > -1 ) {
						resultDiv.innerHTML += "<p>Title: " + returnedJSON.items[x].title + "</p>";
						resultDiv.innerHTML += "<a href='" + returnedJSON.items[x].link + "'>[[[LINK]]]</a>";
						resultDiv.innerHTML += "<img src='" + returnedJSON.items[x].enclosure + "'/>";
					}
				}
			}
			
		</script>
	</head>

	<body>
    	<p id="jsonText">[please search]</p>
    	<form name="Send Request to Europeana" action="" method="get">
        	<p> Enter search term(s): </p>
            <input type="text" name="textBox_searchTerms" value="Art" id="searchTerms"/>
        	<input type="button" name="button_sendRequest" value="Send Request" onClick="sendEuropeanaRequest();"/>
        </form>
        <div id="resultDiv"></div>
	</body>
</html>
