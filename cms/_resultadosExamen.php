<?php include("personalizado/php/comunes/manejaSesion.php"); ?>
        <?php include("personalizado/php/comunes/constantes.php"); ?>
        <?php include("personalizado/php/comunes/funciones.php"); ?>

        <?php
             $conexion = obtenConexion();

            // Obtiene parametros de request

            $idCurso = filter_input(INPUT_GET, "idCurso");
            $idAlumno = filter_input(INPUT_GET, "idAlumno");


            // Parametros enviados por origen

            


            registraEvento("Consulta de pregunta | idCurso = " . $idCurso . " | idAlumno = " . $idAlumno);

        ?>

        <div class="wrapper" >

            <!-- Contenido -->

            <div class="page-wrapper" style="margin-left: 0; text-align: initial;">
                <div class="container-fluid">

                        <!-- Titulo -->

                        <div class="row heading-bg bg-blue">
                            <div class="col-xs-12">
                                <h5 class="txt-light">Resultados del curso</h5>
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
                                                                                    <div class="table-wrap">
                                                                                        <div class="table-responsive">
                                                                                            <table class="table mb-0">
                                                                                                <thead>
                                                                                                    <tr>
                                                                                                        <th>Módulo</th>
                                                                                                        <th>Fecha examen</th>
                                                                                                        <th>Calificación</th>
                                                                                                        <th>Acciones</th>
                                                                                                    </tr>
                                                                                                </thead>

                                                                                                <tbody id="tabla_cursos">
                                                                                                    <?php
                                                                                                        if(!estaVacio($idCurso)) {
                                                                                                            $cursos_BD = consulta($conexion, "select er.*, mo.nombre from examenresumen er
                                                                                                                inner join modulo mo on er.idModulo = mo.id
                                                                                                                where er.idCurso = " . $idCurso . "
                                                                                                                and er.idAlumno = " . $idAlumno . " ORDER BY mo.nombre DESC");

                                                                                                            while ($curso = obtenResultado($cursos_BD)) {
                                                                                                                echo "<tr>";
                                                                                                                echo "<td>" . $curso["nombre"] . "</td>";
                                                                                                                echo "<td>" . $curso["fechaRegistro"] . "</td>";
                                                                                                                echo "<td> " . $curso["calificacion"] . "</td>";
                                                                                                                
                                                                                                                echo "<td>";

                                                                                                                echo "<a class='verExamen' href='alumnodetalleexamen.hp?id=" . $curso["id"] . "' title='Ver examen'><i class='fa fa-search'></i></a>";

                                                                                                                

                                                                                                                echo "</td>";

                                                                                                                echo "</tr>";

                                                                                                            }
                                                                                                        }
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
            
        </script>