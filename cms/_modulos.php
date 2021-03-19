<?php include("personalizado/php/comunes/manejaSesion.php"); ?>


<!DOCTYPE html>


<html lang="es">
    <head>
        <?php include("personalizado/php/estructura/head.php"); ?>

        <?php

            // Inicializa variables

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
                    <?php if ($esUsuarioMaster || $usuario_permisoConsultarModulos || $usuario_permisoEditarModulos) { ?>

                        <!-- Titulo -->

                        <div class="row heading-bg bg-blue">
                            <div class="col-xs-12">
                                <h5 class="txt-light">Consulta de Módulos</h5>
                            </div>
                        </div>

                        <!-- Tabla de resultados -->

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
                                                                <th>Resumen</th>
                                                                <th>Duración</th>
                                                                <th>Link de video</th>
                                                                <th>Examen Relacionado</th>
                                                                <th>Examen Habilitados</th>
                                                                <th>Imagen de portada</th>
                                                                <th class="columna_acciones">Acciones</th>
                                                            </tr>
                                                        </thead>

                                                        <tfoot>
                                                            <tr>
                                                                <th>ID</th>
                                                                <th>Nombre</th>
                                                                <th>Resumen</th>
                                                                <th>Duración</th>
                                                                <th>Link de video</th>
                                                                <th>Examen Relacionado</th>
                                                                <th>Examen Habilitados</th>
                                                                <th>Imagen de portada</th>
                                                                <th class="columna_acciones">Acciones</th>
                                                            </tr>
                                                        </tfoot>

                                                        <tbody>
                                                            <?php

                                                                // Consulta base de datos

                                                                $modulos_BD = consulta($conexion, "SELECT * FROM modulo ORDER BY nombre");

                                                                while ($modulo = obtenResultado($modulos_BD)) {
                                                                    echo "<tr>";
                                                                    echo "<td>" . $modulo["id"] . "</td>";
                                                                    echo "<td>" . $modulo["nombre"] . "</td>";
                                                                    echo "<td>" . $modulo["resumen"] . "</td>";
                                                                    echo "<td>" . $modulo["duracion"] . "</td>";
                                                                    echo "<td>" . $modulo["linkVideo"] . "</td>";
                                                                    echo "<td>" . $modulo["idExamen"] . "</td>";
                                                                    echo "<td>" . ($modulo["examenHabilitado"] == 1 ? "Si" : "No") . "</td>";
                                                                    echo "<td><img alt='' src='" . $constante_urlModulo . $modulo["id"] . "/" . $modulo["imagen"] . "?" . $prefijoImagenes . "' style='width: 60px' /></td>";
                                                                    echo "<td>";
                                                                        echo "<a class='link_editar' data-id='" . $modulo["id"] . "' data-toggle='tooltip' href='javascript:;' title='Ver detalle'><i class='fa fa-search'></i></a>";
                                                                    echo "</td>";
                                                                    echo "</tr>";
                                                                }

                                                                registraEvento("Consulta de módulos");
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                            <br />

                                            <div class="form-group mb-0">
                                                <?php if ($esUsuarioMaster || $usuario_permisoEditarModulos) { ?>
                                                    <button class="btn btn-primary mr-20" id="boton_agregar" type="button">Agregar</button>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Formulario de redireccion hacia edicion -->

                        <form action="modulo.php" id="formulario_edicion" method="post">
                            <input name="origen" type="hidden" value="modulos.php" />

                            <!-- Ida -->

                            <input id="campo_edicion_id" name="id" type="hidden" />
                        </form>
                    <?php
                        } else {
                            registraEvento("Consulta de módulos bloqueada");
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
