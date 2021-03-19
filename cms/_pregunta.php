<?php include("personalizado/php/comunes/manejaSesion.php"); ?>
        <?php include("personalizado/php/comunes/constantes.php"); ?>
        <?php include("personalizado/php/comunes/funciones.php"); ?>

        <?php
             $conexion = obtenConexion();

            // Obtiene parametros de request

            $id = filter_input(INPUT_GET, "id");

            // Parametros enviados por origen

            $pregunta_BD = consulta($conexion, "SELECT * FROM pregunta WHERE id = " . $id);
            $pregunta = obtenResultado($pregunta_BD);

            $id = $pregunta["id"];
            $tipoRespuesta = $pregunta["tipoRespuesta"];
            $preguntaDesc = $pregunta["pregunta"];

            $orden = $pregunta["orden"];
            $respuesta1_texto = $pregunta["respuesta1_texto"];
            $respuesta1_correcta = $pregunta["respuesta1_correcta"];

            $respuesta2_texto = $pregunta["respuesta2_texto"];
            $respuesta2_correcta = $pregunta["respuesta2_correcta"];
            
            $respuesta3_texto = $pregunta["respuesta3_texto"];
            $respuesta3_correcta = $pregunta["respuesta3_correcta"];
            
            $respuesta4_texto = $pregunta["respuesta4_texto"];
            $respuesta4_correcta = $pregunta["respuesta4_correcta"];
            
            $respuesta5_texto = $pregunta["respuesta5_texto"];
            $respuesta5_correcta = $pregunta["respuesta5_correcta"];
            
            $respuesta6_texto = $pregunta["respuesta6_texto"];
            $respuesta6_correcta = $pregunta["respuesta6_correcta"];
            
            $respuesta7_texto = $pregunta["respuesta7_texto"];
            $respuesta7_correcta = $pregunta["respuesta7_correcta"];
            
            $respuesta8_texto = $pregunta["respuesta8_texto"];
            $respuesta8_correcta = $pregunta["respuesta8_correcta"];
            
            $respuesta9_texto = $pregunta["respuesta9_texto"];
            $respuesta9_correcta = $pregunta["respuesta9_correcta"];
            
            $respuesta10_texto = $pregunta["respuesta10_texto"];
            $respuesta10_correcta = $pregunta["respuesta10_correcta"];

            registraEvento("Consulta de pregunta | id = " . $id);

        ?>

        <div class="wrapper" >

            <!-- Contenido -->

            <div class="page-wrapper" style="margin-left: 0; text-align: initial;">
                <div class="container-fluid">

                        <!-- Titulo -->

                        <div class="row heading-bg bg-blue">
                            <div class="col-xs-12">
                                <h5 class="txt-light">Detalle de pregunta</h5>
                            </div>
                        </div>

                        <!-- Bloques de informacion -->

                        <form action="pregunta.php" enctype="multipart/form-data" id="formPregunta" method="post">
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
                                                                                <div class="col-md-12">
                                                                                    <div class="form-group">
                                                                                        <label class="control-label mb-10">Id</label>
                                                                                        <input class="form-control" name="id" readonly type="text" value="<?php echo $id ?>" />
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-md-12">
                                                                                    <div class="form-group">
                                                                                        <label class="control-label mb-10">Tipo de respuesta</label>
                                                                                        <select class="form-control select2" name="tipoRespuesta">
                                                                                            <option value='Opcion' <?php echo $tipoRespuesta == "Opcion" ? "selected" : ""; ?>>Opcion</option>
                                                                                            <option value='Abierta' <?php echo $tipoRespuesta == "Abierta" ? "selected" : ""; ?>>Abierta</option>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-md-12">
                                                                                    <div class="form-group">
                                                                                        <label class="control-label mb-10">Orden</label>
                                                                                        <input class="form-control" name="orden" type="number" value="<?php echo $orden; ?>" />
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-md-12">
                                                                                    <div class="form-group">
                                                                                        <label class="control-label mb-10">Pregunta</label>
                                                                                        <textarea class="form-control" name="pregunta" ><?php echo $preguntaDesc; ?></textarea>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="row mb-30">
                                                                                <div class="col-md-12">
                                                                                    <h5><strong>Respuestas</strong></h5>
                                                                                </div>
                                                                            </div>

                                                                            <div class="row mb-30">

                                                                                <div class="col-md-12">
                                                                                    <div class="form-group">
                                                                                        <label class="control-label mb-10">Respuesta 1</label>
                                                                                        <textarea class="form-control" name="respuesta1_texto" ><?php echo $respuesta1_texto; ?></textarea>
                                                                                        <input type="radio" id="respuesta1_correcta" name="respuestaCorrecta" value="respuesta1_correcta" <?php echo $respuesta1_correcta == 1 ? "checked" : "" ;?>>
                                                                                        <label class="control-label mb-10">Es la correcta</label>

                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-md-12">
                                                                                    <div class="form-group">
                                                                                        <label class="control-label mb-10">Respuesta 2</label>
                                                                                        <textarea class="form-control" name="respuesta2_texto" ><?php echo $respuesta2_texto; ?></textarea>
                                                                                        <input type="radio" id="respuesta2_correcta" name="respuestaCorrecta" value="respuesta2_correcta" <?php echo $respuesta2_correcta == 1 ? "checked" : "" ;?>>
                                                                                        <label class="control-label mb-10">Es la correcta</label>

                                                                                    </div>
                                                                                </div>


                                                                                <div class="col-md-12">
                                                                                    <div class="form-group">
                                                                                        <label class="control-label mb-10">Respuesta 3</label>
                                                                                        <textarea class="form-control" name="respuesta3_texto" ><?php echo $respuesta3_texto; ?></textarea>
                                                                                        <input type="radio" id="respuesta3_correcta" name="respuestaCorrecta" value="respuesta3_correcta" <?php echo $respuesta3_correcta == 1 ? "checked" : "" ;?>>
                                                                                        <label class="control-label mb-10">Es la correcta</label>

                                                                                    </div>
                                                                                </div>


                                                                                <div class="col-md-12">
                                                                                    <div class="form-group">
                                                                                        <label class="control-label mb-10">Respuesta 4</label>
                                                                                        <textarea class="form-control" name="respuesta4_texto" ><?php echo $respuesta4_texto; ?></textarea>
                                                                                        <input type="radio" id="respuesta4_correcta" name="respuestaCorrecta" value="respuesta4_correcta" <?php echo $respuesta4_correcta == 1 ? "checked" : "" ;?>>
                                                                                        <label class="control-label mb-10">Es la correcta</label>

                                                                                    </div>
                                                                                </div>


                                                                                <div class="col-md-12">
                                                                                    <div class="form-group">
                                                                                        <label class="control-label mb-10">Respuesta 5</label>
                                                                                        <textarea class="form-control" name="respuesta5_texto" ><?php echo $respuesta5_texto; ?></textarea>
                                                                                        <input type="radio" id="respuesta5_correcta" name="respuestaCorrecta" value="respuesta5_correcta" <?php echo $respuesta5_correcta == 1 ? "checked" : "" ;?>>
                                                                                        <label class="control-label mb-10">Es la correcta</label>

                                                                                    </div>
                                                                                </div>


                                                                                <div class="col-md-12">
                                                                                    <div class="form-group">
                                                                                        <label class="control-label mb-10">Respuesta 6</label>
                                                                                        <textarea class="form-control" name="respuesta6_texto" ><?php echo $respuesta6_texto; ?></textarea>
                                                                                        <input type="radio" id="respuesta6_correcta" name="respuestaCorrecta" value="respuesta6_correcta" <?php echo $respuesta6_correcta == 1 ? "checked" : "" ;?>>
                                                                                        <label class="control-label mb-10">Es la correcta</label>

                                                                                    </div>
                                                                                </div>


                                                                                <div class="col-md-12">
                                                                                    <div class="form-group">
                                                                                        <label class="control-label mb-10">Respuesta 7</label>
                                                                                        <textarea class="form-control" name="respuesta7_texto" ><?php echo $respuesta7_texto; ?></textarea>
                                                                                        <input type="radio" id="respuesta7_correcta" name="respuestaCorrecta" value="respuesta7_correcta" <?php echo $respuesta7_correcta == 1 ? "checked" : "" ;?>>
                                                                                        <label class="control-label mb-10">Es la correcta</label>

                                                                                    </div>
                                                                                </div>


                                                                                <div class="col-md-12">
                                                                                    <div class="form-group">
                                                                                        <label class="control-label mb-10">Respuesta 8</label>
                                                                                        <textarea class="form-control" name="respuesta8_texto" ><?php echo $respuesta8_texto; ?></textarea>
                                                                                        <input type="radio" id="respuesta8_correcta" name="respuestaCorrecta" value="respuesta8_correcta" <?php echo $respuesta8_correcta == 1 ? "checked" : "" ;?>>
                                                                                        <label class="control-label mb-10">Es la correcta</label>

                                                                                    </div>
                                                                                </div>


                                                                                <div class="col-md-12">
                                                                                    <div class="form-group">
                                                                                        <label class="control-label mb-10">Respuesta 9</label>
                                                                                        <textarea class="form-control" name="respuesta9_texto" ><?php echo $respuesta9_texto; ?></textarea>
                                                                                        <input type="radio" id="respuesta9_correcta" name="respuestaCorrecta" value="respuesta9_correcta" <?php echo $respuesta9_correcta == 1 ? "checked" : "" ;?>>
                                                                                        <label class="control-label mb-10">Es la correcta</label>

                                                                                    </div>
                                                                                </div>


                                                                                <div class="col-md-12">
                                                                                    <div class="form-group">
                                                                                        <label class="control-label mb-10">Respuesta 10</label>
                                                                                        <textarea class="form-control" name="respuesta10_texto" ><?php echo $respuesta10_texto; ?></textarea>
                                                                                        <input type="radio" id="respuesta10_correcta" name="respuestaCorrecta" value="respuesta10_correcta" <?php echo $respuesta10_correcta == 1 ? "checked" : "" ;?>>
                                                                                        <label class="control-label mb-10">Es la correcta</label>

                                                                                    </div>
                                                                                </div>

                                                                            </div>
                                                                        </div>

                                                                        <div class="form-actions mt-50">
                                                                                <button class="btn btn-success" type="button" id="guardarPregunta">Guardar</button>
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

                    <?php include("personalizado/php/estructura/pieDePagina.php"); ?>
                </div>
            </div>
        </div>
        <script>
            $("#guardarPregunta").click(function(){
                $.ajax({
                    data: $("#formPregunta").serialize(),
                    type: "post",
                    url: "personalizado/php/ajax/guardaPregunta.php",
                    success: function(resultado) {
                        if (resultado === "ok") {
                            alert("Se guardo la información de la pregunta correctamente.");
                        } else {
                            alert("Ocurrio un error al guardar la información, favor de intentar de nuevo.");
                        }
                    }
                });
            });
        </script>