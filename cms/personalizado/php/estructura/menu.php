
            <!-- Menu -->

            <div class="fixed-sidebar-left">
                <ul class="nav navbar-nav side-nav nicescroll-bar">
                    <li>
                        <a href="inicio.php">
                            Inicio
                        </a>
                    </li>

                    <?php if ($esUsuarioMaster || $usuario_permisoConsultarUsuarios || $usuario_permisoEditarUsuarios) { ?>
                        <li><a href="usuarios.php">Usuarios del sistema</a></li>
                    <?php } ?>

                    <?php if ($esUsuarioMaster || $usuario_permisoConsultarInstructores || $usuario_permisoEditarInstructores) { ?>
                        <li><a href="instructores.php">Instructores</a></li>
                    <?php } ?>

                    <?php if ($esUsuarioMaster || $usuario_permisoEditarCategorias || $usuario_permisoConsultarCategorias) { ?>
                        <li><a href="categorias.php">Categorias</a></li>
                    <?php } ?>

                    <?php if ($esUsuarioMaster || $usuario_permisoConsultarCursos || $usuario_permisoEditarCursos) { ?>
                        <li><a href="cursos.php">Cursos</a></li>
                    <?php } ?>

                    <?php if ($esUsuarioMaster || $usuario_permisoConsultarModulos || $usuario_permisoEditarModulos) { ?>
                        <li><a href="modulos.php">Módulos</a></li>
                    <?php } ?>

                    <?php if ($esUsuarioMaster || $usuario_permisoConsultarAlumnos || $usuario_permisoEditarAlumnos) { ?>
                        <li><a href="alumnos.php">Alumnos</a></li>
                    <?php } ?>

                    <?php if ($esUsuarioMaster || $usuario_permisoConsultarEvaluaciones) { ?>
                        <li><a href="evaluaciones.php">Evaluaciones</a></li>
                    <?php } ?>

                    <?php if ($esUsuarioMaster || $usuario_permisoConsultarEvaluaciones) { ?>
                        <li><a href="examenes.php">Exámenes</a></li>
                    <?php } ?>
                    <?php if ($esUsuarioMaster || $usuario_permisoConsultarEvaluaciones) { ?>
                        <li><a href="examenescontestados.php">Exámenes Contestados</a></li>
                    <?php } ?>
                </ul>
            </div>
