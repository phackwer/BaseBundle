/**
 * Arquivo responsável pelo JS GERAL
 *
 * Copyright(c) Todos os direitos reservados
 */

if (typeof console == 'undefined') console = { log: function() {} };

var app = {
    init: function()
    {
        if( $( ".logar" ).length )
            app.login();

        if( $( ".area_print" ).length )
            app._print();
        
        if( $( "#myTab" ).length )
            app.aba();

        if( $( ".date" ).length )
            app.date();

        if( $( ".acordeon" ).length )
            app.acordeon();
        
        $('.btn-navbar').filter('.select').click(function(){
            if ($(window).innerWidth() > 980) {
            	$('.nav-collapse').find('.dropdown-submenu').find('.f-dropdown-menu').addClass('dropdown-menu');
                $('.nav-collapse').find('.dropdown-submenu').find('.f-dropdown-menu').removeClass('f-dropdown-menu');
                $('.navbar').filter('.nav').css({'width':''});
            }
            else{
            	$('.nav-collapse').find('.dropdown-submenu').find('.dropdown-menu').addClass('f-dropdown-menu');
                $('.nav-collapse').find('.dropdown-submenu').find('.f-dropdown-menu').removeClass('dropdown-menu');
                $('.navbar').filter('.nav').css({'width':'100%'});
                if ($('.navbar-responsive-collapse').filter('.in').size()) {
                	$('#BoxMenuPrincipal').removeClass("nf-nav");
                } else {
                	$('#BoxMenuPrincipal').addClass("nf-nav");
                }
            }
        });
        
        $(window).bind('resize', function() {
        	if ($(window).innerWidth() > 980) {
        		$('.nav-collapse').find('.dropdown-submenu').find('.f-dropdown-menu').addClass('dropdown-menu');
                $('.nav-collapse').find('.dropdown-submenu').find('.f-dropdown-menu').removeClass('f-dropdown-menu');
        		$('.navbar').filter('.nav').css({'width':''});
        		$('#BoxMenuPrincipal').removeClass("nf-nav");
        	}
        	else{
        		$('.nav-collapse').find('.dropdown-submenu').find('.dropdown-menu').addClass('f-dropdown-menu');
                $('.nav-collapse').find('.dropdown-submenu').find('.f-dropdown-menu').removeClass('dropdown-menu');
        		$('.navbar').filter('.nav').css({'width':'100%'});
        		if ($('.navbar-responsive-collapse').filter('.in').size()) {
                	$('#BoxMenuPrincipal').removeClass("nf-nav");
                } else {
                	$('#BoxMenuPrincipal').addClass("nf-nav");
                }
        	}
            $('.grid').setGridWidth(10);
            var width = $('.jqGrid_container').width();
            $('.grid').setGridWidth(width);
            
            var sectionHeight = $(window).innerHeight() - ($('.header').outerHeight() + $('#BoxRodapePrincipal').outerHeight());
            
            $('.section').css({'min-height':sectionHeight});
        });
        
        $(window).scroll(function () {
            if ($(this).scrollTop() > 114) {
            	$('#BoxMenuPrincipal').removeClass("BoxMenuPrincipal");
                $('#BoxMenuPrincipal').addClass("f-nav");
                
            	$('.nav-collapse').addClass("f-nav-collapse");
            	
            	if ($('.nav-collapse').height() > 40) {
                	$('.nav-collapse').height($(window).innerHeight() - 40);
            	}
                
            } else {
                $('#BoxMenuPrincipal').removeClass("f-nav");
                $('.nav-collapse').removeClass("f-nav-collapse");
                $('#BoxMenuPrincipal').addClass("BoxMenuPrincipal");
            }
        });
        
    }
};

app.init();

