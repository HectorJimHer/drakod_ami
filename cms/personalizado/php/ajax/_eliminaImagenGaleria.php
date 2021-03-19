<?php
    header("Access-Control-Allow-Origin: *");
    //header("Access-Control-Allow-Origin: http://www.requesting-page.com");

    /*
     * Elimina una imagen de una galeria fotografica
     */

    session_start();

    include("../comunes/constantes.php");
    include("../comunes/funciones.php");

    // Obtiene parametros de request

    $idProducto = filter_input(INPUT_POST, "idProducto");
    $idPost = filter_input(INPUT_POST, "idPost");
    $imagen = filter_input(INPUT_POST, "imagen");

    // Obtiene conexion a base de datos

    $conexion = obtenConexion();

    // Elimina archivo

    try {
        if (!estaVacio($imagen)) {
            if (!estaVacio($idProducto)) {
                unlink($constante_directorioComercio . "producto/" . $idProducto . "/galeria/" . $imagen);
            } else if (!estaVacio($idPost)) {
                unlink($constante_directorioComercio . "post/" . $idPost . "/galeria/" . $imagen);
            }
        }

        echo "ok";
    } catch (Exception $ex) {
        echo "error";
    }
?>