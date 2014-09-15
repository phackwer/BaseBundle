function timeBlur()
{
    if ($(this).val() != '' && $(this).val().search(/^([0-1][0-9]|[2][0-3]):([0-5][0-9])$/) == -1){
        $(this).val('');
        $("#dialog-msg").dialog({
            resizable: false,
            height: 200,
            width: 300,
            modal: true,
            close: false,
            buttons: {
                "Fechar": function () {
                    $(this).dialog("close");
                }
            }
        }).html('Favor informar valores entre 00:00 e 23:59.');
    }
}

function onlyIntegers(e) 
{
	if (e.ctrlKey)
		return false;
     var key = e.charCode || e.keyCode || 0;
     // allow backspace, tab, delete, arrows, numbers and keypad numbers ONLY
     return (
     key == 8 || 
     key == 9 ||
     key == 46 ||
     key == 16 ||
     (key >= 35 && key <= 40) ||
     (key >= 48 && key <= 57) ||
     (key >= 96 && key <= 105));
 }

function numOnKeyDown(keyCode){
//	console.log(keyCode);
	switch (keyCode)
	{
		case 48:
			return 0;
		case 49:
			return 1;
		case 50:
			return 2;
		case 51:
			return 3;
		case 52:
			return 4;
		case 53:
			return 5;
		case 54:
			return 6;
		case 55:
			return 7;
		case 56:
			return 8;
		case 57:
			return 9;
		case 96:
			return 0;
		case 97:
			return 1;
		case 98:
			return 2;
		case 99:
			return 3;
		case 100:
			return 4;
		case 101:
			return 5;
		case 102:
			return 6;
		case 103:
			return 7;
		case 104:
			return 8;
		case 105:
			return 9;
	}
}

function mask() {
	
    $('.dateBR').datepicker({
        changeMonth: true,
        changeYear: true
    }).keydown(function(e) {
    	var key = e.keyCode;
    	if (key == 8 || 
		     key == 9 ||
		     key == 46 ||
		     (key >= 35 && key <= 40)
		     ){
    		return true;
    	}
    	var currNumber = numOnKeyDown(key);
    	
    	if (isNaN(currNumber)){
    		e.preventDefault();
    		return false;
    	}
    	
    	var data = ($(this).val().replace(/_/g,'')).split('/');
        var dia = data[0];
        var mes = data[1];
        var ano = data[2];
        
        if (((dia + '').length) < 2){
			dia = parseInt(dia + ''  + currNumber);
		}

	    if (((mes + '').length) < 2){
	    	mes = parseInt(mes + ''  + currNumber);
		}

	    if (((ano + '').length) < 4){
	    	ano = parseInt(ano + ''  + currNumber);
		}
        
        if (isNaN(dia) || dia > 31) {
        	e.preventDefault();
    	};
        if (isNaN(mes) || mes > 12) {
        	e.preventDefault();
    	};
        if (!isNaN(mes) && (mes == 4 || mes == 6 || mes == 9 || mes == 11) && dia == 31){
        	e.preventDefault();
        }
        if (!isNaN(mes) && mes == 2 && (dia > 29 || ( !isNaN(ano) && (ano + '').length == 4 && (dia == 29 && ano % 4 != 0)))){
        	e.preventDefault();
        };
        if (data[0] + '' + currNumber == '00' || data[1] + '' + currNumber == '00'){
        	e.preventDefault();
        }
    	
    }).mask('99/99/9999');

    $('.date_mmyyyy').monthpicker({
        startYear: 1700,
        finalYear: 2025,
        openOnFocus: false
    }).mask('99/9999');

    $('.bt_date').bind('click', function () {
        $('.date_mmyyyy').monthpicker('show');
    });

    $.mask.definitions['H'] = "[0-2]";
    $.mask.definitions['h'] = "[0-9]";
    $.mask.definitions['M'] = "[0-5]";
    $.mask.definitions['m'] = "[0-9]";

    $('.time').mask('Hh:Mm');
    $('.time').blur(timeBlur);

    $('.money').maskMoney({ allowNegative: true, thousands:'.', decimal:','});
    $('.float').maskMoney({ allowNegative: true, thousands:'', decimal:'.'});
    $('.float-br').maskMoney({ allowNegative: true, thousands:'', decimal:','});
    
	$('.phone').mask('(99)(99) 9999-9999?9');
	$('.cpf').mask('999.999.999-99');
	$('.cnpj').mask('99.999.999/9999-99');
	$('.cep').mask('99.999-999');
	$('.integer').keydown( function (e) 
	{
		if (e.ctrlKey)
			return false;
	     var key = e.charCode || e.keyCode || 0;
	     // allow backspace, tab, delete, arrows, numbers and keypad numbers ONLY
	     return (
	     key == 8 || 
	     key == 9 ||
	     key == 46 ||
	     (key >= 37 && key <= 40) ||
	     (key >= 48 && key <= 57) ||
	     (key >= 96 && key <= 105));
	 });
}

function unmask() {
	
    $('.dateBR').unmask();

    $('.date_mmyyyy').unmask();

    $('.time').unmask();
    $('.time').unbind('blur', timeBlur);

    $('.money').maskMoney({ allowNegative: true, thousands:'.', decimal:','});
    $('.float').maskMoney({ allowNegative: true, thousands:'', decimal:'.'});
    $('.float-br').maskMoney({ allowNegative: true, thousands:'', decimal:','});
    
	$('.phone').unmask();
	$('.cpf').unmask();
	$('.cnpj').unmask();
	$('.cep').unmask();
	$('.integer').unbind('keydown', onlyIntegers);
}

$(document).ready(function ($){
	mask()
})