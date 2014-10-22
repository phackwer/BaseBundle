/**
 * Arquivo responsável pelo JS GERAL
 *
 * Copyright(c) Todos os direitos reservados
 */

if (typeof console == 'undefined') console = { log: function() {} };

var numberPattern = /\d+/g;

function confirmRemoveItem()
{
	$("#cancelConfirmButton").removeClass('btn-success');
	$("#cancelConfirmButton").html('Cancelar');
	$("#confirmDialogBody").html("Tem certeza que descartar estes dados?<br>Para cancelar esta remoção, basta cancelar a edição do final do formulário.");
	$("#confirmDialog").modal('show');
	$("#confirmButton").show();
	
	$("#confirmButton").unbind('click');
	
	$("#confirmButton")[0].target = $(this);
	
	if ($(this).hasClass('single-line'))
		$("#confirmButton").click(removeItem);
	if ($(this).hasClass('multi-line'))
		$("#confirmButton").click(removeMultiItem);
}

function removeItem()
{
	var target = $("#confirmButton")[0].target;
	$("#confirmDialog").modal('hide');
	var grandPa = target.parent().parent();
	if (grandPa.children().length > 1) {

		//Renomeia o campo id do objeto para idDel, marcando para remoção do lado do server
		var idField = target.parent().find('input:hidden');
		if(idField.val())
		{
	     	var name = idField.attr('name');
	     	idField.attr('name', name.replace('[id]','[idDel]') );
	     	idField.insertAfter(grandPa)
		}
		target.parent().remove();
		//se tiver o botão de plus, ele deve ser movido para o último item da listagem
		var plus = target.parent().find('.icon-plus');
		if (plus) {
			plus.insertAfter(grandPa.find('.icon-minus').last());
			plus.click(addItem);
		}
		//se tiver conteúdo no label, tem que manter
		var label = target.parent().find('label');
		if (label.html() != '&nbsp;&nbsp;&nbsp;') {
			grandPa.find('span:visible').first().find('label').html(label.html());
		}
	} else {
		target.parent().find('input').val('');
		target.parent().find('textarea').val('');
		target.parent().find('select').find(':selected').prop('selected', false);
		target.parent().find('select').find('option').first().prop('selected', true);
		alert('É preciso ter um item pelo menos. O campo será deixado em branco para exclusão no banco de dados.')
	}

	grandPa.find('.icon-minus').each(hideMinus);
}

function removeMultiItem()
{
	var target = $("#confirmButton")[0].target;
	$("#confirmDialog").modal('hide');
	
	var grandPa = target.parent().parent().parent();
	if (grandPa.children().length > 2) {

		//Renomeia o campo id do objeto para idDel, marcando para remoção do lado do server
		var idField = target.parent().find('input:hidden');
		if(idField.val())
		{
	     	var name = idField.attr('name');
	     	idField.attr('name', name.replace('[id]','[idDel]') );
	     	idField.insertAfter(grandPa);
		}
		target.parent().parent().remove();
		//se tiver o botão de plus, ele deve ser movido para o último item da listagem
		var plus = target.parent().find('.icon-plus');
		if (plus) {
			plus.insertAfter(grandPa.find('.icon-minus').last());
			plus.click(addMultiItem);
		}
		//se tiver conteúdo no label, tem que manter
		var label = target.parent().find('label');
		if (label.html() != '&nbsp;&nbsp;&nbsp;') {
			grandPa.find('span:visible').first().find('label').html(label.html());
		}
		//Se tiver h5, tem que renumerar
		grandPa.find('h5:visible').each(reindexMultiNumberLabel);
	} else {
		target.parent().find('input').val('');
		target.parent().find('textarea').val('');
		target.parent().find('select').find(':selected').prop('selected', false);
		target.parent().find('select').find('option').first().prop('selected', true);
		alert('É preciso ter um item pelo menos. O campo será deixado em branco para exclusão no banco de dados.')
	}

	grandPa.find('.icon-minus').each(hideMultiMinus);
}

function incrementNumber (value)
{
	values = value.match(numberPattern);
	for(var i = 0; values.length < i; $i++) {
		values[i]++;
	}
	
	var numberId = parseInt(values[0]) + 1

	var number = $(this).parent().parent().children().length;
	
	if ($(this).prop('required') || $(this).parent().hasClass('nospaceuse')) {
		var number = $(this).parent().parent().parent().children().length;
	}
	
	return (numberId > number) ? numberId : number;
}

