<?php

    /*
     * Habilita un curso para ser visible en el sitio web
     */

    session_start();

    include("../comunes/funciones.php");

    // Obtiene parametros de request

    $id = filter_input(INPUT_POST, "id");
    $habilitado = filter_input(INPUT_POST, "habilitado");

    // Obtiene parametros de sesion

    $idUsuario = $_SESSION["cms_usuario_id"];

    // Inicializa variables

    $fechaActual = date("Y-m-d H:i:s");

    // Obtiene conexion a base de datos

    $conexion = obtenConexion();

    // Habilita / inhabilita cursso

    if (!estaVacio($id)) {
        consulta($conexion, "UPDATE curso SET habilitado = " . $habilitado . " WHERE id = " . $id);
    }

    // Registra evento

    consulta($conexion, "INSERT INTO log (fecha, evento, idUsuario) VALUES ('" . $fechaActual . "', '" . ($habilitado ? "Habilitacion" : "Inhabilitacion") . " de curso | id = " . $id . "', " . $idUsuario . ")");

    // Cierra la conexion con base de datos y libera recursos

    liberaConexion($conexion);

    // Regresa el xml resultante

    echo "ok";
?>