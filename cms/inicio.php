<?php include("personalizado/php/comunes/manejaSesion.php"); ?>


<!DOCTYPE html>


<html lang="es">
    <head>
        <?php include("personalizado/php/estructura/head.php"); ?>
    </head>


    <body>

        <!--Preloader-->

        <div class="preloader-it">
            <div class="la-anim-1"></div>
        </div>

        <div class="wrapper">
            <?php include("personalizado/php/estructura/encabezado.php"); ?>

            <?php include("personalizado/php/estructura/menu.php"); ?>

            <!-- Contenido -->

            <div class="page-wrapper">
                <div class="container-fluid">

                    <!-- Titulo -->

                    <div class="row heading-bg bg-blue">
                        <div class="col-xs-12">
                            <h5 class="txt-light">Inicio</h5>
                        </div>
                    </div>

                    <!--?php if ($esUsuarioMaster || $esUsuarioAdministrador || $esUsuarioOperacion || $esUsuarioContenido) { ?-->
                    <!--?php } ?-->

                    <?php include("personalizado/php/estructura/pieDePagina.php"); ?>
                </div>
            </div>
        </div>

        <?php include("personalizado/php/estructura/plugins.php"); ?>

        <?php include("personalizado/php/estructura/scripts.php"); ?>


        <!-- Personalizado -->


        <script>
            $(function() {
            });
        </script>
    </body>
</html>
