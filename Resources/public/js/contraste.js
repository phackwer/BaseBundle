function setaCookieContraste(b)
{
	var a=new Date,c=a.getTime()+6048E5;
	a.setTime(c);
	extras  =  "expires="+a.toGMTString()+";";
	extras +=  "domain="+document.domain+";";
	extras +=  "path=/;";
	document.cookie="ativo"==b?"Contraste=Sim;"+extras:"Contraste=;"+extras;
}
function atribuirContraste()
{
	!0==$("body").hasClass("acessibilidade")?($("body").removeClass("acessibilidade"),setaCookieContraste("inativo")):($("body").addClass("acessibilidade"),setaCookieContraste("ativo"))
}
var results=document.cookie.match("(^|;) ?Contraste=([^;]*)(;|$)");
$(this).load(function(){null!=results&&"Sim"==results[2]&&atribuirContraste("ativo")});