function removeItem()
{
	var grandPa = $(this).parent().parent();
	if (grandPa.children().length > 1) {

		//Renomeia o campo id do objeto para idDel, marcando para remoção do lado do server
		var idField = $(this).parent().find('input:hidden');
		if(idField.val())
		{
	     	var name = idField.attr('name');
	     	idField.attr('name', name.replace('[id]','[idDel]') );
			$(this).parent().hide();
		}
		else {
			$(this).parent().remove();
		}
		//se tiver o botão de plus, ele deve ser movido para o último item da listagem
		var plus = $(this).parent().find('.icon-plus');
		if (plus) {
			plus.insertAfter(grandPa.find('.icon-minus').last());
			if(!idField.val()) {
				plus.click(addItem);
			}
		}
		//se tiver conteúdo no label, tem que manter
		var label = $(this).parent().find('label');
		if (label.html() != '&nbsp;&nbsp;&nbsp;') {
			grandPa.find('span:visible').first().find('label').html(label.html());
		}
	} else {
		$(this).parent().find('input').val('');
		$(this).parent().find('textarea').val('');
		$(this).parent().find('select').find(':selected').prop('selected', false);
		$(this).parent().find('select').find('option').first().prop('selected', true);
		alert('É preciso ter um item pelo menos. O campo será deixado em branco para exclusão no banco de dados.')
	}

	$('.icon-minus').each(hideMinus);
}

function removeMultiItem()
{
	var grandPa = $(this).parent().parent().parent();
	if (grandPa.children().length > 2) {

		//Renomeia o campo id do objeto para idDel, marcando para remoção do lado do server
		var idField = $(this).parent().find('input:hidden');
		if(idField.val())
		{
	     	var name = idField.attr('name');
	     	idField.attr('name', name.replace('[id]','[idDel]') );
			$(this).parent().parent().hide();
		}
		else {
			$(this).parent().parent().remove();
		}
		//se tiver o botão de plus, ele deve ser movido para o último item da listagem
		var plus = $(this).parent().find('.icon-plus');
		if (plus) {
			plus.insertAfter(grandPa.find('.icon-minus').last());
			if(!idField.val()) {
				plus.click(addMultiItem);
			}
		}
		//se tiver conteúdo no label, tem que manter
		var label = $(this).parent().find('label');
		if (label.html() != '&nbsp;&nbsp;&nbsp;') {
			grandPa.find('span:visible').first().find('label').html(label.html());
		}
		//Se tiver h5, tem que renumerar
		grandPa.find('h5:visible').each(reindexMultiNumberLabel);
	} else {
		$(this).parent().find('input').val('');
		$(this).parent().find('textarea').val('');
		$(this).parent().find('select').find(':selected').prop('selected', false);
		$(this).parent().find('select').find('option').first().prop('selected', true);
		alert('É preciso ter um item pelo menos. O campo será deixado em branco para exclusão no banco de dados.')
	}

	$('.icon-minus').each(hideMultiMinus);
}

function addNumber()
{
	var numberPattern = /\d+/g;
	
	var number = $(this).parent().parent().children().length;
	if ($(this).prop('required')) {
		var number = $(this).parent().parent().parent().children().length;
		$.each($(this).data('events'), function(i, e) {
		    console.log(i, e);
		});
	}
		
	
	if ($(this).attr('name')) {
		$(this).attr('name', $(this).attr('name').replace( numberPattern , number ))
	}
	if ($(this).prop('id')) {
		$(this).prop('id', $(this).prop('id').replace( numberPattern , number ))
	}
	if ($(this).attr('targetId')) {
		$(this).attr('targetId', $(this).attr('targetId').replace( numberPattern , number ))
	}
}

function addMultiNumber()
{
	var numberPattern = /\d+/g;
	var number = $(this).parent().parent().parent().children().length -1;
	
	if ($(this).attr('name')) {
		$(this).attr('name', $(this).attr('name').replace( numberPattern , number ))
	}
	if ($(this).prop('id')) {
		$(this).prop('id', $(this).prop('id').replace( numberPattern , number ))
	}
	if ($(this).attr('targetId')) {
		$(this).attr('targetId', $(this).attr('targetId').replace( numberPattern , number ))
	}
}

function reindexMultiNumberLabel(index)
{
	var numberPattern = /\d+/g;
	$(this).html($(this).html().replace( numberPattern , index ))
}

function addItem() {
	unmask();
	//clona
    var newFill = $(this).parent().clone(true, true);
    newFill.find('input').val('');
    newFill.find('textarea').val('');
    $(this).parent().find('select').find(':selected').prop('selected', false);
	$(this).parent().find('select').find('option').first().prop('selected', true);
    newFill.insertAfter($(this).parent());
    //renomeia todos adicionando um valor ao número do contador
    newFill.find('input').each(addNumber);
    newFill.find('textarea').each(addNumber);
    newFill.find('select').each(addNumber);
    //esconde o label duplicado
    newFill.find('label').html("&nbsp;&nbsp;&nbsp;").addClass('visible-desktop');
    //retira o botão de adição do clone
    newFill.find('.icon-plus').remove();
    //define comportamento do minus
    newFill.find('.icon-minus').show();
//    newFill.find('.icon-minus').click(removeItem);
//    newFill.find('.autocomplete').each(autoCompleteField);
    $(this).insertAfter(newFill.find('.icon-minus'));

    $('.icon-minus').each(hideMinus);
    mask();
};

