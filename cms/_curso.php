<?php include("personalizado/php/comunes/manejaSesion.php"); ?>


<!DOCTYPE html>


<html lang="es">
    <head>
        <?php include("personalizado/php/estructura/head.php"); ?>
    </head>


    <body>
        <?php

            // Obtiene parametros de request

            $idInstructor = 0;

            $esSubmit = filter_input(INPUT_POST, "esSubmit");
            $id = filter_input(INPUT_POST, "id");
            $idCategoria = filter_input(INPUT_POST, "idCategoria");
            $idInstructor = filter_input(INPUT_POST, "idInstructor");
            $habilitado = filter_input(INPUT_POST, "habilitado");
            $evaluacion = filter_input(INPUT_POST, "evaluacion");
            $nombre = filter_input(INPUT_POST, "nombre");
            $resumen = filter_input(INPUT_POST, "resumen");
            $descripcion = filter_input(INPUT_POST, "descripcion");
            $fechaInicio = filter_input(INPUT_POST, "fechaInicio");
            $fechaFin = filter_input(INPUT_POST, "fechaFin");
            $duracion = filter_input(INPUT_POST, "duracion");
            $sede = filter_input(INPUT_POST, "sede");
            $linkZoom = filter_input(INPUT_POST, "linkZoom");

            $cantidadArchivos = filter_input(INPUT_POST, "cantidadArchivos");
            $cantidadModulos = filter_input(INPUT_POST, "cantidadModulos");

            // Parametros enviados por origen

            $origen = filter_input(INPUT_POST, "origen");
            $origen_idCategoria = filter_input(INPUT_POST, "origen_idCategoria");
            $origen_rangoFechas = filter_input(INPUT_POST, "origen_rangoFechas");

            // Inicializa variables

            $mensaje = "";
            $fechaActual = date("Y-m-d H:i:s");
            $habilitado = estaVacio($habilitado) ? 0 : 1;
            $evaluacion = estaVacio($evaluacion) ? 0 : 1;
            $imagen = "";
            $prefijoImagenes = generaCadenaAleatoria(5);

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

                    $curso_BD = consulta($conexion, "SELECT imagen FROM curso WHERE id = " . $id);
                    $curso = obtenResultado($curso_BD);

                    $imagen = $curso["imagen"];
                } else {
                    if (estaVacio($id)) {

                        // Es insercion

                        $curso_BD = consulta($conexion, "SELECT id FROM curso WHERE nombre = '" . $nombre . "'");

                        if (cuentaResultados($curso_BD) > 0) {
                            $mensaje = "El curso ya se encuentra registrado en la base de datos";
                        } else {
                            consulta($conexion, "INSERT INTO curso ("
                                    . "idCategoria"
                                    . ", idInstructor"
                                    . ", fechaRegistro"
                                    . ", habilitado"
                                    . ", evaluacion"
                                    . ", nombre"
                                    . ", resumen"
                                    . ", descripcion"
                                    . ", fechaInicio"
                                    . ", fechaFin"
                                    . ", duracion"
                                    . ", sede"
                                    . ", linkZoom"
                                . ") VALUES ("
                                    . $idCategoria
                                    . ", " . (estaVacio($idInstructor) ? "NULL" : $idInstructor)
                                    . ", '" . $fechaActual . "'"
                                    . ", " . $habilitado
                                    . ", " . $evaluacion
                                    . ", '" . $nombre . "'"
                                    . ", '" . mysqli_real_escape_string($conexion, $resumen) . "'"
                                    . ", " . (estaVacio($descripcion) ? "NULL" : "'" . mysqli_real_escape_string($conexion, $descripcion) . "'")
                                    . ", " . (estaVacio($fechaInicio) ? "NULL" : "'" . $fechaInicio . "'")
                                    . ", " . (estaVacio($fechaFin) ? "NULL" : "'" . $fechaFin . "'")
                                    . ", " . (estaVacio($duracion) ? "NULL" : "'" . $duracion . "'")
                                    . ", " . (estaVacio($sede) ? "NULL" : "'" . $sede . "'")
                                    . ", " . (estaVacio($linkZoom) ? "NULL" : "'" . $linkZoom . "'")
                                . ")");

                            // Carga informacion actualizada

                            $curso_BD = consulta($conexion, "SELECT * FROM curso WHERE nombre = '" . $nombre . "'");
                            $curso = obtenResultado($curso_BD);

                            $id = $curso["id"];
                            $idCategoria = $curso["idCategoria"];
                            $idInstructor = $curso["idInstructor"];
                            $fechaRegistro = $curso["fechaRegistro"];
                            $habilitado = $curso["habilitado"];
                            $evaluacion = $curso["evaluacion"];
                            $nombre = $curso["nombre"];
                            $resumen = $curso["resumen"];
                            $descripcion = $curso["descripcion"];
                            $fechaInicio = $curso["fechaInicio"];
                            $fechaFin = $curso["fechaFin"];
                            $duracion = $curso["duracion"];
                            $sede = $curso["sede"];
                            $linkZoom = $curso["linkZoom"];
                            $imagen = $curso["imagen"];

                            registraEvento("Alta de curso | id = " . $id);

                            $mensaje = "ok - El curso ha sido registrado";
                        }
                    } else {

                        // Es actualizacion

                        consulta($conexion, "UPDATE curso SET"
                                . " idCategoria = " . $idCategoria
                                . ", idInstructor = " . (estaVacio($idInstructor) ? "NULL" : $idInstructor)
                                . ", habilitado = " . $habilitado
                                . ", evaluacion = " . $evaluacion
                                . ", nombre = '" . $nombre . "'"
                                . ", resumen = '" . mysqli_real_escape_string($conexion, $resumen) . "'"
                                . ", descripcion = " . (estaVacio($descripcion) ? "NULL" : "'" . mysqli_real_escape_string($conexion, $descripcion) . "'")
                                . ", fechaInicio = " . (estaVacio($fechaInicio) ? "NULL" : "'" . $fechaInicio . "'")
                                . ", fechaFin = " . (estaVacio($fechaFin) ? "NULL" : "'" . $fechaFin . "'")
                                . ", duracion = " . (estaVacio($duracion) ? "NULL" : "'" . $duracion . "'")
                                . ", sede = " . (estaVacio($sede) ? "NULL" : "'" . $sede . "'")
                                . ", linkZoom = " . (estaVacio($linkZoom) ? "NULL" : "'" . $linkZoom . "'")
                            . " WHERE id = " . $id);

                        // Carga imagen principal

                        if (isset($_FILES["imagen"])) {
                            try {
                                $archivo = $_FILES["imagen"];

                                if ($archivo["size"] > 0) {
                                    $archivo_ruta = $constante_directorioCurso. $id . "/";
                                    $archivo_nombre = $id . "_imagen." . pathinfo($archivo["name"], PATHINFO_EXTENSION);

                                    if (!file_exists($archivo_ruta)) {
                                        mkdir($archivo_ruta, 0755, true);
                                    }

                                    move_uploaded_file($archivo["tmp_name"], $archivo_ruta . $archivo_nombre);

                                    consulta($conexion, "UPDATE curso SET imagen = " . (estaVacio($archivo_nombre) ? "NULL" : "'" . $archivo_nombre . "'") . " WHERE id = " . $id);
                                }
                            } catch (Exception $e) {
                            }
                        }

                        // Carga modulos

                        if (!estaVacio($cantidadModulos) && $cantidadModulos > 0) {
                            consulta($conexion, "DELETE FROM curso_modulo WHERE idCurso = " . $id);

                            for ($indiceModulos = 0; $indiceModulos < $cantidadModulos; $indiceModulos++) {
                                $idModulo = filter_input(INPUT_POST, "modulo_idModulo_" . $indiceModulos);
                                $orden = filter_input(INPUT_POST, "modulo_orden_" . $indiceModulos);

                                if (!estaVacio($idModulo)) {
                                    consulta($conexion, "INSERT INTO curso_modulo (idCurso, idModulo, orden) VALUES (" . $id . ", " . $idModulo . ", " . $orden . ")");
                                }
                            }
                        }

                        // Carga alumnos

                        $alumnos_BD = consulta($conexion, "SELECT idAlumno FROM alumno_curso WHERE idCurso = " . $id);
                        $modulos_BD = consulta($conexion, "SELECT idModulo, orden FROM curso_modulo WHERE idCurso = " . $id);

                        while ($alumno = obtenResultado($alumnos_BD)) {
                            while ($modulo = obtenResultado($modulos_BD)) {
                                $moduloPrevio_BD = consulta($conexion, "SELECT * FROM alumno_curso_modulo WHERE idAlumno = " . $alumno["idAlumno"] . " AND idCurso = " . $id . " AND idModulo = " . $modulo["idModulo"]);

                                if (cuentaResultados($moduloPrevio_BD) == 0) {
                                    consulta($conexion, "INSERT INTO alumno_curso_modulo (idAlumno, idCurso, idModulo, orden) VALUES (" . $alumno["idAlumno"] . ", " . $id . ", " . $modulo["idModulo"] . ", " . $modulo["orden"] . ")");
                                } else {
                                    consulta($conexion, "UPDATE alumno_curso_modulo SET orden = " . $modulo["orden"] . " WHERE idAlumno = " . $alumno["idAlumno"] . " AND idCurso = " . $id . " AND idModulo = " . $modulo["idModulo"]);
                                }
                            }

                            reiniciaResultados($modulos_BD);
                        }

                        consulta($conexion, "update alumno_curso_modulo a
                                                set a.activo = 0
                                                where a.idModulo not in (select cm.idModulo from curso_modulo cm where cm.idCurso = " . $id . ")
                                                and a.idCurso = " . $id);

                        // Carga archivos

                        if (!estaVacio($cantidadArchivos) && $cantidadArchivos > 0) {
                            for ($indiceArchivos = 0; $indiceArchivos < $cantidadArchivos; $indiceArchivos++) {
                                $archivo_id = filter_input(INPUT_POST, "archivo_id_" . $indiceArchivos);
                                $archivo_titulo = filter_input(INPUT_POST, "archivo_titulo_" . $indiceArchivos);
                                $archivo_orden = filter_input(INPUT_POST, "archivo_orden_" . $indiceArchivos);

                                if (!estaVacio($archivo_titulo) && !estaVacio($archivo_orden)) {
                                    if (estaVacio($archivo_id) && isset($_FILES["archivo_archivo_" . $indiceArchivos]) && $_FILES["archivo_archivo_" . $indiceArchivos]["size"] > 0) {
                                        consulta($conexion, "INSERT INTO curso_archivo (idCurso, titulo, nombre, orden) VALUES (" . $id . ", '" . $archivo_titulo . "', '', " . $archivo_orden . ")");

                                        $archivo_id = obtenResultado(consulta($conexion, "SELECT MAX(id) AS id FROM curso_archivo WHERE idCurso = " . $id))["id"];

                                        if (isset($_FILES["archivo_archivo_" . $indiceArchivos]) && $_FILES["archivo_archivo_" . $indiceArchivos]["size"] > 0) {
                                            $archivo = $_FILES["archivo_archivo_" . $indiceArchivos];

                                            $archivo_ruta = $constante_directorioCurso . $id . "/";
                                            $archivo_nombre = "curso_" . $id . "_archivo_" . $archivo_id . "." . pathinfo($archivo["name"], PATHINFO_EXTENSION);

                                            if (!file_exists($archivo_ruta)) {
                                                mkdir($archivo_ruta, 0755, true);
                                            }

                                            move_uploaded_file($archivo["tmp_name"], $archivo_ruta . $archivo_nombre);

                                            consulta($conexion, "UPDATE curso_archivo SET nombre = '" . $archivo_nombre . "' WHERE id = " . $archivo_id);
                                        }
                                    } else if (!estaVacio($archivo_id)) {
                                        consulta($conexion, "UPDATE curso_archivo SET titulo = '" . $archivo_titulo . "', orden = " . $archivo_orden . " WHERE id = " . $archivo_id);

                                        if (isset($_FILES["archivo_archivo_" . $indiceArchivos]) && $_FILES["archivo_archivo_" . $indiceArchivos]["size"] > 0) {
                                            $archivo = $_FILES["archivo_archivo_" . $indiceArchivos];

                                            $archivo_ruta = $constante_directorioCurso . $id . "/";
                                            $archivo_nombre = "curso_" . $id . "_archivo_" . $archivo_id . "." . pathinfo($archivo["name"], PATHINFO_EXTENSION);

                                            if (!file_exists($archivo_ruta)) {
                                                mkdir($archivo_ruta, 0755, true);
                                            }

                                            move_uploaded_file($archivo["tmp_name"], $archivo_ruta . $archivo_nombre);

                                            consulta($conexion, "UPDATE curso_archivo SET nombre = '" . $archivo_nombre . "' WHERE id = " . $archivo_id);
                                        }
                                    }
                                }
                            }
                        }

                        // Carga informacion actualizada

                        $curso_BD = consulta($conexion, "SELECT * FROM curso WHERE id = " . $id);
                        $curso = obtenResultado($curso_BD);

                        $id = $curso["id"];
                        $idCategoria = $curso["idCategoria"];
                        $idInstructor = $curso["idInstructor"];
                        $fechaRegistro = $curso["fechaRegistro"];
                        $habilitado = $curso["habilitado"];
                        $evaluacion = $curso["evaluacion"];
                        $nombre = $curso["nombre"];
                        $resumen = $curso["resumen"];
                        $descripcion = $curso["descripcion"];
                        $fechaInicio = $curso["fechaInicio"];
                        $fechaFin = $curso["fechaFin"];
                        $duracion = $curso["duracion"];
                        $sede = $curso["sede"];
                        $linkZoom = $curso["linkZoom"];
                        $imagen = $curso["imagen"];

                        registraEvento("Actualizacion de curso | id = " . $id);

                        $mensaje = "ok - Los cambios han sido guardados";
                    }
                }
            } else {
                if (!estaVacio($id)) {

                    // Es consulta

                    $curso_BD = consulta($conexion, "SELECT * FROM curso WHERE id = " . $id);
                    $curso = obtenResultado($curso_BD);

                    $id = $curso["id"];
                    $idCategoria = $curso["idCategoria"];
                    $idInstructor = $curso["idInstructor"];
                    $fechaRegistro = $curso["fechaRegistro"];
                    $habilitado = $curso["habilitado"];
                    $evaluacion = $curso["evaluacion"];
                    $nombre = $curso["nombre"];
                    $resumen = $curso["resumen"];
                    $descripcion = $curso["descripcion"];
                    $fechaInicio = $curso["fechaInicio"];
                    $fechaFin = $curso["fechaFin"];
                    $duracion = $curso["duracion"];
                    $sede = $curso["sede"];
                    $linkZoom = $curso["linkZoom"];
                    $imagen = $curso["imagen"];

                    registraEvento("Consulta de curso | id = " . $id);
                }
            }

            $indiceArchivos = 0;
            $indiceModulos = 0;
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
                    <?php if ($esUsuarioMaster || $usuario_permisoConsultarCursos || $usuario_permisoEditarCursos) { ?>

                        <!-- Titulo -->

                        <div class="row heading-bg bg-blue">
                            <div class="col-xs-12">
                                <h5 class="txt-light">Detalle de Curso</h5>
                            </div>
                        </div>

                        <!-- Bloques de informacion -->

                        <form action="curso.php" enctype="multipart/form-data" method="post" id="formulario">
                            <input name="esSubmit" type="hidden" value="1" />

                            <input id="campo_cantidadArchivos" name="cantidadArchivos" type="hidden" />
                            <input id="campo_cantidadModulos" name="cantidadModulos" type="hidden" />

                            <input name="origen" type="hidden" value="<?php echo $origen ?>" />
                            <input name="origen_idCategoria" type="hidden" value="<?php echo $origen_idCategoria ?>" />
                            <input name="origen_rangoFechas" type="hidden" value="<?php echo $origen_rangoFechas ?>" />

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
                                                                                        <label class="control-label mb-10">Fecha de registro</label>
                                                                                        <input class="form-control" name="fechaRegistro" readonly type="text" value="<?php echo $fechaRegistro ?>" />
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="row mb-30">
                                                                                <div class="col-md-6">
                                                                                    <div class="form-group">
                                                                                        <label class="control-label mb-10">Habilitado</label>
                                                                                        <div>
                                                                                            <input <?php echo $habilitado == 1 ? "checked" : "" ?> class="form-control bs-switch" data-off-text="No" data-on-text="Si" name="habilitado" type="checkbox" />
                                                                                        </div>
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
                                                                                        <label class="control-label mb-10">Categoría</label>
                                                                                        <select class="form-control select2" name="idCategoria">
                                                                                            <option value="">Elige</option>
                                                                                            <?php
                                                                                                $categorias_BD = consulta($conexion, "SELECT * FROM categoria ORDER BY nombre");

                                                                                                while ($categoria = obtenResultado($categorias_BD)) {
                                                                                                    echo "<option " . ($categoria["id"] == $idCategoria ? "selected" : "") . " value='" . $categoria["id"] . "'>" . $categoria["nombre"] . "</option>";
                                                                                                }
                                                                                            ?>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="row mb-30">
                                                                                <div class="col-md-6">
                                                                                    <div class="form-group">
                                                                                        <label class="control-label mb-10">Duración <span class="txt-danger ml-10">*</span></label>
                                                                                        <input class="form-control" name="duracion" type="text" value="<?php echo $duracion ?>" />
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <div class="form-group">
                                                                                        <label class="control-label mb-10">Sede</label>
                                                                                        <div class="tags-default">
                                                                                            <input class="form-control" name="sede" type="text" value="<?php echo $sede ?>" />
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="row mb-30">
                                                                                <div class="col-md-6">
                                                                                    <div class="form-group">
                                                                                        <label class="control-label mb-10">Fecha de inicio</label>
                                                                                        <div class="input-group date campo_fecha">
                                                                                            <input class="form-control" name="fechaInicio" type="text" value="<?php echo $fechaInicio ?>" />
                                                                                            <span class="input-group-addon">
                                                                                                <span class="fa fa-calendar"></span>
                                                                                            </span>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <div class="form-group">
                                                                                        <label class="control-label mb-10">Fecha de finalización</label>
                                                                                        <div class="input-group date campo_fecha">
                                                                                            <input class="form-control" name="fechaFin" type="text" value="<?php echo $fechaFin ?>" />
                                                                                            <span class="input-group-addon">
                                                                                                <span class="fa fa-calendar"></span>
                                                                                            </span>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="row mb-30">
                                                                                <div class="col-md-6">
                                                                                    <div class="form-group">
                                                                                        <label class="control-label mb-10">Instructor</label>
                                                                                        <select class="form-control select2" name="idInstructor">
                                                                                            <option value="">Elige</option>
                                                                                            <?php
                                                                                                $instructores_BD = consulta($conexion, "SELECT * FROM instructor ORDER BY nombre, apellido");

                                                                                                while ($instructor = obtenResultado($instructores_BD)) {
                                                                                                    echo "<option " . ($instructor["id"] == $idInstructor ? "selected" : "") . " value='" . $instructor["id"] . "'>" . $instructor["nombre"] . " " . $instructor["apellido"] . "</option>";
                                                                                                }
                                                                                            ?>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <div class="form-group">
                                                                                        <label class="control-label mb-10">Link Zoom</label>
                                                                                        <div class="tags-default">
                                                                                            <input class="form-control" name="linkZoom" type="text" value="<?php echo $linkZoom ?>" />
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="row mb-30">
                                                                                <div class="col-md-6">
                                                                                    <div class="form-group">
                                                                                        <label class="control-label mb-10">Evaluación</label>
                                                                                        <div>
                                                                                            <input <?php echo $evaluacion == 1 ? "checked" : "" ?> class="form-control bs-switch" data-off-text="No" data-on-text="Si" name="evaluacion" type="checkbox" />
                                                                                        </div>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <div class="form-group">
                                                                                        <label class="control-label mb-10">Resumen</label>
                                                                                        <textarea class="form-control" name="resumen" rows="5"><?php echo $resumen ?></textarea>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="row mb-30">
                                                                                <div class="col-md-12">
                                                                                    <div class="form-group">
                                                                                        <label class="control-label mb-10">Detalle</label>
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
                                                                                                                                    echo "<img class='user-img' src='" . $constante_urlCurso . $id . "/" . $imagen . "?" . $prefijoImagenes . "' />";

                                                                                                                                    echo "<div class='user-data'>";
                                                                                                                                    echo "<span class='name block'>" . $imagen . "</span>";
                                                                                                                                    echo "<span class='time block txt-grey'>";
                                                                                                                                    echo "<a data-lightbox='imagen' href='" . $constante_urlCurso . $id . "/" . $imagen . "?" . $prefijoImagenes . "'>Ampliar</a>";
                                                                                                                                    echo "&nbsp;&nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;&nbsp;&nbsp;";
                                                                                                                                    echo "<a download href='" . $constante_urlCurso . $id . "/" . $imagen . "?" . $prefijoImagenes . "'>Descargar</a>";
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
                                                                                        <h5><strong>Módulos</strong></h5>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="row mb-30">
                                                                                    <div class="col-md-12">
                                                                                        <div class="table-wrap">
                                                                                            <div class="table-responsive">
                                                                                                <table class="table mb-0">
                                                                                                    <thead>
                                                                                                        <tr>
                                                                                                            <th style="width: 40%">Módulo</th>
                                                                                                            <th>Orden</th>
                                                                                                            <th>Acciones</th>
                                                                                                        </tr>
                                                                                                    </thead>

                                                                                                    <tbody id="tabla_modulos">
                                                                                                        <?php
                                                                                                            if(!estaVacio($id)) {
                                                                                                                $modulos_BD = consulta($conexion, "SELECT
                                                                                                                        m.id,
                                                                                                                        m.nombre,
                                                                                                                        cm.orden
                                                                                                                    FROM
                                                                                                                        curso_modulo cm
                                                                                                                        LEFT JOIN modulo m ON m.id = cm.idModulo
                                                                                                                    WHERE
                                                                                                                        cm.idCurso = " . $id . " ORDER BY cm.orden, m.nombre");

                                                                                                                while ($modulo = obtenResultado($modulos_BD)) {
                                                                                                                    echo "<tr id='linea_modulo_" . $indiceModulos . "'>";
                                                                                                                    echo "<td><input name='modulo_idModulo_" . $indiceModulos . "' type='hidden' value='" . $modulo["id"] . "' />" . $modulo["nombre"] . "</td>";
                                                                                                                    echo "<td><input class='form-control' min='1' name='modulo_orden_" . $indiceModulos . "' step='1' type='number' value='" . $modulo["orden"] . "' /></td>";

                                                                                                                    echo "<td>";

                                                                                                                    if ($esUsuarioMaster || $usuario_permisoEditarCursos) {
                                                                                                                        echo "<a class='enlace_borrarModulo' data-idModulo='" . $modulo["id"] . "' data-indiceModulos='" . $indiceModulos . "' href='javascript:;' title='Borrar'><i class='fa fa-trash-o'></i></a>";
                                                                                                                    }

                                                                                                                    echo "</td>";

                                                                                                                    echo "</tr>";

                                                                                                                    $indiceModulos++;
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
                                                                                        <?php if ($esUsuarioMaster || $usuario_permisoEditarCursos) { ?>
                                                                                            <a class="btn btn-xs btn-primary" href="javascript:;" id="enlace_agregarModulo">Agregar</a>
                                                                                        <?php } ?>
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
                                                                                                                $archivos_BD = consulta($conexion, "SELECT * FROM curso_archivo WHERE idCurso = ". $id ." ORDER BY orden");

                                                                                                                while ($archivo = obtenResultado($archivos_BD)) {
                                                                                                                    echo "<tr id='linea_archivo_" . $indiceArchivos . "'>";
                                                                                                                    echo "<td><input class='form-control' name='archivo_titulo_" . $indiceArchivos . "' type='text' value='" . $archivo["titulo"] . "' /></td>";
                                                                                                                    echo "<td><input class='form-control' min='1' name='archivo_orden_" . $indiceArchivos . "' step='1' type='number' value='" . $archivo["orden"] . "' /></td>";
                                                                                                                    echo "<td>" . (estaVacio($archivo["nombre"]) ? "" : "<a download href='" . $constante_urlCurso . $id . "/" . $archivo["nombre"] . "'>" . $archivo["nombre"] . "</a><br /><br />") . "<input class='form-control' name='archivo_archivo_" . $indiceArchivos. "' type='file' /></td>";

                                                                                                                    echo "<td>";
                                                                                                                    echo "<input name='archivo_id_" . $indiceArchivos . "' type='hidden' value='" . $archivo["id"] . "' />";

                                                                                                                    if ($esUsuarioMaster || $usuario_permisoEditarCursos) {
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
                                                                                        <?php if ($esUsuarioMaster || $usuario_permisoEditarCursos) { ?>
                                                                                            <a class="btn btn-xs btn-primary" href="javascript:;" id="enlace_agregarArchivo">Agregar</a>
                                                                                        <?php } ?>
                                                                                    </div>
                                                                                </div>
                                                                            <?php } ?>
                                                                        </div>

                                                                        <div class="form-actions mt-50">
                                                                            <?php if ($esUsuarioMaster || $usuario_permisoEditarCursos) { ?>
                                                                                <button class="btn btn-success" type="button" id="boton_guardar">Guardar</button>
                                                                            <?php } ?>

                                                                            <?php if (!estaVacio($origen)) { ?>
                                                                                <a class="btn btn-default ml-10 link_origen" type="button">Volver</a>
                                                                            <?php } ?>

                                                                            <?php if (!estaVacio($id)) { ?>
<a class="btn btn-primary ml-10 replicar_curso" data-curso="<?php echo $id;?>" type="button" style="color:white !important;">Replicar Curso</a>
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

                            <!-- Regreso -->

                            <input name="idCategoria" type="hidden" value="<?php echo $origen_idCategoria ?>" />
                            <input name="rangoFechas" type="hidden" value="<?php echo $origen_rangoFechas ?>" />
                        </form>

                        <!-- Formulario de replica de curso -->

                        <form action="curso.php" id="formulario_replica" method="post">
                            <input name="id" type="hidden" id="campo_replica_id" />
                        </form>
                    <?php
                        } else {
                            registraEvento("Consulta de curso bloqueada | id = " . $id);
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
            var indiceModulos = <?php echo $indiceModulos ?>;


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

                $(".select2").select2();

                $(".campo_fecha").datetimepicker({
                    useCurrent: false,
                    format: "YYYY-MM-DD HH:mm",
                    icons: {
                        time: "fa fa-clock-o",
                        date: "fa fa-calendar",
                        up: "fa fa-arrow-up",
                        down: "fa fa-arrow-down"
                    }
                });

                $(".bs-switch").bootstrapSwitch({
                    handleWidth: 110,
                    labelWidth: 110
                });
            });











            // Carga opciones de modulos disponibles


            function cargaModulos(indice) {
                $.ajax({
                    url: "personalizado/php/ajax/cargaModulos.php",
                    type: "post",
                    success: function(xml) {
                        $("#campo_modulo_idModulo_" + indice).append("<option value=''>Elige</option>");

                        $(xml).find("modulo").each(function() {
                            var id = $(this).find("id").text();
                            var nombre = $(this).find("nombre").text();

                            $("#campo_modulo_idModulo_" + indice).append("<option value='" + id + "'>" + nombre + "</option>");
                        });
                    }
                });
            }


            // Vincula un modulo al curso


            $("#enlace_agregarModulo").click(function() {
                var linea = "";

                linea += "<tr id='linea_modulo_" + indiceModulos + "'>";
                linea += "<td><select class='form-control select2' id='campo_modulo_idModulo_" + indiceModulos + "' name='modulo_idModulo_" + indiceModulos + "'></select></td>";
                linea += "<td><input class='form-control' min='1' name='modulo_orden_" + indiceModulos + "' step='1' type='number' /></td>";
                linea += "<td></td>";
                linea += "<td></td>";
                linea += "</tr>";

                cargaModulos(indiceModulos);

                $("#tabla_modulos").append(linea);

                $(".select2").select2();

                indiceModulos++;
            });


            // Desvincula un modulo del curso


            $(".enlace_borrarModulo").click(function() {
                if (confirm("Al continuar se desvinculará el módulo del curso, ¿desea proceder?")) {
                    var idCurso = "<?php echo $id ?>";
                    var idModulo = $(this).attr("data-idModulo");
                    var indiceModulo = $(this).attr("data-indiceModulos");

                    $.ajax({
                        url: "personalizado/php/ajax/eliminaModuloCurso.php",
                        type: "post",
                        data: { idCurso: idCurso, idModulo: idModulo },
                        success: function(resultado) {
                            $("#linea_modulo_" + indiceModulo).remove();
                        }
                    });
                }
            });


            // Vincula un archivo al curso


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


            // Desvincula un archivo del curso


            $(".enlace_borrarArchivo").click(function() {
                if (confirm("Al continuar se desvinculará el archivo del curso, ¿desea proceder?")) {
                    var idArchivo = $(this).attr("data-idArchivo");
                    var indiceArchivos = $(this).attr("data-indiceArchivos");

                    $.ajax({
                        url: "personalizado/php/ajax/eliminaArchivoCurso.php",
                        type: "post",
                        data: { idArchivo: idArchivo },
                        success: function(resultado) {
                            $("#linea_archivo_" + indiceArchivos).remove();
                        }
                    });
                }
            });










            $('.replicar_curso').click(function(){
                if(confirm("¿Esta seguro de replicar este curso?")){
                    var idCurso = $(this).attr("data-curso");
                    $.ajax({
                        url: "personalizado/php/ajax/replicaCurso.php",
                        type: "post",
                        data: {
                            idCurso: idCurso
                        },
                        success: function(resultado) {
                            if(resultado > 0){
                                //se aplica redireccion con el id nuevo como parametro
                                $("#campo_replica_id").val(resultado);
                                $("#formulario_replica").submit();
                            }
                        }
                    })

                }
            });







            // Procesa envio del formulario


            $("#boton_guardar").click(function(e) {
                $("#campo_cantidadArchivos").val(indiceArchivos);
                $("#campo_cantidadModulos").val(indiceModulos);

                $("#formulario").submit();
            });


            // Regresa a la interfaz de origen


            $(".link_origen").click(function() {
                $("#formulario_origen").submit();
            });
        </script>
    </body>
</html>
