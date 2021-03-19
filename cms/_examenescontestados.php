<?php include("personalizado/php/comunes/manejaSesion.php"); ?>


<!DOCTYPE html>


<html lang="es">
    <head>
        <?php include("personalizado/php/estructura/head.php"); ?>

        <?php

            // Obtiene parametros de request

            $esSubmit = filter_input(INPUT_POST, "esSubmit");
            $idCurso = filter_input(INPUT_POST, "idCurso");
            $idModulo = filter_input(INPUT_POST, "idModulo");
            $idAlumno = filter_input(INPUT_POST, "idAlumno");
            $idInstructor = filter_input(INPUT_POST, "idInstructor");

            $rangoFechas = filter_input(INPUT_POST, "rangoFechas");

            // Inicializa variables

            $fechaInicial = substr($rangoFechas, 0, 10);
            $fechaFinal = substr($rangoFechas, -10, 10);
            $fechaActual = date("Y-m-d H:i:s");
            $prefijoImagenes = generaCadenaAleatoria(5);
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
                    <?php if ($esUsuarioMaster || $usuario_permisoConsultarCursos || $usuario_permisoEditarCursos) { ?>

                        <!-- Titulo -->

                        <div class="row heading-bg bg-blue">
                            <div class="col-xs-12">
                                <h5 class="txt-light">Exámenes Contestados</h5>
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
                                                <form action="examenescontestados.php" method="post">
                                                    <input name="esSubmit" type="hidden" value="1" />

                                                    <div class="form-body">
                                                        <div class="row mb-30">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="control-label mb-10 text-left">Rango de fechas de registro</label>
                                                                    <input autocomplete="off" class="form-control input-daterange-datepicker" id="campo_rangoFechas" name="rangoFechas" type="text" value="<?php echo $rangoFechas ?>" />
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="control-label mb-10">Curso</label>
                                                                    <select class="form-control select2" name="idCurso" id="idCurso">
                                                                        <option value="0">Elige</option>
                                                                        <?php
                                                                            $cursos_BD = consulta($conexion, "SELECT * FROM curso c where c.id in (select idCurso from examenresumen) ORDER BY nombre");

                                                                            while ($curso = obtenResultado($cursos_BD)) {
                                                                                echo "<option " . ($curso["id"] == $idCurso ? "selected" : "") . " value='" . $curso["id"] . "'>" . $curso["nombre"] . "</option>";
                                                                            }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-30">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="control-label mb-10">Módulo</label>
                                                                    <select class="form-control select2" name="idModulo" id="idModulo">
                                                                        <option value="">Elige</option>
                                                                        <?php
                                                                            $modulos_BD = consulta($conexion, "SELECT * FROM modulo m where m.id in (select idModulo from examenresumen) ORDER BY nombre");

                                                                            while ($modulo = obtenResultado($modulos_BD)) {
                                                                                echo "<option " . ($modulo["id"] == $idModulo ? "selected" : "") . " value='" . $modulo["id"] . "'>" . $modulo["nombre"] . "</option>";
                                                                            }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="control-label mb-10">Instructor</label>
                                                                    <select class="form-control select2" name="idInstructor" id="idInstructor">
                                                                        <option value="">Elige</option>
                                                                        <?php
                                                                            $instructor_BD = consulta($conexion, "SELECT * FROM instructor i where i.id in (select idInstructor from curso c inner join examenresumen e on c.id = e.idCurso) ORDER BY nombre,apellido");

                                                                            while ($instructor = obtenResultado($instructor_BD)) {
                                                                                echo "<option " . ($instructor["id"] == $idInstructor ? "selected" : "") . " value='" . $instructor["id"] . "'>" . $instructor["nombre"] . " " . $instructor["apellido"] . "</option>";
                                                                            }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-30">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="control-label mb-10">Alumno</label>
                                                                    <select class="form-control select2" name="idAlumno" id="idAlumno">
                                                                        <option value="">Elige</option>
                                                                        <?php
                                                                            $alumnos_BD = consulta($conexion, "SELECT * FROM alumno a where a.id in (select idAlumno from examenresumen) ORDER BY nombre, apellido");

                                                                            while ($alumno = obtenResultado($alumnos_BD)) {
                                                                                echo "<option " . ($alumno["id"] == $idAlumno ? "selected" : "") . " value='" . $alumno["id"] . "'>" . $alumno["nombre"] . " "  . $alumno["apellido"] . "</option>";
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
                                                                    <th>ID</th>
                                                                    <th>Fecha del examen</th>
                                                                    <th>Curso</th>
                                                                    <th>Módulo</th>
                                                                    <th>Instructor</th>
                                                                    <th>Alumno</th>
                                                                    <th>Calificación Obtenida</th>
                                                                    <th class="columna_acciones">Acciones</th>
                                                                </tr>
                                                            </thead>

                                                            <tfoot>
                                                                <tr>
                                                                    <th>ID</th>
                                                                    <th>Fecha del examen</th>
                                                                    <th>Curso</th>
                                                                    <th>Módulo</th>
                                                                    <th>Instructor</th>
                                                                    <th>Alumno</th>
                                                                    <th>Calificación Obtenida</th>
                                                                    <th>Acciones</th>
                                                                </tr>
                                                            </tfoot>

                                                            <tbody>
                                                                <?php

                                                                    // Arma restricciones

                                                                    $restricciones = "";

                                                                    if (!estaVacio($idCurso) && $idCurso != 0) {
                                                                        $restricciones .= " AND c.id = " . $idCurso;
                                                                    }


                                                                    if (!estaVacio($idModulo) && $idModulo != 0) {
                                                                        $restricciones .= " AND m.id = " . $idModulo;
                                                                    }


                                                                    if (!estaVacio($idAlumno) && $idAlumno != 0) {
                                                                        $restricciones .= " AND a.id = " . $idAlumno;
                                                                    }


                                                                    if (!estaVacio($idInstructor) && $idInstructor != 0) {
                                                                        $restricciones .= " AND i.id = " . $idInstructor;
                                                                    }

                                                                    if (!estaVacio($rangoFechas)) {
                                                                        $restricciones .= " AND (DATE(e.fechaRegistro) BETWEEN '" . $fechaInicial . "' AND '" . $fechaFinal . "')";
                                                                    }

                                                                    // Consulta base de datos
                                                                    
                                                                    $cursos_BD = consulta($conexion, "SELECT 
                                                                                                        e.id,
                                                                                                        e.fechaRegistro,
                                                                                                        c.nombre as nombreCurso,
                                                                                                        m.nombre as nombreModulo,
                                                                                                        i.nombre as nombreInstructor,
                                                                                                        i.apellido as apellidoInstructor,
                                                                                                        e.calificacion,
                                                                                                        a.nombre as nombreAlumno,
                                                                                                        a.apellido as apellidoAlumno
                                                                                                        FROM examenresumen e
                                                                                                        inner join curso c on c.id = e.idCurso
                                                                                                        inner join modulo m on m.id = e.idModulo
                                                                                                        inner join alumno a on a.id = e.idAlumno
                                                                                                        inner join instructor i on c.idInstructor = i.id
                                                                                                        where 1 = 1 " . $restricciones . " 
                                                                                                        order by c.nombre, m.nombre");

                                                                    while ($curso = obtenResultado($cursos_BD)) {
                                                                        echo "<tr>";
                                                                        echo "<td>" . $curso["id"] . "</td>";
                                                                        echo "<td>" . $curso["fechaRegistro"] . "</td>";
                                                                        echo "<td>" . $curso["nombreCurso"] . "</td>";
                                                                        echo "<td>" . $curso["nombreModulo"] . "</td>";
                                                                        echo "<td>" . $curso["nombreInstructor"] . " " . $curso["apellidoInstructor"] . "</td>";
                                                                        echo "<td>" . $curso["nombreAlumno"] . " " . $curso["apellidoAlumno"] . "</td>";
                                                                        echo "<td>" . $curso["calificacion"] . "</td>";
                                                                        
                                                                        echo "<td>";
                                                                        echo "<a class='link_editar' data-id='" . $curso["id"] . "' data-toggle='tooltip' href='javascript:;' title='Ver detalle'><i class='fa fa-search'></i></a>";
                                                                        echo "</td>";
                                                                        echo "</tr>";
                                                                    }

                                                                    registraEvento("Consulta de examenes contestados");
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

                        <!-- Formulario de redireccion hacia edicion -->

                        <form action="examencontestado.php" id="formulario_edicion" method="post">
                            <input name="origen" type="hidden" value="examenescontestados.php" />

                            <!-- Ida -->

                            <input id="campo_edicion_id" name="id" type="hidden" />

                            <!-- Regreso -->

                            <input name="origen_idCurso" type="hidden" value="<?php echo $idCurso ?>" />
                            <input name="origen_idModulo" type="hidden" value="<?php echo $idModulo ?>" />
                            <input name="origen_idInstructor" type="hidden" value="<?php echo $idInstructor ?>" />
                            <input name="origen_idAlumno" type="hidden" value="<?php echo $idAlumno ?>" />
                            <input name="origen_rangoFechas" type="hidden" value="<?php echo $rangoFechas ?>" />
                        </form>
                    <?php
                        } else {
                            registraEvento("Consulta de examenes contestados bloqueada");
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
                        "copy", "excel", "pdf", "print"
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

            $("#idCurso").on("change",function(){
                var idCurso = $("#idCurso").val();
                $("#idModulo").html("");
                $("#idInstructor").html("");
                $("#idAlumno").html("");
                    $.ajax({
                        url: "personalizado/php/ajax/cargaModulosExamen.php",
                        type: "post",
                        data: {
                            idCurso: idCurso
                        },
                        success: function(xml) {
                            $("#idModulo").append("<option value='0'>Elige</option>");

                            $(xml).find("modulo").each(function() {
                                var id = $(this).find("id").text();
                                var nombre = $(this).find("nombre").text();

                                $("#idModulo").append("<option value='" + id + "'>" + nombre + "</option>");
                            });
                        }
                    });

                    $.ajax({
                        url: "personalizado/php/ajax/cargaInstructorExamen.php",
                        type: "post",
                        data: {
                            idCurso: idCurso
                        },
                        success: function(xml) {
                            $("#idInstructor").append("<option value='0'>Elige</option>");

                            $(xml).find("instructor").each(function() {
                                var id = $(this).find("id").text();
                                var nombre = $(this).find("nombre").text();

                                $("#idInstructor").append("<option value='" + id + "'>" + nombre + "</option>");
                            });
                        }
                    });

                    $.ajax({
                        url: "personalizado/php/ajax/cargaAlumnosExamen.php",
                        type: "post",
                        data: {
                            idCurso: idCurso
                        },
                        success: function(xml) {
                            $("#idAlumno").append("<option value='0'>Elige</option>");

                            $(xml).find("alumno").each(function() {
                                var id = $(this).find("id").text();
                                var nombre = $(this).find("nombre").text();

                                $("#idAlumno").append("<option value='" + id + "'>" + nombre + "</option>");
                            });
                        }
                    });
                
            });

            $("#idModulo").on("change",function(){
                var idCurso = $("#idCurso").val();
                var idModulo = $("#idModulo").val();

                $("#idAlumno").html("");

                    $.ajax({
                        url: "personalizado/php/ajax/cargaAlumnosExamen.php",
                        type: "post",
                        data: {
                            idCurso: idCurso,
                            idModulo: idModulo
                        },
                        success: function(xml) {
                            $("#idAlumno").append("<option value='0'>Elige</option>");

                            $(xml).find("alumno").each(function() {
                                var id = $(this).find("id").text();
                                var nombre = $(this).find("nombre").text();

                                $("#idAlumno").append("<option value='" + id + "'>" + nombre + "</option>");
                            });
                        }
                    });
                
            });


            // Redirige hacia alta


            $("#boton_agregar").click(function() {
                $("#formulario_edicion").submit();
            });


            // Redirige hacia edicion


            $(".link_editar").click(function() {
                $("#campo_edicion_id").val($(this).attr("data-id"));

                $("#formulario_edicion").submit();
            });


            
        </script>
    </body>
</html>
