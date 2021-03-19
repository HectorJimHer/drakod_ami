<?php include("personalizado/php/comunes/manejaSesion.php"); ?>


<!DOCTYPE html>


<html lang="es">
    <head>
        <?php include("personalizado/php/estructura/head.php"); ?>

        <?php

            // Obtiene parametros de request

            $esSubmit = filter_input(INPUT_POST, "esSubmit");
            $idCategoria = filter_input(INPUT_POST, "idCategoria");
            $rangoFechas = filter_input(INPUT_POST, "rangoFechas");
            $idCurso = filter_input(INPUT_POST, "idCurso");

            // Inicializa variables

            $fechaInicial = substr($rangoFechas, 0, 10);
            $fechaFinal = substr($rangoFechas, -10, 10);
            $fechaActual = date("Y-m-d H:i:s");
        ?>
    </head>


    <body>

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
                    <?php if ($esUsuarioMaster || $usuario_permisoConsultarEvaluaciones) { ?>

                        <!-- Titulo -->

                        <div class="row heading-bg bg-blue">
                            <div class="col-xs-12">
                                <h5 class="txt-light">Listado de respuestas de encuestas</h5>
                            </div>
                        </div>

                        <!-- Formulario -->

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="panel panel-default card-view">
                                    <div class="panel-heading">
                                        <div class="pull-left">
                                            <h6 class="panel-title txt-dark">Utiliza los filtros para detallar tu búsqueda</h6>

                                            <hr />
                                        </div>

                                        <div class="clearfix"></div>
                                    </div>

                                    <div class="panel-wrapper collapse in">
                                        <div class="panel-body">
                                            <div class="alert" id="contenedor_mensaje">
                                                <span></span>
                                            </div>

                                            <div class="form-wrap">
                                                <form action="evaluaciones.php" method="post">
                                                    <input name="esSubmit" type="hidden" value="1" />

                                                    <div class="form-body">
                                                        <div class="row mb-30">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="control-label mb-10">Curso</label>
                                                                    <select class="form-control select2" name="idCurso">
                                                                        <option value="">Elige</option>
                                                                        <?php
                                                                            $cursos_BD = consulta($conexion, "SELECT * FROM curso ORDER BY nombre");

                                                                            while ($curso = obtenResultado($cursos_BD)) {
                                                                                echo "<option " . ($idCurso == $curso["id"] ? "selected" : "") . " value='" . $curso["id"] . "'>" . $curso["nombre"] . "</option>";
                                                                            }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="control-label mb-10 text-left">Rango de fechas de inicio de curso</label>
                                                                    <input autocomplete="off" class="form-control input-daterange-datepicker" id="campo_rangoFechas" name="rangoFechas" type="text" value="<?php echo $rangoFechas ?>"/>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row mb-30">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="control-label mb-10">Categorías</label>
                                                                    <select class="form-control select2" name="idCategoria">
                                                                        <option value="">Elige</option>
                                                                        <?php
                                                                            $categorias_BD = consulta($conexion, "SELECT * FROM categoria ORDER BY nombre");

                                                                            while ($categoria = obtenResultado($categorias_BD)) {
                                                                                echo "<option " . ($idCategoria == $categoria["id"] ? "selected" : "") . " value='" . $categoria["id"] . "'>" . $categoria["nombre"] . "</option>";
                                                                            }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-actions mt-10">
                                                        <button class="btn btn-primary" id="boton_consultar" type="submit">Consultar</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tabla de resultados -->

                        <?php if (!estaVacio($esSubmit) && $esSubmit === "1") { ?>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="panel panel-default card-view">
                                        <div class="panel-wrapper collapse in">
                                            <div class="panel-body">
                                                <div class="table-wrap">
                                                    <div class="table-responsive">
                                                        <table class="table table-hover display  pb-30" id="tabla_resultados">
                                                            <thead>
                                                                <tr>
                                                                    <th>Categoría</th>
                                                                    <th>Curso</th>
                                                                    <th>Instructor</th>
                                                                    <th>Alumno</th>
                                                                    <th>Promedio Exámenes</th>
                                                                    <th>Fecha encuesta</th>
                                                                    <th>P1</th>
                                                                    <th>P2</th>
                                                                    <th>P3</th>
                                                                    <th>P4</th>
                                                                    <th>P5</th>
                                                                    <th>P6</th>
                                                                    <th>P7</th>
                                                                    <th>P8</th>
                                                                    <th>P9</th>
                                                                    <th>P10</th>
                                                                </tr>
                                                            </thead>

                                                            <tfoot>
                                                                <tr>
                                                                    <th>Categoría</th>
                                                                    <th>Curso</th>
                                                                    <th>Instructor</th>
                                                                    <th>Alumno</th>
                                                                    <th>Promedio Exámenes</th>
                                                                    <th>Fecha encuesta</th>
                                                                    <th>P1</th>
                                                                    <th>P2</th>
                                                                    <th>P3</th>
                                                                    <th>P4</th>
                                                                    <th>P5</th>
                                                                    <th>P6</th>
                                                                    <th>P7</th>
                                                                    <th>P8</th>
                                                                    <th>P9</th>
                                                                    <th>P10</th>
                                                                </tr>
                                                            </tfoot>

                                                            <tbody>
                                                                <?php

                                                                    // Arma restricciones

                                                                    $restricciones = "";

                                                                    if (!estaVacio($idCurso) && $idCurso != 0) {
                                                                        $restricciones .= " AND ae.idCurso = " . $idCurso;
                                                                    }

                                                                    if (!estaVacio($rangoFechas)) {
                                                                        $restricciones .= " AND (DATE(cu.fechaInicio) BETWEEN '" . $fechaInicial . "' AND '" . $fechaFinal . "')";
                                                                    }

                                                                    if (!estaVacio($idCategoria) && $idCategoria != 0) {
                                                                        $restricciones .= " AND cu.idCategoria = " . $idCategoria;
                                                                    }

                                                                    // Consulta base de datos

                                                                    

                                                                    $evaluaciones_BD = consulta($conexion, "SELECT "
                                                                            . " ae.*"
                                                                            . ", cu.nombre AS curso"
                                                                            . ",i.nombre as nombreInstructor"
                                                                            . ",i.apellido as apellidoInstructor"
                                                                            . ",a.nombre as nombreAlumno"
                                                                            . ",a.apellido as apellidoAlumno"
                                                                            . ", ca.nombre AS categoria"
                                                                            . ", (select sum(c.calificacion)/ count(c.id) 
                                                                                from elearning.examenresumen c
                                                                                where c.idAlumno = ae.idAlumno 
                                                                                and c.idCurso = ae.idCurso) AS promedio"
                                                                        . " FROM"
                                                                            . " alumno_evaluacion ae"
                                                                            . " LEFT JOIN curso cu ON cu.id = ae.idCurso"
                                                                            . " LEFT JOIN categoria ca ON ca.id = cu.idCategoria"
                                                                            . " LEFT JOIN instructor i ON cu.idInstructor = i.id"
                                                                            . " LEFT JOIN alumno a ON ae.idAlumno = a.id"
                                                                        . " WHERE"
                                                                            . " 1 = 1 " . $restricciones
                                                                        . " ORDER BY "
                                                                            . "cu.nombre, ae.fechaRegistro DESC");

                                                                    while ($evaluacion = obtenResultado($evaluaciones_BD)) {
                                                                        echo "<tr>";
                                                                        echo "<td>" . $evaluacion["categoria"] . "</td>";
                                                                        echo "<td>" . $evaluacion["curso"] . "</td>";
                                                                        echo "<td>" . $evaluacion["nombreInstructor"] . " " . $evaluacion["apellidoInstructor"] . "</td>";
                                                                        echo "<td>" . $evaluacion["nombreAlumno"] . " " . $evaluacion["apellidoAlumno"] . "</td>";
                                                                        if($evaluacion["promedio"] > 0 ){
                                                                           echo "<td>" . number_format((float)$evaluacion["promedio"],2,'.','') . "</td>";
                                                                        }else{
                                                                           echo "<td></td>";
                                                                        }
                                                                        echo "<td>" . $evaluacion["fechaRegistro"] . "</td>";
                                                                        echo "<td>" . $evaluacion["respuesta1"] . "</td>";
                                                                        echo "<td>" . $evaluacion["respuesta2"] . "</td>";
                                                                        echo "<td>" . $evaluacion["respuesta3"] . "</td>";
                                                                        echo "<td>" . $evaluacion["respuesta4"] . "</td>";
                                                                        echo "<td>" . $evaluacion["respuesta5"] . "</td>";
                                                                        echo "<td>" . $evaluacion["respuesta6"] . "</td>";
                                                                        echo "<td>" . $evaluacion["respuesta7"] . "</td>";
                                                                        echo "<td>" . $evaluacion["respuesta8"] . "</td>";
                                                                        echo "<td>" . $evaluacion["respuesta9"] . "</td>";
                                                                        echo "<td>" . $evaluacion["respuesta10"] . "</td>";
                                                                        echo "</tr>";
                                                                    }

                                                                    registraEvento("Consulta de respuestas de encuestas");
                                                                ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    <?php
                        } else {
                            registraEvento("Consulta de respuestas de encuestas bloqueada");
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

                // Inicializa tabla de resultados

                $("#tabla_resultados").DataTable({
                    order: [[0, "desc"]],
                    dom: "Bfrtip",
                    buttons: [
                        "copy",
                        "excel",
                        {
                            extend: 'pdfHtml5',
                            orientation: 'landscape',
                            pageSize: 'LEGAL'
                        },
                        "print"
                    ],
                    language: {
                        decimal: "",
                        emptyTable: "No hay información",
                        info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
                        infoEmpty: "Mostrando 0 to 0 of 0 registros",
                        infoFiltered: "(Filtrado de _MAX_ total registros)",
                        infoPostFix: "",
                        lengthMenu: "Mostrar _MENU_ registros",
                        loadingRecords: "Cargando...",
                        processing: "Procesando...",
                        search: "Buscar:",
                        thousands: ",",
                        zeroRecords: "No se han encontrado resultados",
                        paginate: {
                            first: "Primero",
                            last: "Último",
                            next: "Siguiente",
                            previous: "Anterior"
                        },
                        buttons: {
                            copy: "Copiar",
                            excel: "Excel",
                            pdf: "PDF",
                            print: "Imprimir"
                        }
                    }
                });

                // Inicializa plugins

                $(".select2").select2();

                $(".input-daterange-datepicker").daterangepicker({
                    buttonClasses: ["btn", "btn-sm"],
                    applyClass: "btn-info",
                    cancelClass: "btn-default",
                    locale: {
                        format: "YYYY-MM-DD",
                        applyLabel: "Aplicar",
                        cancelLabel: 'Limpiar'
                    },
                    showClear: true,
                    autoUpdateInput: false
                });

                $(".input-daterange-datepicker").on('apply.daterangepicker', function(ev, picker) {
                    $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
                });

                $(".input-daterange-datepicker").on("cancel.daterangepicker", function(ev, picker) {
                    $(this).val("");
                });
            });
        </script>
    </body>
</html>