function addMultiItem() {
	unmask();
	//clona
    var newFill = $(this).parent().parent().clone(true, true);
    newFill.find('input').val('');
    newFill.find('textarea').val('');
    $(this).parent().find('select').find(':selected').prop('selected', false);
	$(this).parent().find('select').find('option').first().prop('selected', true);
    newFill.insertAfter($(this).parent().parent());
    //renomeia todos adicionando um valor ao número do contador
    $(this).parent().parent().parent().find('h5:visible').each(reindexMultiNumberLabel);
    newFill.find('input').each(addMultiNumber);
    newFill.find('textarea').each(addMultiNumber);
    newFill.find('select').each(addMultiNumber);
    //retira o botão de adição do clone
    newFill.find('.icon-plus').remove();
    //define comportamento do minus
    newFill.find('.icon-minus').show();
//    newFill.find('.icon-minus').click(removeMultiItem);
//    newFill.find('.autocomplete').each(autoCompleteField);
    newFill.find('.validationSpan').find('.error').remove();
    newFill.find('.error').removeClass('error')
    $(this).insertAfter(newFill.find('.icon-minus'));

    $('.icon-minus').each(hideMultiMinus);
    mask();
};

function hideMinus(index)
{
	var visibleChildren = $(this).parent().parent().find('span:visible:not(.validationSpan)').length
	if (visibleChildren == 1) {
		$(this).hide();
	}
	else {
		$(this).show();
	}
}

function hideMultiMinus(index)
{
	var visibleChildren = $(this).parent().parent().parent().find('ul:visible').length;
	if (visibleChildren == 2) {
		$(this).hide();
	}
	else {
		$(this).show();
	}
}

function autoCompleteField()
{
	var callBack = $(this).attr('callBack') ? eval($(this).attr('callBack')) : null;
	
	var exceptId = $(this).attr('exceptId');
	if (exceptId) {
		if (exceptId.indexOf('#') == -1) {
			exceptId = '#' + exceptId;
		}
		
		exceptId = $(exceptId).val();
	} else {
		exceptId = null
	}
	
	$(this).simpleAutoComplete
	(
		$(this).attr('sourcePath'),
		{
			autoCompleteClassName: 'autocomplete',
			selectedClassName: 'sel',
			attrCallBack: 'rel',
			identifier: exceptId
		},
		callBack
	);
}

function transformToReadForm() {

    $('input').attr('readOnly', true);
    $('textarea').attr('readOnly', true);
    $('select').attr('readOnly', true);
    $('input').attr('disabled', true);
    $('textarea').attr('disabled', true);
    $('select').attr('disabled', true);

    //some com botões de ação
    $('.icon-plus').remove();
    $('.icon-minus').remove();
    $('.icon-edit').remove();
    $('.icon-trash').remove();
    $('.addFile').remove();
    $('#save_bt').remove();
    hrefRemove = $('.foto').parent();
    $('.foto').each(function(){$(this).insertBefore($(this).parent())});
    hrefRemove.each(function(){$(this).remove()});
    
    $('#cancel_bt').html('Voltar');
    
    $('#cancel_bt').unbind('click',cancelData);
    $('#cancel_bt').click(function(){window.history.back()});
}

function cancelData()
{
	if (confirm('Tem certeza que deseja descartar os dados?')){
		window.history.back();
	}
}

$(document).ready(function() {
	//Funções para edição de objetos
	$('.single-line').parent().find('.icon-minus').click(removeItem);
	$('.single-line').parent().find('.icon-plus').click(addItem);
	$('.single-line').parent().find('.icon-minus').each(hideMinus);
	

	$('.multi-line').parent().find('.icon-minus').click(removeMultiItem);
	$('.multi-line').parent().find('.icon-plus').click(addMultiItem);
	$('.multi-line').parent().find('.icon-minus').each(hideMultiMinus);
	
	//Funções autocomplete
	$('.autocomplete').each(autoCompleteField);
	$('form').validate();
	
	$('#cancel_bt').click(cancelData);
});