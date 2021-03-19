<?php include("personalizado/php/comunes/manejaSesion.php"); ?>


<!DOCTYPE html>


<html lang="es">
    <head>
        <?php include("personalizado/php/estructura/head.php"); ?>

        <style type="text/css">
            .ms-container {
                width: 100%;
            }
        </style>
    </head>


    <body>
        <?php

            // Obtiene parametros de request

            $esSubmit = filter_input(INPUT_POST, "esSubmit");
            $id = filter_input(INPUT_POST, "id");
            $nombre = filter_input(INPUT_POST, "nombre");
            $apellido = filter_input(INPUT_POST, "apellido");
            $telefono = filter_input(INPUT_POST, "telefono");
            $correoElectronico = filter_input(INPUT_POST, "correoElectronico");
            $puesto = filter_input(INPUT_POST, "puesto");
            $curriculum = filter_input(INPUT_POST, "curriculum");

            // Parametros enviados por origen

            $origen = filter_input(INPUT_POST, "origen");

            // Inicializa variables

            $mensaje = "";
            $fechaActual = date("Y-m-d H:i:s");
            $imagen = "";
            $prefijoImagenes = generaCadenaAleatoria(5);

            // Procesa el request

            if (!estaVacio($esSubmit) && $esSubmit === "1") {

                // Valida los campos obligatorios

                if (estaVacio($nombre)) {
                    $mensaje .= "* Nombre<br />";
                }

                if (estaVacio($apellido)) {
                    $mensaje .= "* Apellido<br />";
                }

                if (estaVacio($telefono)) {
                    $mensaje .= "* Teléfono<br />";
                }

                if (estaVacio($correoElectronico)) {
                    $mensaje .= "* Correo electrónico<br />";
                } else if (!filter_var($correoElectronico, FILTER_VALIDATE_EMAIL)) {
                    $mensaje .= "* Correo electrónico con formato correcto<br />";
                }

                if (estaVacio($puesto)) {
                    $mensaje .= "* Puesto<br />";
                }

                if (!estaVacio($mensaje)) {
                    $mensaje = "Proporciona los siguientes datos:<br /><br />" . $mensaje;
                } else {
                    if (estaVacio($id)) {

                        // Es insercion

                        $instructor_BD = consulta($conexion, "SELECT id FROM instructor WHERE correoElectronico = '" . $correoElectronico . "'");

                        if (cuentaResultados($instructor_BD) > 0) {
                            $mensaje = "El instructor ya se encuentra registrado en la base de datos";
                        } else {
                            consulta($conexion, "INSERT INTO instructor ("
                                    . "nombre"
                                    . ", apellido"
                                    . ", telefono"
                                    . ", correoElectronico"
                                    . ", puesto"
                                    . ", curriculum"
                                . ") VALUES ("
                                    . "'" . $nombre . "'"
                                    . ", '" . $apellido . "'"
                                    . ", '" . $telefono . "'"
                                    . ", '" . $correoElectronico . "'"
                                    . ", '" . $puesto . "'"
                                    . ", " . (estaVacio($curriculum) ? "NULL" : "'" . mysqli_real_escape_string($conexion, $curriculum) . "'")
                                . ")");

                            // Carga informacion actualizada

                            $instructor_BD = consulta($conexion, "SELECT * FROM instructor WHERE correoElectronico = '" . $correoElectronico . "'");
                            $instructor = obtenResultado($instructor_BD);

                            $id = $instructor["id"];
                            $nombre = $instructor["nombre"];
                            $apellido = $instructor["apellido"];
                            $telefono = $instructor["telefono"];
                            $correoElectronico = $instructor["correoElectronico"];
                            $puesto = $instructor["puesto"];
                            $curriculum = $instructor["curriculum"];
                            $imagen = $instructor["imagen"];

                            registraEvento("Alta de instructor | id = " . $id);

                            $mensaje = "ok - El instructor ha sido registrado";
                        }
                    } else {

                        // Es actualizacion

                        consulta($conexion, "UPDATE instructor SET "
                                . "nombre = '" . $nombre . "'"
                                . ", apellido = '" . $apellido . "'"
                                . ", telefono = '" . $telefono . "'"
                                . ", correoElectronico = '" . $correoElectronico . "'"
                                . ", puesto = '" . $puesto . "'"
                                . ", curriculum = " . (estaVacio($curriculum) ? "NULL" : "'" . mysqli_real_escape_string($conexion, $curriculum) . "'")
                            . " WHERE id = " . $id);

                        // Carga imagen de instructor

                        if (isset($_FILES["imagen"])) {
                            try {
                                $archivo = $_FILES["imagen"];

                                if ($archivo["size"] > 0) {
                                    $archivo_ruta = $constante_directorioInstructor . $id . "/";
                                    $archivo_nombre = $id . "_imagen." . pathinfo($archivo["name"], PATHINFO_EXTENSION);

                                    if (!file_exists($archivo_ruta)) {
                                        mkdir($archivo_ruta, 0755, true);
                                    }

                                    move_uploaded_file($archivo["tmp_name"], $archivo_ruta . $archivo_nombre);

                                    consulta($conexion, "UPDATE instructor SET imagen = " . (estaVacio($archivo_nombre) ? "NULL" : "'" . $archivo_nombre . "'") . " WHERE id = " . $id);
                                }
                            } catch (Exception $e) {
                            }
                        }

                        // Carga informacion actualizada

                        $instructor_BD = consulta($conexion, "SELECT * FROM instructor WHERE id = " . $id);
                        $instructor = obtenResultado($instructor_BD);

                        $id = $instructor["id"];
                        $nombre = $instructor["nombre"];
                        $apellido = $instructor["apellido"];
                        $telefono = $instructor["telefono"];
                        $correoElectronico = $instructor["correoElectronico"];
                        $puesto = $instructor["puesto"];
                        $curriculum = $instructor["curriculum"];
                        $imagen = $instructor["imagen"];

                        registraEvento("Actualizacion de instructor | id = " . $id);

                        $mensaje = "ok - Los cambios han sido guardados";
                    }
                }
            } else {
                if (!estaVacio($id)) {

                    // Es consulta

                    $instructor_BD = consulta($conexion, "SELECT * FROM instructor WHERE id = " . $id);
                    $instructor = obtenResultado($instructor_BD);

                    $id = $instructor["id"];
                    $nombre = $instructor["nombre"];
                    $apellido = $instructor["apellido"];
                    $telefono = $instructor["telefono"];
                    $correoElectronico = $instructor["correoElectronico"];
                    $puesto = $instructor["puesto"];
                    $curriculum = $instructor["curriculum"];
                    $imagen = $instructor["imagen"];

                    registraEvento("Consulta de instructor | id = " . $id);
                }
            }
        ?>

        <!-- Preloader -->

        <div class="preloader-it">
            <div class="la-anim-1"></div>
        </div>

        <div class="wrapper">
            <?php include("personalizado/php/estructura/encabezado.php"); ?>

            <?php include("personalizado/php/estructura/menu.php"); ?>

            <!-- Contenido -->

            <div class="page-wrapper">
                <div class="container-fluid">
                    <?php if ($esUsuarioMaster || $usuario_permisoConsultarInstructores || $usuario_permisoEditarInstructores) { ?>

                        <!-- Titulo -->

                        <div class="row heading-bg bg-blue">
                            <div class="col-xs-12">
                                <h5 class="txt-light">Detalle de instructor</h5>
                            </div>
                        </div>

                        <!-- Bloques de informacion -->

                        <form action="instructor.php" enctype="multipart/form-data" method="post">
                            <input name="esSubmit" type="hidden" value="1" />
                            <input name="id" type="hidden" value="<?php echo $id ?>" />

                            <input name="origen" type="hidden" value="<?php echo $origen ?>" />

                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="panel panel-default card-view">
                                        <div class="panel-wrapper collapse in">
                                            <div class="panel-body">
                                                <div class="alert" id="contenedor_mensaje">
                                                    <span></span>
                                                </div>

                                                <!-- Generales -->

                                                <div class="panel panel-default card-view">
                                                    <div class="panel-heading">
                                                        <div class="pull-left">
                                                            <h6 class="panel-title txt-dark">
                                                                Proporciona la información del instructor
                                                            </h6>

                                                            <hr />
                                                        </div>

                                                        <div class="clearfix"></div>
                                                    </div>

                                                    <div class="panel-wrapper collapse in">
                                                        <div class="panel-body">
                                                            <div class="row">
                                                                <div class="col-sm-12 col-xs-12">
                                                                    <div class="form-wrap">
                                                                        <div class="form-body">
                                                                            <div class="row mb-30">
                                                                                <div class="col-md-12">
                                                                                    <h5><strong>Información de control</strong></h5>
                                                                                </div>
                                                                            </div>

                                                                            <div class="row mb-30">
                                                                                <div class="col-md-6">
                                                                                    <div class="form-group">
                                                                                        <label class="control-label mb-10">Id</label>
                                                                                        <input class="form-control" readonly type="text" value="<?php echo $id ?>" />
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <br /><br />

                                                                            <div class="row mb-30">
                                                                                <div class="col-md-12">
                                                                                    <h5><strong>Datos generales</strong></h5>
                                                                                </div>
                                                                            </div>

                                                                            <div class="row mb-30">

                                                                                <div class="col-md-6">
                                                                                    <div class="form-group">
                                                                                        <label class="control-label mb-10">Nombre <span class="txt-danger ml-10">*</span></label>
                                                                                        <input class="form-control" name="nombre" type="text" value="<?php echo $nombre ?>" />
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <div class="form-group">
                                                                                        <label class="control-label mb-10">Apellido <span class="txt-danger ml-10">*</span></label>
                                                                                        <input class="form-control" name="apellido" type="text" value="<?php echo $apellido ?>" />
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="row mb-30">
                                                                                <div class="col-md-6">
                                                                                    <div class="form-group">
                                                                                        <label class="control-label mb-10">Teléfono<span class="txt-danger ml-10">*</span></label>
                                                                                        <input class="form-control" name="telefono" maxlength="10" type="text" value="<?php echo $telefono ?>" />
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <div class="form-group">
                                                                                        <label class="control-label mb-10">Correo electrónico <span class="txt-danger ml-10">*</span></label>
                                                                                        <input class="form-control" name="correoElectronico" type="text" value="<?php echo $correoElectronico ?>" />
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="row mb-30">
                                                                                <div class="col-md-6">
                                                                                    <div class="form-group">
                                                                                        <label class="control-label mb-10">Puesto <span class="txt-danger ml-10">*</span></label>
                                                                                        <input class="form-control" name="puesto"  type="text" value="<?php echo $puesto ?>" />
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="row mb-30">
                                                                                <div class="col-md-12">
                                                                                    <div class="form-group">
                                                                                        <label class="control-label mb-10">Curriculum</label>
                                                                                        <textarea class="tinymce" name="curriculum"><?php echo $curriculum ?></textarea>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <?php if (!estaVacio($id)) { ?>
                                                                                <div class="row mb-30">
                                                                                    <div class="col-md-6">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label mb-10">Fotografía del instructor</label>

                                                                                            <span>
                                                                                                <br />
                                                                                                Formatos aceptados: .jpg, .jpeg, .png
                                                                                                <br />
                                                                                                Tamaño preferente: 278 x 319 pixeles
                                                                                                <br />
                                                                                            </span>

                                                                                            <div>
                                                                                                <br />
                                                                                                <input name="imagen" type="file" />
                                                                                                <br />
                                                                                                <div class="row">
                                                                                                    <div class="col-sm-12">
                                                                                                        <div class="panel panel-success card-view">
                                                                                                            <div class="panel-wrapper collapse in">
                                                                                                                <div class="panel-body">
                                                                                                                    <ul class="chat-list-wrap">
                                                                                                                        <li class="chat-list">
                                                                                                                            <div class="chat-body">
                                                                                                                                <?php
                                                                                                                                    if (!estaVacio($imagen)) {
                                                                                                                                        echo "<div class='chat-data'>";
                                                                                                                                        echo "<img class='user-img' src='" . $constante_urlInstructor . $id . "/" . $imagen . "?" . $prefijoImagenes . "' />";

                                                                                                                                        echo "<div class='user-data'>";
                                                                                                                                        echo "<span class='name block'>" . $imagen . "</span>";
                                                                                                                                        echo "<span class='time block txt-grey'>";
                                                                                                                                        echo "<a data-lightbox='imagen' href='" . $constante_urlInstructor . $id . "/" . $imagen . "?" . $prefijoImagenes . "'>Ampliar</a>";
                                                                                                                                        echo "&nbsp;&nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;&nbsp;&nbsp;";
                                                                                                                                        echo "<a download href='" . $constante_urlInstructor . $id . "/" . $imagen . "?" . $prefijoImagenes . "'>Descargar</a>";
                                                                                                                                        echo "</span>";
                                                                                                                                        echo "</div>";
                                                                                                                                        echo "<div class='clearfix'></div>";
                                                                                                                                        echo "</div>";
                                                                                                                                    }
                                                                                                                                ?>
                                                                                                                            </div>
                                                                                                                        </li>
                                                                                                                    </ul>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            <?php } ?>
                                                                        </div>

                                                                        <div class="form-actions mt-50">
                                                                            <?php if ($esUsuarioMaster || $usuario_permisoEditarInstructores) { ?>
                                                                                <button class="btn btn-success" type="submit">Guardar</button>
                                                                            <?php } ?>

                                                                            <?php if (!estaVacio($origen)) { ?>
                                                                                <a class="btn btn-default ml-10 link_origen" type="button">Volver</a>
                                                                            <?php } ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <!-- Formulario de retorno a pagina origen -->

                        <form action="<?php echo $origen ?>" id="formulario_origen" method="post">
                            <input name="esSubmit" type="hidden" value="1" />
                        </form>
                    <?php
                        } else {
                            registraEvento("Consulta de instructor bloqueada | id = " . $id);
                            muestraBloqueo();
                        }
                    ?>

                    <?php include("personalizado/php/estructura/pieDePagina.php"); ?>
                </div>
            </div>
        </div>

        <?php include("personalizado/php/estructura/plugins.php"); ?>

        <!-- Tinymce JavaScript -->
        <script src="vendors/bower_components/tinymce/tinymce.min.js"></script>

        <!-- Tinymce Wysuhtml5 Init JavaScript -->
        <script src="dist/js/tinymce-data.js"></script>

        <!--
         Lightbox
         http://lokeshdhakar.com/projects/lightbox2/
        -->
        <link href="personalizado/js/lightbox2-master/dist/css/lightbox.min.css" rel="stylesheet">
        <script src="personalizado/js/lightbox2-master/dist/js/lightbox.min.js"></script>


        <?php include("personalizado/php/estructura/scripts.php"); ?>


        <!-- Scripts -->


        <script>
            $(function() {
                var mensaje = "<?php echo $mensaje ?>";

                if (mensaje !== "") {
                    $("#contenedor_mensaje").hide();
                    $("#contenedor_mensaje").removeClass("alert-success");
                    $("#contenedor_mensaje").removeClass("alert-danger");

                    if (mensaje.startsWith("ok - ")) {
                        $("#contenedor_mensaje span").html(mensaje.substring(5));
                        $("#contenedor_mensaje").addClass("alert-success");
                        $("#contenedor_mensaje").show();
                    } else {
                        $("#contenedor_mensaje span").html(mensaje);
                        $("#contenedor_mensaje").addClass("alert-danger");
                        $("#contenedor_mensaje").show();
                    }

                    $("html, body").animate({ scrollTop: 0 }, "slow");
                }
            });


            // Regresa a la interfaz de origen


            $(".link_origen").click(function() {
                $("#formulario_origen").submit();
            });
        </script>
    </body>
</html>
