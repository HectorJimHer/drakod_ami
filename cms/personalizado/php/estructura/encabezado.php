            <!-- Barra superior -->

            <nav class="navbar navbar-inverse navbar-fixed-top">
                <a id="toggle_nav_btn" class="toggle-left-nav-btn inline-block mr-20 pull-left" href="javascript:void(0);">
                    <i class="fa fa-bars"></i>
                </a>

                <a href="inicio.php">
                    <img alt="" class="brand-img pull-left" src="<?php echo $logotipo ?>" style="margin-top: 13px">
                </a>

                <ul class="nav navbar-right top-nav pull-right">
                    <li style="border-right: 1px solid #ddd; padding-right: 10px">
                        <a href="javascript:;" style="cursor: default; ">
                            <?php echo $usuario_nombre ?>
                        </a>
                    </li>

                    <li>
                        <a href="javascript:;" id="link_cerrarSesion">
                            <i class="fa fa-fw fa-power-off"></i>
                            Cerrar sesión
                        </a>
                    </li>
                </ul>
            </nav>
