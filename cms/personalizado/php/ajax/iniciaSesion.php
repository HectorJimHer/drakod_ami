<?php

    /*
     * Maneja el inicio de sesion de un usuario
     */

    session_start();

    include("../comunes/funciones.php");

    // Obtiene parametros de request

    $correoElectronico = filter_input(INPUT_POST, 'correoElectronico');
    $contrasena = filter_input(INPUT_POST, 'contrasena');

    // Inicializa variables

    $fechaActual = date("Y-m-d H:i:s");

    if (!estaVacio($correoElectronico) && !estaVacio($contrasena)) {

	// Busca al usuario en la base de datos

	$conexion = obtenConexion();

	$usuario_BD = consulta($conexion, "SELECT * FROM usuario WHERE correoElectronico  = '". $correoElectronico . "' AND contrasena = '" . md5($contrasena) . "' AND habilitado = 1");

        if (cuentaResultados($usuario_BD) > 0) {
	    $usuario = obtenResultado($usuario_BD);

            // Registra evento

            consulta($conexion, "INSERT INTO log (fecha, evento, idUsuario) VALUES ('" . $fechaActual . "', 'Inicio de sesión', " . $usuario["id"] . ")");

	    // Inicializa una nueva sesion

            session_unset();

	    $_SESSION["cms_usuario_id"] = $usuario["id"];
	    $_SESSION["cms_usuario_rol"] = $usuario["rol"];
	    $_SESSION["cms_usuario_nombre"] = $usuario["nombre"];
	    $_SESSION["cms_usuario_correoElectronico"] = $usuario["correoElectronico"];

	    $_SESSION["cms_permisoConsultarUsuarios"] = $usuario["permisoConsultarUsuarios"];
	    $_SESSION["cms_permisoEditarUsuarios"] = $usuario["permisoEditarUsuarios"];
	    /*$_SESSION["cms_permisoConsultarInstructores"] = $usuario["permisoConsultarInstructores"];
	    $_SESSION["cms_permisoEditarInstructores"] = $usuario["permisoEditarInstructores"];
	    $_SESSION["cms_permisoConsultarCategorias"] = $usuario["permisoConsultarCategorias"];
	    $_SESSION["cms_permisoEditarCategorias"] = $usuario["permisoEditarCategorias"];
            $_SESSION["cms_permisoConsultarCursos"] = $usuario["permisoConsultarCursos"];
	    $_SESSION["cms_permisoEditarCursos"] = $usuario["permisoEditarCursos"];
	    $_SESSION["cms_permisoConsultarModulos"] = $usuario["permisoConsultarModulos"];
	    $_SESSION["cms_permisoEditarModulos"] = $usuario["permisoEditarModulos"];
	    $_SESSION["cms_permisoConsultarAlumnos"] = $usuario["permisoConsultarAlumnos"];
	    $_SESSION["cms_permisoEditarAlumnos"] = $usuario["permisoEditarAlumnos"];
	    $_SESSION["cms_permisoConsultarEvaluaciones"] = $usuario["permisoConsultarEvaluaciones"];*/

            $_SESSION['cms_sesion_horaDeInicio'] = time();
	    $_SESSION['cms_sesion_horaDeExpiracion'] = $_SESSION['cms_sesion_horaDeInicio'] + (60 * 60);

            echo "ok";
	} else {

	    // Mensaje de error en caso de que el usuario no haya sido encontrado

	    echo "No te hemos encontrado en nuestra base de datos o tu contrase&ntilde;a no concuerda, por favor rectifica tus datos de acceso";
	}
    } else {

	// Mensaje de error general

	echo "Por favor proporciona tus datos de acceso";
    }
?>