<<<<<<< HEAD
/*
	Simple AutoComplete plugin for jQuery
	Author: Wellington Ribeiro
	Version: 1.0.0 (14/03/2010 12:02)
	Version: 1.1.0 (04/05/2010 13:05) - resolve problemas do ie6 sem necessidade de hacks, fecha o autocomplete ao clicar fora, insere automaticamente o atributo para não permitir o autocomplete do navegador.
	Copyright (c) 2008-2010 IdealMind ( www.idealmind.com.br )
	Licensed under the GPL license (http://blog.idealmind.com.br/projetos/simple-autocomplete-jquery-plugin/#license)

 * 
 * $('selector').simpleAutoComplete("ajax_query.php", {
 *	identifier: 'estado',
 *	extraParamFromInput: '#extra',
 *	attrCallBack: 'rel',
 *	autoCompleteClassName: 'autocomplete',
 *	selectedClassName: 'sel'
 * },calbackFunction);
 * 
 */

(function($){
	$.fn.extend(
	{
		simpleAutoComplete: function( page, options, callback )
		{
			if(typeof(page) == "undefined" )
			{
				alert("simpleAutoComplete: Você deve especificar a página que processará a consulta.");
			}
			
			var classAC = 'autocomplete';
			var selClass = 'sel';
			var attrCB = 'rel';
			var thisElement = $(this);

			$(":not(div." + classAC + ")").click(function(){
				$("div." + classAC).remove();
			});
			
			thisElement.attr("autocomplete","off");
			
			thisElement.keydown(function ( ev )
			{
				kc = ( ( typeof( ev.charCode ) == 'undefined' || ev.charCode === 0 ) ? ev.keyCode : ev.charCode );
				
				if (kc == 13)
				{
					$('div.' + classAC + ' li.' + selClass).trigger('click');
					return false;
				}
			});
			
			thisElement.keyup(function( ev )
			{
				var getOptions = { query: thisElement.val() }
				
				if( typeof(options) == "object" )
				{
					classAC = typeof( options.autoCompleteClassName ) != "undefined" ? options.autoCompleteClassName : classAC;
					selClass = typeof( options.selectedClassName ) != "undefined" ? options.selectedClassName : selClass;
					
					attrCB = typeof( options.attrCallBack ) != "undefined" ? options.attrCallBack : attrCB;
					if( typeof( options.identifier ) == "string" )
						getOptions.identifier = options.identifier;
					if( typeof( options.identifier ) == "function" )
						getOptions.identifier = options.identifier();

					if( typeof( options.extraParamFromInput ) != "undefined" )
					getOptions.extraParam = $( options.extraParamFromInput ).val();
				}

				kc = ( ( typeof( ev.charCode ) == 'undefined' || ev.charCode === 0 ) ? ev.keyCode : ev.charCode );
				key = String.fromCharCode(kc);

				if (kc == 27)
				{
					$('div.' + classAC).remove();
				}
				if (kc == 13)
				{
					$('div.' + classAC + ' li.' + selClass).trigger('click');
					return false;
				}
				if (key.match(/[a-zA-Z0-9_\- ]/) || kc == 8 || kc == 46)
				{
					$.get(page, getOptions, function(r)
					{
						$('div.' + classAC).remove();
						autoCompleteList = $('<div>').addClass(classAC).html(r);
						if (r != '')
						{
							autoCompleteList.insertAfter(thisElement);
							
							var position = thisElement.position();
							var height = thisElement.height();
							var width = thisElement.width();

							$('div.' + classAC).css({
								'top': ( height + position.top + 6 ) + 'px',
								'left': ( position.left )+'px',
								'margin': '0px'
							});
							
							$('div.' + classAC + ' ul').css({
								'margin-left': '0px'
							});
							
							$('div.' + classAC + ' li').each(function( n, el )
							{
								el = $(el);
								el.mouseenter(function(){
									$('div.' + classAC + ' li.' + selClass).removeClass(selClass);
									$(this).addClass(selClass);
								});
								el.click(function()
								{
									thisElement.attr('value', el.text());
									
									var values = el.attr(attrCB).split('__');

									if( typeof( callback ) == "function" )
										callback( values );
									else if (thisElement.attr('targetId')){
										var targetId = thisElement.attr('targetId');
										if (targetId.indexOf('#') == -1)
											targetId = '#' + targetId;
										$(targetId).val(values[0])
										thisElement.val(values[1]);
										thisElement.attr('selectedId', values[0]);
										thisElement.attr('selectedName',values[1]);
									}
									else{
										thisElement.val(values);
									}
									
									$('div.' + classAC).remove();
									thisElement.focus();
								});
							});
						}
					});
				}
				if (kc == 38 || kc == 40){
					if ($('div.' + classAC + ' li.' + selClass).length == 0)
					{
						if (kc == 38)
						{
							$($('div.' + classAC + ' li')[$('div.' + classAC + ' li').length - 1]).addClass(selClass);
						} else {
							$($('div.' + classAC + ' li')[0]).addClass(selClass);
						}
					}
					else
					{
						sel = false;
						$('div.' + classAC + ' li').each(function(n, el)
						{
							el = $(el);
							if ( !sel && el.hasClass(selClass) )
							{
							el.removeClass(selClass);
							$($('div.' + classAC + ' li')[(kc == 38 ? (n - 1) : (n + 1))]).addClass(selClass);
							sel = true;
							}
						});
					}
				}
				if (thisElement.val() == '')
				{
					$('div.' + classAC).remove();
				}
			});
			
			thisElement.blur(function( ev )
			{
				window.setTimeout(function (){$('div.' + classAC).remove();},150);
				
				if (thisElement.val() != thisElement.attr('selectedName')){
					thisElement.val('');
					
					if (thisElement.attr('targetId')){
						var targetId = thisElement.attr('targetId');
						if (targetId.indexOf('#') == -1)
							targetId = '#' + targetId;
						$(targetId).val('')
					}
				}
			});
		}
	});
})(jQuery);
=======
/*
	Simple AutoComplete plugin for jQuery
	Author: Wellington Ribeiro
	Version: 1.0.0 (14/03/2010 12:02)
	Version: 1.1.0 (04/05/2010 13:05) - resolve problemas do ie6 sem necessidade de hacks, fecha o autocomplete ao clicar fora, insere automaticamente o atributo para não permitir o autocomplete do navegador.
	Copyright (c) 2008-2010 IdealMind ( www.idealmind.com.br )
	Licensed under the GPL license (http://blog.idealmind.com.br/projetos/simple-autocomplete-jquery-plugin/#license)

 * 
 * $('selector').simpleAutoComplete("ajax_query.php", {
 *	identifier: 'estado',
 *	extraParamFromInput: '#extra',
 *	attrCallBack: 'rel',
 *	autoCompleteClassName: 'autocomplete',
 *	selectedClassName: 'sel'
 * },calbackFunction);
 * 
 */

