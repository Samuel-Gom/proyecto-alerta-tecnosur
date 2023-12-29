<?php
    include("conexion.php");
    session_start();

    // OBTENIENDO LOS VALORES
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $nombres = $_POST['txtNombres'];
        $apellidos = $_POST['txtApellidos'];
        $dni = $_POST['txtDni'];
        $celular = $_POST['txtCelular'];
        $imagen = 'default.jpg';
    }

    // INSERTAR
    if (isset($_POST['btnRegistrar'])) {
        if (!empty($nombres) && !empty($apellidos) && !empty($dni) && !empty($celular)) {
            if(is_numeric($dni) && is_numeric($celular) && strlen($dni) == 8 && strlen($celular) == 9) {
                // SI EL USUARIO SUBIO UNA IMAGEN
                if (isset($_FILES['filImagen']) && $_FILES['filImagen']['error'] == 0) {
                    // SOLO SE PODRA SUBIR ARCHIVOS TIPO IMAGEN
                    if ($_FILES['filImagen']['type'] == 'image/jpeg' || $_FILES['filImagen']['type'] == 'image/png') {
                        // OBTENEMOS LA EXTENSION DE LA IMAGEN SUBIDA
                        $imagenSubida = pathinfo($_FILES['filImagen']['name']);
                        $extension = $imagenSubida['extension'];
                        // UNIMOS EL DNI CON LA EXTENSION, ESTE SERA EL NOMBRE DE LA IMAGEN
                        $imagen = "$dni.$extension";
    
                        $temporal = $_FILES['filImagen']['tmp_name'];
                        $destino = "images/users/$imagen";
                        // MOVEMOS DE LA CARPETA TEMPORAL A NUESTRA CARPETA ESPECIFICADA
                        move_uploaded_file($temporal, $destino);
                    } else {
                        header('Location: registro.php?error=archivo');
                        exit();
                    }
                }

                $insertar = $conexion -> query("INSERT INTO usuario(Dni, Celular, Nombres, Apellidos, Imagen, Flag) VALUES('$dni', '$celular', '$nombres', '$apellidos', '$imagen', 'n')");

                if ($insertar) {
                    echo "
                    <script>
                        alert('¡Se ha registrado exitosamente!');
                        setTimeout(function() {
                            window.location.href = 'index.php';
                        }, 0);
                    </script>";
                } else {
                    echo "<script>alert('Ocurrio un error al registrar. El dni ingresado ya esta en uso.')</script>";
                }
            } else {
                header('Location: registro.php?error=valor');
                exit();
            }
        } else {
            header('Location: registro.php?error=si');
            exit();
        }
    }

    // BUSCAR
    // SI LA VARIABLE DE SESION NO EXISTE, LA INICIALIZAMOS COMO FALSE
    if (!isset($_SESSION['busquedaRealizada'])) {
        $_SESSION['busquedaRealizada'] = false;
    }

    if (isset($_POST['btnBuscar'])) {
        if (!empty($dni)) {
            $busqueda = $conexion -> query("SELECT * FROM usuario WHERE Dni = '$dni'");

            if ($fila = $busqueda -> fetch_assoc()) {
                $nombres = $fila['Nombres'];
                $apellidos = $fila['Apellidos'];
                $dni = $fila['Dni'];
                $celular = $fila['Celular'];
                $imagen = $fila['Imagen'];

                // MARCAMOS LA VARIBLE DE SESION COMO TRUE, PARA INDICAR QUE SI SE REALIZO UNA BUSQUEDA EXITOSA
                $_SESSION['busquedaRealizada'] = true;
            } else {
                echo "<script>alert('No se encontraron coincidencias.')</script>";
            }
        } else {
            echo "
            <script>
                alert('Para realizar una busqueda, el campo Dni es requerido.');
                setTimeout(function() {
                    window.location.href = 'registro.php';
                }, 0);
            </script>";
        }
    } 

    // ACTUALIZAR
    if (isset($_POST['btnActualizar'])) {
        if ($_SESSION['busquedaRealizada']) {
            if (!empty($nombres) && !empty($apellidos) && !empty($dni) && !empty($celular)) {
                if(is_numeric($dni) && is_numeric($celular) && strlen($dni) == 8 && strlen($celular) == 9) {
                    // SI EL USUARIO SUBIO UNA IMAGEN
                    if (isset($_FILES['filImagen']) && $_FILES['filImagen']['error'] == 0) {
                        // SOLO SE PODRA SUBIR ARCHIVOS TIPO IMAGEN
                        if ($_FILES['filImagen']['type'] == 'image/jpeg' || $_FILES['filImagen']['type'] == 'image/png') {
                            // OBTENEMOS LA EXTENSION DE LA IMAGEN SUBIDA
                            $imagenSubida = pathinfo($_FILES['filImagen']['name']);
                            $extension = $imagenSubida['extension'];
                            // UNIMOS EL DNI CON LA EXTENSION, ESTE SERA EL NOMBRE DE LA IMAGEN
                            $imagen = "$dni.$extension";
        
                            $temporal = $_FILES['filImagen']['tmp_name'];
                            $destino = "images/users/$imagen";
                            // MOVEMOS DE LA CARPETA TEMPORAL A NUESTRA CARPETA ESPECIFICADA
                            move_uploaded_file($temporal, $destino);
                        } else {
                            header('Location: registro.php?error=archivo');
                            exit();
                        }
                    } else {
                        // EL NOMBRE DE LA IMAGEN DEL USUARIO BUSCADO, SI EL USUARIO NO SELECCIONA UNA NUEVA IMAGEN
                        $imagen = $_POST['imagenAntiguo'];
                    }
    
                    // EN DNI DEL USUARIO BUSCADO, CON EL CUAL ACTUALIZAREMOS EL REGISTRO
                    $dniAntiguo = $_POST['txtDniAntiguo'];
    
                    $actualizar = $conexion -> query("UPDATE usuario SET Dni = '$dni', Celular = '$celular', Nombres = '$nombres', Apellidos = '$apellidos', Imagen = '$imagen' WHERE Dni = '$dniAntiguo'");
    
                    if ($actualizar) {
                        // CUANDO SE ACTUALICE CORRECTAMENTE, MARCAMOS LA VARIABLE DE SESION COMO FALSE, PARA VOLVER A VALIDAR LA BUSQUEDA
                        $_SESSION['busquedaRealizada'] = false;

                        echo "
                        <script>
                            alert('Datos actualizados correctamente.');
                            setTimeout(function() {
                                window.location.href = 'index.php';
                            }, 0);
                        </script>";
                    } else {
                        echo "<script>alert('Ocurrio un error al actualizar.')</script>";
                    }
                } else {
                    header('Location: registro.php?error=valor');
                    exit();
                }
            } else {
                header('Location: registro.php?error=si');
                exit();
            }
        } else {
            echo "
            <script>
                alert('Para actualizar un registro, necesita realizar una busqueda.');
                setTimeout(function() {
                    window.location.href = 'registro.php';
                }, 0);
            </script>";
        }   
    }

    // ELIMINAR
    if (isset($_POST['btnEliminar'])) {
        if ($_SESSION['busquedaRealizada']) {
            $eliminarRegistrosAyuda = $conexion -> query("DELETE FROM ayuda WHERE Dni = '$dni'");
            $eliminar = $conexion -> query("DELETE FROM usuario WHERE Dni = '$dni'");

            if ($eliminar && $eliminarRegistrosAyuda) {
                // CUANDO SE ELIMINE CORRECTAMENTE, MARCAMOS LA VARIABLE DE SESION COMO FALSE, PARA VOLVER A VALIDAR LA BUSQUEDA
                $_SESSION['busquedaRealizada'] = false;

                echo "<script>alert('Datos eliminados correctamente.')</script>";
            } else {
                echo "<script>alert('Ocurrio un error al eliminar.')</script>";
            }
        } else {
            echo "
            <script>
                alert('Para eliminar un registro, necesita realizar una busqueda.');
                setTimeout(function() {
                    window.location.href = 'registro.php';
                }, 0);
            </script>";
        }    
    }

    $conexion -> close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="hover.css">
