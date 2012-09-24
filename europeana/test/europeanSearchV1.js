var imageList = new Array();
var searchStatus = "Please wait a few seconds...";
			
var returnedJSON = {};

function sendEuropeanaRequest(searchTerms) {
	imageList = new Array();
	searchStatus = "Starting search for '" + searchTerms + "' ...";
	var url = "http://api.europeana.eu/api/opensearch.json?searchTerms="+searchTerms+"&wskey=ROAMXHDBDQ";
	var http_request = new XMLHttpRequest();
	http_request.open("GET", url, true);
	http_request.onreadystatechange = function () {
   		var done = 4, ok = 200;
	    if (http_request.readyState == done && http_request.status == ok) {
			returnedJSON = JSON.parse( http_request.responseText );
			if( returnedJSON == null )
				searchStatus = "JSON parse failed!";
			else
				searchStatus = "Search complete, parsing...";
			parseResponse();
	    }
	};
	http_request.send(null);
}
		
function parseResponse() {
	var numItems = 0;
	for( x in returnedJSON.items ) {
		var obj = returnedJSON.items[x];
		var type = obj['europeana:type'];
		//if( type == "IMAGE" ) {
		if( returnedJSON.items[x].enclosure != null ) {
			if( returnedJSON.items[x].enclosure.indexOf(".jpg") > -1
					|| returnedJSON.items[x].enclosure.indexOf(".png") > -1
					|| returnedJSON.items[x].enclosure.indexOf(".gif") > -1
					|| returnedJSON.items[x].enclosure.indexOf(".tif") > -1
					|| returnedJSON.items[x].enclosure.indexOf(".jpeg") > -1 ) {
				imageList[numItems] = obj;
				numItems++;
			}
		}
	}
	searchStatus = "Search finished, got " + numItems + " items";
}