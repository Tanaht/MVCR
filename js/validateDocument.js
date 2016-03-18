var w3c_uri = "http://validator.w3.org/nu/";

/*
var req = new XMLHttpRequest();
req.open('POST', w3c_uri + '?out=json'); 
req.setRequestHeader("Content-type", "multipart/form-data");
//req.setRequestHeader("Content-type", "text/html; charset=utf-8");
req.send(document);
if(req.status == 200)
  	console.log(req.responseText);
else{
	console.log("Validator request error");
	console.log(req.responseText);
}
*/

messages = messages.messages;
for(var i=0 ; i < messages.length ; i++) {
	var m = messages[i];
	if(m.type == "error") {
		console.error("[" + m.firstLine + ":" + m.firstColumn + "][" + m.lastLine + ":" + m.lastColumn + "]" + m.message);
	}
	else {
		console.log("[" + m.firstLine + ":" + m.firstColumn + "][" + m.lastLine + ":" + m.lastColumn + "]" + m.message);	
	}
}