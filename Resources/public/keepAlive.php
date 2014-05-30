<?php
@session_start();

if(count($_SESSION) > 0) {
    if (!isset($_SESSION["keepAlive"]) || isset($_GET['reset']))
        $_SESSION["keepAlive"] = time() + (1200); // Sessão de 20 minutos

    $tempo = (int) $_SESSION["keepAlive"] - time();

    if($tempo <= 0) {
        @session_destroy();
    }

} else {
    $tempo = -1;
}

die(json_encode(array("keepAlive" => 1, "time" => $tempo)));