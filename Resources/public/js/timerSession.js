var sessionTimeFull = -1;
var sessionTime = -1;

function keepAliveLoop() {
    if(sessionTime > -1) {
        date = (new Date(0,0,0,0,0,sessionTime,0));

        mins = parseInt(date.getMinutes());
        secs = ("0" + date.getSeconds()).slice(-2);

        dateString = mins+"min "+secs;
        $("#keepAlive").html(dateString);

        if (sessionTime > 180)
            $("#session-dialog").dialog('close');

        switch(sessionTime) {
            case 0:

                $("#session-dialog").html("Sua sessão expirou! Efetuando logout...");
                sleep(5);
                $("#session-dialog").dialog({
                    modal:           true,
                    draggable:       false,
                    resizable:       false,
                    closeOnEscape:   false,
                    buttons: {
                        "Fechar": function() {
                            $(this).dialog('close');
                            window.location.href = "/logout.php?time=true";
                        }
                    }
                });

                window.location.href= '/logout.php?time=true';

                break;
            case 180:
                $("#session-dialog").html("Sua sessão está prestes a expirar, deseja renová-la agora?");
                $("#session-dialog").dialog({
                    modal:           true,
                    draggable:       false,
                    resizable:       false,
                    closeOnEscape:   false,
                    buttons: {
                        "Sim": function() {
                            $.ajax({
                                type:'GET',
                                data: {
                                    reset : true
                                },
                                url: '/keepAlive.php'
                            }).done(function(data) {
                                    eval("dados = "+data);
                                    sessionTime = dados.time;

                                    window.setTimeout(function (){$.ajax({
                                        type:'GET',
                                        url: '/keepAlive.php'
                                    }).done(function(data){
                                            eval("dados = "+data);
                                            sessionTime = dados.time;
                                            keepAliveLoop();
                                        });
                                    },1000);

                                    $("#session-dialog").html("Sessão renovada!");
                                    $("#session-dialog").dialog({
                                        modal:           true,
                                        draggable:       false,
                                        resizable:       false,
                                        closeOnEscape:   false,
                                        buttons: {
                                            "Fechar": function() {
                                                $(this).dialog('close');
                                            }
                                        }
                                    });
                                });
                        },
                        "Não": function() {
                            $(this).dialog('close');
                        }
                    }
                });
                break;
        }

        window.setTimeout(function (){$.ajax({
            type:'GET',
            url: '/keepAlive.php'
        }).success(function(data){
                if (data) {
                    eval("dados = "+data);
                    sessionTime = dados.time;
                    keepAliveLoop();
                }
            }).error(function(data){
                if (data) {
                    eval("dados = "+data);
                    sessionTime = dados.time;
                    keepAliveLoop();
                }
            });
        },1000)
    }
}

$(document).ready(function(){

    $('#session-dialog').dialog({title: "Inicializando",
        resizable: false,
        height: 200,
        width: 300,
        modal: true,
        close: false}).html('Aguarde...');
    $('#session-dialog').dialog('close');

    $.ajax({
        type:'GET',
        data: {reset:true},
        url: '/keepAlive.php'
    }).done(function(data){
            eval("dados = "+data);
            sessionTime = dados.time;
            sessionTimeFull = sessionTime;

            keepAliveLoop();
        });

    $.ajaxPrefilter(function( options, originalOptions, jqXHR ) {
        sessionTime = sessionTimeFull;
    })
});