(function($){
	$.fn.extend(
	{
		simpleAutoComplete: function( page, options, callback )
		{
			if(typeof(page) == "undefined" )
			{
				alert("simpleAutoComplete: Você deve especificar a página que processará a consulta.");
			}
			
			var classAC = 'autocomplete';
			var selClass = 'sel';
			var attrCB = 'rel';
			var thisElement = $(this);

			$(":not(div." + classAC + ")").click(function(){
				$("div." + classAC).remove();
			});
			
			thisElement.attr("autocomplete","off");
			
			thisElement.keydown(function ( ev )
			{
				kc = ( ( typeof( ev.charCode ) == 'undefined' || ev.charCode === 0 ) ? ev.keyCode : ev.charCode );
				
				if (kc == 13)
				{
					$('div.' + classAC + ' li.' + selClass).trigger('click');
					return false;
				}
			});
			
			thisElement.keyup(function( ev )
			{
				var getOptions = { query: thisElement.val() }
				
				if( typeof(options) == "object" )
				{
					classAC = typeof( options.autoCompleteClassName ) != "undefined" ? options.autoCompleteClassName : classAC;
					selClass = typeof( options.selectedClassName ) != "undefined" ? options.selectedClassName : selClass;
					
					attrCB = typeof( options.attrCallBack ) != "undefined" ? options.attrCallBack : attrCB;
					if( typeof( options.identifier ) == "string" )
						getOptions.identifier = options.identifier;
					if( typeof( options.identifier ) == "function" )
						getOptions.identifier = options.identifier();

					if( typeof( options.extraParamFromInput ) != "undefined" )
					getOptions.extraParam = $( options.extraParamFromInput ).val();
				}

				kc = ( ( typeof( ev.charCode ) == 'undefined' || ev.charCode === 0 ) ? ev.keyCode : ev.charCode );
				key = String.fromCharCode(kc);

				if (kc == 27)
				{
					$('div.' + classAC).remove();
				}
				if (kc == 13)
				{
					$('div.' + classAC + ' li.' + selClass).trigger('click');
					return false;
				}
				if (key.match(/[a-zA-Z0-9_\- ]/) || kc == 8 || kc == 46)
				{
					$.get(page, getOptions, function(r)
					{
						$('div.' + classAC).remove();
						autoCompleteList = $('<div>').addClass(classAC).html(r);
						if (r != '')
						{
							autoCompleteList.insertAfter(thisElement);
							
							var position = thisElement.position();
							var height = thisElement.height();
							var width = thisElement.width();

							$('div.' + classAC).css({
								'top': ( height + position.top + 6 ) + 'px',
								'left': ( position.left )+'px',
								'margin': '0px'
							});
							
							$('div.' + classAC + ' ul').css({
								'margin-left': '0px'
							});
							
							$('div.' + classAC + ' li').each(function( n, el )
							{
								el = $(el);
								el.mouseenter(function(){
									$('div.' + classAC + ' li.' + selClass).removeClass(selClass);
									$(this).addClass(selClass);
								});
								el.click(function()
								{
									thisElement.attr('value', el.text());
									
									var values = el.attr(attrCB).split('__');

									if (thisElement.attr('targetId')){
										var targetId = thisElement.attr('targetId');
										if (targetId.indexOf('#') == -1)
											targetId = '#' + targetId;
										$(targetId).val(values[0])
										thisElement.val(values[1]);
										thisElement.attr('selectedId', values[0]);
										thisElement.attr('selectedName',values[1]);
									}
									else{
										thisElement.val(values);
									}
									
									if( typeof( callback ) == "function" )
										callback( thisElement, values );
									else if (thisElement.attr('callback')){
										var callback = thisElement.attr('callback');
										eval(callback + '( thisElement, values )');
									}
									
									$('div.' + classAC).remove();
									thisElement.focus();
								});
							});
						}
					});
				}
				if (kc == 38 || kc == 40){
					if ($('div.' + classAC + ' li.' + selClass).length == 0)
					{
						if (kc == 38)
						{
							$($('div.' + classAC + ' li')[$('div.' + classAC + ' li').length - 1]).addClass(selClass);
						} else {
							$($('div.' + classAC + ' li')[0]).addClass(selClass);
						}
					}
					else
					{
						sel = false;
						$('div.' + classAC + ' li').each(function(n, el)
						{
							el = $(el);
							if ( !sel && el.hasClass(selClass) )
							{
							el.removeClass(selClass);
							$($('div.' + classAC + ' li')[(kc == 38 ? (n - 1) : (n + 1))]).addClass(selClass);
							sel = true;
							}
						});
					}
				}
				if (thisElement.val() == '')
				{
					$('div.' + classAC).remove();
				}
			});
			
			thisElement.blur(function( ev )
			{
				window.setTimeout(function (){$('div.' + classAC).remove();},150);
				
				if (thisElement.val() != thisElement.attr('selectedName')){
					thisElement.val('');
					
					if( typeof( callback ) == "function" )
						callback( thisElement );
					else if (thisElement.attr('callback')){
						var callback = thisElement.attr('callback');
						eval(callback + '( thisElement )');
					}
					
					if (thisElement.attr('targetId')){
						var targetId = thisElement.attr('targetId');
						if (targetId.indexOf('#') == -1)
							targetId = '#' + targetId;
						$(targetId).val('')
					}
				}
			});
		}
	});
})(jQuery);
>>>>>>> 0567d9a7991222d74a0abbf1de0631919494d6f4
