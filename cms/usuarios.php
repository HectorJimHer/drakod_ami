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
                    <?php if ($esUsuarioMaster || $usuario_permisoConsultarUsuarios || $usuario_permisoEditarUsuarios) { ?>

                        <!-- Titulo -->

                        <div class="row heading-bg bg-blue">
                            <div class="col-xs-12">
                                <h5 class="txt-light">Consulta de usuarios</h5>
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
                                                                <th>Rol</th>
                                                                <th>Nombre</th>
                                                                <th>Correo electrónico</th>
                                                                <th>Habilitado</th>
                                                                <th class="columna_acciones">Acciones</th>
                                                            </tr>
                                                        </thead>

                                                        <tfoot>
                                                            <tr>
                                                                <th>ID</th>
                                                                <th>Rol</th>
                                                                <th>Nombre</th>
                                                                <th>Correo electrónico</th>
                                                                <th>Habilitado</th>
                                                                <th>Acciones</th>
                                                            </tr>
                                                        </tfoot>

                                                        <tbody>
                                                            <?php

                                                                // Consulta base de datos

                                                                $usuarios_BD = consulta($conexion, "SELECT * FROM usuario WHERE rol != 'Master' ORDER BY rol, nombre");

                                                                while ($usuario = obtenResultado($usuarios_BD)) {
                                                                    echo "<tr>";
                                                                    echo "<td>" . $usuario["id"] . "</td>";
                                                                    echo "<td>" . $usuario["rol"] . "</td>";
                                                                    echo "<td>" . $usuario["nombre"] . "</td>";
                                                                    echo "<td>" . $usuario["correoElectronico"] . "</td>";
                                                                    echo "<td>" . ($usuario["habilitado"] == 1 ? "Si" : "No") . "</td>";
                                                                    echo "<td>";
                                                                        echo "<a class='link_editar' data-id='" . $usuario["id"] . "' data-toggle='tooltip' href='javascript:;' title='Ver detalle'><i class='fa fa-search'></i></a>";

                                                                        if ($esUsuarioMaster || $usuario_permisoEditarUsuarios) {
                                                                            echo "<a class='link_habilitar' data-id='" . $usuario["id"] . "'  data-habilitado='" . $usuario["habilitado"] . "' data-toggle='tooltip' href='javascript:;' style='color: " . ($usuario["habilitado"] == 1 ? "#2f2c2c" : "#bfbcbc") . "'  title='" . ($usuario["habilitado"] == 1 ? "Inhabilitar" : "Habilitar") . "'><i class='fa fa-globe'></i></a>";
                                                                        }
                                                                    echo "</td>";
                                                                    echo "</tr>";
                                                                }

                                                                registraEvento("Consulta de usuarios");
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

                        <form action="usuario.php" id="formulario_edicion" method="post">
                            <input name="origen" type="hidden" value="usuarios.php" />

                            <!-- Ida -->

                            <input id="campo_edicion_id" name="id" type="hidden" />
                        </form>
                    <?php
                        } else {
                            registraEvento("Consulta de usuarios bloqueada");
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


            // Habilita / inhabilita un usuario


            $(".link_habilitar").click(function() {
                var id = $(this).attr("data-id");
                var habilitado = $(this).attr("data-habilitado");

                if (habilitado == 1) {
                    if (confirm("Al continuar se inhabilitará este usuario y ya no tendrá acceso al sistema, ¿desea proceder?")) {
                        habilitaUsuario(id, "0");
                    }
                } else {
                    habilitaUsuario(id, "1");
                }
            });


            function habilitaUsuario(id, habilitado) {
                $.ajax({
                    data: {
                        id: id,
                        habilitado: habilitado
                    },
                    type: "post",
                    url: "personalizado/php/ajax/habilitaUsuario.php",
                    success: function(resultado) {
                        location.reload();
                    }
                });
            }
        </script>
    </body>
</html>
