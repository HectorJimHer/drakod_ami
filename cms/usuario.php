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
            $habilitado = filter_input(INPUT_POST, "habilitado");
            $fechaRegistro = filter_input(INPUT_POST, "fechaRegistro");
            $rol = filter_input(INPUT_POST, "rol");
            $nombre = filter_input(INPUT_POST, "nombre");
            $correoElectronico = filter_input(INPUT_POST, "correoElectronico");
            $contrasena = filter_input(INPUT_POST, "contrasena");

            $permisoConsultarUsuarios = filter_input(INPUT_POST, "permisoConsultarUsuarios");
	    $permisoEditarUsuarios = filter_input(INPUT_POST, "permisoEditarUsuarios");
            /*$permisoConsultarInstructores = filter_input(INPUT_POST, "permisoConsultarInstructores");
	    $permisoEditarInstructores = filter_input(INPUT_POST, "permisoEditarInstructores");
            $permisoConsultarCategorias = filter_input(INPUT_POST, "permisoConsultarCategorias");
	    $permisoEditarCategorias = filter_input(INPUT_POST, "permisoEditarCategorias");
            $permisoConsultarCursos = filter_input(INPUT_POST, "permisoConsultarCursos");
	    $permisoEditarCursos = filter_input(INPUT_POST, "permisoEditarCursos");
	    $permisoConsultarModulos = filter_input(INPUT_POST, "permisoConsultarModulos");
	    $permisoEditarModulos = filter_input(INPUT_POST, "permisoEditarModulos");
            $permisoConsultarAlumnos = filter_input(INPUT_POST, "permisoConsultarAlumnos");
	    $permisoEditarAlumnos = filter_input(INPUT_POST, "permisoEditarAlumnos");
            $permisoConsultarEvaluaciones = filter_input(INPUT_POST, "permisoConsultarEvaluaciones");*/

            // Parametros enviados por origen

            $origen = filter_input(INPUT_POST, "origen");

            // Inicializa variables

            $mensaje = "";
            $fechaActual = date("Y-m-d H:i:s");
            $habilitado = estaVacio($habilitado) ? 0 : 1;

            $permisoConsultarUsuarios = estaVacio($permisoConsultarUsuarios) ? 0 : 1;
	    $permisoEditarUsuarios = estaVacio($permisoEditarUsuarios) ? 0 : 1;
            /*$permisoConsultarInstructores = estaVacio($permisoConsultarInstructores) ? 0 : 1;
	    $permisoEditarInstructores = estaVacio($permisoEditarInstructores) ? 0 : 1;
            $permisoConsultarCategorias = estaVacio($permisoConsultarCategorias) ? 0 : 1;
	    $permisoEditarCategorias = estaVacio($permisoEditarCategorias) ? 0 : 1;
            $permisoConsultarCursos = estaVacio($permisoConsultarCursos) ? 0 : 1;
	    $permisoEditarCursos = estaVacio($permisoEditarCursos) ? 0 : 1;
	    $permisoConsultarModulos = estaVacio($permisoConsultarModulos) ? 0 : 1;
	    $permisoEditarModulos = estaVacio($permisoEditarModulos) ? 0 : 1;
            $permisoConsultarAlumnos = estaVacio($permisoConsultarAlumnos) ? 0 : 1;
	    $permisoEditarAlumnos = estaVacio($permisoEditarAlumnos) ? 0 : 1;
            $permisoConsultarEvaluaciones = estaVacio($permisoConsultarEvaluaciones) ? 0 : 1;*/

            // Procesa el request

            if (!estaVacio($esSubmit) && $esSubmit === "1") {

                // Valida los campos obligatorios

                if (estaVacio($rol)) {
                    $mensaje .= "* Rol<br />";
                }

                if (estaVacio($nombre)) {
                    $mensaje .= "* Nombre<br />";
                }

                if (estaVacio($correoElectronico)) {
                    $mensaje .= "* Correo electrónico<br />";
                } else if (!filter_var($correoElectronico, FILTER_VALIDATE_EMAIL)) {
                    $mensaje .= "* Correo electrónico con formato correcto<br />";
                }

                if (!estaVacio($mensaje)) {
                    $mensaje = "Proporciona los siguientes datos:<br /><br />" . $mensaje;
                } else {
                    if (estaVacio($id)) {

                        // Es insercion

                        if (estaVacio($contrasena)) {
                            $mensaje .= "* Contraseña<br />";
                        }

                        if (!estaVacio($mensaje)) {
                            $mensaje = "Proporciona los siguientes datos:<br /><br />" . $mensaje;
                        } else {

                            // Confirma si usuario existe

                            $usuario_BD = consulta($conexion, "SELECT id FROM usuario WHERE correoElectronico = '" . $correoElectronico . "'");

                            if (cuentaResultados($usuario_BD) > 0) {
                                $mensaje = "El usuario ya se encuentra registrado en la base de datos";
                            } else {
                                consulta($conexion, "INSERT INTO usuario ("
                                        . "habilitado"
                                        . ", fechaRegistro"
                                        . ", rol"
                                        . ", nombre"
                                        . ", correoElectronico"
                                        . ", contrasena"
                                        . ", permisoConsultarUsuarios"
                                        . ", permisoEditarUsuarios"
                                        /*. ", permisoConsultarInstructores"
                                        . ", permisoEditarInstructores"
                                        . ", permisoConsultarCategorias"
                                        . ", permisoEditarCategorias"
                                        . ", permisoConsultarCursos"
                                        . ", permisoEditarCursos"
                                        . ", permisoConsultarModulos"
                                        . ", permisoEditarModulos"
                                        . ", permisoConsultarAlumnos"
                                        . ", permisoEditarAlumnos"
                                        . ", permisoConsultarEvaluaciones"*/
                                    . ") VALUES ("
                                        . $habilitado
                                        . ", '" . $fechaActual . "'"
                                        . ", '" . $rol . "'"
                                        . ", '" . $nombre . "'"
                                        . ", '" . $correoElectronico . "'"
                                        . ", '" . md5($contrasena) . "'"
                                        . ", " . $permisoConsultarUsuarios
                                        . ", " . $permisoEditarUsuarios
                                        /*. ", " . $permisoConsultarInstructores
                                        . ", " . $permisoEditarInstructores
                                        . ", " . $permisoConsultarCategorias
                                        . ", " . $permisoEditarCategorias
                                        . ", " . $permisoConsultarCursos
                                        . ", " . $permisoEditarCursos
                                        . ", " . $permisoConsultarModulos
                                        . ", " . $permisoEditarModulos
                                        . ", " . $permisoConsultarAlumnos
                                        . ", " . $permisoEditarAlumnos
                                        . ", " . $permisoConsultarEvaluaciones*/
                                    . ")");

                                // Carga informacion actualizada

                                $usuario_BD = consulta($conexion, "SELECT * FROM usuario WHERE correoElectronico = '" . $correoElectronico . "'" . $restricciones);
                                $usuario = obtenResultado($usuario_BD);

                                $id = $usuario["id"];
                                $habilitado = $usuario["habilitado"];
                                $fechaRegistro = $usuario["fechaRegistro"];
                                $rol = $usuario["rol"];
                                $nombre = $usuario["nombre"];
                                $correoElectronico = $usuario["correoElectronico"];
                                $contrasena = "";
                                $permisoConsultarUsuarios = $usuario["permisoConsultarUsuarios"];
                                $permisoEditarUsuarios = $usuario["permisoEditarUsuarios"];
                                /*$permisoConsultarInstructores = $usuario["permisoConsultarInstructores"];
                                $permisoEditarInstructores = $usuario["permisoEditarInstructores"];
                                $permisoConsultarCategorias = $usuario["permisoConsultarCategorias"];
                                $permisoEditarCategorias = $usuario["permisoEditarCategorias"];
                                $permisoConsultarCursos = $usuario["permisoConsultarCursos"];
                                $permisoEditarCursos = $usuario["permisoEditarCursos"];
                                $permisoConsultarModulos = $usuario["permisoConsultarModulos"];
                                $permisoEditarModulos = $usuario["permisoEditarModulos"];
                                $permisoConsultarAlumnos = $usuario["permisoConsultarAlumnos"];
                                $permisoEditarAlumnos = $usuario["permisoEditarAlumnos"];
                                $permisoConsultarEvaluaciones = $usuario["permisoConsultarEvaluaciones"];*/

                                registraEvento("Alta de usuario | id = " . $id);

                                $mensaje = "ok - El usuario ha sido registrado";
                            }
                        }
                    } else {

                        // Es actualizacion

                        consulta($conexion, "UPDATE usuario SET "
                                . " habilitado = " . $habilitado
                                . ", rol = '" . $rol . "'"
                                . ", nombre = '" . $nombre . "'"
                                . ", correoElectronico = '" . $correoElectronico . "'"
                                . ", permisoConsultarUsuarios = " . $permisoConsultarUsuarios
                                . ", permisoEditarUsuarios = " . $permisoEditarUsuarios
                                /*. ", permisoConsultarInstructores = " . $permisoConsultarInstructores
                                . ", permisoEditarInstructores = " . $permisoEditarInstructores
                                . ", permisoConsultarCategorias = " . $permisoConsultarCategorias
                                . ", permisoEditarCategorias = " . $permisoEditarCategorias
                                . ", permisoConsultarCursos = " . $permisoConsultarCursos
                                . ", permisoEditarCursos = " . $permisoEditarCursos
                                . ", permisoConsultarModulos = " . $permisoConsultarModulos
                                . ", permisoEditarModulos = " . $permisoEditarModulos
                                . ", permisoConsultarAlumnos = " . $permisoConsultarAlumnos
                                . ", permisoEditarAlumnos = " . $permisoEditarAlumnos
                                . ", permisoConsultarEvaluaciones = " . $permisoConsultarEvaluaciones*/
                            . " WHERE id = " . $id);

                        if (!estaVacio($contrasena)) {
                            consulta($conexion, "UPDATE usuario SET "
                                . "contrasena = '" . md5($contrasena) . "'"
                                . " WHERE id = " . $id);
                        }

                        // Carga informacion actualizada

                        $usuario_BD = consulta($conexion, "SELECT * FROM usuario WHERE id = " . $id);
                        $usuario = obtenResultado($usuario_BD);

                        $id = $usuario["id"];
                        $habilitado = $usuario["habilitado"];
                        $fechaRegistro = $usuario["fechaRegistro"];
                        $rol = $usuario["rol"];
                        $nombre = $usuario["nombre"];
                        $correoElectronico = $usuario["correoElectronico"];
                        $contrasena = "";
                        $permisoConsultarUsuarios = $usuario["permisoConsultarUsuarios"];
                        $permisoEditarUsuarios = $usuario["permisoEditarUsuarios"];
                        /*$permisoConsultarInstructores = $usuario["permisoConsultarInstructores"];
                        $permisoEditarInstructores = $usuario["permisoEditarInstructores"];
                        $permisoConsultarCategorias = $usuario["permisoConsultarCategorias"];
                        $permisoEditarCategorias = $usuario["permisoEditarCategorias"];
                        $permisoConsultarCursos = $usuario["permisoConsultarCursos"];
                        $permisoEditarCursos = $usuario["permisoEditarCursos"];
                        $permisoConsultarModulos = $usuario["permisoConsultarModulos"];
                        $permisoEditarModulos = $usuario["permisoEditarModulos"];
                        $permisoConsultarAlumnos = $usuario["permisoConsultarAlumnos"];
                        $permisoEditarAlumnos = $usuario["permisoEditarAlumnos"];
                        $permisoConsultarEvaluaciones = $usuario["permisoConsultarEvaluaciones"];*/

                        registraEvento("Actualizacion de usuario | id = " . $id);

                        $mensaje = "ok - Los cambios han sido guardados";
                    }
                }
            } else {
                if (!estaVacio($id)) {

                    // Es consulta

                    $usuario_BD = consulta($conexion, "SELECT * FROM usuario WHERE id = " . $id);
                    $usuario = obtenResultado($usuario_BD);

                    $id = $usuario["id"];
                    $habilitado = $usuario["habilitado"];
                    $fechaRegistro = $usuario["fechaRegistro"];
                    $rol = $usuario["rol"];
                    $nombre = $usuario["nombre"];
                    $correoElectronico = $usuario["correoElectronico"];
                    $contrasena = "";
                    $permisoConsultarUsuarios = $usuario["permisoConsultarUsuarios"];
                    $permisoEditarUsuarios = $usuario["permisoEditarUsuarios"];
                    /*$permisoConsultarInstructores = $usuario["permisoConsultarInstructores"];
                    $permisoEditarInstructores = $usuario["permisoEditarInstructores"];
                    $permisoConsultarCategorias = $usuario["permisoConsultarCategorias"];
                    $permisoEditarCategorias = $usuario["permisoEditarCategorias"];
                    $permisoConsultarCursos = $usuario["permisoConsultarCursos"];
                    $permisoEditarCursos = $usuario["permisoEditarCursos"];
                    $permisoConsultarModulos = $usuario["permisoConsultarModulos"];
                    $permisoEditarModulos = $usuario["permisoEditarModulos"];
                    $permisoConsultarAlumnos = $usuario["permisoConsultarAlumnos"];
                    $permisoEditarAlumnos = $usuario["permisoEditarAlumnos"];
                    $permisoConsultarEvaluaciones = $usuario["permisoConsultarEvaluaciones"];*/

                    registraEvento("Consulta de usuario | id = " . $id);
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
                    <?php if ($esUsuarioMaster || $usuario_permisoConsultarUsuarios || $usuario_permisoEditarUsuarios) { ?>

                        <!-- Titulo -->

                        <div class="row heading-bg bg-blue">
                            <div class="col-xs-12">
                                <h5 class="txt-light">Detalle de usuario</h5>
                            </div>
                        </div>

                        <!-- Bloques de informacion -->

                        <form action="usuario.php" enctype="multipart/form-data" method="post">
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
                                                                Proporciona la información del usuario
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
                                                                                            <input <?php echo $habilitado == 1 ? "checked" : "" ?> class="form-control bs-switch" data-off-text="Inhabilitado" data-on-text="Habilitado" name="habilitado" type="checkbox" />
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
                                                                                        <label class="control-label mb-10">Rol <span class="txt-danger ml-10">*</span></label>
                                                                                        <select class="form-control select2" name="rol">
                                                                                            <option value="">Elige</option>
                                                                                            <option <?php echo ($rol == "Administrador") ? "selected" : "" ?> value="Administrador">Administrador</option>
                                                                                            <option <?php echo ($rol == "Editor") ? "selected" : "" ?> value="Editor">Editor</option>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <div class="form-group">
                                                                                        <label class="control-label mb-10">Nombre <span class="txt-danger ml-10">*</span></label>
                                                                                        <input class="form-control" name="nombre" type="text" value="<?php echo $nombre ?>" />
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="row mb-30">
                                                                                <div class="col-md-6">
                                                                                    <div class="form-group">
                                                                                        <label class="control-label mb-10">Correo electrónico <span class="txt-danger ml-10">*</span></label>
                                                                                        <input class="form-control" name="correoElectronico" type="text" value="<?php echo $correoElectronico ?>" />
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <div class="form-group">
                                                                                        <label class="control-label mb-10">Contraseña <span class="txt-danger ml-10">(captura un valor solo si deseas cambiarla)</span></label>
                                                                                        <input class="form-control" name="contrasena" type="text" value="<?php echo $contrasena ?>" />
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <br /><br />

                                                                            <div class="row mb-30">
                                                                                <div class="col-md-12">
                                                                                    <h5><strong>Permisos</strong></h5>
                                                                                </div>
                                                                            </div>

                                                                            <div class="row mb-30">
                                                                                <div class="col-md-12">
                                                                                    <div class="table-wrap">
                                                                                        <div class="table-responsive">
                                                                                            <table class="table mb-0">
                                                                                                <thead>
                                                                                                    <tr>
                                                                                                        <th></th>
                                                                                                        <th>Consultar</th>
                                                                                                        <th>Editar</th>
                                                                                                    </tr>
                                                                                                </thead>

                                                                                                <tbody id="tabla_contactos">
                                                                                                    <tr>
                                                                                                        <td>Usuarios</td>
                                                                                                        <td><input class="js-switch" <?php echo $permisoConsultarUsuarios == 1 ? "checked" : "" ?> data-color="#FAAB15" data-size="small" name="permisoConsultarUsuarios" type="checkbox" /></td>
                                                                                                        <td><input class="js-switch" <?php echo $permisoEditarUsuarios == 1 ? "checked" : "" ?> data-color="#FAAB15" data-size="small" name="permisoEditarUsuarios" type="checkbox" /></td>
                                                                                                    </tr>
                                                                                                    <!--tr>
                                                                                                        <td>Instructores</td>
                                                                                                        <td><input class="js-switch" < ?php echo $permisoConsultarInstructores == 1 ? "checked" : "" ?> data-color="#FAAB15" data-size="small" name="permisoConsultarInstructores" type="checkbox" /></td>
                                                                                                        <td><input class="js-switch" < ?php echo $permisoEditarInstructores == 1 ? "checked" : "" ?> data-color="#FAAB15" data-size="small" name="permisoEditarInstructores" type="checkbox" /></td>
                                                                                                    </tr>
                                                                                                    <tr>
                                                                                                        <td>Categorías</td>
                                                                                                        <td><input class="js-switch" < ?php echo $permisoConsultarCategorias == 1 ? "checked" : "" ?> data-color="#FAAB15" data-size="small" name="permisoConsultarCategorias" type="checkbox" /></td>
                                                                                                        <td><input class="js-switch" < ?php echo $permisoEditarCategorias == 1 ? "checked" : "" ?> data-color="#FAAB15" data-size="small" name="permisoEditarCategorias" type="checkbox" /></td>
                                                                                                    </tr>
                                                                                                    <tr>
                                                                                                        <td>Cursos</td>
                                                                                                        <td><input class="js-switch" < ?php echo $permisoConsultarCursos == 1 ? "checked" : "" ?> data-color="#FAAB15" data-size="small" name="permisoConsultarCursos" type="checkbox" /></td>
                                                                                                        <td><input class="js-switch" < ?php echo $permisoEditarCursos == 1 ? "checked" : "" ?> data-color="#FAAB15" data-size="small" name="permisoEditarCursos" type="checkbox" /></td>
                                                                                                    </tr>
                                                                                                    <tr>
                                                                                                        <td>Módulos</td>
                                                                                                        <td><input class="js-switch" < ?php echo $permisoConsultarModulos == 1 ? "checked" : "" ?> data-color="#FAAB15" data-size="small" name="permisoConsultarModulos" type="checkbox" /></td>
                                                                                                        <td><input class="js-switch" < ?php echo $permisoEditarModulos == 1 ? "checked" : "" ?> data-color="#FAAB15" data-size="small" name="permisoEditarModulos" type="checkbox" /></td>
                                                                                                    </tr>
                                                                                                    <tr>
                                                                                                        <td>Alumnos</td>
                                                                                                        <td><input class="js-switch" < ?php echo $permisoConsultarAlumnos == 1 ? "checked" : "" ?> data-color="#FAAB15" data-size="small" name="permisoConsultarAlumnos" type="checkbox" /></td>
                                                                                                        <td><input class="js-switch" < ?php echo $permisoEditarAlumnos == 1 ? "checked" : "" ?> data-color="#FAAB15" data-size="small" name="permisoEditarAlumnos" type="checkbox" /></td>
                                                                                                    </tr>
                                                                                                    <tr>
                                                                                                        <td>Evaluaciones</td>
                                                                                                        <td><input class="js-switch" < ?php echo $permisoConsultarEvaluaciones == 1 ? "checked" : "" ?> data-color="#FAAB15" data-size="small" name="permisoConsultarEvaluaciones" type="checkbox" /></td>
                                                                                                        <td></td>
                                                                                                    </tr-->
                                                                                                </tbody>
                                                                                            </table>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="form-actions mt-50">
                                                                            <?php if ($esUsuarioMaster || $usuario_permisoEditarUsuarios) { ?>
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
                            registraEvento("Consulta de usuario bloqueada | id = " . $id);
                            muestraBloqueo();
                        }
                    ?>

                    <?php include("personalizado/php/estructura/pieDePagina.php"); ?>
                </div>
            </div>
        </div>

        <?php include("personalizado/php/estructura/plugins.php"); ?>

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

                $(".select2").select2();

                $(".bs-switch").bootstrapSwitch({
                    handleWidth: 110,
                    labelWidth: 110
                });

                $(".js-switch").each(function() {
                    new Switchery($(this)[0], $(this).data());
                });
            });


            // Regresa a la interfaz de origen


            $(".link_origen").click(function() {
                $("#formulario_origen").submit();
            });
        </script>
    </body>
</html>
