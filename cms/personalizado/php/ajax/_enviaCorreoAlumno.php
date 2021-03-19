<?php
    /*
     * Elimina un banner de las paginas secundarias
     */
    session_start();

    require_once "../comunes/funciones.php";
    require_once "../comunes/constantes.php";

    // Obtiene parametros de request

    $idAlumno = filter_input(INPUT_POST, "idAlumno");
    $idCurso = filter_input(INPUT_POST, "idCurso");

    // Obtiene parametros de sesion

    $idUsuario = $_SESSION["cms_usuario_id"];

    // Inicializa variables

    $resultado = "";
    $fechaActual = date("Y-m-d H:i:s");

    // Obtiene conexion a base de datos

    $conexion = obtenConexion();

    // Carga cotizacion

    $alumno_BD = consulta($conexion, "SELECT al.correoElectronico, al.nombre, al.apellido, al.autorizador_correoElectronico, cu.nombre as curso,  cu.fechaInicio, cu.fechaFin, alumno_curso.diploma FROM alumno al INNER JOIN alumno_curso ON al.id = alumno_curso.idAlumno INNER JOIN curso cu ON cu.id = alumno_curso.idCurso WHERE alumno_curso.idAlumno = " .$idAlumno. " AND alumno_curso.idCurso = " .$idCurso);

    if (cuentaResultados($alumno_BD) == 0) {
        $resultado = "No se han encontrado datos del alumno";
    } else {
        $alumno = obtenResultado($alumno_BD);

        if (estaVacio($alumno["correoElectronico"])) {
            $resultado = "Proporciona el correo electrónico del alumno";
        } else {
            if(estaVacio($alumno["diploma"])){
                $resultado = "No hay un documento adjunto de diploma para este curso";
            } else {
                //envia Correo
                $mensaje = "<html>
                            <head></head>
                            <body>
                                <div style='color: #6f6f6f; font-family: sans-serif; font-size: 14px; line-height: 25px; margin-left: auto; margin-right: auto; max-width: 530px; text-align: center'>
                                    <p style='font-size: 25px; font-weight: bold; margin-top: 25px'>
                                        Envío de Diploma
                                    </p>
                                    <p>
                                        Buen día ".$alumno['nombre']." " .$alumno['apellido']. ", por este medio te hacemos llegar tu diploma por haber tomado el curso '" .$alumno['curso']. "' llevado a cabo del día ".date('d-m-Y', strtotime($alumno['fechaInicio']))." al ".date('d-m-Y', strtotime($alumno['fechaFin']))."
                                    </p>
                                    <br />
                                    <p>
                                        ¡Felicidades por completar tu programa de formación!
                                        <br />
                                        Te invitamos a visitar nuestro sitio para encontrar más cursos de interés: <a href='https://www.consultek.com.mx/'>www.consultek.com.mx</a>
                                    </p>
                                    <br />
                                </div>
                            </body>
                        </html>";
                $ruta =  $constante_urlAlumno . $idAlumno . "/".  $alumno['diploma'];

                $res = enviaCorreoAldeamo($alumno["correoElectronico"] . "," . $alumno["autorizador_correoElectronico"],"Envío de Diploma Consultek",$mensaje,$ruta);
                //enviaCorreoMailerArchivoAdjunto( $alumno["correoElectronico"], "Confirmación de Diploma", $mensaje, $alumno['diploma'], $ruta);

                //enviaCorreoMailerArchivoAdjunto( $alumno["autorizador_correoElectronico"], "Confirmación de Diploma", $mensaje, $alumno['diploma'], $ruta);

                //actualiza fecha Envio
                consulta($conexion, "UPDATE alumno_curso SET fechaEnvio = '" .$fechaActual. "' WHERE idAlumno = ".$idAlumno. " AND idCurso = ".$idCurso);

                // Registra evento

                consulta($conexion, "INSERT INTO log (fecha, evento, idUsuario) VALUES ('" . $fechaActual . "', 'Envia correo al alumno | id = " . $idAlumno . "', " . $idUsuario . ")");

                // Regresa el xml resultante
//                if($res == 200){
                    $resultado = "ok";
/*
                }else{
                    //$resultado = "err";
                    $resultado = $res;
                }
*/
            }
        }
    }
    // Cierra la conexion con base de datos y libera recursos

    liberaConexion($conexion);

    // Devuelve resultado

    echo $resultado;
?>