<?php

    session_start();
        $_SESSION = [];
    session_destroy();
    
    header('location: ../index.php');
    
    exit;

    // uit de sessie gegaan en terug gestuurd naar de home pagina

    ?>