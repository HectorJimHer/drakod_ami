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
            $apellido = filter_input(INPUT_POST, "apellido");
            $telefonoFijo = filter_input(INPUT_POST, "telefonoFijo");
            $telefonoMovil = filter_input(INPUT_POST, "telefonoMovil");
            $correoElectronico = filter_input(INPUT_POST, "correoElectronico");
            $contrasena = filter_input(INPUT_POST, "contrasena");
            $empresa = filter_input(INPUT_POST, "empresa");
            $puesto = filter_input(INPUT_POST, "puesto");
            $autorizador_nombre = filter_input(INPUT_POST, "autorizador_nombre");
            $autorizador_correoElectronico = filter_input(INPUT_POST, "autorizador_correoElectronico");

            $cantidadCursos = filter_input(INPUT_POST, "cantidadCursos");

            // Parametros enviados por origen

            $origen = filter_input(INPUT_POST, "origen");
            $origen_idCurso = filter_input(INPUT_POST, "origen_idCurso");
            $origen_rangoFechas = filter_input(INPUT_POST, "origen_rangoFechas");

            // Inicializa variables

            $mensaje = "";
            $fechaActual = date("Y-m-d H:i:s");
            $habilitado = estaVacio($habilitado) ? 0 : 1;

            // Procesa el request

            if (!estaVacio($esSubmit) && $esSubmit === "1") {

                // Valida los campos obligatorios

                if (estaVacio($nombre)) {
                    $mensaje .= "* Nombre<br />";
                }

                if (estaVacio($apellido)) {
                    $mensaje .= "* Apellido<br />";
                }

                if (estaVacio($correoElectronico)) {
                    $mensaje .= "* Correo Electrónico<br />";
                }

                if (!estaVacio($mensaje)) {
                    $mensaje = "Proporciona los siguientes datos:<br /><br />" . $mensaje;
                } else {
                    if (estaVacio($id)) {

                        // Es insercion
                        $contrasena = generaCadenaAleatoria(8);

                        if (estaVacio($contrasena)) {
                            $mensaje = "Proporciona los siguientes datos:<br /><br />* Contraseña" . $mensaje;
                        } else {
                            $alumno_BD = consulta($conexion, "SELECT id FROM alumno WHERE nombre = '" . $nombre. "' AND apellido = '". $apellido."' AND correoElectronico = '" . $correoElectronico . "'");

                            if (cuentaResultados($alumno_BD) > 0) {
                                $mensaje = "Este alumno ya se encuentra registrado en la base de datos";
                            } else {
                                consulta($conexion, "INSERT INTO alumno ("
                                        . "fechaRegistro"
                                        . ", habilitado"
                                        . ", nombre"
                                        . ", apellido"
                                        . ", telefonoFijo"
                                        . ", telefonoMovil"
                                        . ", correoElectronico"
                                        . ", contrasena"
                                        . ", empresa"
                                        . ", puesto"
                                        . ", autorizador_nombre"
                                        . ", autorizador_correoElectronico"
                                    . ") VALUES ("
                                        . "'" . $fechaActual . "'"
                                        . ", " . $habilitado
                                        . ", '" . $nombre . "'"
                                        . ", '" . $apellido . "'"
                                        . ", " . (estaVacio($telefonoFijo) ? "NULL" : "'" . $telefonoFijo . "'")
                                        . ", " . (estaVacio($telefonoMovil) ? "NULL" : "'" . $telefonoMovil . "'")
                                        . ", '" . $correoElectronico . "'"
                                        . ", '" . md5($contrasena) . "'"
                                        . ", " . (estaVacio($empresa) ? "NULL" : "'" . $empresa . "'")
                                        . ", " . (estaVacio($puesto) ? "NULL" : "'" . $puesto . "'")
                                        . ", " . (estaVacio($autorizador_nombre) ? "NULL" : "'" . $autorizador_nombre . "'")
                                        . ", " . (estaVacio($autorizador_correoElectronico) ? "NULL" : "'" . $autorizador_correoElectronico . "'")
                                    . ")");

                                $alumno_BD = consulta($conexion, "SELECT * FROM alumno WHERE nombre = '" . $nombre. "' AND apellido = '". $apellido."' AND correoElectronico = '" . $correoElectronico . "'");
                                $alumno = obtenResultado($alumno_BD);

                                $id = $alumno["id"];
                                $fechaRegistro = $alumno["fechaRegistro"];
                                $habilitado = $alumno["habilitado"];
                                $nombre = $alumno["nombre"];
                                $apellido = $alumno["apellido"];
                                $telefonoFijo = $alumno["telefonoFijo"];
                                $telefonoMovil = $alumno["telefonoMovil"];
                                $correoElectronico = $alumno["correoElectronico"];
                                $contrasena = $alumno["contrasena"];
                                $empresa = $alumno["empresa"];
                                $puesto = $alumno["puesto"];
                                $autorizador_nombre = $alumno["autorizador_nombre"];
                                $autorizador_correoElectronico = $alumno["autorizador_correoElectronico"];

                                // Carga cursos
                                echo $cantidadCursos . "koi";
                                if (!estaVacio($cantidadCursos) && $cantidadCursos > 0) {
                                    for ($indiceCursos = 0; $indiceCursos < $cantidadCursos; $indiceCursos++) {
                                        $idCurso = filter_input(INPUT_POST, "curso_idCurso_" . $indiceCursos);

                                        if (!estaVacio($idCurso)) {
                                            consulta($conexion, "INSERT INTO alumno_curso (idCurso, idAlumno) VALUES (" . $idCurso . ", " . $id . ")");

                                            //Se buscan todos los modulos del curso
                                            $modulos_BD = consulta($conexion, "select * from curso_modulo cm
                                                                                                            inner join modulo m on cm.idModulo = m.id and m.habilitado = 1
                                                                                                            where cm.idCurso = " . $idCurso . "
                                                                                                           order by cm.orden");
                                            //El primero modulo es activo
                                            $activo = 1;
                                            while ($modulo = obtenResultado($modulos_BD)) {

                                                //Se insertan los modulos dêl curso en la tabla alumno_curso_modulo
                                                consulta($conexion, "INSERT INTO alumno_curso_modulo (idAlumno, idCurso, idModulo, orden, activo, concluido)
                                                VALUES (" . $id . ", " . $idCurso . ", " . $modulo["idModulo"] . ", " . $modulo["orden"] . ", " . $activo . ", " . 0 . ")");

                                                //Los modulos subsecuentes no estara activos
                                                $activo = 0;
                                            }

                                            if (isset($_FILES["curso_diploma_" . $indiceCursos]) && $_FILES["curso_diploma_" . $indiceCursos]["size"] > 0) {
                                                $archivo = $_FILES["curso_diploma_" . $indiceCursos];

                                                $archivo_ruta = $constante_directorioAlumno . $id . "/";
                                                $archivo_nombre = "alumno_" . $id . "_curso_" . $idCurso . "_diploma." . pathinfo($archivo["name"], PATHINFO_EXTENSION);

                                                if (!file_exists($archivo_ruta)) {
                                                    mkdir($archivo_ruta, 0755, true);
                                                }

                                                move_uploaded_file($archivo["tmp_name"], $archivo_ruta . $archivo_nombre);

                                                consulta($conexion, "UPDATE alumno_curso SET diploma = '" . $archivo_nombre . "' WHERE idCurso = " . $idCurso . " AND idAlumno = " . $id);
                                            }
                                        }
                                    }
                                }

                                // Carga informacion actualizada




                                registraEvento("Alta de alumno | id = " . $id);

                                $mensaje = "ok - El alumno ha sido registrado";
                            }
                        }
                    } else {

                        // Es actualizacion

                        consulta($conexion, "UPDATE alumno SET"
                                . " habilitado = " . $habilitado
                                . ", nombre = '" . $nombre . "'"
                                . ", apellido = '" . $apellido . "'"
                                . ", telefonoFijo = " . (estaVacio($telefonoFijo) ? "NULL" : "'" . $telefonoFijo . "'")
                                . ", telefonoMovil = " . (estaVacio($telefonoMovil) ? "NULL" : "'" . $telefonoMovil . "'")
                                . ", correoElectronico = '" . $correoElectronico . "'"
                                . ", empresa = " . (estaVacio($empresa) ? "NULL" : "'" . $empresa . "'")
                                . ", puesto = " . (estaVacio($puesto) ? "NULL" : "'" . $puesto . "'")
                                . ", autorizador_nombre = " . (estaVacio($autorizador_nombre) ? "NULL" : "'" . $autorizador_nombre . "'")
                                . ", autorizador_correoElectronico = " . (estaVacio($autorizador_correoElectronico) ? "NULL" : "'" . $autorizador_correoElectronico . "'")
                            . " WHERE id = " . $id);



                        // Carga cursos

                        if (!estaVacio($cantidadCursos) && $cantidadCursos > 0) {
                            for ($indiceCursos = 0; $indiceCursos < $cantidadCursos; $indiceCursos++) {
                                $idCurso = filter_input(INPUT_POST, "curso_idCurso_" . $indiceCursos);

                                if (!estaVacio($idCurso)) {

                                    //Validando existencia del registro
                                    $resultado = consulta($conexion, "select * from alumno_curso ac where ac.idCurso = " . $idCurso. " and ac.idAlumno = " . $id);
                                    if(cuentaResultados($resultado) <= 0){

                                        consulta($conexion, "INSERT INTO alumno_curso (idCurso, idAlumno) VALUES (" . $idCurso . ", " . $id . ")");

                                        //Se buscan todos los modulos del curso
                                        $modulos_BD = consulta($conexion, "select * from curso_modulo cm
                                                                                                        inner join modulo m on cm.idModulo = m.id and m.habilitado = 1
                                                                                                        where cm.idCurso = " . $idCurso . "
                                                                                                       order by cm.orden");
                                        //El primero modulo es activo
                                        $activo = 1;
                                        while ($modulo = obtenResultado($modulos_BD)) {

                                            //Se insertan los modulos dêl curso en la tabla alumno_curso_modulo
                                            consulta($conexion, "INSERT INTO alumno_curso_modulo (idAlumno, idCurso, idModulo, orden, activo, concluido)
                                            VALUES (" . $id . ", " . $idCurso . ", " . $modulo["idModulo"] . ", " . $modulo["orden"] . ", " . $activo . ", " . 0 . ")");

                                            //Los modulos subsecuentes no estara activos
                                            $activo = 0;
                                        }


                                    }
                                }

                                if (isset($_FILES["curso_diploma_" . $indiceCursos]) && $_FILES["curso_diploma_" . $indiceCursos]["size"] > 0) {
                                    $archivo = $_FILES["curso_diploma_" . $indiceCursos];

                                    $archivo_ruta = $constante_directorioAlumno . $id . "/";
                                    $archivo_nombre = "alumno_" . $id . "_curso_" . $idCurso . "_diploma." . pathinfo($archivo["name"], PATHINFO_EXTENSION);

                                    if (!file_exists($archivo_ruta)) {
                                        mkdir($archivo_ruta, 0755, true);
                                    }

                                    move_uploaded_file($archivo["tmp_name"], $archivo_ruta . $archivo_nombre);

                                    consulta($conexion, "UPDATE alumno_curso SET diploma = '" . $archivo_nombre . "' WHERE idCurso = " . $idCurso . " AND idAlumno = " . $id);
                                }
                            }
                        }

                        // Carga informacion actualizada

                        $alumno_BD = consulta($conexion, "SELECT * FROM alumno WHERE id = " . $id);
                        $alumno = obtenResultado($alumno_BD);

                        $id = $alumno["id"];
                        $fechaRegistro = $alumno["fechaRegistro"];
                        $habilitado = $alumno["habilitado"];
                        $nombre = $alumno["nombre"];
                        $apellido = $alumno["apellido"];
                        $telefonoFijo = $alumno["telefonoFijo"];
                        $telefonoMovil = $alumno["telefonoMovil"];
                        $correoElectronico = $alumno["correoElectronico"];
                        $contraseña = $alumno["contrasena"];
                        $empresa = $alumno["empresa"];
                        $puesto = $alumno["puesto"];
                        $autorizador_nombre = $alumno["autorizador_nombre"];
                        $autorizador_correoElectronico = $alumno["autorizador_correoElectronico"];

                        registraEvento("Actualizacion de alumno | id = " . $id);

                        $mensaje = "ok - Los cambios han sido guardados";
                    }
                }
            } else {
                if (!estaVacio($id)) {

                    // Es consulta

                    $alumno_BD = consulta($conexion, "SELECT * FROM alumno WHERE id = " . $id);
                    $alumno = obtenResultado($alumno_BD);

                    $id = $alumno["id"];
                    $fechaRegistro = $alumno["fechaRegistro"];
                    $habilitado = $alumno["habilitado"];
                    $nombre = $alumno["nombre"];
                    $apellido = $alumno["apellido"];
                    $telefonoFijo = $alumno["telefonoFijo"];
                    $telefonoMovil = $alumno["telefonoMovil"];
                    $correoElectronico = $alumno["correoElectronico"];
                    $contraseña = $alumno["contrasena"];
                    $empresa = $alumno["empresa"];
                    $puesto = $alumno["puesto"];
                    $autorizador_nombre = $alumno["autorizador_nombre"];
                    $autorizador_correoElectronico = $alumno["autorizador_correoElectronico"];

                    registraEvento("Consulta de alumno | id = " . $id);
                }
            }

            $indiceCursos = 0;
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
                    <?php if ($esUsuarioMaster || $usuario_permisoConsultarAlumnos || $usuario_permisoEditarAlumnos) { ?>

                        <!-- Titulo -->

                        <div class="row heading-bg bg-blue">
                            <div class="col-xs-12">
                                <h5 class="txt-light">Detalle de Alumno</h5>
                            </div>
                        </div>

                        <!-- Bloques de informacion -->

                        <form action="alumno.php" enctype="multipart/form-data" method="post" id="formulario">
                            <input name="esSubmit" type="hidden" value="1" />

                            <input id="campo_cantidadCursos" name="cantidadCursos" type="hidden" />

                            <input name="origen" type="hidden" value="<?php echo $origen ?>" />
                            <input name="origen_idCurso" type="hidden" value="<?php echo $origen_idCurso ?>" />
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
                                                                                        <label class="control-label mb-10">Apellido <span class="txt-danger ml-10">*</span></label>
                                                                                        <input class="form-control" name="apellido" type="text" value="<?php echo $apellido ?>" />
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="row mb-30">
                                                                                <div class="col-md-6">
                                                                                    <div class="form-group">
                                                                                        <label class="control-label mb-10">Correo electrónico<span class="txt-danger ml-10">*</span></label>
                                                                                        <input class="form-control" name="correoElectronico" type="email" value="<?php echo $correoElectronico ?>" />
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-md-6" style="display:none;">
                                                                                    <div class="form-group">
                                                                                        <label class="control-label mb-10">Contraseña</label>
                                                                                        <input class="form-control" name="contrasena" type="password" value="" />
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <div class="form-group">
                                                                                        <label class="control-label mb-10">Teléfono Fijo</label>
                                                                                        <input class="form-control" name="telefonoFijo" type="text" value="<?php echo $telefonoFijo ?>" />
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="row mb-30">


                                                                                <div class="col-md-6">
                                                                                    <div class="form-group">
                                                                                        <label class="control-label mb-10">Teléfono Móvil</label>
                                                                                        <input class="form-control" name="telefonoMovil" type="text" value="<?php echo $telefonoMovil ?>" />
                                                                                    </div>
                                                                                </div>

                                                                                 <div class="col-md-6">
                                                                                    <div class="form-group">
                                                                                        <label class="control-label mb-10">Empresa</label>
                                                                                        <input class="form-control" name="empresa" type="text" value="<?php echo $empresa ?>" />
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="row mb-30">

                                                                                <div class="col-md-6">
                                                                                    <div class="form-group">
                                                                                        <label class="control-label mb-10">Puesto</label>
                                                                                        <input class="form-control" name="puesto" type="text" value="<?php echo $puesto ?>" />
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <br /><br />

                                                                            <div class="row mb-30">
                                                                                <div class="col-md-12">
                                                                                    <h5><strong>Autorizador</strong></h5>
                                                                                </div>
                                                                            </div>

                                                                            <div class="row mb-30">
                                                                                <div class="col-md-6">
                                                                                    <div class="form-group">
                                                                                        <label class="control-label mb-10">Nombre</label>
                                                                                        <input class="form-control" name="autorizador_nombre" type="text" value="<?php echo $autorizador_nombre ?>" />
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <div class="form-group">
                                                                                        <label class="control-label mb-10">Correo electrónico</label>
                                                                                        <input class="form-control" name="autorizador_correoElectronico" type="text" value="<?php echo $autorizador_correoElectronico ?>" />
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <br /><br />

                                                                            <div class="row mb-30">
                                                                                <div class="col-md-12 col-xs-12">
                                                                                    <h5><strong>Cursos</strong></h5>
                                                                                </div>
                                                                            </div>

                                                                            <div class="row mb-30">
                                                                                <div class="col-md-12">
                                                                                    <div class="table-wrap">
                                                                                        <div class="table-responsive">
                                                                                            <table class="table mb-0">
                                                                                                <thead>
                                                                                                    <tr>
                                                                                                        <th style="width: 40%">Curso</th>
                                                                                                        <th>Modulos concluidos</th>
                                                                                                        <th>Calificación promedio</th>
                                                                                                        <th>Diploma</th>
                                                                                                        <th>Fecha de envío</th>
                                                                                                        <th>Acciones</th>
                                                                                                    </tr>
                                                                                                </thead>

                                                                                                <tbody id="tabla_cursos">
                                                                                                    <?php
                                                                                                        if(!estaVacio($id)) {
                                                                                                            $cursos_BD = consulta($conexion, "SELECT
                                                                                                                c.id,
                                                                                                                c.nombre,
                                                                                                                ac.idCurso,
                                                                                                                ac.diploma,
                                                                                                                ac.fechaEnvio,
                                                                                                                (select count(distinct m.idModulo) from curso_modulo m where ac.idCurso = m.idCurso) as modulos,
                                                                                                                (select count(distinct acm.idModulo) from alumno_curso_modulo acm where acm.idAlumno = ac.idAlumno and acm.idCurso =  ac.idCurso
                                                                                                                    and acm.activo = 1 and acm.concluido = 1) as terminados,
                                                                                                                (select sum(er.calificacion) from examenresumen er where er.idAlumno = ac.idAlumno and er.idCurso = ac.idCurso) as sumaCalificacion,
                                                                                                                (select count(*) from curso_modulo cm
                                                                                                                inner join modulo mo on cm.idModulo = mo.id
                                                                                                                where cm.idCurso = ac.idCurso
                                                                                                                and mo.examenHabilitado = 1 and mo.idExamen > 0) as modulosExamen
                                                                                                                FROM
                                                                                                                alumno_curso ac
                                                                                                                LEFT JOIN curso c ON c.id = ac.idCurso
                                                                                                                WHERE
                                                                                                                ac.idAlumno = " . $id . " ORDER BY ac.idCurso DESC");

                                                                                                            while ($curso = obtenResultado($cursos_BD)) {
                                                                                                                echo "<tr id='linea_curso_" . $indiceCursos . "'>";
                                                                                                                echo "<td><input name='curso_idCurso_" . $indiceCursos . "' type='hidden' value='" . $curso["id"] . "' />" . $curso["nombre"] . "</td>";
                                                                                                                echo "<td>" . $curso["terminados"] . " de " . $curso["modulos"] . "</td>";
                                                                                                                if($curso["modulosExamen"] != 0){
                                                                                                                    echo "<td>" . number_format(($curso["sumaCalificacion"]/$curso["modulosExamen"]),2) ."</td>";
                                                                                                                }else{
                                                                                                                    echo "<td>N/A</td>";
                                                                                                                }
                                                                                                                echo "<td>" . (estaVacio($curso["diploma"]) ? "" : "<a download href='" . $constante_urlAlumno . $id . "/" . $curso["diploma"] . "'>" . $curso["diploma"] . "</a><br /><br />") . "<input class='form-control' name='curso_diploma_" . $indiceCursos. "' type='file' /></td>";
                                                                                                                echo "<td>" . $curso['fechaEnvio'] . "</td>";

                                                                                                                echo "<td>";

                                                                                                                if ($esUsuarioMaster || $usuario_permisoEditarAlumnos) {
                                                                                                                    echo "<a class='enlace_borrarCurso' data-idCurso='" . $curso["idCurso"] . "' data-indiceCursos='" . $indiceCursos . "' href='javascript:;' title='Borrar'><i class='fa fa-trash-o'></i></a>";

                                                                                                                    if(!estaVacio($curso["diploma"])) {
echo "&nbsp; | &nbsp; <a class='enlace_enviarCorreo' data-idCurso='" . $curso["idCurso"] . "' data-idAlumno='" . $id . "' href='javascript:;' title='Enviar correo'><i class='fa fa-file-o'></i></a>";
                                                                                                                    }
                                                                                                                    echo "&nbsp; | &nbsp; <a class='enlace_correoBienvenida' data-idCurso='" . $curso["idCurso"] . "' data-idAlumno='" . $id . "' href='javascript:;' title='Enviar bienvenida'><i class='fa fa-hand-paper-o'></i></a>";
                                                                                                                    echo "&nbsp; | &nbsp; <a class='verExamenes'  href='resultadosExamen.php?idCurso=" . $curso["id"] . "&idAlumno=" . $id . "' title='Ver exámenes' data-toggle='modal' data-target='#theModal" . $curso["id"]. "'><i class='fa fa-search'></i></a>";

                                                                                                                }

                                                                                                                echo "</td>";

                                                                                                                echo "</tr>";
                                                                                                                echo '<div id="theModal' . $curso["id"] . '" class="modal fade text-center">
                                                                                                                    <div class="modal-dialog modal-lg">
                                                                                                                      <div class="modal-content">
                                                                                                                      </div>
                                                                                                                    </div>
                                                                                                                </div>';

                                                                                                                $indiceCursos++;
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
                                                                                    <?php if ($esUsuarioMaster || $usuario_permisoEditarAlumnos) { ?>
                                                                                        <a class="btn btn-xs btn-primary" href="javascript:;" id="enlace_agregarCurso">Agregar</a>
                                                                                    <?php } ?>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="form-actions mt-50">
                                                                            <?php if ($esUsuarioMaster || $usuario_permisoEditarAlumnos) { ?>
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

                            <!-- Regreso -->

                            <input name="idCurso" type="hidden" value="<?php echo $origen_idCurso ?>" />
                            <input name="rangoFechas" type="hidden" value="<?php echo $origen_rangoFechas ?>" />
                        </form>
                    <?php
                        } else {
                            registraEvento("Consulta de alumno bloqueada | id = " . $id);
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
            var indiceCursos = <?php echo $indiceCursos ?>;


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


            // Carga opciones de cursos disponibles


            function cargaCursos(indice) {
                $.ajax({
                    url: "personalizado/php/ajax/cargaCursos.php",
                    type: "post",
                    success: function(xml) {
                        $("#campo_curso_idCurso_" + indice).append("<option value=''>Elige</option>");

                        $(xml).find("curso").each(function() {
                            var id = $(this).find("id").text();
                            var nombre = $(this).find("nombre").text();

                            $("#campo_curso_idCurso_" + indice).append("<option value='" + id + "'>" + nombre + "</option>");
                        });
                    }
                });
            }


            // Vincula un curso al alumno


            $("#enlace_agregarCurso").click(function() {
                var linea = "";

                linea += "<tr id='linea_curso_" + indiceCursos + "'>";
                linea += "<td><select class='form-control select2' id='campo_curso_idCurso_" + indiceCursos + "' name='curso_idCurso_" + indiceCursos + "'></select></td>";
                linea += "<td><input class='form-control' name='curso_diploma_" + indiceCursos + "' type='file' /></td>";
                linea += "<td></td>";
                linea += "<td></td>";
                linea += "</tr>";

                cargaCursos(indiceCursos);

                $("#tabla_cursos").append(linea);

                $(".select2").select2();

                indiceCursos++;
            });


            // Desvincula un curso del alumno


            $(".enlace_borrarCurso").click(function() {
                if (confirm("Al continuar se desvinculará el curso del alumno, ¿desea proceder?")) {
                    var idAlumno = "<?php echo $id ?>";
                    var idCurso = $(this).attr("data-idCurso");
                    var indiceCurso = $(this).attr("data-indiceCursos");

                    $.ajax({
                        url: "personalizado/php/ajax/eliminaCursoAlumno.php",
                        type: "post",
                        data: { idAlumno: idAlumno, idCurso: idCurso },
                        success: function() {
                            $("#linea_curso_" + indiceCurso).remove();
                        }
                    });
                }
            });




                $(".enlace_enviarCorreo").click(function(){
                    if (confirm("¿Proceder a enviar correo al Alumno?")) {
                        var idCurso = $(this).attr("data-idCurso");
                        var idAlumno = $(this).attr("data-idAlumno");

                        $.ajax({
                            url: "personalizado/php/ajax/enviaCorreoAlumno.php",
                            type: "post",
                            data: { idAlumno: idAlumno, idCurso: idCurso },
                            success: function(resultado) {
                                if (resultado == "ok") {
                                    $("#contenedor_mensaje span").html("Se ha enviado al alumno un correo de información");
                                    $("#contenedor_mensaje").removeClass("alert-danger");
                                    $("#contenedor_mensaje").addClass("alert-success");
                                    $("#contenedor_mensaje").show();
                                } else {
                                    $("#contenedor_mensaje span").html(resultado);
                                    $("#contenedor_mensaje").removeClass("alert-success");
                                    $("#contenedor_mensaje").addClass("alert-danger");
                                    $("#contenedor_mensaje").show();
                                }

                                $("html, body").animate({ scrollTop: 0 }, "slow");
                            }
                        });
                    }
                });

                $(".enlace_correoBienvenida").click(function(){
                    if (confirm("¿Proceder a enviar correo al Alumno?")) {
                        var idCurso = $(this).attr("data-idCurso");
                        var idAlumno = $(this).attr("data-idAlumno");

                        $.ajax({
                            url: "personalizado/php/ajax/enviaCorreoBienvenida.php",
                            type: "post",
                            data: { idAlumno: idAlumno, idCurso: idCurso },
                            success: function(resultado) {
                                if (resultado == "ok") {
                                    $("#contenedor_mensaje span").html("Se ha enviado al alumno un correo de información");
                                    $("#contenedor_mensaje").removeClass("alert-danger");
                                    $("#contenedor_mensaje").addClass("alert-success");
                                    $("#contenedor_mensaje").show();
                                } else {
                                    $("#contenedor_mensaje span").html(resultado);
                                    $("#contenedor_mensaje").removeClass("alert-success");
                                    $("#contenedor_mensaje").addClass("alert-danger");
                                    $("#contenedor_mensaje").show();
                                }

                                $("html, body").animate({ scrollTop: 0 }, "slow");
                            }
                        });
                    }
                })







            // Procesa envio del formulario


            $("#boton_guardar").click(function(e) {
                $("#campo_cantidadCursos").val(indiceCursos);

                $("#formulario").submit();
            });


            // Regresa a la interfaz de origen


            $(".link_origen").click(function() {
                $("#formulario_origen").submit();
            });
        </script>
    </body>
</html>