function incrementMultiNumber (value)
{
	values = value.match(numberPattern);
	for(var i = 0; values.length < i; $i++) {
		values[i]++;
	}
	
	var numberId = parseInt(values[0]) + 1

	var number = $(this).parent().parent().parent().children().length -1;

	if ($(this).prop('required') || $(this).parent().hasClass('nospaceuse')) {
		var number = $(this).parent().parent().parent().parent().children().length;
	}
	
	return (numberId > number) ? numberId : number;
}

function addNumber()
{
	if ($(this).attr('name')) {
		$(this).attr('name', $(this).attr('name').replace( numberPattern , incrementNumber ))
	}
	if ($(this).prop('id')) {
		$(this).prop('id', $(this).prop('id').replace( numberPattern , incrementNumber ))
	}
	if ($(this).attr('targetId')) {
		$(this).attr('targetId', $(this).attr('targetId').replace( numberPattern , incrementNumber ))
	}
	if ($(this).attr('for')) {
		$(this).attr('for', $(this).attr('for').replace( numberPattern , incrementNumber ))
	}
	if ($(this).attr('dateCompare')) {
		$(this).attr('dateCompare', $(this).attr('dateCompare').replace( numberPattern , incrementNumber ))
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
    $(this).parent().parent().find('.icon-minus').each(hideMinus);
};

function addMultiItem() {
	//clona
    var newFill = $(this).parent().parent().clone(true, true);
    newFill.insertAfter($(this).parent().parent());
    
    //Ajusta campos e valores
    adjustNewItem(newFill, $(this), addNumber);
    //Reindexa o título
    $(this).parent().parent().parent().find('h5:visible').each(reindexMultiNumberLabel);
    
    $(this).parent().parent().parent().find('.icon-minus').each(hideMultiMinus);
};

function adjustNewItem(newFill, original, addNumberFunction)
{
    //Retira máscaras para evitar referências erradas de targets
    unmask();
    //Ajusta
    newFill.find('input:not([notCleanable])').not(':checkbox').not(':radio').val('');
    newFill.find(':checkbox').prop('checked', false);
    newFill.find(':checkbox').prop('disabled', false);
    newFill.find('textarea').val('');
    newFill.find('select').find(':selected').prop('selected', false);
    newFill.find('select').find('option').first().prop('selected', true);

    //renomeia todos adicionando um valor ao número do contador
    newFill.find('input, select, textarea, .textCounter, label, img').each(addNumberFunction);
    newFill.find('textarea').each(textAreaLimit);
    newFill.find('.choosePhoto').attr('src', '../../bundles/sansiscorebase/images/camera-icon.jpg');
    
    //retira o botão de adição do clone
    newFill.find('.icon-plus').remove();
    //define comportamento do minus
    newFill.find('.icon-minus').show();
    original.insertAfter(newFill.find('.icon-minus'));
    //Resolve mensagens de erro por ventura copiados
    newFill.find('label.error').remove();
    newFill.find('.error').removeClass('.error');
    
    $('.autocomplete').each(autoCompleteField);

    //Recoloca as máscaras
    mask();
    //Recoloca validação de formulário
//    $('form').validate(validateOptions);
}

function hideMinus(index)
{
	var visibleChildren = $(this).parent().parent().find('span:visible').filter('.clonable').length
	if (visibleChildren == 1) {
		$(this).hide();
	}
	else {
		$(this).show();
	}
}

function hideMultiMinus(index)
{
	var visibleChildren = $(this).parent().parent().parent().find('ul:visible').filter('.clonable').length;
	if (visibleChildren == 1) {
		$(this).hide();
	}
	else {
		$(this).show();
	}
}

function autoCompleteField()
{
	$(this).unbind('blur');
	$(this).unbind('keyup');
	$(this).unbind('keydown');
	
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
    
    $('input, select, textarea').attr('readOnly', true);
    $('input, select, textarea').attr('disabled', true);

    //some com botões de ação
    $('.icon-plus, .icon-minus, .icon-edit, .icon-trash, .addFile, #save_bt').remove();
    hrefRemove = $('.foto').parent();
    $('.foto').each(function(){$(this).insertBefore($(this).parent())});
    hrefRemove.each(function(){$(this).remove()});
    $('.dateBR').datepicker("destroy");
    
    $('#cancel_bt').html('Voltar');
    
    $('#cancel_bt').unbind('click',cancelData);
    $('#cancel_bt').click(function(){window.history.back()});
    $('#cancel_bt').addClass('btn-primary');
}

function cancelData()
{
	$("#cancelConfirmButton").removeClass('btn-success');
	$("#cancelConfirmButton").html('Cancelar');
	$("#confirmDialogBody").html("Tem certeza que deseja descartar os dados informados?");
	$("#confirmDialog").modal('show');
	$("#confirmButton").show();
	
	$("#confirmButton").unbind('click');
	
	$("#confirmButton").click(function() {
    	$("#cancelConfirmButton").addClass('btn-success');
    	$("#cancelConfirmButton").html('Fechar');
        $("#searchBt").trigger('click');
        $("#confirmButton").hide();
        $("#confirmDialogBody").html("Descartando dados...");
        window.setTimeout(function(){window.history.back();}, 1000);
	});
}

//Limita textareas com contados
function textAreaLimit(){
	var maxsize = $(this).attr('maxlength');
	if (!maxsize || maxsize == 0 || maxsize == 'undefined') {
		maxsize = 65535;
	}
	if(!$(this).parent().hasClass('nospaceuse')){
		var spanClass = $(this)[0].outerHTML.match(/(span\d)/);
		if (!spanClass)
			var container = $('<span class="nospaceuse"></span>');
		else
			var container = $('<span class="'+spanClass[0]+' nospaceuse"></span>');
		container.addClass('span');
		container.insertAfter($(this));
		container.append($(this));
	}
	
	if ($('#counter' + $(this).prop('id')).length == 0) {
		$('<div id="counter' + $(this).prop('id') + '" class="textCounter">' + maxsize + ' / ' + (maxsize - $(this).val().length) + ' caracteres restantes</div>').insertAfter($(this));
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
		target.html(maxsize + ' / ' + (maxsize - $(this).val().length) + ' caracteres restantes');
	});
	
	$(this).trigger('keyup');
}

