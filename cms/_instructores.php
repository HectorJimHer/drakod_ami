<?php include("personalizado/php/comunes/manejaSesion.php"); ?>


<!DOCTYPE html>


<html lang="es">
    <head>
        <?php include("personalizado/php/estructura/head.php"); ?>
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
                    <?php if ($esUsuarioMaster || $usuario_permisoConsultarInstructores || $usuario_permisoEditarInstructores) { ?>

                        <!-- Titulo -->

                        <div class="row heading-bg bg-blue">
                            <div class="col-xs-12">
                                <h5 class="txt-light">Consulta de instructores</h5>
                            </div>
                        </div>

                        <!-- Tabla de resultados -->

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="panel panel-default card-view">
                                    <div class="panel-heading">
                                        <div class="pull-left">
                                            <h6 class="panel-title txt-dark">Resultados</h6>

                                            <hr />
                                        </div>

                                        <div class="clearfix"></div>
                                    </div>

                                    <div class="panel-wrapper collapse in">
                                        <div class="panel-body">
                                            <div class="table-wrap">
                                                <div class="table-responsive">
                                                    <table class="table table-hover display  pb-30" id="tabla_resultados">
                                                        <thead>
                                                            <tr>
                                                                <th>ID</th>
                                                                <th>Nombre</th>
                                                                <th>Teléfono</th>
                                                                <th>Correo electrónico</th>
                                                                <th class="columna_acciones">Acciones</th>
                                                            </tr>
                                                        </thead>

                                                        <tfoot>
                                                            <tr>
                                                                <th>ID</th>
                                                                <th>Nombre</th>
                                                                <th>Teléfono</th>
                                                                <th>Correo electrónico</th>
                                                                <th>Acciones</th>
                                                            </tr>
                                                        </tfoot>

                                                        <tbody>
                                                            <?php

                                                                // Consulta base de datos

                                                                $instructores_BD = consulta($conexion, "SELECT * FROM instructor ORDER BY nombre, apellido");

                                                                while ($instructor = obtenResultado($instructores_BD)) {
                                                                    echo "<tr>";
                                                                    echo "<td>" . $instructor["id"] . "</td>";
                                                                    echo "<td>" . $instructor["nombre"] . " " . $instructor["apellido"] . "</td>";
                                                                    echo "<td>" . $instructor["telefono"] . "</td>";
                                                                    echo "<td>" . $instructor["correoElectronico"] . "</td>";
                                                                    echo "<td>";
                                                                        echo "<a class='link_editar' data-id='" . $instructor["id"] . "' data-toggle='tooltip' href='javascript:;' title='Ver detalle'><i class='fa fa-search'></i></a>";

                                                                    echo "</td>";
                                                                    echo "</tr>";
                                                                }

                                                                registraEvento("Consulta de instructores");
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                            <br />

                                            <div class="form-group mb-0">
                                                <a class="btn btn-primary" href="javascript:;" id="boton_agregar">Agregar</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Formulario de redireccion hacia edicion -->

                        <form action="instructor.php" id="formulario_edicion" method="post">
                            <input name="origen" type="hidden" value="instructores.php" />

                            <!-- Ida -->

                            <input id="campo_edicion_id" name="id" type="hidden" />
                        </form>
                    <?php
                        } else {
                            registraEvento("Consulta de instructores bloqueada");
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
                    //order: [[0, "desc"]],
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
