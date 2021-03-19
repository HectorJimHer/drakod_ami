<?php

    /*
     * Agrega un correo electronico al newsletter de farfarela
     */

    session_start();

    include("../comunes/funciones.php");

    // Inicializa variables

    $resultado = "";
    $fechaActual = date("Y-m-d H:i:s");

    try {

        // Obtiene conexion a base de datos

        $conexion = obtenConexion();

        // Obtiene parametros de request

        $correoElectronico = sanitiza($conexion, filter_input(INPUT_POST, "correoElectronico"));

        // Valida parametros de request

        if (estaVacio($correoElectronico)) {
            $resultado = "Proporciona tu correo electrónico";
        } else if (!filter_var($correoElectronico, FILTER_VALIDATE_EMAIL)) {
            $resultado = "Proporciona un correo electrónico válido";
        } else {

            // Registra correo electronico

            $newsletter_BD = consulta($conexion, "SELECT * FROM newsletter_farfarela WHERE correoElectronico = '" . $correoElectronico . "'");

            if (cuentaResultados($newsletter_BD) > 0) {
                consulta($conexion, "UPDATE newsletter_farfarela SET habilitado = 1 WHERE correoElectronico = '" . $correoElectronico . "'");
            } else {
                consulta($conexion, "INSERT INTO newsletter_farfarela ("
                        . "fechaRegistro"
                        . ", correoElectronico"
                    . ") VALUES ("
                        . "'" . $fechaActual . "'"
                        . ", '" . $correoElectronico . "'"
                    . ")");
            }

            // Libera conexion a base de datos

            liberaConexion($conexion);

            $resultado = "ok";
        }
    } catch (Exception $ex) {
        $resultado = "No te hemos podido registrar en este momento, por favor inténtalo más tarde.";
    }

    // Devuelve resultado

    echo $resultado;
?>