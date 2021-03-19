<?php
    /*
     * Habilita una pregunta frecuente para ser mostrada en el sitio web
     */

    session_start();

    include("../comunes/funciones.php");
    include("../comunes/constantes.php");


    // Obtiene parametros de request

    $idCurso = filter_input(INPUT_POST, "idCurso");

    // Obtiene parametros de sesion

    $idUsuario = $_SESSION["cms_usuario_id"];


    // Inicializa variables

    $fechaActual = date("Y-m-d H:i:s");

    // Obtiene conexion a base de datos

    $conexion = obtenConexion();

    $idCursoNuevo = 0;


    if (!estaVacio($idCurso)) {
        //Se extrae el curso a copiar
        $curso_BD = consulta($conexion, "SELECT * FROM curso where id = " . $idCurso);


        if ($curso = obtenResultado($curso_BD)) {

            //se inserta el nuevo registro
/*
            consulta($conexion, "INSERT INTO curso ("
                                    . "idCategoria"
                                    . ", fechaRegistro"
                                    . ", nombre"
                                    . ", resumen"
                                    . ", descripcion"
                                    . ", duracion"
                                    . ", sede"
                                    . ", instructor_nombre"
                                    . ", instructor_curriculum"
                                    . ", instructor_titulo"
                                    . ", instructor_facebook"
                                    . ", instructor_twitter"
                                    . ", instructor_instagram"
                                    . ", instructor_linkedin"
                                    . ", instructor_git"
                                    . ", imagenPortada"
                                    . ", instructor_fotografia"
                                . ") VALUES ("
                                    . $curso["idCategoria"]
                                    . ", '" . $fechaActual . "'"
                                    . ", '" . $curso["nombre"] . "'"
                                    . ", " . $curso["resumen"]
                                    . ", " . (!estaVacio($curso["descripcion"]) ? "'" . $curso["descripcion"] . "'" : "NULL")
                                    . ", '" . $curso["duracion"] . "'"
                                    . ", '" . $curso["sede"] ."'"
                                    . ", '" . $curso["instructor_nombre"] ."'"
                                    . ", '" . $curso["instructor_curriculum"] ."'"
                                    . ", '" . $curso["instructor_titulo"] ."'"
                                    . ", '" . $curso["instructor_facebook"] ."'"
                                    . ", '" . $curso["instructor_twitter"] ."'"
                                    . ", '" . $curso["instructor_instagram"] ."'"
                                    . ", '" . $curso["instructor_linkedin"] ."'"
                                    . ", '" . $curso["instructor_git"] ."'"
                                    . ", '" . $curso["imagenPortada"] ."'"
                                    . ", '" . $curso["instructor_fotografia"] ."'"
                                . ")");
*/

            consulta($conexion, "INSERT INTO curso ("
                    . "idCategoria"
                    . ", fechaRegistro"
                    . ", nombre"
                    . ", resumen"
                    . ", descripcion"
                    . ", duracion"
                    . ", sede"
                    . ", instructor_nombre"
                    . ", instructor_curriculum"
                    . ", instructor_titulo"
                    . ", instructor_facebook"
                    . ", instructor_twitter"
                    . ", instructor_instagram"
                    . ", instructor_linkedin"
                    . ", instructor_git"
                    . ", imagenPortada"
                    . ", instructor_fotografia"
                . ") ("
                    . "SELECT "
                        . "idCategoria"
                        . ", '" . $fechaActual . "'"
                        . ", nombre"
                        . ", resumen"
                        . ", descripcion"
                        . ", duracion"
                        . ", sede"
                        . ", instructor_nombre"
                        . ", instructor_curriculum"
                        . ", instructor_titulo"
                        . ", instructor_facebook"
                        . ", instructor_twitter"
                        . ", instructor_instagram"
                        . ", instructor_linkedin"
                        . ", instructor_git"
                        . ", imagenPortada"
                        . ", instructor_fotografia"
                    . " FROM"
                        . " curso"
                    . " WHERE"
                        . " id = " . $idCurso
                . ")");

            //Falta  modulos, archivos,

            $cursoNuevo_BD = consulta($conexion, "SELECT max(id) as id FROM curso");
            if ($cursoNuevo = obtenResultado($cursoNuevo_BD)) {
                $idCursoNuevo = $cursoNuevo["id"];

                //Traspaso de archivos
                $rutaFuente = $constante_directorioCurso. $idCurso . "/";
                $rutaDestino = $constante_directorioCurso. $idCursoNuevo . "/";


                $dir = opendir($rutaFuente);
                mkdir($rutaDestino, 0755, true);
                while(false !== ( $file = readdir($dir)) ) {
                    if (( $file != '.' ) && ( $file != '..' )) {
                        copy($rutaFuente . $file,$rutaDestino . $file);
                    }
                }
                closedir($dir);

                //Se guarda archivos en BD
                $archivos_BD = consulta($conexion, "SELECT * FROM archivo where idCurso = " . $idCurso);
                while ($archivo = obtenResultado($archivos_BD)) {
                    consulta($conexion, "INSERT INTO archivo ("
                                                . " idCurso"
                                                . ", titulo"
                                                . ", nombre"
                                                . ", orden"
                                            . ") VALUES ("
                                                . $idCursoNuevo
                                                . ", '" . $archivo["titulo"] . "'"
                                                . ", ". (!estaVacio($archivo["nombre"]) ? "'" . $archivo["nombre"] . "'" : "NULL")
                                                . ", " . $archivo["orden"]
                                            . ")");

                }

                //Se guarda lista de modulos en BD
                $modulos_BD = consulta($conexion, "SELECT * FROM curso_capitulo where idCurso = " . $idCurso);
                while ($modulo = obtenResultado($modulos_BD)) {
                    consulta($conexion, "INSERT INTO curso_capitulo ("
                                                . " idCurso"
                                                . ", idCapitulo"
                                                . ", orden"
                                            . ") VALUES ("
                                                . $idCursoNuevo
                                                . ", " . $modulo["idCapitulo"]
                                                . ", " . $modulo["orden"]
                                            . ")");

                }

            }
        }


    }

    // Registra evento

    consulta($conexion, "INSERT INTO log (fecha, evento, idUsuario) VALUES ('" . $fechaActual . "', 'Replica de curso con id '" . $idCurso . "' ', " . $idUsuario . ")");

    // Cierra la conexion con base de datos y libera recursos

    liberaConexion($conexion);

    // Regresa el xml resultante

    echo $idCursoNuevo;
?>