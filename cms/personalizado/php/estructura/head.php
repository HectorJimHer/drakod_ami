        <?php include("personalizado/php/comunes/constantes.php"); ?>
        <?php include("personalizado/php/comunes/funciones.php"); ?>

        <title>Consultek | Plataforma eLearning</title>

        <meta charset="UTF-8" />
        <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
        <meta content="Consultek | Plataforma eLearning" name="description" />
        <meta content="Consultek | Plataforma eLearning" name="keywords" />
        <meta content="Consultek | Plataforma eLearning" name="author" />

        <!-- Favicon -->
        <link href="favicon.ico" rel="shortcut icon">
        <link href="favicon.ico" rel="icon" type="image/x-icon">
        <link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="http://www.datatables.net/rss.xml">

        <!-- Data table CSS -->
        <link href="vendors/bower_components/datatables/media/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/>

        <!-- Jasny-bootstrap CSS -->
        <link href="vendors/bower_components/jasny-bootstrap/dist/css/jasny-bootstrap.min.css" rel="stylesheet" type="text/css"/>

        <!-- Custom Fonts -->
        <link href="dist/css/font-awesome.min.css" rel="stylesheet" type="text/css">

        <!-- vector map CSS -->
        <link href="vendors/bower_components/jasny-bootstrap/dist/css/jasny-bootstrap.min.css" rel="stylesheet" type="text/css"/>

        <!-- select2 CSS -->
        <link href="vendors/bower_components/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css"/>

        <!-- bootstrap-select CSS -->
        <link href="vendors/bower_components/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet" type="text/css"/>

        <!-- bootstrap-tagsinput CSS -->
        <link href="vendors/bower_components/bootstrap-tagsinput/dist/bootstrap-tagsinput.css" rel="stylesheet" type="text/css"/>

        <!-- multi-select CSS -->
        <link href="vendors/bower_components/multiselect/css/multi-select.css" rel="stylesheet" type="text/css"/>

        <!-- Bootstrap Switches CSS -->
        <link href="vendors/bower_components/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css" rel="stylesheet" type="text/css"/>

        <!-- switchery CSS -->
        <link href="vendors/bower_components/switchery/dist/switchery.min.css" rel="stylesheet" type="text/css"/>

        <!-- Bootstrap Colorpicker CSS -->
        <link href="vendors/bower_components/mjolnic-bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css" rel="stylesheet" type="text/css"/>

        <!-- Bootstrap Datetimepicker CSS -->
        <link href="vendors/bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css"/>

        <!-- Bootstrap Daterangepicker CSS -->
        <link href="vendors/bower_components/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet" type="text/css"/>

        <!-- Custom CSS -->
        <link href="dist/css/style.css" rel="stylesheet" type="text/css">


        <!-- Personalizado -->


        <link href="https://fonts.googleapis.com/css?family=Kodchasan|Audiowide|Gruppo" rel="stylesheet">
        <link href="personalizado/css/estilos.css" rel="stylesheet" type="text/css">

        <?php

            /*
             * Mobile Detect
             * http://mobiledetect.net/
             */

            require_once "personalizado/php/plugins/Mobile-Detect-2.8.25/Mobile_Detect.php";

            $mobileDetect = new Mobile_Detect;
            $esMovil = $mobileDetect->isMobile();

            // Obtiene parametros de sesion

            $usuario_id = isset($_SESSION["cms_usuario_id"]) ? $_SESSION["cms_usuario_id"] : "";
            $usuario_rol = isset($_SESSION["cms_usuario_rol"]) ? $_SESSION["cms_usuario_rol"] : "";
            $usuario_nombre = isset($_SESSION["cms_usuario_nombre"]) ? $_SESSION["cms_usuario_nombre"] : "";
            $usuario_correoElectronico = isset($_SESSION["cms_usuario_correoElectronico"]) ? $_SESSION["cms_usuario_correoElectronico"] : "";

            $usuario_permisoConsultarUsuarios = isset($_SESSION["cms_permisoConsultarUsuarios"]) ? $_SESSION["cms_permisoConsultarUsuarios"] : "";
	    $usuario_permisoEditarUsuarios = isset($_SESSION["cms_permisoEditarUsuarios"]) ? $_SESSION["cms_permisoEditarUsuarios"] : "";
            $usuario_permisoConsultarInstructores = isset($_SESSION["cms_permisoConsultarInstructores"]) ? $_SESSION["cms_permisoConsultarInstructores"] : "";
	    $usuario_permisoEditarInstructores = isset($_SESSION["cms_permisoEditarInstructores"]) ? $_SESSION["cms_permisoEditarInstructores"] : "";
            $usuario_permisoConsultarCategorias = isset($_SESSION["cms_permisoConsultarCategorias"]) ? $_SESSION["cms_permisoConsultarCategorias"] : "";
	    $usuario_permisoEditarCategorias = isset($_SESSION["cms_permisoEditarCategorias"]) ? $_SESSION["cms_permisoEditarCategorias"] : "";
            $usuario_permisoConsultarCursos = isset($_SESSION["cms_permisoConsultarCursos"]) ? $_SESSION["cms_permisoConsultarCursos"] : "";
	    $usuario_permisoEditarCursos = isset($_SESSION["cms_permisoEditarCursos"]) ? $_SESSION["cms_permisoEditarCursos"] : "";
            $usuario_permisoConsultarModulos = isset($_SESSION["cms_permisoConsultarModulos"]) ? $_SESSION["cms_permisoConsultarModulos"] : "";
	    $usuario_permisoEditarModulos = isset($_SESSION["cms_permisoEditarModulos"]) ? $_SESSION["cms_permisoEditarModulos"] : "";
            $usuario_permisoConsultarAlumnos = isset($_SESSION["cms_permisoConsultarAlumnos"]) ? $_SESSION["cms_permisoConsultarAlumnos"] : "";
	    $usuario_permisoEditarAlumnos = isset($_SESSION["cms_permisoEditarAlumnos"]) ? $_SESSION["cms_permisoEditarAlumnos"] : "";
            $usuario_permisoConsultarEvaluaciones = isset($_SESSION["cms_permisoConsultarEvaluaciones"]) ? $_SESSION["cms_permisoConsultarEvaluaciones"] : "";

            $sesion_horaDeInicio = isset($_SESSION["cms_sesion_horaDeInicio"]) ? $_SESSION["cms_sesion_horaDeInicio"] : "";
            $sesion_horaDeExpiracion = isset($_SESSION["cms_sesion_horaDeExpiracion"]) ? $_SESSION["cms_sesion_horaDeExpiracion"] : "";

            // Inicializa variables para cotejamiento de permisos de acceso

            $esUsuarioMaster = ($usuario_rol === "Master");
            $esUsuarioAdministrador = ($usuario_rol === "Administrador");
            $esUsuarioOperador = ($usuario_rol === "Operador");

            // Obtiene conexion a base de datos

            $conexion = obtenConexion();

            $logotipo = "personalizado/img/logotipo.png";
        ?>


        <style type="text/css">
            <?php if ($esMovil) { ?>
                .card-view {
                    padding-left: 0 !important;
                    padding-right: 0 !important;
                }

                .page-wrapper, .container-fluid {
                    padding-left: 5px !important;
                    padding-right: 5px !important;
                }

                .panel-body {
                    padding-left: 5px !important;
                    padding-right: 5px !important;
                }
            <?php } ?>
        </style>
