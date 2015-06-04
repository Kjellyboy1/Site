function geef(doen) {
    var nBericht;
	
    var oBericht = document.fr.text.value;
    if (doen == "url") {
		      var URL = prompt("Voer de link in naar de website:", "http://");
		      var titel = prompt("Plaats hier de tekst die je als link wil gebruiken", "");
		      var Code = '<a target="_blank" href="'+URL+'">'+titel+'</a>';
		      nBericht = oBericht+Code;
		      document.fr.text.value=nBericht;
		      document.fr.text.focus();
		      return;
    }
	
	if (doen == "email") {
		      var MAIL = prompt("Voer het email adres in:", "");
			  var NAAM = prompt("Naar wie? (kan naam of e-mail adres zijn)", "");
		      var Code = '<a href="mailto:'+MAIL+'">'+NAAM+'</a>';
		      nBericht = oBericht+Code;
		      document.fr.text.value=nBericht;
		      document.fr.text.focus();
		      return;
    } 

    if (doen == "b") {
		      var tonen = prompt("Voer de tekst in die je vet wil zetten:", "");
			  var Code = "<b>"+tonen+"</b>";
			  nBericht = oBericht+Code;
			  document.fr.text.value=nBericht;
		      document.fr.text.focus();
			  return;
	}
	if (doen == "i") {
			  var tonen = prompt("Voer de tekst in die je cursief wil zetten:", "");
			  var Code = "<i>"+tonen+"</i>";
			  nBericht = oBericht+Code;
			  document.fr.text.value=nBericht;
		      document.fr.text.focus();
			  return;
	}    
	if (doen == "u") {
			  var tonen = prompt("Voer de tekst in die je wil onderstrepen:", "");
			  var Code = "<u>"+tonen+"</u>";
			  nBericht = oBericht+Code;
			  document.fr.text.value=nBericht;
		      document.fr.text.focus();
			  return;
	}
	
	var fBericht = document.fr.textfoto.value;
    if (doen == "url_onder") {
		      var URL = prompt("Voer de link in naar de website:", "http://");
		      var titel = prompt("Plaats hier de tekst die je als link wil gebruiken", "");
		      var Code = '<a target="_blank" href="'+URL+'">'+titel+'</a>';
		      nBericht = fBericht+Code;
		      document.fr.textfoto.value=nBericht;
		      document.fr.textfoto.focus();
		      return;
    }
	
	if (doen == "email_onder") {
		      var MAIL = prompt("Voer het email adres in:", "");
			  var NAAM = prompt("Naar wie? (kan naam of e-mail adres zijn)", "");
		      var Code = '<a href="mailto:'+MAIL+'">'+NAAM+'</a>';
		      nBericht = fBericht+Code;
		      document.fr.textfoto.value=nBericht;
		      document.fr.textfoto.focus();
		      return;
    } 

    if (doen == "b_onder") {
		      var tonen = prompt("Voer de tekst in die je vet wil zetten:", "");
			  var Code = "<b>"+tonen+"</b>";
		      nBericht = fBericht+Code;
		      document.fr.textfoto.value=nBericht;
		      document.fr.textfoto.focus();
			  return;
	}
	if (doen == "i_onder") {
			  var tonen = prompt("Voer de tekst in die je cursief wil zetten:", "");
			  var Code = "<i>"+tonen+"</i>";
		      nBericht = fBericht+Code;
		      document.fr.textfoto.value=nBericht;
		      document.fr.textfoto.focus();
			  return;
	}    
	if (doen == "u_onder") {
			  var tonen = prompt("Voer de tekst in die je wil onderstrepen:", "");
			  var Code = "<u>"+tonen+"</u>";
		      nBericht = fBericht+Code;
		      document.fr.textfoto.value=nBericht;
		      document.fr.textfoto.focus();
			  return;
	}	
}