//Define seleção de imagens para upload
function choosePhoto()
{
	var targetId = $(this).attr('targetId');
	var target = $('#' + targetId);
	
	if (!target[0]) {
		
		var targetId = $(this).prop('id') + '_upload';
	}
	
	target = $('#' + targetId);
	
    $(target).click();
}

//
function removePhoto()
{
	$("#cancelConfirmButton").removeClass('btn-success');
	$("#cancelConfirmButton").html('Cancelar');
	$("#confirmDialogBody").html("Tem certeza que descartar esta imagem?<br>Para cancelar esta remoção, basta cancelar a edição do final do formulário.");
	$("#confirmDialog").modal('show');
	$("#confirmButton").show();
	
	$("#confirmButton").unbind('click');
	
	var targetId = $(this).attr('targetId');
	var input = $('#' + targetId);
	
	if (!input[0]) {
		var targetId = $(this).prop('id') + '_upload';
	}
	
	$("#confirmButton").attr('targetId', targetId);
	
	$("#confirmButton").click(function() {
		
		var jInput = $('#' + $("#confirmButton").attr('targetId'));
        
        var idTarget = jInput.attr('targetId');
    	var target= $('#' + idTarget);
    	if (!target[0]) {
    	    target = $('.' + idTarget);
    	}
		
		var newJInput = jInput.clone(true,true);
		jInput.replaceWith(newJInput);
		
		target.each(function(){
    		$(this).attr('src', '../../bundles/sansiscorebase/images/camera-icon.jpg')
    		$('label[for="'+ $(this).attr('id') +'"]').each( function () {
    			if ($(this).hasClass('choosePhoto')) {
    				$(this).show();
    			}
    			else if ($(this).hasClass('removePhoto')) {
    				$(this).hide();
    			}
			});
    	})		
		
    	$("#cancelConfirmButton").addClass('btn-success');
    	$("#cancelConfirmButton").html('Fechar');
        $("#searchBt").trigger('click');
        $("#confirmButton").hide();
        $("#confirmDialogBody").html("Descartando imagem...");
        window.setTimeout(function(){$("#confirmDialog").modal('hide');}, 1000);
	});
}

//Converte data url para blob
function dataURItoBlob(dataURI)
{
	var dataTYPE = dataURI.split(';')[0].split(':')[1];
    var binary = atob(dataURI.split(',')[1]), array = [];
    for(var i = 0; i < binary.length; i++) array.push(binary.charCodeAt(i));
    return new Blob([new Uint8Array(array)], {type: dataTYPE});
}

