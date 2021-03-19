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
                                <h5 class="txt-light">Consulta de Cursos</h5>
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
                                                <form action="cursos.php" method="post">
                                                    <input name="esSubmit" type="hidden" value="1" />

                                                    <div class="form-body">
                                                        <div class="row mb-30">
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

                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="control-label mb-10 text-left">Rango de fechas de registro</label>
                                                                    <input autocomplete="off" class="form-control input-daterange-datepicker" id="campo_rangoFechas" name="rangoFechas" type="text" value="<?php echo $rangoFechas ?>" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-actions mt-10">
                                                        <button class="btn btn-primary" id="boton_consultar" type="submit">Consultar</button>

                                                        <?php if ($esUsuarioMaster || $usuario_permisoEditarCursos) { ?>
                                                            <button class="btn btn-primary ml-20" id="boton_agregar" type="button">Agregar</button>
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
                                                                    <th>Categoría</th>
                                                                    <th>Nombre</th>
                                                                    <th>Fecha de inicio</th>
                                                                    <th>Fecha de finalización</th>
                                                                    <th>Sede</th>
                                                                    <th>Duración</th>
                                                                    <th>Imagen de portada</th>
                                                                    <th>Habilitado</th>
                                                                    <th class="columna_acciones">Acciones</th>
                                                                </tr>
                                                            </thead>

                                                            <tfoot>
                                                                <tr>
                                                                    <th>ID</th>
                                                                    <th>Categoría</th>
                                                                    <th>Nombre</th>
                                                                    <th>Fecha de inicio</th>
                                                                    <th>Fecha de finalización</th>
                                                                    <th>Sede</th>
                                                                    <th>Duración</th>
                                                                    <th>Imagen de portada</th>
                                                                    <th>Habilitado</th>
                                                                    <th>Acciones</th>
                                                                </tr>
                                                            </tfoot>

                                                            <tbody>
                                                                <?php

                                                                    // Arma restricciones

                                                                    $restricciones = "";

                                                                    if (!estaVacio($idCategoria) && $idCategoria != 0) {
                                                                        $restricciones .= " AND c.idCategoria = " . $idCategoria;
                                                                    }

                                                                    if (!estaVacio($rangoFechas)) {
                                                                        $restricciones .= " AND (DATE(c.fechaRegistro) BETWEEN '" . $fechaInicial . "' AND '" . $fechaFinal . "')";
                                                                    }

                                                                    // Consulta base de datos

                                                                    $cursos_BD = consulta($conexion, "SELECT "
                                                                            . "c.*"
                                                                            . ", ca.nombre as categoria"
                                                                        . " FROM"
                                                                            . " curso c"
                                                                            . " LEFT JOIN categoria ca ON c.idCategoria = ca.id"
                                                                        . " WHERE"
                                                                            . " 1 = 1 " . $restricciones
                                                                        . " ORDER BY"
                                                                            . " ca.nombre, c.nombre");

                                                                    while ($curso = obtenResultado($cursos_BD)) {
                                                                        echo "<tr>";
                                                                        echo "<td>" . $curso["id"] . "</td>";
                                                                        echo "<td>" . $curso["categoria"] . "</td>";
                                                                        echo "<td>" . $curso["nombre"] . "</td>";
                                                                        echo "<td>" . $curso["fechaInicio"] . "</td>";
                                                                        echo "<td>" . $curso["fechaFin"] . "</td>";
                                                                        echo "<td>" . $curso["sede"] . "</td>";
                                                                        echo "<td>" . $curso["duracion"] . "</td>";
                                                                        echo "<td><img alt='' src='" . $constante_urlCurso . $curso["id"] . "/" . $curso["imagen"] . "?" . $prefijoImagenes . "' style='width: 60px' /></td>";
                                                                        echo "<td>" . ($curso["habilitado"] == 1 ? "Si" : "No") . "</td>";
                                                                        echo "<td>";
                                                                            echo "<a class='link_editar' data-id='" . $curso["id"] . "' data-toggle='tooltip' href='javascript:;' title='Ver detalle'><i class='fa fa-search'></i></a>";

                                                                            if ($esUsuarioMaster || $usuario_permisoEditarCursos) {
                                                                                echo "<a class='link_habilitar' data-id='" . $curso["id"] . "'  data-habilitado='" . $curso["habilitado"] . "' data-toggle='tooltip' href='javascript:;' style='color: " . ($curso["habilitado"] == 1 ? "#2f2c2c" : "#bfbcbc") . "'  title='" . ($curso["habilitado"] == 1 ? "Deshabilitar" : "Habilitar") . "'><i class='fa fa-globe'></i></a>";
                                                                            }

                                                                        echo "</td>";
                                                                        echo "</tr>";
                                                                    }

                                                                    registraEvento("Consulta de cursos");
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

                        <form action="curso.php" id="formulario_edicion" method="post">
                            <input name="origen" type="hidden" value="cursos.php" />

                            <!-- Ida -->

                            <input id="campo_edicion_id" name="id" type="hidden" />

                            <!-- Regreso -->

                            <input name="origen_idCategoria" type="hidden" value="<?php echo $idCategoria ?>" />
                            <input name="origen_rangoFechas" type="hidden" value="<?php echo $rangoFechas ?>" />
                        </form>
                    <?php
                        } else {
                            registraEvento("Consulta de cursos bloqueada");
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
                    if (confirm("Al continuar se inhabilitará este curso y ya no será visible en el sitio web, ¿desea proceder?")) {
                        habilitaCurso(id, "0");
                    }
                } else {
                    habilitaCurso(id, "1");
                }
            });


            function habilitaCurso(id, habilitado) {
                $.ajax({
                    url: "personalizado/php/ajax/habilitaCurso.php",
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
