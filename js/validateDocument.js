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

var htmlValidatorPrefix = "[HTML_VALIDATOR]";

messages = messages.messages;

if(messages.length == 0) {
	console.info(htmlValidatorPrefix + "La syntaxe html est parfaite !");
}
else{	
	for(var i=0 ; i < messages.length ; i++) {
		var m = messages[i];
		if(m.type == "error") {
			console.error(htmlValidatorPrefix + "At lines: [" + (m.firstLine != undefined ? m.firstLine : m.lastLine) + ":" + m.firstColumn + "][" + m.lastLine + ":" + m.lastColumn + "] => \n" + m.extract + "\ninfo: " + m.message);
		}
		else if(m.type.indexOf("warn") != -1){
			console.warn(htmlValidatorPrefix + "At lines: [" + (m.firstLine != undefined ? m.firstLine : m.lastLine) + ":" + m.firstColumn + "][" + m.lastLine + ":" + m.lastColumn + "] => \n" + m.extract + "\ninfo: " + m.message);	
		}
		else {
			console.info(htmlValidatorPrefix + "At lines: [" + (m.firstLine != undefined ? m.firstLine : m.lastLine) + ":" + m.firstColumn + "][" + m.lastLine + ":" + m.lastColumn + "] => \n" + m.extract + "\ninfo: " + m.message);	
		}
	}
}