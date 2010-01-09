function createRequestObject() {
	var ro;
	var browser = navigator.appName;
	if(browser == "Microsoft Internet Explorer"){
		ro = new ActiveXObject("Microsoft.XMLHTTP");
	}else{
		ro = new XMLHttpRequest();
	}
	return ro;
}

var http = createRequestObject();

/* AJAX call. Uses post to allow for larger datasets */
function analyzeText() {
	
	var text = document.getElementById("words").value;
	var ignore = true; //document.getElementById("ignoreCommon").checked;
	
	var params = 'ignore=' + ignore + '&text=' + text;
	http.open("POST",'/wordalyzer/analyze.php', true);

	//Send the proper header information along with the request
	http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	http.setRequestHeader("Content-length", params.length);
	http.setRequestHeader("Connection", "close");

	http.onreadystatechange = handleResponse;
	http.send(params);
}

function showError(msg){
	document.getElementById("error").style.display = "block";
	document.getElementById("display").style.display = "none";
	document.getElementById("error").innerHTML = msg;
}

function hideError(){
	document.getElementById("error").style.display = "none";
	document.getElementById("display").style.display = "block";
}

/** function to print the tag cloud 
 *	@param data the xml structure containing the frequency data
 */
function printCloud(data){
	
	// # of things to print 
	var limit = 5;
	
	// Max intensity color
	var minR = 255;
	var minG = 40;
	var minB = 40;
	
	var maxR = 255;
	var maxG = 199
	var maxB = 199
	
	// Max Padding
	var maxPadding = 15;   
	var minPadding = 3;
	
	var freq = data.getElementsByTagName('item');	

	var str = "";
	var max = parseFloat(freq[0].getAttribute("hits"));

	// only print 8 things
	var n = (limit < freq.length) ? limit : freq.length;

	for(var i=0;i<n;i++){
		
		var title = freq[i].getAttribute("title");
		var hits = freq[i].getAttribute("hits");
		
		var p = parseFloat(hits) / max;
		
		// Color space transform
		var color = "rgb(" + Math.floor((1-p)*(maxR-minR) + minR) 
					+ "," + Math.floor((1-p)*(maxG-minG) + minG) 
					+ "," + Math.floor((1-p)*(maxB-minB) + minB) + ")";
		
		var padding = (maxPadding-minPadding)*p +minPadding;
		
		str +=	"<span style=\"padding:" + padding
			+	"px;background-color:" + color + "\">" 
			+	"<strong>" + title + "</strong><em>(" + hits + ")</em></span>";		
	}

	var el = document.getElementById("cloudHistogram").innerHTML = str;	 
}


/** Prints the distribution table 
 *	@param data the xml structure containing the frequency data
 */
function printDistribution(data){

	var str = "";
	
	var freq = data.getElementsByTagName('item');
	var max = parseFloat(freq[0].getAttribute("hits"));
	
	str += "<table>";
	
	   
	for(var i=0;i<freq.length;i++){
		
		var title = freq[i].getAttribute("title");
		var hits = freq[i].getAttribute("hits");
	
		var p = parseFloat(hits) / max;
		p = p.toFixed(2);
		p *=100;
		
		str += "<tr><td class=\"word\">" + title + "</td><td>" 
			+ "<span class=\"pink\" style=\"width:" + p  + "%;\">" + hits +"</span></td></tr>";	
	}
	
	str += "</table>";

	var el = document.getElementById("distribution").innerHTML = str;  
}

/** Prints the uniqueness chart */
function printUnique(unique, total){
	
	var size= 600; // Size of the area in pixels
	var unique = parseFloat(unique);
	var total = parseFloat(total);
	var boring = total - unique;
	
	var pUnique = (unique / total);
	var pBoring = 1 - pUnique;
	
	var str = 	"<p><strong>" + total + " </strong> total words with <strong>" 
				+ unique + "</strong> unique words. <strong>" 
				+ (pUnique*100).toFixed(2) + "%</strong> unique.</p>";
				
	str += "<span class=\"pink\"style=\"width:" + pUnique*size  + "px;\">" + unique + "</span>";
	str += "<span class=\"gray\"style=\"width:" + pBoring*size  + "px;\">" + boring + "</span>";
	var el = document.getElementById("unique").innerHTML = str;	 
}

/** AJAX callback, parses the return data structure and visualizes the results 
 */
function handleResponse() {
	if(http.readyState == 4){
		var response = http.responseText;

		var parser = new DOMParser();
		var xmlDoc = parser.parseFromString(response,"text/xml");
		
		var root = xmlDoc.getElementsByTagName('packet')[0];		
		
		if(root){
		
			// make sure there were no errors
			var ok = root.getElementsByTagName('status')[0];
			
			if(!ok.firstChild){
				hideError();
				
				// Print the viz
				var data = xmlDoc.getElementsByTagName('data')[0];								
				printCloud(data);
				printDistribution(data);

				var freq = xmlDoc.getElementsByTagName('freq')[0].lastChild.nodeValue;
				var total = xmlDoc.getElementsByTagName('total')[0].lastChild.nodeValue;
				
				printUnique(freq,total);
										
			}
			else{
				showError("Fail: " + ok.firstChild.nodeValue); 
			}
		}
		else{
			showError("Fail: I had a problem communicating with my server :(<br/>" + response );		
		}
	}
}
