<?php include("personalizado/php/comunes/manejaSesion.php"); ?>


<!DOCTYPE html>


<html lang="es">
    <head>
        <?php include("personalizado/php/estructura/head.php"); ?>
        <style type="text/css" media="screen">
            .col-md-2 .form-group{
                text-align: center;
                margin-bottom: 0;
            }
            p{
                color: black;
            }

            .col-md-10 .form-group{
                margin-bottom: 0;
            }

            .menorMargen{
                margin-bottom: 10px !important;
            }
            .margenSuperior{
                margin-top: 30px;
            }

            .col-md-2{
                padding-right: 0 !important;
            }

            .col-md-10{
                padding-left: 0 !important;
            }
            
        </style>
    </head>


    <body>
        <?php

            // Obtiene parametros de request

            $esSubmit = filter_input(INPUT_POST, "esSubmit");
            $id = filter_input(INPUT_POST, "id");

            // Parametros enviados por origen

            $origen = filter_input(INPUT_POST, "origen");
            $origen_idCurso = filter_input(INPUT_POST, "origen_idCurso");
            $origen_idModulo = filter_input(INPUT_POST, "origen_idModulo");
            $origen_idAlumno = filter_input(INPUT_POST, "origen_idAlumno");
            $origen_idInstructor = filter_input(INPUT_POST, "origen_idInstructor");
            $origen_rangoFechas = filter_input(INPUT_POST, "origen_rangoFechas");


            // Inicializa variables

            $mensaje = "";
            $fechaActual = date("Y-m-d H:i:s");

            // Procesa el request

            if (!estaVacio($id)) {

                // Es consulta

                $examen_BD = consulta($conexion, "SELECT 
                                                        e.id,
                                                        e.fechaRegistro,
                                                        a.nombre as nombreAlumno,
                                                        a.apellido as apellidoAlumno,
                                                        c.nombre as nombreCurso,
                                                        m.nombre as nombreModulo,
                                                        ex.nombre as nombreExamen,
                                                        ex.titulo as tituloExamen,
                                                        (select count(*) from pregunta p
                                                            where p.idExamen = e.idExamen 
                                                            and p.tipoRespuesta = 'Opcion') as cuantas,
                                                        e.aciertos,
                                                        e.errores, 
                                                        e.calificacion
                                                    FROM examenresumen e
                                                    inner join curso c on e.idCurso = c.id
                                                    inner join modulo m on e.idModulo = m.id
                                                    inner join alumno a on e.idAlumno = a.id
                                                    inner join examen ex on e.idExamen = ex.id
                                                    where e.id =" . $id);
                $examen = obtenResultado($examen_BD);

                $id = $examen["id"];
                $fechaRegistro = $examen["fechaRegistro"];
                $nombreAlumno = $examen["nombreAlumno"];
                $apellidoAlumno = $examen["apellidoAlumno"];
                $nombreCurso = $examen["nombreCurso"];
                $nombreModulo = $examen["nombreModulo"];
                $nombreExamen = $examen["nombreExamen"];
                $tituloExamen = $examen["tituloExamen"];
                $cuantas = $examen["cuantas"];
                $aciertos = $examen["aciertos"];
                $errores = $examen["errores"];
                $calificacion = $examen["calificacion"];



                registraEvento("Consulta de examen contestado | id = " . $id);
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
                    <?php if ($esUsuarioMaster || $usuario_permisoConsultarCategorias || $usuario_permisoEditarCategorias) { ?>

                        <!-- Titulo -->

                        <div class="row heading-bg bg-blue">
                            <div class="col-xs-12">
                                <h5 class="txt-light">Detalle de exaamen contestado</h5>
                            </div>
                        </div>

                        <!-- Bloques de informacion -->

                        <form action="categoria.php" enctype="multipart/form-data" method="post">
                            <input name="esSubmit" type="hidden" value="1" />

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
                                                                                        <label class="control-label mb-10">Fecha de Registro</label>
                                                                                        <input class="form-control" name="fechaRegistro" readonly type="text" value="<?php echo $fechaRegistro; ?>" />
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="row mb-30">
                                                                                <div class="col-md-6">
                                                                                    <div class="form-group">
                                                                                        <label class="control-label mb-10">Nombre completo del alumno</label>
                                                                                        <input class="form-control" name="nombre" readonly type="text" value="<?php echo $nombreAlumno . " " . $apellidoAlumno; ?>" />
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <div class="form-group">
                                                                                        <label class="control-label mb-10">Curso </label>
                                                                                        <input class="form-control" name="nombreCurso" readonly type="text" value="<?php echo $nombreCurso; ?>" />
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="row mb-30">
                                                                                <div class="col-md-6">
                                                                                    <div class="form-group">
                                                                                        <label class="control-label mb-10">Módulo</label>
                                                                                        <input class="form-control" name="nombreModulo" readonly type="text" value="<?php echo $nombreModulo;?>" />
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <div class="form-group">
                                                                                        <label class="control-label mb-10">Nombre Examen </label>
                                                                                        <input class="form-control" name="nombreExamen" readonly type="text" value="<?php echo $nombreExamen; ?>" />
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="row mb-30">
                                                                                <div class="col-md-6">
                                                                                    <div class="form-group">
                                                                                        <label class="control-label mb-10">Título Examen</label>
                                                                                        <input class="form-control" name="tituloExamen" readonly type="text" value="<?php echo $tituloExamen;?>" />
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <div class="form-group">
                                                                                        <label class="control-label mb-10">Total de preguntas para calificación</label>
                                                                                        <input class="form-control" name="cuantas" readonly type="text" value="<?php echo $cuantas; ?>" />
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="row mb-30">
                                                                                <div class="col-md-6">
                                                                                    <div class="form-group">
                                                                                        <label class="control-label mb-10">Número de aciertos</label>
                                                                                        <input class="form-control" name="aciertos" readonly type="text" value="<?php echo $aciertos; ?>" />
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-6">
                                                                                    <div class="form-group">
                                                                                        <label class="control-label mb-10">Número de errores</label>
                                                                                        <input class="form-control" name="errores" readonly type="text" value="<?php echo $errores;?>" />
                                                                                    </div>
                                                                                </div>

                                                                                
                                                                            </div>

                                                                            <div class="row mb-30">
                                                                                <div class="col-md-6">
                                                                                    <div class="form-group">
                                                                                        <label class="control-label mb-10">Calificación obtenida</label>
                                                                                        <input class="form-control" name="calificacion" readonly type="text" value="<?php echo $calificacion; ?>" />
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="row mb-30">
                                                                                <div class="col-md-12">
                                                                                    <h5><strong>Preguntas</strong></h5>
                                                                                </div>
                                                                            </div>

                                                                            <?php 
                                                                            $queryPreguntas = "SELECT 
                                                                                                    p.*,
                                                                                                    ed.respondioAlumno,
                                                                                                    ed.correcta
                                                                                                FROM examendetalle ed 
                                                                                                inner join pregunta p on ed.idPregunta = p.id
                                                                                                WHERE ed.idExamenResumen = " . $id . "
                                                                                                order by p.orden";


                                                                            $preguntas_BD = consulta($conexion, $queryPreguntas);
                                                                            
                                                                            while($pregunta = obtenResultado($preguntas_BD)){ ?>

                                                                                <div class="row mb-30 menorMargen margenSuperior">
                                                                                    <div class="col-md-2">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label mb-10">Pregunta <?php echo $pregunta["orden"];?></label>
                                                                                        </div>
                                                                                    </div>

                                                                                    <div class="col-md-10">
                                                                                        <div class="form-group">
                                                                                            <p><?php echo $pregunta["pregunta"]; ?> </p>
                                                                                        </div>
                                                                                    </div>
                                                                                        
                                                                                </div>
                                                                            

                                                                            <?php if($pregunta["tipoRespuesta"] == "Abierta"){

                                                                            ?>

                                                                                

                                                                                <div class="row mb-30 menorMargen">
                                                                                    <div class="col-md-2">
                                                                                    </div>

                                                                                    <div class="col-md-10">
                                                                                        <div class="form-group">
                                                                                            <p><?php echo $pregunta["respondioAlumno"]; ?> </p>
                                                                                        </div>
                                                                                    </div>
                                                                                        
                                                                                </div>
                                                                            <?php }else{ ?>

                                                                                <?php if($pregunta["respuesta1_texto"] != ""){?>

                                                                                <div class="row mb-30 menorMargen">
                                                                                    <div class="col-md-2">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label mb-10">
                                                                                                <?php 
                                                                                                if($pregunta["respondioAlumno"] == "respuesta1"){
                                                                                                    if($pregunta["correcta"] == 1){
                                                                                                        echo '<i class="fa fa-check" aria-hidden="true"></i>';
                                                                                                    }else{
                                                                                                        echo '<i class="fa fa-times" aria-hidden="true"></i>
';
                                                                                                    }
                                                                                                }

                                                                                                ?>
                                                                                            </label>
                                                                                        </div>
                                                                                    </div>

                                                                                    <div class="col-md-10">
                                                                                        <div class="form-group">
                                                                                            <p style="<?php echo $pregunta["respuesta1_correcta"] == 1 ? "font-weight: bold;" : "" ; ?>"><?php echo $pregunta["respuesta1_texto"]; ?> </p>
                                                                                        </div>
                                                                                    </div>
                                                                                        
                                                                                </div>

                                                                                <?php }?>

                                                                                <?php if($pregunta["respuesta2_texto"] != ""){?>

                                                                                <div class="row mb-30">
                                                                                    <div class="col-md-2">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label mb-10">
                                                                                                <?php 
                                                                                                if($pregunta["respondioAlumno"] == "respuesta2"){
                                                                                                    if($pregunta["correcta"] == 1){
                                                                                                        echo '<i class="fa fa-check" aria-hidden="true"></i>';
                                                                                                    }else{
                                                                                                        echo '<i class="fa fa-times" aria-hidden="true"></i>
';
                                                                                                    }
                                                                                                }

                                                                                                ?>
                                                                                            </label>
                                                                                        </div>
                                                                                    </div>

                                                                                    <div class="col-md-10">
                                                                                        <div class="form-group">
                                                                                            <p style="<?php echo $pregunta["respuesta2_correcta"] == 1 ? "font-weight: bold;" : "" ; ?>"><?php echo $pregunta["respuesta2_texto"]; ?> </p>
                                                                                        </div>
                                                                                    </div>
                                                                                        
                                                                                </div>

                                                                                <?php }?>

                                                                                <?php if($pregunta["respuesta3_texto"] != ""){?>

                                                                                <div class="row mb-30">
                                                                                    <div class="col-md-2">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label mb-10">
                                                                                                <?php 
                                                                                                if($pregunta["respondioAlumno"] == "respuesta3"){
                                                                                                    if($pregunta["correcta"] == 1){
                                                                                                        echo '<i class="fa fa-check" aria-hidden="true"></i>';
                                                                                                    }else{
                                                                                                        echo '<i class="fa fa-times" aria-hidden="true"></i>
';
                                                                                                    }
                                                                                                }

                                                                                                ?>
                                                                                            </label>
                                                                                        </div>
                                                                                    </div>

                                                                                    <div class="col-md-10">
                                                                                        <div class="form-group">
                                                                                            <p style="<?php echo $pregunta["respuesta3_correcta"] == 1 ? "font-weight: bold;" : "" ; ?>"><?php echo $pregunta["respuesta3_texto"]; ?> </p>
                                                                                        </div>
                                                                                    </div>
                                                                                        
                                                                                </div>

                                                                                <?php }?>

                                                                                <?php if($pregunta["respuesta4_texto"] != ""){?>

                                                                                <div class="row mb-30">
                                                                                    <div class="col-md-2">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label mb-10">
                                                                                                <?php 
                                                                                                if($pregunta["respondioAlumno"] == "respuesta4"){
                                                                                                    if($pregunta["correcta"] == 1){
                                                                                                        echo '<i class="fa fa-check" aria-hidden="true"></i>';
                                                                                                    }else{
                                                                                                        echo '<i class="fa fa-times" aria-hidden="true"></i>
';
                                                                                                    }
                                                                                                }

                                                                                                ?>
                                                                                            </label>
                                                                                        </div>
                                                                                    </div>

                                                                                    <div class="col-md-10">
                                                                                        <div class="form-group">
                                                                                            <p style="<?php echo $pregunta["respuesta4_correcta"] == 1 ? "font-weight: bold;" : "" ; ?>"><?php echo $pregunta["respuesta4_texto"]; ?> </p>
                                                                                        </div>
                                                                                    </div>
                                                                                        
                                                                                </div>

                                                                                <?php }?>

                                                                                <?php if($pregunta["respuesta5_texto"] != ""){?>

                                                                                <div class="row mb-30">
                                                                                    <div class="col-md-2">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label mb-10">
                                                                                                <?php 
                                                                                                if($pregunta["respondioAlumno"] == "respuesta5"){
                                                                                                    if($pregunta["correcta"] == 1){
                                                                                                        echo '<i class="fa fa-check" aria-hidden="true"></i>';
                                                                                                    }else{
                                                                                                        echo '<i class="fa fa-times" aria-hidden="true"></i>
';
                                                                                                    }
                                                                                                }

                                                                                                ?>
                                                                                            </label>
                                                                                        </div>
                                                                                    </div>

                                                                                    <div class="col-md-10">
                                                                                        <div class="form-group">
                                                                                            <p style="<?php echo $pregunta["respuesta5_correcta"] == 1 ? "font-weight: bold;" : "" ; ?>"><?php echo $pregunta["respuesta5_texto"]; ?> </p>
                                                                                        </div>
                                                                                    </div>
                                                                                        
                                                                                </div>

                                                                                <?php }?>

                                                                                <?php if($pregunta["respuesta6_texto"] != ""){?>

                                                                                <div class="row mb-30">
                                                                                    <div class="col-md-2">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label mb-10">
                                                                                                <?php 
                                                                                                if($pregunta["respondioAlumno"] == "respuesta6"){
                                                                                                    if($pregunta["correcta"] == 1){
                                                                                                        echo '<i class="fa fa-check" aria-hidden="true"></i>';
                                                                                                    }else{
                                                                                                        echo '<i class="fa fa-times" aria-hidden="true"></i>
';
                                                                                                    }
                                                                                                }

                                                                                                ?>
                                                                                            </label>
                                                                                        </div>
                                                                                    </div>

                                                                                    <div class="col-md-10">
                                                                                        <div class="form-group">
                                                                                            <p style="<?php echo $pregunta["respuesta6_correcta"] == 1 ? "font-weight: bold;" : "" ; ?>"><?php echo $pregunta["respuesta6_texto"]; ?> </p>
                                                                                        </div>
                                                                                    </div>
                                                                                        
                                                                                </div>

                                                                                <?php }?>

                                                                                <?php if($pregunta["respuesta7_texto"] != ""){?>

                                                                                <div class="row mb-30">
                                                                                    <div class="col-md-2">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label mb-10">
                                                                                                <?php 
                                                                                                if($pregunta["respondioAlumno"] == "respuesta7"){
                                                                                                    if($pregunta["correcta"] == 1){
                                                                                                        echo '<i class="fa fa-check" aria-hidden="true"></i>';
                                                                                                    }else{
                                                                                                        echo '<i class="fa fa-times" aria-hidden="true"></i>
';
                                                                                                    }
                                                                                                }

                                                                                                ?>
                                                                                            </label>
                                                                                        </div>
                                                                                    </div>

                                                                                    <div class="col-md-10">
                                                                                        <div class="form-group">
                                                                                            <p style="<?php echo $pregunta["respuesta7_correcta"] == 1 ? "font-weight: bold;" : "" ; ?>"><?php echo $pregunta["respuesta7_texto"]; ?> </p>
                                                                                        </div>
                                                                                    </div>
                                                                                        
                                                                                </div>

                                                                                <?php }?>

                                                                                <?php if($pregunta["respuesta8_texto"] != ""){?>

                                                                                <div class="row mb-30">
                                                                                    <div class="col-md-2">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label mb-10">
                                                                                                <?php 
                                                                                                if($pregunta["respondioAlumno"] == "respuesta8"){
                                                                                                    if($pregunta["correcta"] == 1){
                                                                                                        echo '<i class="fa fa-check" aria-hidden="true"></i>';
                                                                                                    }else{
                                                                                                        echo '<i class="fa fa-times" aria-hidden="true"></i>
';
                                                                                                    }
                                                                                                }

                                                                                                ?>
                                                                                            </label>
                                                                                        </div>
                                                                                    </div>

                                                                                    <div class="col-md-10">
                                                                                        <div class="form-group">
                                                                                            <p style="<?php echo $pregunta["respuesta8_correcta"] == 1 ? "font-weight: bold;" : "" ; ?>"><?php echo $pregunta["respuesta8_texto"]; ?> </p>
                                                                                        </div>
                                                                                    </div>
                                                                                        
                                                                                </div>

                                                                                <?php }?>

                                                                                <?php if($pregunta["respuesta9_texto"] != ""){?>

                                                                                <div class="row mb-30">
                                                                                    <div class="col-md-2">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label mb-10">
                                                                                                <?php 
                                                                                                if($pregunta["respondioAlumno"] == "respuesta9"){
                                                                                                    if($pregunta["correcta"] == 1){
                                                                                                        echo '<i class="fa fa-check" aria-hidden="true"></i>';
                                                                                                    }else{
                                                                                                        echo '<i class="fa fa-times" aria-hidden="true"></i>
';
                                                                                                    }
                                                                                                }

                                                                                                ?>
                                                                                            </label>
                                                                                        </div>
                                                                                    </div>

                                                                                    <div class="col-md-10">
                                                                                        <div class="form-group">
                                                                                            <p style="<?php echo $pregunta["respuesta9_correcta"] == 1 ? "font-weight: bold;" : "" ; ?>"><?php echo $pregunta["respuesta9_texto"]; ?> </p>
                                                                                        </div>
                                                                                    </div>
                                                                                        
                                                                                </div>

                                                                                <?php }?>

                                                                                <?php if($pregunta["respuesta10_texto"] != ""){?>

                                                                                <div class="row mb-30">
                                                                                    <div class="col-md-2">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label mb-10">
                                                                                                <?php 
                                                                                                if($pregunta["respondioAlumno"] == "respuesta10"){
                                                                                                    if($pregunta["correcta"] == 1){
                                                                                                        echo '<i class="fa fa-check" aria-hidden="true"></i>';
                                                                                                    }else{
                                                                                                        echo '<i class="fa fa-times" aria-hidden="true"></i>
';
                                                                                                    }
                                                                                                }

                                                                                                ?>
                                                                                            </label>
                                                                                        </div>
                                                                                    </div>

                                                                                    <div class="col-md-10">
                                                                                        <div class="form-group">
                                                                                            <p style="<?php echo $pregunta["respuesta10_correcta"] == 1 ? "font-weight: bold;" : "" ; ?>"><?php echo $pregunta["respuesta10_texto"]; ?> </p>
                                                                                        </div>
                                                                                    </div>
                                                                                        
                                                                                </div>

                                                                                <?php }?>

                                                                            <?php } 
                                                                                } ?>

                                                                        </div>

                                                                        <div class="form-actions mt-50">

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
                            <input name="idCurso" type="hidden" value="<?php echo $origen_idCurso ?>" />
                            <input name="idModulo" type="hidden" value="<?php echo $origen_idModulo ?>" />
                            <input name="idInstructor" type="hidden" value="<?php echo $origen_idInstructor ?>" />
                            <input name="idAlumno" type="hidden" value="<?php echo $origen_idAlumno ?>" />
                            <input name="rangoFechas" type="hidden" value="<?php echo $origen_rangoFechas ?>" />
                        </form>
                    <?php
                        } else {
                            registraEvento("Consulta de examen contestado bloqueado | id = " . $id);
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
            });


            // Regresa a la interfaz de origen


            $(".link_origen").click(function() {
                $("#formulario_origen").submit();
            });
        </script>
    </body>
</html>