//Apresenta imagem na hora da seleção
function readURL(input)
{
    if (input.files && input.files[0]) {
    	
    	var reader = new FileReader();
        
        reader.onload = function (e)
        {
        	//Se imagem maior que X, 
        	if (dataURItoBlob(e.target.result).size > 2048000) {
        		
        		var jInput = $('#' + input.id);
                
                var idTarget = jInput.attr('targetId');
            	var target= $('#' + idTarget);
            	if (!target[0]) {
            	    target = $('.' + idTarget);
            	}
        		
        		var newJInput = jInput.clone(true,true);
        		jInput.replaceWith(newJInput);
        		
        		message  = "Imagem excede o tamanho máximo permitido de 2048000 bytes" 
        		
        		$("#errorDialogBody").html(message);
        	    $("#errorDialog").modal('show');
        	}
        	else {
	        	var readerInner = new FileReader();
	            
	        	readerInner.onload = function (e)
	        	{
	            	var jInput = $('#' + input.id);
	                
	                var idTarget = jInput.attr('targetId');
	            	var target= $('#' + idTarget);
	            	if (!target[0]) {
	            	    target = $('.' + idTarget);
	            	}
	            	
	            	target.each(function(){
	            		$(this).attr('src', e.target.result);
	            		$('label[for="'+ $(this).attr('id') +'"]').each( function () {
	            			if ($(this).hasClass('choosePhoto')) {
	            				$(this).hide();
	            			}
	            			else if ($(this).hasClass('removePhoto')) {
	            				$(this).show();
	            			}
        				});
	            	})
	            }
	        	readerInner.readAsDataURL(input.files[0]);
        	}
        }
        
        reader.readAsDataURL(input.files[0]);
        
    } else if (input.value) {
    	
    	var idTarget = $('#' + input.id).attr('targetId');
    	var target= $('#' + idTarget);
    	if (!target[0]) {
    	    target = $('.' + idTarget);
    	}
    	
    	target.each(function(){
    		
    		message  = "Seu navegador não é compatível com a pré-visualização em tempo real.<br>" 
    		message += "Por favor, utilize a versão mais atualizada para evitar esta mensagem."
    		
    		$("#errorDialogBody").html(message);
    	    $("#errorDialog").modal('show');

    		
            $('label[for="'+ $(this).attr('id') +'"]').hide();
    	})
    }
}

//Local das mensagens de erro
function errorPlacement (error, element)
{
	try {
		if (element) {
			if (element.is(':checkbox')){
				var parent = element.parent().parent();
				var getSpanFrom = element.parent();
			}
			else{
				var parent = element.parent();
				var getSpanFrom = element;
			}
			
			if(!parent.hasClass('validationSpan')){
				var spanClass = getSpanFrom[0].outerHTML.match(/(span\d)/);
				if (!spanClass)
					var container = $('<span class="validationSpan"></span>');
				else {
					if (element.hasClass('dateBR')) {
						value = parseInt(spanClass[0].substr(4)) + 1;
						spanClass[0] = 'span'+ value;
					}
					var container = $('<span class="'+spanClass[0]+' validationSpan"></span>');
				}
				if (!parent.hasClass('nospaceuse') && container) {
		
					if (element.is(':checkbox')) {
						checkboxes = $('input[name="'+ element.attr('name') +'"]');
						checkboxes.each(function(){
							checkContainer = container.clone();
							checkContainer.insertAfter($('label[for="'+$(this).prop('id')+'"]'));
							checkContainer.append($('label[for="'+$(this).prop('id')+'"]'));
						});
						
					} else {
						container.insertAfter(element)
						container.append(element);
					}
					
					if (error) {
						if (element.hasClass('dateBR')) {
							var br = $('<br />').insertAfter(parent.find('.ui-datepicker-trigger'))
							error.insertAfter(br);
						}
						else if (element.is(':checkbox')) {
							checkboxes = $('input[name="'+ element.attr('name') +'"]');
							checkboxes.each(function(){
								errorCheck = error.clone();
								errorCheck.insertAfter($('label[for="'+$(this).prop('id')+'"]'));
								errorCheck.html('<span>&#8593;</span> Selecione pelo menos um item.');
							});
						}
						else {
							error.insertAfter(element);
						}
					}
				} else if (container) {
					var oldParent = parent;
					container.insertAfter(oldParent)
					container.append(oldParent);
					if (error) {
						error.insertAfter(oldParent);
					}
				}
			}
			else  if (error) {
				if (element.hasClass('dateBR')) {
					var br = $('<br />').insertAfter(parent.find('.ui-datepicker-trigger'))
					error.insertAfter(br);
				} 
				else if (element.is(':checkbox')) {
					checkboxes = $('input[name="'+ element.attr('name') +'"]');
					checkboxes.each(function(){
						errorCheck = error.clone();
						errorCheck.insertAfter($('label[for="'+$(this).prop('id')+'"]'));
						errorCheck.html('<span>&#8593;</span> Selecione pelo menos um item.');
					});
				}
				else {
					error.insertAfter(element);
				}
			}
		}
	} catch (err) {
		//faça nada
	}
}

