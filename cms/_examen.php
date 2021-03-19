<?php include("personalizado/php/comunes/manejaSesion.php"); ?>


<!DOCTYPE html>


<html lang="es">
    <head>
        <?php include("personalizado/php/estructura/head.php"); ?>
        <style type="text/css" media="screen">
            td{
                vertical-align: top !important;
            }
        </style>
    </head>


    <body>
        <?php

            // Obtiene parametros de request

            $esSubmit = filter_input(INPUT_POST, "esSubmit");
            $id = filter_input(INPUT_POST, "id");
            $titulo = filter_input(INPUT_POST, "titulo");
            $nombre = filter_input(INPUT_POST, "nombre");

            $habilitado = filter_input(INPUT_POST, "habilitado");
            $instrucciones = filter_input(INPUT_POST, "instrucciones");

            $cantidadPreguntas = filter_input(INPUT_POST, "cantidadPreguntas");



            // Parametros enviados por origen

            $origen = filter_input(INPUT_POST, "origen");
            $origen_idCurso = filter_input(INPUT_POST, "origen_idCurso");
            $origen_idModulo = filter_input(INPUT_POST, "origen_idModulo");


            // Inicializa variables

            $mensaje = "";
            $fechaActual = date("Y-m-d H:i:s");
            $habilitado = estaVacio($habilitado) ? 0 : 1;

            // Procesa el request

            if (!estaVacio($esSubmit) && $esSubmit === "1") {

                // Valida los campos obligatorios

                if (estaVacio($titulo)) {
                    $mensaje .= "* Titulo<br />";
                }
                if (estaVacio($nombre)) {
                    $mensaje .= "* Nombre<br />";
                }

                if (!estaVacio($mensaje)) {
                    $mensaje = "Proporciona los siguientes datos:<br /><br />" . $mensaje;
                } else {
                    if (estaVacio($id)) {

                        // Es insercion
                        
                            consulta($conexion, "INSERT INTO examen (titulo, nombre, instrucciones, habilitado) 
                                VALUES ('" . $titulo . "','" . $nombre . "','" . $instrucciones . "', " . $habilitado . ")");

                            // Carga informacion actualizada

                            $examen_BD = consulta($conexion, "SELECT * FROM examen WHERE titulo = '" . $titulo . "'");
                            $examen = obtenResultado($examen_BD);

                            $id = $examen["id"];
                            $titulo = $examen["titulo"];
                            $nombre = $examen["nombre"];
                            $instrucciones = $examen["instrucciones"];
                            $habilitado = $examen["habilitado"];

                            // Carga preguntas

                            if (!estaVacio($cantidadPreguntas) && $cantidadPreguntas > 0) {
                                for ($indicePreguntas = 0; $indicePreguntas < $cantidadPreguntas; $indicePreguntas++) {
                                    $idPregunta = filter_input(INPUT_POST, "pregunta_idPregunta_" . $indicePreguntas);
                                    $tipoRespuesta = filter_input(INPUT_POST, "pregunta_tipo_" . $indicePreguntas);
                                    $orden = filter_input(INPUT_POST, "pregunta_orden_" . $indicePreguntas);
                                    $pregunta = filter_input(INPUT_POST, "pregunta_pregunta_" . $indicePreguntas);


                                    if (estaVacio($idPregunta)) {
                                        consulta($conexion, "INSERT INTO pregunta (idExamen, pregunta,tipoRespuesta,orden) 
                                                            VALUES (" . $id . ", '" . $pregunta . "', '" . $tipoRespuesta . "', " . $orden . ")");

                                    }
                                }
                            }


                            registraEvento("Alta de examen | id = " . $id);

                            $mensaje = "ok - El examen ha sido registrado";
                    } else {

                        // Es actualizacion


                        consulta($conexion, "UPDATE examen 
                                            SET titulo = '" . $titulo . "',
                                            instrucciones = '" . $instrucciones . "',
                                            nombre = '" . $nombre . "',
                                            habilitado = " . $habilitado . "
                                            WHERE id = " . $id);

                        // Carga informacion actualizada

                        $examen_BD = consulta($conexion, "SELECT * FROM examen WHERE id = " . $id);
                        $examen = obtenResultado($examen_BD);

                        $id = $examen["id"];
                        $titulo = $examen["titulo"];
                            $nombre = $examen["nombre"];
                        $instrucciones = $examen["instrucciones"];
                        $habilitado = $examen["habilitado"];

                        // Carga preguntas

                        if (!estaVacio($cantidadPreguntas) && $cantidadPreguntas > 0) {
                            for ($indicePreguntas = 0; $indicePreguntas < $cantidadPreguntas; $indicePreguntas++) {

                                $idPregunta = filter_input(INPUT_POST, "pregunta_idPregunta_" . $indicePreguntas);
                                $tipoRespuesta = filter_input(INPUT_POST, "pregunta_tipo_" . $indicePreguntas);
                                $orden = filter_input(INPUT_POST, "pregunta_orden_" . $indicePreguntas);
                                $pregunta = filter_input(INPUT_POST, "pregunta_pregunta_" . $indicePreguntas);


                                if (estaVacio($idPregunta)) {
                                    
                                    consulta($conexion, "INSERT INTO pregunta (idExamen, pregunta,tipoRespuesta,orden) 
                                                        VALUES (" . $id . ", '" . $pregunta. "', '" . $tipoRespuesta . "', " . $orden . ")");

                                }else{
                                    consulta($conexion, "update pregunta set 
                                                        tipoRespuesta = '" . $tipoRespuesta . "'
                                                        ,orden =  " . $orden . "
                                                        ,pregunta =  '" . $pregunta . "'
                                                        where id = " . $idPregunta . "
                                                        and idExamen = " . $id);
                                }
                            }
                        }

                        registraEvento("Actualizacion de examen | id = " . $id);

                        $mensaje = "ok - Los cambios han sido guardados";
                    }
                }
            } else {
                if (!estaVacio($id)) {

                    // Es consulta

                    $examen_BD = consulta($conexion, "SELECT * FROM examen WHERE id = " . $id);
                    $examen = obtenResultado($examen_BD);

                    $id = $examen["id"];
                    $titulo = $examen["titulo"];
                    $nombre = $examen["nombre"];

                    $instrucciones = $examen["instrucciones"];
                    $habilitado = $examen["habilitado"];

                    registraEvento("Consulta de examen | id = " . $id);
                }
            }
            $indicePreguntas = 0;


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
                                <h5 class="txt-light">Detalle de examen</h5>
                            </div>
                        </div>

                        <!-- Bloques de informacion -->

                        <form action="examen.php" enctype="multipart/form-data" method="post" id="formulario">
                            <input name="esSubmit" type="hidden" value="1" />
                            <input id="campo_cantidadPreguntas" name="cantidadPreguntas" type="hidden" />

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
                                                                                        <label class="control-label mb-10">Nombre <span class="txt-danger ml-10">*</span></label>
                                                                                        <input class="form-control" name="nombre" type="text" value="<?php echo $nombre ?>" />
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <div class="form-group">
                                                                                        <label class="control-label mb-10">Título <span class="txt-danger ml-10">*</span></label>
                                                                                        <input class="form-control" name="titulo" type="text" value="<?php echo $titulo ?>" />
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="row mb-30">

                                                                                <div class="col-md-6">
                                                                                    <div class="form-group">
                                                                                        <label class="control-label mb-10">Instrucciones</label>
                                                                                        <textarea class="form-control" name="instrucciones" rows="5"><?php echo $instrucciones ?></textarea>
                                                                                    </div>
                                                                                </div>

                                                                            </div>

                                                                            <br /><br />

                                                                            <div class="row mb-30">
                                                                                <div class="col-md-12 col-xs-12">
                                                                                    <h5><strong>Preguntas</strong></h5>
                                                                                </div>
                                                                            </div>

                                                                            <div class="row mb-30">
                                                                                <div class="col-md-12">
                                                                                    <div class="table-wrap">
                                                                                        <div class="table-responsive">
                                                                                            <table class="table mb-0">
                                                                                                <thead>
                                                                                                    <tr>
                                                                                                        <th>ID</th>
                                                                                                        <th>Orden</th>
                                                                                                        <th>Pregunta</th>
                                                                                                        <th>Tipo Respuesta</th>
                                                                                                        <th>Acciones</th>
                                                                                                    </tr>
                                                                                                </thead>

                                                                                                <tbody id="tabla_preguntas">
                                                                                                    <?php
                                                                                                        if(!estaVacio($id)) {
                                                                                                            $preguntas_BD = consulta($conexion, "SELECT
                                                                                                                    *
                                                                                                                FROM
                                                                                                                    pregunta
                                                                                                                WHERE
                                                                                                                    idExamen = " . $id . " ORDER BY orden");

                                                                                                            while ($pregunta = obtenResultado($preguntas_BD)) {
                                                                                                                echo "<tr id='linea_pregunta_" . $indicePreguntas . "'>";
                                                                                                                echo "<td><input name='pregunta_idPregunta_" . $indicePreguntas . "' type='hidden' value='" . $pregunta["id"] . "' />" . $pregunta["id"] . "</td>";
                                                                                                                echo "<td>" . "<input class='form-control' name='pregunta_orden_" . $indicePreguntas. "' type='number' value='" . $pregunta["orden"] . "'/></td>";
                                                                                                                echo "<td><textarea class='form-control' name='pregunta_pregunta_" . $indicePreguntas. "'>" . $pregunta['pregunta'] . "</textarea><div style='margin-left: 30px; margin-top: 10px;'>"
                                                                                                                    . $pregunta["respuesta1_texto"] . "<br>"
                                                                                                                    . $pregunta["respuesta2_texto"] . "<br>"
                                                                                                                    . $pregunta["respuesta3_texto"] . "<br>"
                                                                                                                    . "</div></td>";
                                                                                                                echo "<td><select class='form-control select2' name='pregunta_tipo_" . $indicePreguntas . "' >
                                                                                                                <option value='Opcion' " . ($pregunta["tipoRespuesta"] == "Opcion" ? "selected" : "" ) . ">Opcion</option>
                                                                                                                <option value='Abierta' " . ($pregunta["tipoRespuesta"] == "Abierta" ? "selected" : "" ) . ">Abierta</option>
                                                                                                                </select></td>";

                                                                                                                echo "<td>";

                                                                                                                if ($esUsuarioMaster || $usuario_permisoEditarAlumnos) {
                                                                                                                    echo "<a class='enlace_verRespuestas' data-idPregunta='" . $pregunta["id"] . "' data-indicePreguntas='" . $indicePreguntas . "' href='pregunta.php?id=" . $pregunta["id"] . "' title='Ver Respuestas' data-toggle='modal' data-target='#theModal" . $pregunta["id"]. "'>Ver respuestas</a>";

                                                                                                                }

                                                                                                                echo "</td>";

                                                                                                                echo "</tr>";
                                                                                                                echo '<div id="theModal' . $pregunta["id"] . '" class="modal fade text-center">
                                                                                                                    <div class="modal-dialog">
                                                                                                                      <div class="modal-content">
                                                                                                                      </div>
                                                                                                                    </div>
                                                                                                                </div>';

                                                                                                                $indicePreguntas++;
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
                                                                                        <a class="btn btn-xs btn-primary" href="javascript:;" id="enlace_agregarPregunta">Agregar</a>
                                                                                    <?php } ?>
                                                                                </div>
                                                                            </div>





                                                                        </div>

                                                                        <div class="form-actions mt-50">
                                                                            <?php if ($esUsuarioMaster || $usuario_permisoEditarCategorias) { ?>
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
                            <input name="idCurso" type="hidden" value="<?php echo $origen_idCurso;?>" />
                            <input name="idModulo" type="hidden" value="<?php echo $origen_idModulo;?>" />
                        </form>

                        <form action="examen.php" id="formulario_refresh" method="post">
                            <input type="hidden" name="id" value="<?php echo  $id; ?>" >
                            <input name="idCurso" type="hidden" value="<?php echo $origen_idCurso;?>" />
                            <input name="idModulo" type="hidden" value="<?php echo $origen_idModulo;?>" />
                        </form>
                    <?php
                        } else {
                            registraEvento("Consulta de examen bloqueada | id = " . $id);
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
            var indicePreguntas = <?php echo $indicePreguntas ?>;
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

            $(".bs-switch").bootstrapSwitch({
                handleWidth: 110,
                labelWidth: 110
            });

            $(".js-switch").each(function() {
                new Switchery($(this)[0], $(this).data());
            });

             $("#enlace_agregarPregunta").click(function() {
                var linea = "";

                linea += "<tr id='linea_pregunta_" + indicePreguntas + "'>";
                linea += "<td></td>";
                linea += "<td><input class='form-control' name='pregunta_orden_" + indicePreguntas + "' type='number' /></td>";
                linea += "<td><textarea class='form-control' name='pregunta_pregunta_" + indicePreguntas + "'></textarea></td>";
                linea += "<td><select class='form-control select2' name='pregunta_tipo_" + indicePreguntas + "' >";
                linea += "<option value='Opcion'>Opcion</option>";
                linea += "<option value='Abierta'>Abierta</option>";
                linea += "</select></td>";
                linea += "<td></td>";
                linea += "</tr>";

                $("#tabla_preguntas").append(linea);

                //$(".select2").select2();

                indicePreguntas++;
            });

            $(document).ready(function(){

                $(".enlace_verRespuestas").click(function(e){ //on clicking the link above
                    e.preventDefault();
                      
                })
            }); 

            $(".modal").on("hidden.bs.modal", function () {
                $("#formulario_refresh").submit();

              
            });

            $("#boton_guardar").click(function(e) {
                $("#campo_cantidadPreguntas").val(indicePreguntas);

                $("#formulario").submit();
            });

        </script>
    </body>
</html>
