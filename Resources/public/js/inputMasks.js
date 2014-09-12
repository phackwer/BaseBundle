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
     (key >= 37 && key <= 40) ||
     (key >= 48 && key <= 57) ||
     (key >= 96 && key <= 105));
 }

function mask() {
	
    $('.dateBR').datepicker({
        changeMonth: true,
        changeYear: true
    }).mask('99/99/9999').keypress( function (e) {
    	console.log($(this).val());
//    	String.fromCharCode(e.charCode)
    });

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