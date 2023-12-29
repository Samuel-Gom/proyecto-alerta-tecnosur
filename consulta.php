<?php
    include('conexion.php');

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['btnIncendio'])) {
            $opcion = 'Incendio';
        } elseif (isset($_POST['btnRobo'])) {
            $opcion = 'Robo / Asalto';
        } elseif (isset($_POST['btnAccidente'])) {
            $opcion = 'Accidente de transito';
        } elseif (isset($_POST['btnOtros'])) {
            $opcion = 'Otros';
        } else {
            $opcion = '';
        }
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="hover.css">
</head>
<body>
    <div class="center center-consult">
        <div class="container-consult">
            <h1 class="consult__title">Modulo de Ayuda y Alerta</h1>
            <div class="consult">
                <div class="consult__options">
                    <form class="consult__form" action="" method="post">
                        <input class="form__input--submit consult__option" name="btnIncendio" type="submit" value="Incendio">
                        <input class="form__input--submit consult__option" name="btnRobo" type="submit" value="Robo / Asalto">
                        <input class="form__input--submit consult__option" name="btnAccidente" type="submit" value="Accidente de transito">
                        <input class="form__input--submit consult__option" name="btnOtros" type="submit" value="Otros">
                    </form>
                </div>
            </div>
            <div class="result">
                <p class="result__paragraph">Resultados</p>
                <div class="result__table">
                <?php
                    if(!empty($opcion)) {
                        $busqueda = $conexion -> query("SELECT a.`Nro`, u.`Nombres`, u.`Apellidos`, u.`Dni`, u.`Celular`, a.`TipoAyuda`, a.`Direccion`, a.`FechaEnvio`
                        FROM usuario AS u, ayuda AS a
                        WHERE u.`Dni` = a.`Dni` AND a.`TipoAyuda` = '$opcion'");

                        echo "
                        <table class='table'>
                        <thead>
                            <tr>
                                <th>Nro</th>
                                <th>Nombres</th>
                                <th>Apellidos</th>
                                <th>Dni</th>
                                <th>Celular</th>
                                <th>Tipo de Ayuda</th>
                                <th>Direcciòn</th>
                                <th>Fecha de Envio</th>
                            </tr>
                        </thead>
                        <tbody>";
                        while ($fila = $busqueda -> fetch_assoc()) {
                            echo 
                            '<tr>' . 
                                '<td>' . $fila["Nro"] . '</td>' .
                                '<td>' . $fila["Nombres"] . '</td>' .
                                '<td>' . $fila["Apellidos"] . '</td>' .
                                '<td>' . $fila["Dni"] . '</td>' .
                                '<td>' . $fila["Celular"] . '</td>' .
                                '<td>' . $fila["TipoAyuda"] . '</td>' .
                                '<td>' . $fila["Direccion"] . '</td>' .
                                '<td>' . $fila["FechaEnvio"] . '</td>' .
                            '</tr>';
                        }
                        echo "
                        </tbody>
                        </table>";
                    }
                ?>
                </div>
            </div>
            <div class="stadistic">
                <P class="stadistic__paragraph">Estadisticas</P>
                <div class="container-stadistic">
                    <div class="stadistic__info">
                    <p>
                    <?php
                        if(!empty($opcion)) {
                            // num_rows nos muestra la cantidad de registros que devuelve la consulta
                            $cantidadSubtotal = $busqueda->num_rows;

                            echo $cantidadSubtotal; 
                        } else {
                            echo '0';
                        } 
                    ?>
                    </p>
                    <p>Subtotal de registros</p>
                    </div>
                    <div class="stadistic__info">
                    <p>
                    <?php
                        if(!empty($opcion)) {
                            // num_rows nos muestra la cantidad de registros que devuelve la consulta
                            $busquedaTotal = $conexion -> query("SELECT * FROM ayuda");
                            $cantidadTotal = $busquedaTotal->num_rows;

                            echo $cantidadTotal; 
                        } else {
                            echo '0';
                        }   
                    ?>
                    </p>
                    <p>Total de registros</p>
                    </div>
                    <div class="stadistic__info">
                    <p>
                    <?php
                        if(!empty($opcion)) {
                            echo round(($cantidadSubtotal * 100) / $cantidadTotal) . '%'; 
                        } else {
                            echo '0%';
                        }
                    ?>
                    </p>
                    <p>Porcentaje</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>