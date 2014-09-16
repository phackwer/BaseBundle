/**
 * Arquivo responsável pelo JS GERAL
 *
 * Copyright(c) Todos os direitos reservados
 */

if (typeof console == 'undefined') console = { log: function() {} };

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
	if ($(this).prop('required') || $(this).parent().hasClass('nospaceuse')) {
		var number = $(this).parent().parent().parent().children().length;
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

	if ($(this).prop('required') || $(this).parent().hasClass('nospaceuse')) {
		var number = $(this).parent().parent().parent().parent().children().length;
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

function reindexMultiNumberLabel(index)
{
	var numberPattern = /\d+/g;
	$(this).html($(this).html().replace( numberPattern , index ))
}

function addItem() {
	
	//clona
    var newFill = $(this).parent().clone(true, true);
    newFill.insertAfter($(this).parent());
    
    adjustNewItem(newFill,$(this), addNumber);
    
    //esconde o label duplicado
    newFill.find('label').html("&nbsp;&nbsp;&nbsp;").addClass('visible-desktop');$(this).parent().parent().parent().find('h5:visible').each(reindexMultiNumberLabel);
    $('.icon-minus').each(hideMinus);
};

function addMultiItem() {
	//clona
    var newFill = $(this).parent().parent().clone(true, true);
    newFill.insertAfter($(this).parent().parent());
    
    //Ajusta campos e valores
    adjustNewItem(newFill, $(this), addMultiNumber);
    //Reindexa o título
    $(this).parent().parent().parent().find('h5:visible').each(reindexMultiNumberLabel);
    
    $('.icon-minus').each(hideMultiMinus);
};

function adjustNewItem(newFill, original, addNumberFunction)
{
    //Retira máscaras para evitar referências erradas de targets
    unmask();    
    //Ajusta
    newFill.find('input').val('');
    newFill.find('textarea').val('');
    newFill.find('select').find(':selected').prop('selected', false);
    newFill.find('select').find('option').first().prop('selected', true);

    //renomeia todos adicionando um valor ao número do contador
    newFill.find('input').each(addNumberFunction);
    newFill.find('select').each(addNumberFunction);
    newFill.find('textarea').each(addNumberFunction);
    newFill.find('textarea').each(textAreaLimit);
    newFill.find('.textCounter').each(addNumberFunction);
    
    //retira o botão de adição do clone
    newFill.find('.icon-plus').remove();
    //define comportamento do minus
    newFill.find('.icon-minus').show();
    original.insertAfter(newFill.find('.icon-minus'));
    //Resolve mensagens de erro por ventura copiados
    newFill.find('label.error').remove();
    newFill.find('.error').removeClass('.error');

    //Recoloca as máscaras
    mask();
}

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

function textAreaLimit(){
	var maxsize = $(this).attr('maxlength');
	if (!maxsize || maxsize == 0 || maxsize == 'undefined') {
		maxsize = 65535;
	}
	if(!$(this).parent().hasClass('nospaceuse')){
		var spanClass = $(this)[0].outerHTML.match(/(span\d)/);
		var container = $('<div class="'+spanClass[0]+' nospaceuse"></div>');
		container.addClass('span');
		$(this).replaceWith(container);
		container.append($(this));
	}
	
	if (!$('#counter' + $(this).prop('id')).length) {
		$('<div id="counter' + $(this).prop('id') + '" class="textCounter">' + maxsize + ' / ' + $(this).val().length + ' caracteres restantes</div>').insertAfter($(this));
	}
	
	$(this).keydown(function(e){
		if ($(this).val().length > (maxsize - 1)) {
			var key = e.keyCode;
		    $(this).val($(this).val().substr(0,maxsize));

		     // allow backspace, tab, delete, home, end, pageup, pagedown, arrows, numbers and keypad numbers ONLY
		     if (key == 8 || key == 9 || key == 46 || (key >= 35 && key <= 40));
		     	return;
		    
			e.preventVault();
			return false;
		}
	});
	
	$(this).keyup(function(e){
		$(this).val($(this).val().substr(0,maxsize));
		var target = $('#counter' + $(this).prop('id'));
		target.html(maxsize + ' / ' + $(this).val().length + ' caracteres restantes');
	});
}

function errorPlacement (error, element) {
	if(!element.parent().hasClass('validationSpan')){
		var spanClass = element[0].outerHTML.match(/(span\d)/);
		var container = $('<div class="'+spanClass[0]+' validationSpan"></div>');
		
		if (!element.parent().hasClass('nospaceuse')) {
			element.replaceWith(container);
			container.append(element);
			error.insertAfter(element);
		} else {
			var oldParent = element.parent();
			console.log(oldParent[0].outerHTML)
			oldParent.replaceWith(container);
			container.append(oldParent);
			error.insertAfter(oldParent);
		}
	}
	else {
		error.insertAfter(element);
	}
	$('textarea').each(textAreaLimit);
}

//Localiza a aba do primeiro elemento que tem um erro na lista de validação, para abrir a aba
function findTabPane(element){
	//se não tem parent, para
	if(!element.parent()){
		return;
	}
	if (!element.parent().hasClass('tab-pane')){
		findTabPane(element.parent());
		return;
	}
	else{
		tabId = element.parent().prop('id');
		$('a[href="#'+tabId+'"]').trigger('click');
		return;
	}
}

function invalidHandler(event, validator) 
{
	var errors = validator.numberOfInvalids();
	if (errors) {
		findTabPane($('#'+validator.errorList[0].element.id));
	    var message = errors == 1
	      ? 'Erro encontrado no formulário. Corrija-o para prosseguir.'
	      : 'Erros encontrados no formulário. Corrija-os para prosseguir.';
		$("#errorDialogBody").html(message);
	    $("#errorDialog").modal('show');
	}
}

//Sobrescreva para definir comportamento específico para o formulário em questão
var validateOptions = {
	ignore: '',
	errorPlacement: errorPlacement,
	invalidHandler: invalidHandler
};

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
	
	$('#cancel_bt').click(cancelData);
	
	//Ajustes de tela
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
    
    $('textarea').each(textAreaLimit);
    
	//Validação de formulário
	$('form').validate(validateOptions);
});