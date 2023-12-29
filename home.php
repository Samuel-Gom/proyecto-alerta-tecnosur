<?php
    include('conexion.php');
    session_start();
    
    if (!isset($_SESSION['autentificado'])) {
        header('Location: salir.php');
    }

    // OBTENIENDO LOS VALORES
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $tipoAyuda = $_POST['txtTipoAyuda'];
        $nombres = $_SESSION['nombres'];
        $apellidos = $_SESSION['apellidos'];
        $direccion = $_POST['txtDireccion'];
        $dni = $_SESSION['dni'];
        $celular = $_SESSION['celular'];
    }

    if (isset($_POST['btnEnviar'])) {
        if (!empty($direccion)) {
            // CAMBIAMOS AL HORARIO DE LIMA Y OBTENEMOS LA FECHA ACTUAL
            date_default_timezone_set('America/Lima');
            $fechaActual = date('Y-m-d H:i:s');

            $insertar = $conexion -> query("INSERT INTO ayuda(Dni, TipoAyuda, Direccion, FechaEnvio) VALUES('$dni', '$tipoAyuda', '$direccion', '$fechaActual')");

            if ($insertar) {
                echo "
                <script>
                    alert('Formulario enviado correctamente, seras redirigido a la página Iniciar Sesión.');
                    setTimeout(function() {
                        window.location.href = 'salir.php';
                    }, 1000);
                </script>";
            } else {
                echo "<script>alert('Ocurrio un error al enviar el formulario.')</script>";
            }
        } else {
            echo "<script>alert('El campo Dirección es obligatorio.')</script>";
        }   
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="hover.css">
</head>
<body>
    <div class="center center-home">
        <section class="container">
            <div class="home">
                <h1 class="home__title">Bienvenido al Modulo de Ayuda</h1>
                <div class="options">
                    <p>Seleccione el tipo de ayuda:</p>
                    <div class="group-buttons">
                        <button id="incendio-click" class="home__option">Incendio</button>
                        <button id="incendio-dblclick" class="home__option home__option--dblclick">Incendio</button>
                    </div>
                    <div class="group-buttons">
                        <button id="robo-click" class="home__option">Robo / Asalto</button>
                        <button id="robo-dblclick" class="home__option home__option--dblclick">Robo / Asalto</button>
                    </div>
                    <div class="group-buttons">
                        <button id="accidente-click" class="home__option">Accidente de tránsito</button>
                        <button id="accidente-dblclick" class="home__option home__option--dblclick">Accidente de tránsito</button>
                    </div>
                    <div class="group-buttons">
                        <button id="otros-click" class="home__option">Otros</button>
                        <button id="otros-dblclick" class="home__option home__option--dblclick">Otros</button>
                    </div>
                </div>
                <div class="info">
                    <div class="user">
                        <img class="image_perfil" src="./images/users/<?php echo $_SESSION['imagen'];?>" alt="Imagen de perfil">
                        <p>
                            <?php
                                echo $_SESSION['nombres'] . " " . $_SESSION['apellidos'];
                            ?>
                        </p>
                    </div>
                    <div class="timer">
                        <span id="temporizador">4:00</span>
                    </div>
                </div>
                <a class="home__salir" href="salir.php"><i class="bi bi-arrow-left-short icon"></i>Salir</a>
            </div>
            <div class="container-form bg-change">
                <form id="form-ayuda" class="form form--ayuda" action="" method="post">
                    <div class="group">
                        <input id="tipo-ayuda" class="form__input form__input--default" name="txtTipoAyuda" type="text" readonly tabindex="-1">
                        <label class="form__label" for="ayuda">Tipo de Ayuda</label>
                    </div>
                    <div class="group">
                        <input id="nombres" class="form__input form__input--default" name="txtNombres" type="text" disabled value="<?php echo $_SESSION['nombres'] . " " . $_SESSION['apellidos'] ?>">
                        <label class="form__label" for="nombres">Nombres y Apellidos</label>
                    </div>
                    <div class="group">
                        <input id="dni" class="form__input form__input--default" name="txtDni" type="text" disabled value="<?php echo $_SESSION['dni'] ?>">
                        <label class="form__label" for="dni">Dni</label>
                    </div>
                    <div class="group">
                        <input id="direccion" class="form__input" name="txtDireccion" type="text" required>
                        <label class="form__label" for="direccion">Dirección</label>
                    </div>
                    <input id="enviar" class="form__input form__input--submit hvr-pop" name="btnEnviar" type="submit" value="Enviar">
                    <div id="texts" class="option-selected">
                        <p id="paragraph" class="content">Selecciona una opción.</p>
                        <p id="guide" class="guide">Da doble click sobre la opción que seleccionaste para llenar el formulario de envio.</p>
                    </div>
                </form>
            </div>
        </section>
    </div>

    <script>
        // Obtener los elementos por su id
        let incendioClick = document.getElementById("incendio-click");
        let incendioDblClick = document.getElementById("incendio-dblclick");
        let roboClick = document.getElementById("robo-click");
        let roboDblClick = document.getElementById("robo-dblclick");
        let accidenteClick = document.getElementById("accidente-click");
        let accidenteDblClick = document.getElementById("accidente-dblclick");
        let otrosClick = document.getElementById("otros-click");
        let otrosDblClick = document.getElementById("otros-dblclick");
        let paragraph = document.getElementById("paragraph");
        let guide = document.getElementById("guide");
        let texts = document.getElementById("texts");
        let tipoAyuda = document.getElementById("tipo-ayuda");

        // Agregando el evento click
        incendioClick.addEventListener("click", function() {
            click(incendioClick, incendioDblClick, "Has seleccionado incendio.");
        });

        roboClick.addEventListener("click", function() {
            click(roboClick, roboDblClick, "Has seleccionado robo / asalto.");
        });

        accidenteClick.addEventListener("click", function() {
            click(accidenteClick, accidenteDblClick, "Has seleccionado accidente de tránsito.");
        });

        otrosClick.addEventListener("click", function() {
            click(otrosClick, otrosDblClick, "Has seleccionado otros.");
        });

        // Agregando el evento dblclick
        incendioDblClick.addEventListener("dblclick", function() {
            dblClick("Incendio");
        });

        roboDblClick.addEventListener("dblclick", function() {
            dblClick("Robo / Asalto");
        });

        accidenteDblClick.addEventListener("dblclick", function() {
            dblClick("Accidente de transito");
        });

        otrosDblClick.addEventListener("dblclick", function() {
            dblClick("Otros");
        });


        function click(elementoClick, elementoDblClick, paragraphText) {
            // Eliminar la clase "view-button" de todos los elementos dblclick y agregar "view-button" a los elementos click
            incendioDblClick.classList.remove("view-button");
            roboDblClick.classList.remove("view-button");
            accidenteDblClick.classList.remove("view-button");
            otrosDblClick.classList.remove("view-button");
            incendioClick.classList.add("view-button");
            roboClick.classList.add("view-button");
            accidenteClick.classList.add("view-button");
            otrosClick.classList.add("view-button");

            // Agregar la clase "hidden-button" y "view-button" al boton seleccionado
            elementoClick.classList.add("hidden-button");
            elementoDblClick.classList.add("view-button");
            paragraph.textContent = paragraphText;
            guide.classList.add("view-guide");
        }

        function dblClick(tipoAyudaText) {
            texts.classList.add("hidden-texts");
            let form = document.getElementById("form-ayuda");
            form.classList.add("overflow-auto"),
            tipoAyuda.value = tipoAyudaText;
        }

        // Temporizador
        let tiempoRestante = 240;
        let cuentaAtras = document.getElementById('temporizador');

        // Iniciar el temporizador
        let temporizador = setTimeout(actualizarTemporizador, 1000);

        // Función que se ejecuta cada segundo
        function actualizarTemporizador() {
            let minutos = Math.floor(tiempoRestante / 60);
            let segundos = tiempoRestante % 60;

            // Formatear el tiempo en minutos y segundos
            let tiempoFormateado = `${minutos}:${segundos < 10 ? '0' : ''}${segundos}`;

            cuentaAtras.textContent = tiempoFormateado;

            if (tiempoRestante === 0) {
                clearTimeout(temporizador);
                alert("Se acabo el tiempo, seras redirigido a la página Iniciar Sesión");
                setTimeout(function() {
                    window.location.href = "salir.php";
                }, 1000);
            } else {
                tiempoRestante--;
                temporizador = setTimeout(actualizarTemporizador, 1000);
            }
        }
    </script>
</body>
</html>