</head>
<body>
    <div class="center center--register">
        <section class="container-figure">
            <figure class="figure">
                <img class="figure__img" src="./images/registro.svg" alt="Imagen Login">
            </figure>
        </section>
        <section class="container-form">
            <form class="form form--register" action="" method="post" enctype="multipart/form-data">
                <div class="container-texts">
                    <h1 class="form__title">Crea una cuenta</h1>
                    <p class="form__paragraph">¿Tienes una cuenta? <a class="link" href="index.php">Acceder</a></p>
                </div>
                <input type="hidden" name="txtDniAntiguo" value="<?php if(isset($_POST['btnBuscar']) && !empty($dni)) {echo $dni;} ?>">
                <input type="hidden" name="imagenAntiguo" value="<?php if(isset($_POST['btnBuscar']) && !empty($dni)) {echo $imagen;} ?>">
                <div class="container-all">
                    <div class="container-all-inputs">
                        <div class="group group--register">
                            <input id="nombres" class="form__input" name="txtNombres" type="text" placeholder=" "  value="<?php if(isset($_POST['btnBuscar']) && !empty($dni)) {echo $nombres;} ?>" >
                            <label class="form__label" for="nombres">Nombres</label>
                            <?php
                                if (isset($_GET['error']) && $_GET['error'] == 'si') {
                                    echo "<span class='form__error'>Este campo es requerido</span>";
                                }
                            ?>
                        </div>
                        <div class="group group--register">
                            <input id="apellidos" class="form__input" name="txtApellidos" type="text" placeholder=" "  value="<?php if(isset($_POST['btnBuscar']) && !empty($dni)) {echo $apellidos;} ?>" >
                            <label class="form__label" for="apellidos">Apellidos</label>
                            <?php
                                if (isset($_GET['error']) && $_GET['error'] == 'si') {
                                    echo "<span class='form__error'>Este campo es requerido</span>";
                                }
                            ?>
                        </div>
                        <div class="group group--register">
                            <input id="dni" class="form__input" name="txtDni" type="text" placeholder=" " value="<?php if(isset($_POST['btnBuscar']) && !empty($dni)) {echo $dni;} ?>" >
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
                        <div class="group group--register">
                            <input id="celular" class="form__input" name="txtCelular" type="text" placeholder=" "value="<?php if(isset($_POST['btnBuscar']) && !empty($dni)) {echo $celular;} ?>" >
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
                    </div>
                    <div class="view_image">
                        <img id="image" class="visualizar" src="./images/users/<?php if(isset($_POST['btnBuscar']) && !empty($dni)) {echo $imagen;} else { echo 'default.jpg'; } ?>">
                        <label class="label-image" for="uploadImage">Seleccionar Imagen</label>
                        <input id="uploadImage" class="form__input" name="filImagen" accept=".jpg, .jpeg, .png" type="file" >
                        <?php
                            if (isset($_GET['error']) && $_GET['error'] == 'archivo') {
                                echo "<span class='form__error form__error--file'>Solo se permiten archivos. ( jpg, jpeg o png )</span>";
                            }
                        ?>
                    </div>
                </div>
                <div class="container-options-register">
                    <input id="registrar" class="form__input form__input--submit form__input--register hvr-pop" name="btnRegistrar" type="submit" value="Registrarme">
                    <input id="actualizar" class="form__input form__input--submit form__input--register secundary" name="btnActualizar" type="submit" value="Actualizar">
                    <input id="eliminar" class="form__input form__input--submit form__input--register secundary" name="btnEliminar" type="submit" value="Eliminar">
                    <input id="buscar" class="form__input form__input--submit form__input--register secundary" name="btnBuscar" type="submit" value="Buscar">
                </div>
            </form>
        </section>
    </div>

    <script>
        function init() {
            let inputFile = document.getElementById('uploadImage');
            inputFile.addEventListener('change', mostrarImagen);
        }

        function mostrarImagen(event) {
            let file = event.target.files[0];
            let reader = new FileReader();
            
            reader.onload = function(event) {
                let img = document.getElementById('image');
                img.src = event.target.result;
            }

            reader.readAsDataURL(file);
        }

        // La función init se llama cuando la ventana ha terminado de cargarse, y escuche una evento de subida
        window.addEventListener('load', init);

        // Validacion de los campos cuando se registre el usuario
        let nombres = document.getElementById("nombres");
        let apellidos = document.getElementById("apellidos");
        let dni = document.getElementById("dni");
        let celular = document.getElementById("celular");
        let registrar = document.getElementById("registrar");
        let actualizar = document.getElementById("actualizar");
        let eliminar = document.getElementById("eliminar");
        let buscar = document.getElementById("buscar");

        // Agregamos a los inputs las respectivas validaciones, solo cuando sea registrar
        registrar.addEventListener("click", function() {
            nombres.setAttribute("required", true);
            apellidos.setAttribute("required", true);
            dni.setAttribute("required", true);
            dni.setAttribute("pattern", "[0-9]{8}");
            dni.setAttribute("title", "Solo se permiten números. (8 digitos)");
            celular.setAttribute("required", true);
            celular.setAttribute("pattern", "[0-9]{9}");
            celular.setAttribute("title", "Solo se permiten números. (9 digitos)");
        });

        // Eliminamos todos los atributos agregados cuando se da click en otro boton
        actualizar.addEventListener("click", function() {
            nombres.removeAttribute("required");
            apellidos.removeAttribute("required");
            dni.removeAttribute("required");
            dni.removeAttribute("pattern");
            dni.removeAttribute("title");
            celular.removeAttribute("required");
            celular.removeAttribute("pattern");
            celular.removeAttribute("title");
        });

        eliminar.addEventListener("click", function() {
            nombres.removeAttribute("required");
            apellidos.removeAttribute("required");
            dni.removeAttribute("required");
            dni.removeAttribute("pattern");
            dni.removeAttribute("title");
            celular.removeAttribute("required");
            celular.removeAttribute("pattern");
            celular.removeAttribute("title");
        });

        buscar.addEventListener("click", function() {
            nombres.removeAttribute("required");
            apellidos.removeAttribute("required");
            dni.removeAttribute("required");
            dni.removeAttribute("pattern");
            dni.removeAttribute("title");
            celular.removeAttribute("required");
            celular.removeAttribute("pattern");
            celular.removeAttribute("title");
        });
    </script>
</body>
</html>