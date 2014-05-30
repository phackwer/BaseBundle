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

        $(window).scroll(function () {
            if ($(this).scrollTop() > 114) {
            	$('#BoxMenuPrincipal').removeClass("BoxMenuPrincipal");
                $('#BoxMenuPrincipal').addClass("f-nav");
            } else {
                $('#BoxMenuPrincipal').removeClass("f-nav");
                $('#BoxMenuPrincipal').addClass("BoxMenuPrincipal");
            }
        });
    },

    login: function()
    {
        $( "a.botaoLogin" ).click(function(){
            var emial = $( ".campo_email" ).val(),
                senha = $( ".campo_senha" ).val()

            if( emial == "" || senha == "" ){
                alert( "Preencha os Campos" )
            }else{
                window.location.replace("template.html");
            }
        })
    },

    _print: function()
    {
        $('.print').click(function()
        {
            window.print();
            return false;
        });
        $('.print2').click(function()
        {
            window.print();
            return false;
        });
    },

    aba: function()
    {
        $('#myTab a').click(function (e) {
            e.preventDefault();
            $(this).tab('show');
        })
    },

    date: function()
    {
        $('.date').datepicker();
    },

    acordeon: function()
    {
        $( ".acordeon a.abrir" ).click(function()
        {
            var _this = $( this ),
                Pclasse = _this.attr("class").split(' ')[0],
                Sclasse = _this.attr("class").split(' ')[2],
                textoAcordeon = _this.siblings(),
                texto = _this.find(".texto"),
                sinal = _this.find(".sinal");

            $( _this ).each(function()
            {
                if ( Sclasse !== "ativo" )
                {
                    _this.addClass( "ativo" );
                    $( "div." + Pclasse ).show();
                    _this.find(".texto").hide();
                    _this.find(".sinal").text("-");
                } else {
                    _this.removeClass( "ativo" );
                    textoAcordeon.hide();
                    texto.show();
                    sinal.text("+");
                }
            });

            return false;
        })
    }
};

// Mensageria
(function()
{
    var $ = jQuery;
    window.Message =
	{
	    show: show,
	    init: init
	};

    var htmlRef = null;

    function init()
    {
        htmlRef = jQuery("<div class='message' style='background-color:#ffffff; border:5px solid #FFC435; display:none; left:50%; margin-left:-245px; padding:20px; position:fixed; top:50%; width:450px; z-index:10000;'><p style='color:#535353; display:block; font-size:14px; line-height:1.3em; text-align:center;'></p></div>").appendTo('body');
    };

    function show(message, type)
    {
        type = type == null ? "" : type;
        htmlRef.fadeIn('slow');
        htmlRef.attr("class", "message " + type);
        htmlRef.find( "p" ).html(message);
		
		//if( jQuery.browser.msie && jQuery.browser.version == "6.0" )
			//htmlRef.css('top', jQuery(window).scrollTop() + 100);
        
		jQuery('body').mousemove(startHide);

    };

    function startHide()
    {
        jQuery('body').unbind('mousemove', startHide);
        var htmlRef = htmlRef;
        setTimeout(hide, 1000);
    };

    function hide()
    {
        htmlRef.fadeOut('slow');
    };
})();

jQuery( Message.init );

jQuery(function()
{
    jQuery.fn.resetDefaultValue = function()
    {
        function _clearDefaultValue()
        {
            var _$ = jQuery(this);
            if (_$.val() == this.defaultValue) { _$.val(''); }
        };
        function _resetDefaultValue()
        {
            var _$ = jQuery(this);
            if (_$.val() == '') { _$.val(this.defaultValue); }
        };
        return this.click(_clearDefaultValue).focus(_clearDefaultValue).blur(_resetDefaultValue);
    }
});

$(function()   
{
	app.init();
	
//	window.alert = function(msg)
//	{
//		msg += '';
//		Message.show(msg.replace(/\n{1}/gi, '<br/>'));
//		return null;
//	}
})