<?php include("personalizado/php/comunes/manejaSesion.php"); ?>


<!DOCTYPE html>


<html lang="es">
    <head>
        <?php include("personalizado/php/estructura/head.php"); ?>

        <?php

            // Obtiene parametros de request

            $esSubmit = filter_input(INPUT_POST, "esSubmit");
            $idCurso = filter_input(INPUT_POST, "idCurso");
            $rangoFechas = filter_input(INPUT_POST, "rangoFechas");

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
                    <?php if ($esUsuarioMaster || $usuario_permisoConsultarAlumnos || $usuario_permisoEditarAlumnos) { ?>

                        <!-- Titulo -->

                        <div class="row heading-bg bg-blue">
                            <div class="col-xs-12">
                                <h5 class="txt-light">Consulta de Alumnos</h5>
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
                                                <form action="alumnos.php" method="post">
                                                    <input name="esSubmit" type="hidden" value="1" />

                                                    <div class="form-body">
                                                        <div class="row mb-30">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="control-label mb-10 text-left">Rango de fechas de registro</label>
                                                                    <input autocomplete="off" class="form-control input-daterange-datepicker" id="campo_rangoFechas" name="rangoFechas" type="text" value="<?php echo $rangoFechas ?>"/>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="control-label mb-10">Curso</label>
                                                                    <select class="form-control select2" name="idCurso">
                                                                        <option <?php echo estaVacio($idCurso) ? "selected" : "" ?> value="">Ver todo</option>

                                                                        <?php
                                                                            $curso_BD = consulta($conexion, "SELECT * FROM curso ORDER BY nombre");

                                                                            while ($curso = obtenResultado($curso_BD)) {
                                                                                echo "<option " . ($idCurso == $curso["id"] ? "selected" : "") . " value='" . $curso["id"] . "'>" . $curso["nombre"] . "</option>";
                                                                            }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-actions mt-10">
                                                        <button class="btn btn-primary mr-20" id="boton_consultar" type="submit">Consultar</button>

                                                        <?php if ($esUsuarioMaster || $usuario_permisoEditarAlumnos) { ?>
                                                            <button class="btn btn-primary mr-20" id="boton_agregar" type="button">Agregar</button>
                                                        <?php } ?>
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
                                                                    <th>Fecha de registro</th>
                                                                    <th>Nombre</th>
                                                                    <th>Teléfono fijo</th>
                                                                    <th>Teléfono móvil</th>
                                                                    <th>Correo electrónico</th>
                                                                    <th>Empresa</th>
                                                                    <th>Puesto</th>
                                                                    <th>Habilitado</th>
                                                                    <th class="columna_acciones">Acciones</th>
                                                                </tr>
                                                            </thead>

                                                            <tfoot>
                                                                <tr>
                                                                    <th>ID</th>
                                                                    <th>Fecha de registro</th>
                                                                    <th>Nombre</th>
                                                                    <th>Teléfono fijo</th>
                                                                    <th>Teléfono móvil</th>
                                                                    <th>Correo electrónico</th>
                                                                    <th>Empresa</th>
                                                                    <th>Puesto</th>
                                                                    <th>Habilitado</th>
                                                                    <th>Acciones</th>
                                                                </tr>
                                                            </tfoot>

                                                            <tbody>
                                                                <?php

                                                                    // Arma restricciones

                                                                    $restricciones = "";

                                                                    if (!estaVacio($rangoFechas)) {
                                                                        $restricciones .= " AND (DATE(a.fechaRegistro) BETWEEN '" . $fechaInicial . "' AND '" . $fechaFinal . "')";
                                                                    }

                                                                    if (!estaVacio($idCurso)) {
                                                                        $restricciones .= " AND a.id IN (SELECT idAlumno FROM alumno_curso WHERE idCurso = " . $idCurso . ")";
                                                                    }

                                                                    // Consulta base de datos

                                                                    $alumnos_BD = consulta($conexion, "SELECT DISTINCT a.* FROM alumno a WHERE  1 = 1 " .$restricciones. " ORDER BY a.nombre, a.apellido");

                                                                    while ($alumno = obtenResultado($alumnos_BD)) {
                                                                        echo "<tr>";
                                                                        echo "<td>" . $alumno["id"] . "</td>";
                                                                        echo "<td>" . $alumno["fechaRegistro"] . "</td>";
                                                                        echo "<td>" . $alumno["nombre"] . " " . $alumno["apellido"] . "</td>";
                                                                        echo "<td>" . $alumno["telefonoFijo"] . "</td>";
                                                                        echo "<td>" . $alumno["telefonoMovil"] . "</td>";
                                                                        echo "<td>" . $alumno["correoElectronico"] . "</td>";
                                                                        echo "<td>" . $alumno["empresa"] . "</td>";
                                                                        echo "<td>" . $alumno["puesto"] . "</td>";
                                                                        echo "<td>" . ($alumno["habilitado"] == 1 ? "Si" : "No") . "</td>";
                                                                        echo "<td>";
                                                                        echo "<a class='link_editar' data-id='" . $alumno["id"] . "' data-toggle='tooltip' href='javascript:;' title='Ver detalle'><i class='fa fa-search'></i></a>";

                                                                        if ($esUsuarioMaster || $usuario_permisoEditarCursos) {
                                                                            echo "<a class='link_habilitar' data-id='" . $alumno["id"] . "'  data-habilitado='" . $alumno["habilitado"] . "' data-toggle='tooltip' href='javascript:;' style='color: " . ($alumno["habilitado"] == 1 ? "#2f2c2c" : "#bfbcbc") . "'  title='" . ($alumno["habilitado"] == 1 ? "Deshabilitar" : "Habilitar") . "'><i class='fa fa-globe'></i></a>";
                                                                        }

                                                                        echo "</td>";
                                                                        echo "</tr>";
                                                                    }

                                                                    registraEvento("Consulta de alumnos");
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

                        <form action="alumno.php" id="formulario_edicion" method="post">
                            <input name="origen" type="hidden" value="alumnos.php" />

                            <!-- Ida -->

                            <input id="campo_edicion_id" name="id" type="hidden" />

                            <!-- Regreso -->

                            <input name="origen_idCurso" type="hidden" value="<?php echo $idCurso ?>" />
                            <input name="origen_rangoFechas" type="hidden" value="<?php echo $rangoFechas ?>" />
                        </form>
                    <?php
                        } else {
                            registraEvento("Consulta de alumnos bloqueada");
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


            // Redirige hacia alta


            $("#boton_agregar").click(function() {
                $("#formulario_edicion").submit();
            });


            // Redirige hacia edicion


            $(".link_editar").click(function() {
                $("#campo_edicion_id").val($(this).attr("data-id"));

                $("#formulario_edicion").submit();
            });


            // Habilita / inhabilita curso


            $(".link_habilitar").click(function() {
                var id = $(this).attr("data-id");
                var habilitado = $(this).attr("data-habilitado");

                if (habilitado == 1) {
                    if (confirm("Al continuar se inhabilitará este alumno y ya no tendrá acceso a la plataforma, ¿desea proceder?")) {
                        habilitarAlumno(id, "0");
                    }
                } else {
                    habilitarAlumno(id, "1");
                }
            });


            function habilitarAlumno(id, habilitado) {
                $.ajax({
                    url: "personalizado/php/ajax/habilitaAlumno.php",
                    type: "post",
                    data: {
                        id: id,
                        habilitado: habilitado
                    },
                    success: function(resultado) {
                        location.reload();
                    }
                });
            }
        </script>
    </body>
</html>
