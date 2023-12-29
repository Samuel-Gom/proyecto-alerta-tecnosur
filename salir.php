<?php
    include('conexion.php');
    session_start();

    if (isset($_SESSION['dni'])) {
        $dni = $_SESSION['dni'];

        // DESACTIVAMOS FLAG, PARA QUE LA SESION YA NO ESTE ACTIVA
        $desactivarFlag = $conexion->query("UPDATE usuario SET Flag = 'n' WHERE Dni = '$dni'");
    }

    // ELIMINAMOS LOS DATOS Y LAS VARIABLES DE SESION
    session_unset();
    session_destroy();

    header('Location: index.php');
    exit();
?>