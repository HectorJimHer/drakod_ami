<?php include("personalizado/php/comunes/manejaSesion.php"); ?>


<!DOCTYPE html>


<html lang="es">
    <head>
        <?php include("personalizado/php/estructura/head.php"); ?>
    </head>


    <body>
        <?php

            // Obtiene parametros de request

            $esSubmit = filter_input(INPUT_POST, "esSubmit");
            $id = filter_input(INPUT_POST, "id");
            $fechaRegistro = filter_input(INPUT_POST, "fechaRegistro");
            $habilitado = filter_input(INPUT_POST, "habilitado");
            $nombre = filter_input(INPUT_POST, "nombre");
            $resumen = filter_input(INPUT_POST, "resumen");
            $descripcion = filter_input(INPUT_POST, "descripcion");
            $duracion = filter_input(INPUT_POST, "duracion");
            $linkVideo = filter_input(INPUT_POST, "linkVideo");
            $examenHabilitado = filter_input(INPUT_POST, "examenHabilitado");
            $idExamen = filter_input(INPUT_POST, "idExamen");

            $cantidadArchivos = filter_input(INPUT_POST, "cantidadArchivos");

            if(estaVacio($idExamen)){
                $idExamen = 0;
            }


            // Parametros enviados por origen

            $origen = filter_input(INPUT_POST, "origen");

            // Inicializa variables

            $mensaje = "";
            $fechaActual = date("Y-m-d H:i:s");
            $habilitado = estaVacio($habilitado) ? 0 : 1;
            $examenHabilitado = estaVacio($examenHabilitado) ? 0 : 1;
            $prefijoImagenes = generaCadenaAleatoria(5);

            $imagen = "";
            $indiceArchivos = 0;

            // Procesa el request

            if (!estaVacio($esSubmit) && $esSubmit === "1") {

                // Valida los campos obligatorios

                if (estaVacio($nombre)) {
                    $mensaje .= "* Nombre<br />";
                }

                if (estaVacio($duracion)) {
                    $mensaje .= "* Duración<br />";
                }

                if (estaVacio($resumen)) {
                    $mensaje .= "* Resumen<br />";
                }

                if (!estaVacio($mensaje)) {
                    $mensaje = "Proporciona los siguientes datos:<br /><br />" . $mensaje;

                    $modulo_BD = consulta($conexion, "SELECT imagen FROM modulo WHERE id = " . $id);
                    $modulo = obtenResultado($modulo_BD);

                    $imagen = $modulo["imagen"];
                } else {
                    if (estaVacio($id)) {

                        // Es insercion

                        $modulo_BD = consulta($conexion, "SELECT id FROM modulo WHERE nombre = '" . $nombre . "'");

                        if (cuentaResultados($post_BD) > 0) {
                            $mensaje = "Este módulo ya se encuentra registrado en la base de datos";
                        } else {
                            consulta($conexion, "INSERT INTO modulo ("
                                    . "fechaRegistro"
                                    . ", habilitado"
                                    . ", nombre"
                                    . ", resumen"
                                    . ", descripcion"
                                    . ", duracion"
                                    . ", linkVideo"
                                    . ", examenHabilitado"
                                    . ", idExamen"
                                . ") VALUES ("
                                    . "'" . $fechaActual . "'"
                                    . ", " . $habilitado
                                    . ", '" . $nombre . "'"
                                    . ", '" . mysqli_real_escape_string($conexion, $resumen) . "'"
                                    . ", " . (estaVacio($descripcion) ? "NULL" : "'" . mysqli_real_escape_string($conexion, $descripcion) . "'")
                                    . ", " . (estaVacio($duracion) ? "NULL" : "'" . $duracion . "'")
                                    . ", " . (estaVacio($linkVideo) ? "NULL" : "'" . $linkVideo . "'")
                                    . ", " . $examenHabilitado
                                    . ", " . $idExamen
                                . ")");

                            // Carga informacion actualizada

                            $modulo_BD = consulta($conexion, "SELECT * FROM modulo WHERE nombre = '" . $nombre . "'");
                            $modulo = obtenResultado($modulo_BD);

                            $id = $modulo["id"];
                            $fechaRegistro = $modulo["fechaRegistro"];
                            $habilitado = $modulo["habilitado"];
                            $nombre = $modulo["nombre"];
                            $resumen = $modulo["resumen"];
                            $descripcion = $modulo["descripcion"];
                            $duracion = $modulo["duracion"];
                            $linkVideo = $modulo["linkVideo"];
                            $imagen = $modulo["imagen"];
                            $examenHabilitado = $modulo["examenHabilitado"];
                            $idExamen = $modulo["idExamen"];


                            registraEvento("Alta de módulo | id = " . $id);

                            $mensaje = "ok - El módulo ha sido registrado";
                        }
                    } else {

                        // Es actualizacion

                        consulta($conexion, "UPDATE modulo SET"
                                . " habilitado = " . $habilitado
                                . ", nombre = '" . $nombre . "'"
                                . ", resumen = '" . mysqli_real_escape_string($conexion, $resumen) . "'"
                                . ", descripcion = " . (estaVacio($descripcion) ? "NULL" : "'" . mysqli_real_escape_string($conexion, $descripcion) . "'")
                                . ", duracion = " . (estaVacio($duracion) ? "NULL" : "'" . $duracion . "'")
                                . ", linkVideo = " . (estaVacio($linkVideo) ? "NULL" : "'" . $linkVideo . "'")
                                . ", examenHabilitado = " . $examenHabilitado
                                . ", idExamen = " .$idExamen
                            . " WHERE id = " . $id);

                        // Carga imagen principal

                        if (isset($_FILES["imagen"])) {
                            try {
                                $archivo = $_FILES["imagen"];

                                if ($archivo["size"] > 0) {
                                    $archivo_ruta = $constante_directorioModulo . $id . "/";
                                    $archivo_nombre = $id . "_imagen." . pathinfo($archivo["name"], PATHINFO_EXTENSION);

                                    if (!file_exists($archivo_ruta)) {
                                        mkdir($archivo_ruta, 0755, true);
                                    }

                                    move_uploaded_file($archivo["tmp_name"], $archivo_ruta . $archivo_nombre);

                                    consulta($conexion, "UPDATE modulo SET imagen = " . (estaVacio($archivo_nombre) ? "NULL" : "'" . $archivo_nombre . "'") . " WHERE id = " . $id);
                                }
                            } catch (Exception $e) {
                            }
                        }

                        // Carga archivos

                        if (!estaVacio($cantidadArchivos) && $cantidadArchivos > 0) {
                            for ($indiceArchivos = 0; $indiceArchivos < $cantidadArchivos; $indiceArchivos++) {
                                $archivo_id = filter_input(INPUT_POST, "archivo_id_" . $indiceArchivos);
                                $archivo_titulo = filter_input(INPUT_POST, "archivo_titulo_" . $indiceArchivos);
                                $archivo_orden = filter_input(INPUT_POST, "archivo_orden_" . $indiceArchivos);

                                if (!estaVacio($archivo_titulo) && !estaVacio($archivo_orden)) {
                                    if (estaVacio($archivo_id) && isset($_FILES["archivo_archivo_" . $indiceArchivos]) && $_FILES["archivo_archivo_" . $indiceArchivos]["size"] > 0) {
                                        consulta($conexion, "INSERT INTO modulo_archivo (idModulo, titulo, nombre, orden) VALUES (" . $id . ", '" . $archivo_titulo . "', '', " . $archivo_orden . ")");

                                        $archivo_id = obtenResultado(consulta($conexion, "SELECT MAX(id) AS id FROM modulo_archivo WHERE idModulo = " . $id))["id"];

                                        if (isset($_FILES["archivo_archivo_" . $indiceArchivos]) && $_FILES["archivo_archivo_" . $indiceArchivos]["size"] > 0) {
                                            $archivo = $_FILES["archivo_archivo_" . $indiceArchivos];

                                            $archivo_ruta = $constante_directorioModulo . $id . "/";
                                            $archivo_nombre = "modulo_" . $id . "_archivo_" . $archivo_id . "." . pathinfo($archivo["name"], PATHINFO_EXTENSION);

                                            if (!file_exists($archivo_ruta)) {
                                                mkdir($archivo_ruta, 0755, true);
                                            }

                                            move_uploaded_file($archivo["tmp_name"], $archivo_ruta . $archivo_nombre);

                                            consulta($conexion, "UPDATE modulo_archivo SET nombre = '" . $archivo_nombre . "' WHERE id = " . $archivo_id);
                                        }
                                    } else if (!estaVacio($archivo_id)) {
                                        consulta($conexion, "UPDATE modulo_archivo SET titulo = '" . $archivo_titulo . "', orden = " . $archivo_orden . " WHERE id = " . $archivo_id);

                                        if (isset($_FILES["archivo_archivo_" . $indiceArchivos]) && $_FILES["archivo_archivo_" . $indiceArchivos]["size"] > 0) {
                                            $archivo = $_FILES["archivo_archivo_" . $indiceArchivos];

                                            $archivo_ruta = $constante_directorioModulo . $id . "/";
                                            $archivo_nombre = "modulo_" . $id . "_archivo_" . $archivo_id . "." . pathinfo($archivo["name"], PATHINFO_EXTENSION);

                                            if (!file_exists($archivo_ruta)) {
                                                mkdir($archivo_ruta, 0755, true);
                                            }

                                            move_uploaded_file($archivo["tmp_name"], $archivo_ruta . $archivo_nombre);

                                            consulta($conexion, "UPDATE modulo_archivo SET nombre = '" . $archivo_nombre . "' WHERE id = " . $archivo_id);
                                        }
                                    }
                                }
                            }
                        }

                        // Carga informacion actualizada

                        $modulo_BD = consulta($conexion, "SELECT * FROM modulo WHERE id = " . $id);
                        $modulo = obtenResultado($modulo_BD);

                        $id = $modulo["id"];
                        $fechaRegistro = $modulo["fechaRegistro"];
                        $habilitado = $modulo["habilitado"];
                        $examenHabilitado = $modulo["examenHabilitado"];
                        $nombre = $modulo["nombre"];
                        $resumen = $modulo["resumen"];
                        $descripcion = $modulo["descripcion"];
                        $duracion = $modulo["duracion"];
                        $linkVideo = $modulo["linkVideo"];
                        $imagen = $modulo["imagen"];
                        $idExamen = $modulo["idExamen"];


                        registraEvento("Actualizacion de módulo | id = " . $id);

                        $mensaje = "ok - Los cambios han sido guardados";
                    }
                }
            } else {
                if (!estaVacio($id)) {

                    // Es consulta

                    $modulo_BD = consulta($conexion, "SELECT * FROM modulo WHERE id = " . $id);
                    $modulo = obtenResultado($modulo_BD);

                    $id = $modulo["id"];
                    $fechaRegistro = $modulo["fechaRegistro"];
                    $habilitado = $modulo["habilitado"];
                    $examenHabilitado = $modulo["examenHabilitado"];
                    $nombre = $modulo["nombre"];
                    $resumen = $modulo["resumen"];
                    $descripcion = $modulo["descripcion"];
                    $duracion = $modulo["duracion"];
                    $linkVideo = $modulo["linkVideo"];
                    $imagen = $modulo["imagen"];
                    $idExamen = $modulo["idExamen"];


                    registraEvento("Consulta de módulo | id = " . $id);
                }
            }

            $indiceArchivos = 0;
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
                    <?php if ($esUsuarioMaster || $usuario_permisoConsultarModulos || $usuario_permisoEditarModulos) { ?>

                        <!-- Titulo -->

                        <div class="row heading-bg bg-blue">
                            <div class="col-xs-12">
                                <h5 class="txt-light">Detalle de Módulo</h5>
                            </div>
                        </div>

                        <!-- Bloques de informacion -->

                        <form action="modulo.php" enctype="multipart/form-data" method="post" id="formulario">
                            <input name="esSubmit" type="hidden" value="1" />

                            <input id="campo_cantidadArchivos" name="cantidadArchivos" type="hidden" />

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
                                                                Proporciona la información que se solicita
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
                                                                                        <input class="form-control" name="id" readonly type="text" value="<?php echo $id ?>" />
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <div class="form-group">
                                                                                        <label class="control-label mb-10">Habilitado</label>
                                                                                        <div>
                                                                                            <input <?php echo $habilitado == 1 ? "checked" : "" ?> class="form-control bs-switch" data-off-text="No" data-on-text="Si" name="habilitado" type="checkbox" />
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="row mb-30">

                                                                                <div class="col-md-6">
                                                                                    <div class="form-group">
                                                                                        <label class="control-label mb-10">Examen Habilitado</label>
                                                                                        <div>
                                                                                            <input <?php echo $examenHabilitado == 1 ? "checked" : "" ?> class="form-control bs-switch" data-off-text="No" data-on-text="Si" name="examenHabilitado" type="checkbox" />
                                                                                        </div>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <div class="form-group">
                                                                                        <label class="control-label mb-10">Examen</label>
                                                                                        <select class="form-control select2" name="idExamen">
                                                                                            <option value="">Elige</option>
                                                                                            <?php
                                                                                                $examenes_BD = consulta($conexion, "SELECT * FROM examen where habilitado = 1 ORDER BY nombre");

                                                                                                while ($examen = obtenResultado($examenes_BD)) {
                                                                                                    echo "<option " . ($examen["id"] == $idExamen ? "selected" : "") . " value='" . $examen["id"] . "'>" . $examen["titulo"] . "</option>";
                                                                                                }
                                                                                            ?>
                                                                                        </select>
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
                                                                                        <label class="control-label mb-10">Duración <span class="txt-danger ml-10">*</span></label>
                                                                                        <input class="form-control" name="duracion" type="text" value="<?php echo $duracion ?>" />
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="row mb-30">
                                                                                <div class="col-md-6">
                                                                                    <div class="form-group">
                                                                                        <label class="control-label mb-10">Link de video</label>
                                                                                        <div class="tags-default">
                                                                                            <input class="form-control" name="linkVideo" type="text" value="<?php echo $linkVideo ?>" />
                                                                                        </div>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <div class="form-group">
                                                                                        <label class="control-label mb-10">Resumen <span class="txt-danger ml-10">*</span></label>
                                                                                        <textarea class="form-control" name="resumen" rows="5"><?php echo $resumen ?></textarea>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="row mb-30">
                                                                                <div class="col-md-12">
                                                                                    <div class="form-group">
                                                                                        <label class="control-label mb-10">Descripción</label>
                                                                                        <textarea class="tinymce" name="descripcion"><?php echo $descripcion ?></textarea>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <?php if (!estaVacio($id)) { ?>
                                                                                <br /><br />

                                                                                <div class="row mb-30">
                                                                                    <div class="col-md-12">
                                                                                        <h5><strong>Multimedia</strong></h5>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                    <div class="col-md-12">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label mb-10">Imagen de portada</label>

                                                                                            <span>
                                                                                                <br />
                                                                                                Formatos aceptados: .jpg, .jpeg, .png
                                                                                                <br />
                                                                                                Tamaño preferente: 270 x 250 pixeles
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
                                                                                                                                    echo "<img class='user-img' src='" . $constante_urlModulo . $id . "/" . $imagen . "?" . $prefijoImagenes . "' />";

                                                                                                                                    echo "<div class='user-data'>";
                                                                                                                                    echo "<span class='name block'>" . $imagen . "</span>";
                                                                                                                                    echo "<span class='time block txt-grey'>";
                                                                                                                                    echo "<a data-lightbox='imagen' href='" . $constante_urlModulo . $id . "/" . $imagen . "?" . $prefijoImagenes . "'>Ampliar</a>";
                                                                                                                                    echo "&nbsp;&nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;&nbsp;&nbsp;";
                                                                                                                                    echo "<a download href='" . $constante_urlModulo . $id . "/" . $imagen . "?" . $prefijoImagenes . "'>Descargar</a>";
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

                                                                                <br /><br />

                                                                                <div class="row mb-30">
                                                                                    <div class="col-md-12 col-xs-12">
                                                                                        <h5><strong>Archivos descargables</strong></h5>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="row mb-30">
                                                                                    <div class="col-md-12">
                                                                                        <div class="table-wrap">
                                                                                            <div class="table-responsive">
                                                                                                <table class="table mb-0">
                                                                                                    <thead>
                                                                                                        <tr>
                                                                                                            <th style="width: 40%">Título</th>
                                                                                                            <th>Orden</th>
                                                                                                            <th>Nombre</th>
                                                                                                            <th>Acciones</th>
                                                                                                        </tr>
                                                                                                    </thead>

                                                                                                    <tbody id="tabla_archivos">
                                                                                                        <?php
                                                                                                            if(!estaVacio($id)) {
                                                                                                                $archivos_BD = consulta($conexion, "SELECT * FROM modulo_archivo WHERE idModulo = ". $id ." ORDER BY orden");

                                                                                                                while ($archivo = obtenResultado($archivos_BD)) {
                                                                                                                    echo "<tr id='linea_archivo_" . $indiceArchivos . "'>";
                                                                                                                    echo "<td><input class='form-control' name='archivo_titulo_" . $indiceArchivos . "' type='text' value='" . $archivo["titulo"] . "' /></td>";
                                                                                                                    echo "<td><input class='form-control' min='1' name='archivo_orden_" . $indiceArchivos . "' step='1' type='number' value='" . $archivo["orden"] . "' /></td>";
                                                                                                                    echo "<td>" . (estaVacio($archivo["nombre"]) ? "" : "<a download href='" . $constante_urlModulo . $id . "/" . $archivo["nombre"] . "'>" . $archivo["nombre"] . "</a><br /><br />") . "<input class='form-control' name='archivo_archivo_" . $indiceArchivos. "' type='file' /></td>";

                                                                                                                    echo "<td>";
                                                                                                                    echo "<input name='archivo_id_" . $indiceArchivos . "' type='hidden' value='" . $archivo["id"] . "' />";

                                                                                                                    if ($esUsuarioMaster || $usuario_permisoEditarModulos) {
                                                                                                                        echo "<a class='enlace_borrarArchivo' data-idArchivo='" . $archivo["id"] . "' data-indiceArchivos='" . $indiceArchivos . "' href='javascript:;' title='Borrar'><i class='fa fa-trash-o'></i></a>";
                                                                                                                    }

                                                                                                                    echo "</td>";

                                                                                                                    echo "</tr>";

                                                                                                                    $indiceArchivos++;
                                                                                                                }
                                                                                                            }
                                                                                                        ?>
                                                                                                    </tbody>
                                                                                                </table>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                    <div class="col-md-3">
                                                                                        <?php if ($esUsuarioMaster || $usuario_permisoEditarModulos) { ?>
                                                                                            <a class="btn btn-xs btn-primary" href="javascript:;" id="enlace_agregarArchivo">Agregar</a>
                                                                                        <?php } ?>
                                                                                    </div>
                                                                                </div>
                                                                            <?php } ?>
                                                                        </div>

                                                                        <div class="form-actions mt-50">
                                                                            <?php if ($esUsuarioMaster || $usuario_permisoEditarModulos) { ?>
                                                                                <button class="btn btn-success" type="button" id="boton_guardar">Guardar</button>
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
                            registraEvento("Consulta de módulo bloqueada | id = " . $id);
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
            var indiceArchivos = <?php echo $indiceArchivos ?>;


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

                // Inicializa plugins

                $(".bs-switch").bootstrapSwitch({
                    handleWidth: 110,
                    labelWidth: 110
                });
            });


            // Vincula un archivo al modulo


            $("#enlace_agregarArchivo").click(function() {
                var linea = "";

                linea += "<tr id='linea_archivo_" + indiceArchivos + "'>";
                linea += "<td><input class='form-control' name='archivo_titulo_" + indiceArchivos + "' type='text' /></td>";
                linea += "<td><input class='form-control' min='1' name='archivo_orden_" + indiceArchivos + "' step='1' type='number' /></td>";
                linea += "<td><input class='form-control' name='archivo_archivo_" + indiceArchivos + "' type='file' /></td>";
                linea += "<td></td>";
                linea += "</tr>";

                $("#tabla_archivos").append(linea);

                indiceArchivos++;
            });


            // Desvincula un archivo del modulo


            $(".enlace_borrarArchivo").click(function() {
                if (confirm("Al continuar se desvinculará el archivo del módulo, ¿desea proceder?")) {
                    var idArchivo = $(this).attr("data-idArchivo");
                    var indiceArchivos = $(this).attr("data-indiceArchivos");

                    $.ajax({
                        url: "personalizado/php/ajax/eliminaArchivoModulo.php",
                        type: "post",
                        data: { idArchivo: idArchivo },
                        success: function(resultado) {
                            $("#linea_archivo_" + indiceArchivos).remove();
                        }
                    });
                }
            });


            // Procesa el envio del formulario


            $("#boton_guardar").click(function(e) {
                $("#campo_cantidadArchivos").val(indiceArchivos);

                $("#formulario").submit();
            });


            // Regresa a la interfaz de origen


            $(".link_origen").click(function() {
                $("#formulario_origen").submit();
            });
        </script>
    </body>
</html>