//Abre o tab correto em caso de erro em uma tab que não esteja visível ainda.
function findTabPane(element){
	$('.tab-pane').each(function(){
		if ($(this).find('#' + element.prop('id')).length > 0){
			tabId = $(this).prop('id');
			$('a[href="#'+tabId+'"]').trigger('click');
		}
	}) 
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
	event.preventDefault();
}

//Para permitir sobrescrita mais facilmente
submitHandler = null;

//Sobrescreva para definir comportamento específico para o formulário em questão
var validateOptions = {
//	debug: true,
	ignore: '',
	errorPlacement: errorPlacement,
	invalidHandler: invalidHandler,
	submitHandler: submitHandler 
};

$(document).ready(function() {
	
	//Imagens para upload
    $('.choosePhoto').click(choosePhoto);
    $('.removePhoto').click(removePhoto);
    $('.imageUpload').change(function() {readURL(this);});
	
	//Funções para edição de objetos
	$('.single-line').parent().find('.icon-minus').click(confirmRemoveItem);
	$('.single-line').parent().find('.icon-plus').click(addItem);
	$('.single-line').parent().find('.icon-minus').each(hideMinus);

	$('.multi-line').parent().find('.icon-minus').click(confirmRemoveItem);
	$('.multi-line').parent().find('.icon-plus').click(addMultiItem);
	$('.multi-line').parent().find('.icon-minus').each(hideMultiMinus);
	
	//Funções autocomplete
	$('.autocomplete').each(autoCompleteField);
	
	$('#cancel_bt').click(cancelData);
	
	$('input, select, textarea').filter('[required]').each(function(){errorPlacement(false, $(this))});
	$('.dateBR, .cpf, .cnpj').each(function(){errorPlacement(false, $(this))});
	
	//Ajustes de tela
    $('.btn-navbar').filter('.select').click(function(){
        if ($(window).innerWidth() >= 980) {
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
    	if ($(window).innerWidth() >= 980) {
    		$('.nav-collapse').find('.dropdown-submenu').find('.f-dropdown-menu').addClass('dropdown-menu');
            $('.nav-collapse').find('.dropdown-submenu').find('.f-dropdown-menu').removeClass('f-dropdown-menu');
    		$('.navbar').filter('.nav').css({'width':''});
    		$('#BoxMenuPrincipal').removeClass("nf-nav");
    		$('.validationSpan').find(':checkbox').css('margin-left', '-20px');
    	}
    	else if ($(window).innerWidth() > 767 && $(window).innerWidth() < 980) {
    		$('.nav-collapse').find('.dropdown-submenu').find('.dropdown-menu').addClass('f-dropdown-menu');
            $('.nav-collapse').find('.dropdown-submenu').find('.f-dropdown-menu').removeClass('dropdown-menu');
    		$('.navbar').filter('.nav').css({'width':'100%'});
    		if ($('.navbar-responsive-collapse').filter('.in').size()) {
            	$('#BoxMenuPrincipal').removeClass("nf-nav");
            } else {
            	$('#BoxMenuPrincipal').addClass("nf-nav");
            }
        	$('.validationSpan').find(':checkbox').css('margin-left', '-11px');
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
    		
    		$('.validationSpan').find(':checkbox').css('margin-left', '0px');

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
    
    //contador de caracteres
    $('textarea').each(textAreaLimit);
    
    //máscaras
    mask();
    
	//Validação de formulário
	$('form').validate(validateOptions);
	
	//Mostra primeira aba cmo estilo selecionado (por algum motivo, não mostra por padrão)
    $('.aba').find('.first').addClass('active');
    
    window.setTimeout(function(){$('#flashes-success').slideUp(500)},3000);
    
    //Ajustes de posicionamento para checkboxes    
    if ($(window).innerWidth() >= 980) {
    	$('.validationSpan').find(':checkbox').css('margin-left', '-20px');
    } else if ($(window).innerWidth() > 767 && $(window).innerWidth() < 980) {
    	$('.validationSpan').find(':checkbox').css('margin-left', '-11px');
    }
    else {
    	$('.validationSpan').find(':checkbox').css('margin-left', '0px');
    }
    
});