<?php
    include('conexion.php');

    // OBTENIENDO LOS VALORES
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $dni = $_POST['txtDni'];
        $celular = $_POST['txtCelular'];
    }

    if (isset($_POST['btnIngresar'])) {
        if (!empty($dni) && !empty($celular)) {
            if(is_numeric($dni) && is_numeric($celular) && strlen($dni) == 8 && strlen($celular) == 9) {
                $busqueda = $conexion -> query("SELECT * FROM usuario WHERE Dni = '$dni' AND Celular = '$celular'");

                if ($fila = $busqueda -> fetch_assoc()) {
                    // VERIFICAMOS QUE LA SESION NO ESTE ACTIVA
                    if ($fila['Flag'] == 'n') {
                        // ACTIVAMOS LA SESION
                        $activarFlag = $conexion->query("UPDATE usuario SET Flag = 's' WHERE Dni = '$dni'");

                        // CREAMOS LAS VARIABLES DE SESION
                        session_start();
                        $_SESSION['autentificado'] = true;
                        $_SESSION['dni'] = $fila['Dni'];
                        $_SESSION['celular'] = $fila['Celular'];
                        $_SESSION['nombres'] = $fila['Nombres'];
                        $_SESSION['apellidos'] = $fila['Apellidos'];
                        $_SESSION['imagen'] = $fila['Imagen'];

                        header('Location: home.php');
                        exit();
                    } else {
                        echo "<script>alert('No puedes iniciar sesión. La cuenta esta activa en otro dispositivo.')</script>";
                    }
                } else {
                    header('Location: registro.php');
                    exit();
                }
            } else {
                header('Location: index.php?error=valor');
                exit();
            }
        } else {
            header('Location: index.php?error=si');
            exit();
        }
    }

    $conexion -> close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="hover.css">
</head>
<body>
    <div class="center">
        <section class="container-figure">
            <figure class="figure">
                <img class="figure__img" src="./images/login.svg" alt="Imagen Login">
            </figure>
        </section>
        <section class="container-form">
            <form class="form" action="" method="post">
                <div class="container-texts">
                    <h1 class="form__title">Iniciar Sesión</h1>
                    <p class="form__paragraph">¿Aún no tienes una cuenta? <a class="link" href="registro.php">Registrate</a></p>
                </div>
                <div class="group">
                    <input id="dni" class="form__input" name="txtDni" type="text" placeholder=" " required pattern="[0-9]{8}" title="Solo se permiten números. (8 digitos)">
                    <label class="form__label" for="dni">Dni</label>
                    <?php
                        if (isset($_GET['error']) && $_GET['error'] == 'si') {
                            echo "<span class='form__error'>Este campo es requerido</span>";
                        }

                        if (isset($_GET['error']) && $_GET['error'] == 'valor') {
                            echo "<span class='form__error'>Solo se permiten números. (8 digitos)</span>";
                        }
                    ?>
                </div>
                <div class="group">
                    <input id="celular" class="form__input" name="txtCelular" type="text" placeholder=" " required pattern="[0-9]{9}" title="Solo se permiten números. (9 digitos)">
                    <label class="form__label" for="celular">Celular</label>
                    <?php
                        if (isset($_GET['error']) && $_GET['error'] == 'si') {
                            echo "<span class='form__error'>Este campo es requerido</span>";
                        }

                        if (isset($_GET['error']) && $_GET['error'] == 'valor') {
                            echo "<span class='form__error'>Solo se permiten números. (9 digitos)</span>";
                        }
                    ?>
                </div>
                <input class="form__input form__input--submit hvr-pop" name="btnIngresar" type="submit" value="Ingresar">
            </form>
        </section>
    </div>
</body>
</html>