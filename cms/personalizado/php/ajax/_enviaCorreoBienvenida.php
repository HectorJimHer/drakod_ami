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
    $password = generaCadenaAleatoria(8);

    // Carga cotizacion

    $alumno_BD = consulta($conexion, "SELECT al.correoElectronico, al.nombre, al.apellido, al.autorizador_correoElectronico, cu.nombre as curso,  cu.fechaInicio, cu.fechaFin, alumno_curso.diploma FROM alumno al INNER JOIN alumno_curso ON al.id = alumno_curso.idAlumno INNER JOIN curso cu ON cu.id = alumno_curso.idCurso WHERE alumno_curso.idAlumno = " .$idAlumno. " AND alumno_curso.idCurso = " .$idCurso);

    if (cuentaResultados($alumno_BD) == 0) {
        $resultado = "No se han encontrado datos del alumno";
    } else {
        $alumno = obtenResultado($alumno_BD);

        if (estaVacio($alumno["correoElectronico"])) {
            $resultado = "Proporciona el correo electrónico del alumno";
        } else {
                //envia Correo
                $mensaje = "<html>
                                <head></head>
                                <body>
                                    <div style='color: #2f5496; font-family: sans-serif; font-size: 13px;'>
                                        <p>
                                            Hola!
                                            <br/><bt/><br/>
                                            Te encuentras registrado al curso Online <strong>" .$alumno['curso']. "</strong>
                                            <br/><br/><br/>
                                            Por este medio te enviamos los accesos a la Plataforma de Aprendizaje Consultek Online para que puedas acceder al curso al que estás inscrito.
                                            <br/><br/><br/>
                                            <strong>Ingresa a:</strong> <a href='https://plataforma.consultek.training/'>https://plataforma.consultek.training/</a><br/><br/>
                                            <strong>Usuario: </strong>utiliza tu mail de registro<br/><br/>
                                            <strong>Contraseña: " . $password . " </strong>
                                            <br/><br/><br/>
                                            Te recomendamos:
                                            <br/><br/><br/>
                                            <ul>
                                                <li>Cambiar tu contraseña la primera vez que ingreses a la plataforma en la pestaña MI PERFIL</li>
                                                <li>Contar con buena o excelente conexión a internet</li>
                                                <li>Ingresar desde tu PC, laptop o Tablet</li>
                                                <li>Descargar los archivos de apoyo que encontrarás en la plataforma ya que una vez terminada la fecha del curso dejarás de tener acceso a ellos</li>
                                                <li>Cualquier duda escribirnos a <a href='mailto:online@consultek.com.mx'>online@consultek.com.mx</a></li>
                                            </ul>
                                            <br/><br/><br/>
                                            ¡Éxito en tu proceso de formación!
                                        </p>
                                        <br />
                                    </div>
                                </body>
                            </html>";
                $para = $alumno["correoElectronico"] . ",manuel@socialware.mx,luis@socialware.mx,nancyc@consultek.com.mx";
                $res = enviaCorreoAldeamo($para,"Bienvenido a la Plataforma de Aprendizaje Consultek",$mensaje);

                consulta($conexion, "UPDATE alumno SET contrasena = '" . md5($password) . "' WHERE id = " . $idAlumno);

                // Registra evento

                consulta($conexion, "INSERT INTO log (fecha, evento, idUsuario) VALUES ('" . $fechaActual . "', 'Envia correo de bienvenida al alumno | id = " . $idAlumno . "', " . $idUsuario . ")");

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
    // Cierra la conexion con base de datos y libera recursos

    liberaConexion($conexion);

    // Devuelve resultado

    echo $resultado;
?>