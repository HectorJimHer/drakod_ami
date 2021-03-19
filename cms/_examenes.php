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

            // Inicializa variables

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
                    <?php if ($esUsuarioMaster || $usuario_permisoConsultarCursos || $usuario_permisoEditarCursos) { ?>

                        <!-- Titulo -->

                        <div class="row heading-bg bg-blue">
                            <div class="col-xs-12">
                                <h5 class="txt-light">Listado de exámenes</h5>
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
                                                <form action="examenes.php" method="post">
                                                    <input name="esSubmit" type="hidden" value="1" />

                                                    <div class="form-body">
                                                        <div class="row mb-30">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="control-label mb-10">Curso</label>
                                                                    <select class="form-control select2" name="idCurso" id="selectCurso">
                                                                        <option value="0">Elige</option>
                                                                        <?php
                                                                            $cursos_BD = consulta($conexion, "SELECT * FROM curso ORDER BY nombre");

                                                                            while ($curso = obtenResultado($cursos_BD)) {
                                                                                echo "<option " . ($curso["id"] == $idCurso ? "selected" : "") . " value='" . $curso["id"] . "'>" . $curso["nombre"] . "</option>";
                                                                            }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="control-label mb-10">Módulo</label>
                                                                    <select class="form-control select2" name="idModulo" id="selectModulo">
                                                                    <?php

                                                                    if($idCurso != ""){
                                                                        $modulos_BD = consulta($conexion, "SELECT * FROM curso_modulo cm
                                                                           inner join modulo m  on cm.idModulo = m.id
                                                                           where cm.idCurso = " . $idCurso . " ORDER BY nombre");
                                                                        echo '<option value="0">Elige</option>';

                                                                        while ($modulo = obtenResultado($modulos_BD)) {
                                                                            echo "<option " . ($modulo["id"] == $idModulo ? "selected" : "") . " value='" . $modulo["id"] . "'>" . $modulo["nombre"] . "</option>";
                                                                        }

                                                                    }
                                                                    ?>
                                                                    </select>
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
                                                                    <th>Nombre</th>
                                                                    <th>Título</th>
                                                                    <th>Instrucciones</th>
                                                                    <th>Módulo</th>
                                                                    <th class="columna_acciones">Acciones</th>
                                                                </tr>
                                                            </thead>

                                                            <tfoot>
                                                                <tr>
                                                                    <th>ID</th>
                                                                    <th>Nombre</th>
                                                                    <th>Título</th>
                                                                    <th>Instrucciones</th>
                                                                    <th>Módulo</th>
                                                                    <th>Acciones</th>
                                                                </tr>
                                                            </tfoot>

                                                            <tbody>
                                                                <?php

                                                                    // Arma restricciones

                                                                    $restricciones = "";

                                                                    if (!estaVacio($idCurso) && $idCurso != 0) {
                                                                        $restricciones .= " AND cm.idCurso = " . $idCurso;
                                                                    }

                                                                    if (!estaVacio($idModulo) && $idModulo != 0) {
                                                                        $restricciones .= " AND cm.idModulo = " . $idModulo;
                                                                    }


                                                                    // Consulta base de datos

                                                                    $examenes_BD = consulta($conexion, "SELECT e.*
                                                                                                      FROM examen e
                                                                                                      left join modulo m on e.id = m.idExamen
                                                                                                      left join curso_modulo cm on m.id = cm.idModulo
                                                                                                      WHERE 1 = 1 " . $restricciones
                                                                                                      . " group by id, idModulo, nombre, titulo, instrucciones, habilitado"
                                                                        . " ORDER BY"
                                                                            . " m.nombre, cm.idCurso, e.titulo");

                                                                    while ($curso = obtenResultado($examenes_BD)) {
                                                                        echo "<tr>";
                                                                        echo "<td>" . $curso["id"] . "</td>";
                                                                        echo "<td>" . $curso["nombre"] . "</td>";
                                                                        echo "<td>" . $curso["titulo"] . "</td>";
                                                                        echo "<td>" . $curso["instrucciones"] . "</td>";
                                                                        echo "<td>";
                                                                        $queryTitulo = "select nombre from modulo where idExamen = " . $curso["id"];
                                                                        $titulos_BD = consulta($conexion,$queryTitulo);
                                                                        while($titulo = obtenResultado($titulos_BD)){
                                                                            echo $titulo["nombre"] . "</br>";
                                                                        }

                                                                        echo "</td>";


                                                                        echo "<td>";
                                                                        echo "<a class='link_editar' data-id='" . $curso["id"] . "' data-toggle='tooltip' href='javascript:;' title='Ver detalle'><i class='fa fa-search'></i></a>";
                                                                        echo "</td>";
                                                                        echo "</tr>";
                                                                    }

                                                                    registraEvento("Consulta de examenes");
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

                        <form action="examen.php" id="formulario_edicion" method="post">
                            <input name="origen" type="hidden" value="examenes.php" />

                            <!-- Ida -->

                            <input id="campo_edicion_id" name="id" type="hidden" />

                            <!-- Regreso -->

                            <input name="origen_idCurso" type="hidden" value="<?php echo $idCurso ?>" />
                            <input name="origen_idModulo" type="hidden" value="<?php echo $idModulo ?>" />
                        </form>
                    <?php
                        } else {
                            registraEvento("Consulta de examenes bloqueada");
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
                        //"pdf",
                        {
                            extend: "pdf",
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4]
                            },
                            orientation: "landscape"
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

            $("#selectCurso").on("change",function(){
                var idCurso = $(this).val();
                $("#selectModulo").empty();

                $.ajax({
                    url: "personalizado/php/ajax/cargaModulosCurso.php",
                    data: { idCurso: idCurso },
                    type: "post",
                    success: function(xml) {
                        $("#selectModulo").append("<option value=''>Elige</option>");

                        $(xml).find("modulo").each(function() {
                            var id = $(this).find("id").text();
                            var nombre = $(this).find("nombre").text();

                            $("#selectModulo").append("<option value='" + id + "'>" + nombre + "</option>");
                        });
                    }
                });
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
