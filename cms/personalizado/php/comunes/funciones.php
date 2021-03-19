<?php
    setlocale(LC_ALL, "es_MX");     // Desarrollo
    //setlocale(LC_ALL, "esm");     // Servidor
    date_default_timezone_set('America/Monterrey');


    //require "/Users/mvelasco/Socialware/Proyectos/Web/socialware/php/plugins/PHPMailer-master/src/PHPMailer.php";
    //require "/Users/mvelasco/Socialware/Proyectos/Web/socialware/php/plugins/PHPMailer-master/src/Exception.php";
    require "/var/www/html/socialware/php/plugins/PHPMailer-master/src/PHPMailer.php";
    require "/var/www/html/socialware/php/plugins/PHPMailer-master/src/Exception.php";

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;


    /***************************************************************************
     * Comunicacion con base de datos
     **************************************************************************/


    function consulta($conexion, $consulta) {
        $conexion->query("SET NAMES 'utf8'");
        return $conexion->query($consulta);
    }


    function cuentaResultados($resultados) {
        if ($resultados == false) {
            return 0;
        } else {
            return mysqli_num_rows($resultados);
        }
    }


    function liberaConexion($conexion) {
        $conexion->close();
    }


    function liberaResultados($resultados) {
        $resultados->free();
    }


    function obtenConexion() {
        //return new mysqli("localhost", "root", "", "ami");
        return new mysqli("localhost", "socialware", "un48f45_", "ami");
    }


    function obtenResultado($resultados) {
        if ($resultados != null) {
            return $resultados->fetch_assoc();
        } else {
            return null;
        }
    }


    function reiniciaResultados($resultados) {
        mysqli_data_seek($resultados, 0);
    }


    function registraEvento($evento) {

        // Inicializa variables

        $fechaActual = date("Y-m-d H:i:s");
        $idUsuario = $_SESSION["cms_usuario_id"];

        // Obtiene conexion a base de datos

        $conexion = obtenConexion();

        // Registra evento

        consulta($conexion, "INSERT INTO log (fecha, evento, idUsuario) VALUES ('" . $fechaActual . "', '" . $evento . "', " . $idUsuario . ")");
    }


    /***************************************************************************
     * Control de datos
     **************************************************************************/


    function estaVacio($valor) {
        return (!isset($valor) || trim($valor) === "");
    }


    function esFecha($date, $format = 'Y-m-d') {
        $d = DateTime::createFromFormat($format, $date);
        // The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
        return $d && $d->format($format) === $date;
    }


    function esNumero($valor) {
        return is_numeric($valor);
    }


    function sanitiza($conexion, $valor) {
        if (!estaVacio($valor) && is_string($valor)) {
            //$valor = str_replace("SELECT", "", strtoupper($valor));

            // Elimina SQL Injection

            $valor = preg_replace("/\bDATABASE\b/iu", "", $valor);
            $valor = preg_replace("/\bEXECUTE\b/iu", "", $valor);
            $valor = preg_replace("/\bSHOW\b/iu", "", $valor);
            $valor = preg_replace("/\bSLEEP\b/iu", "", $valor);
            $valor = preg_replace("/\bSELECT\b/iu", "", $valor);
            $valor = preg_replace("/\bFROM\b/iu", "", $valor);
            $valor = preg_replace("/\bAND\b/iu", "", $valor);
            $valor = preg_replace("/\bOR\b/iu", "", $valor);
            $valor = preg_replace("/\bNOT\b/iu", "", $valor);
            $valor = preg_replace("/\bIN\b/iu", "", $valor);
            $valor = preg_replace("/\bJOIN\b/iu", "", $valor);
            $valor = preg_replace("/\bINSERT\b/iu", "", $valor);
            $valor = preg_replace("/\bUPDATE\b/iu", "", $valor);
            $valor = preg_replace("/\bDELETE\b/iu", "", $valor);
            $valor = preg_replace("/\bTRUNCATE\b/iu", "", $valor);
            $valor = preg_replace("/\bDROP\b/iu", "", $valor);

            // Escapa caracteres especiales

            $valor = mysqli_real_escape_string($conexion, $valor);

            // Corrige generacion de saltos de linea de mysqli_real_escape_string

            $valor = str_replace(array('\r','\n'), '', $valor);
        }

        return $valor;
    }


    /***************************************************************************
     * Formato de datos
     **************************************************************************/


    function eliminaCaracteresEspeciales($cadena){
        $caracteresOriginales = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ';
        $caracteresModificados = 'AAAAAAACEEEEIIIIDNOOOOOOUUUUYBSaaaaaaaceeeeiiiidnoooooouuuyybyRr';

        $cadena = utf8_decode($cadena);
        $cadena = strtr($cadena, utf8_decode($caracteresOriginales), $caracteresModificados);

        return utf8_encode($cadena);
    }


    function formatoMoneda($valor) {
        if (!estaVacio($valor)) {
            return '$' . number_format((float) $valor, 2);
        } else {
            return "$0.00";
        }
    }


    function normalizaTelefono($telefono) {
        if (!estaVacio($telefono)) {
            return preg_replace('/\D/', '', $telefono);
        } else {
            return null;
        }
    }


    /***************************************************************************
     * Utilerias
     **************************************************************************/


    function registraLog($valor) {
        $fechaActual = date("Y-m-d H:i:s");

        error_log("\n[" . $fechaActual . "] " . $valor, 3, "/Users/mvelasco/Socialware/Proyectos/Micrositios/trituradospiedrasnegras/error.log");
        //error_log("\n[" . $fechaActual . "] " . $valor, 3, "/var/www/html/micrositios/trituradospiedrasnegras/error.log");
    }


    function calculaTiempoDeInactividad($fechaInicial, $fechaFinal) {
        $tiempoInactividad = $fechaFinal->diff($fechaInicial);
        return $tiempoInactividad->d . " dias, " . $tiempoInactividad->h . " horas, " . $tiempoInactividad->i . " minutos";
    }


    function muestraBloqueo() {
        echo "<div class='table-struct full-width full-height'>"
                . "<div class='table-cell vertical-align-middle'>"
                    . "<div class='auth-form  ml-auto mr-auto no-float'>"
                        . "<div class='panel panel-default card-view mb-0'>"
                            . "<div class='panel-wrapper collapse in'>"
                                . "<div class='panel-body'>"
                                    . "<div class='row'>"
                                        . "<div class='col-sm-12 col-xs-12 text-center'>"
                                            . "<h3 class='mb-20 txt-danger'>Permisos insuficientes</h3>"
                                            . "<p class='font-18 txt-dark mb-15'>Su usuario no cuenta con permiso para utilizar esta funci&oacute;n</p>"
                                            . "<p>Estos intentos quedan registrados autom&aacute;ticamente</p>"
                                        . "</div>"
                                    . "</div>"
                                . "</div>"
                            . "</div>"
                        . "</div>"
                    . "</div>"
                . "</div>"
            . "</div>";
    }


    function generaCadenaAleatoria($longitud) {
        return substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, $longitud);
    }

    function muestraCursos($idCapitulo){  //devuelve los cursos en que se incluye el cápítulo => $idCapitulo
        $resultado = "";
        $conexion = obtenConexion();

        $cursos_BD = consulta($conexion, "SELECT curso.nombre, curso.id FROM curso INNER JOIN curso_capitulo ON curso.id = curso_capitulo.idCurso WHERE curso_capitulo.idCapitulo = ". $idCapitulo);

        while($curso = obtenResultado($cursos_BD)){
            $resultado.= $curso["id"] ." > ". $curso["nombre"];
        }

        return $resultado;
    }

    function muestraCategorias($idProducto) {
        $resultado = "";

        $conexion = obtenConexion();

        $categorias_BD = consulta($conexion, "SELECT * FROM categoria WHERE id IN (SELECT idCategoria FROM producto_categoria WHERE idProducto = " . $idProducto . ")");
        $cantidadResultados = cuentaResultados($categorias_BD);
        $indiceResultados = 0;

        while ($categoria = obtenResultado($categorias_BD)) {
            $indiceResultados++;

            if (!estaVacio($categoria["idCategoriaPadre"])) {
                $resultado .= muestraCategoriasPadre($conexion, $categoria["idCategoriaPadre"]) . $categoria["nombre"];
            } else {
                $resultado .= $categoria["nombre"];
            }

            if ($indiceResultados < $cantidadResultados) {
                $resultado .= "<br /><br />";
            }
        }

        return $resultado;
    }


    function muestraCategoriasPadre($conexion, $idCategoria) {
        $resultado = "";

        if (!estaVacio($idCategoria)) {
            $categoria_BD = consulta($conexion, "SELECT * FROM categoria WHERE id = " . $idCategoria);
            $categoria = obtenResultado($categoria_BD);

            if (!estaVacio($categoria["idCategoriaPadre"])) {
                $resultado = muestraCategoriasPadre($conexion, $categoria["idCategoriaPadre"]) . $categoria["nombre"];
            } else {
                $resultado = $categoria["nombre"];
            }

            $resultado .= " > ";
        }

        return $resultado;
    }


    function enviaCorreo_verificacion($codigoVerificacion) {
        try {

            // Obtiene conexion a base de datos

            $conexion = obtenConexion();

            // Carga comercio

            $comercio_BD = consulta($conexion, "SELECT * FROM comercio WHERE id = 1");
            $comercio = obtenResultado($comercio_BD);

            $comercio_nombre = $comercio["nombre"];
            $comercio_dominio = $comercio["dominio"];

            // Carga parametros de comercio

            $parametros_BD = consulta($conexion, "SELECT * FROM parametro WHERE id = 1");
            $parametros = obtenResultado($parametros_BD);

            // Carga cliente

            $cliente_BD = consulta($conexion, "SELECT * FROM cliente WHERE codigoVerificacion = '" . $codigoVerificacion . "'");
            $cliente = obtenResultado($cliente_BD);

            // Arma correo

            $titulo = $comercio_nombre . " | Verifica tu cuenta";

            $mensaje = "
                <html>
                    <head></head>
                    <body>
                        <div style='color: #6f6f6f; font-family: sans-serif; font-size: 14px; line-height: 25px; margin-left: auto; margin-right: auto; max-width: 530px; text-align: center'>
                            <p style='color: " . $parametros["personalizacion_colorPrimario"] . "; font-size: 25px; font-weight: bold; margin-top: 25px'>
                                ¡BIENVENIDO!
                            </p>

                            <br />

                            <p style='margin-top: 25px'>
                                Gracias por registrarte en <strong>" . $comercio_nombre . "</strong>.
                                <br />
                                Tu cuenta fue creada con el <span style='color: " . $parametros["personalizacion_colorPrimario"] . "'><strong>correo electrónico: " . $cliente["correoElectronico"] . "</strong></span>
                            </p>

                            <p style='margin-top: 25px'>
                                ¡Estás a un solo paso de verificar tu cuenta!  Por favor da click en el siguiente enlace para terminar el proceso de registro:
                            </p>

                            <p style='margin-top: 25px'>
<a href='https://" . $comercio_dominio . "/cliente_verificacion.php?codigoVerificacion=" . $codigoVerificacion . "' style='background-color: " . $parametros["personalizacion_colorPrimario"] . "; border-radius: 10px; color: #fff; padding: 15px; text-decoration: none'>VERIFICAR CORREO</a>
                            </p>

                            <p style='margin-top: 25px'>
                                Te invitamos a que explores todos nuestros productos y apoyes al comercio mexicano.
                            </p>

                            <p style='margin-top: 25px'>
                                Para más información sobre tu cuenta puedes ingresar a <a href='https://" . $comercio_dominio . "'>" . $comercio_dominio . "</a> y seleccionar el ícono para entrar a tu perfil.
                            </p>

                            <p style='margin-top: 25px'>
                                Guarda este e-mail como confirmación de tu registro.
                            </p>

                            <p style='margin-top: 25px'>
                                Gracias,
                                <br />
                                Equipo " . $comercio_nombre . ".
                            </p>


                            <p style='font-size: 11px; margin-top: 50px; text-align: left'>
                                <strong>Nota:</strong>
                                <br />
                                <span style='color: red'>
                                    En ocasiones tu antivirus puede impedir la apertura del enlace al dar click al botón de verificación, si este es el caso te pedimos copiarlo a continuación y pegarlo directamente en tu navegador:
                                    <br />
https://" . $comercio_dominio . "/cliente_verificacion.php?codigoVerificacion=" . $codigoVerificacion . "
                                </span>
                            </p>
                        </div>
                    </body>
                </html>";

            enviaCorreoMailer($cliente["correoElectronico"], $titulo, $mensaje);
            enviaCorreoMailer("manuel@socialware.mx", $titulo, $mensaje);
        } catch (Exception $ex) {
        }
    }


    function enviaCorreo_recuperacionContrasena($idCliente, $contrasena) {
        try {

            // Obtiene conexion a base de datos

            $conexion = obtenConexion();
            $comercio_nombre = "Elearning ";

            $destinatario_nombre = "";
            $destinatario_correoElectronico = "";

            if (!estaVacio($idCliente)) {
                $cliente_BD = consulta($conexion, "SELECT nombre, correoElectronico FROM alumno WHERE id = " . $idCliente);
                $cliente = obtenResultado($cliente_BD);

                $destinatario_nombre = $cliente["nombre"];
                $destinatario_correoElectronico = $cliente["correoElectronico"];
            }

            //if (!estaVacio($destinatario_nombre) && !estaVacio($destinatario_correoElectronico) && !estaVacio($contrasena)) {
            if (!estaVacio($destinatario_correoElectronico) && !estaVacio($contrasena)) {

                // Arma correo

                $titulo = $comercio_nombre . " | Recupera tu contraseña";

                $mensaje = "
                    <html>
                        <head></head>
                        <body>
                            <div style='color: #6f6f6f; font-family: sans-serif; font-size: 14px; line-height: 25px; margin-left: auto; margin-right: auto; max-width: 530px; text-align: center'>
                                <p style='color: blue; font-size: 25px; font-weight: bold; margin-top: 25px'>
                                    RECUPERACIÓN DE CONTRASEÑA
                                </p>

                                <br />

                                <p style='margin-top: 25px'>
                                    Hola <strong>" . $destinatario_nombre . "</strong>,
                                    <br />
                                    Recibimos la solicitud para cambiar la contraseña de tu cuenta, a continuación te proporcionamos una contraseña provisional que podrás utilizar para entrar a la plataforma.  Te recomendamos entrar a tu perfil y cambiarla de inmediato por una personalizada.
                                </p>

                                <p style='margin-top: 50px'>
                                    <span style='color: blue'><strong>Contraseña: " . $contrasena . "</strong></span>
                                </p>

                                <p style='margin-top: 50px'>
                                    En caso de requerir apoyo para personalizar tu contraseña no dudes en ponerte en contacto con nuestro equipo de soporte.
                                </p>

                                <p style='margin-top: 25px'>
                                    Gracias,
                                    <br />
                                    Equipo " . $comercio_nombre . ".
                                </p>
                            </div>
                        </body>
                    </html>";

                enviaCorreoMailer($destinatario_correoElectronico, $titulo, $mensaje);
                enviaCorreoMailer("manuel@socialware.mx", $titulo, $mensaje);
            }
        } catch (Exception $ex) {
        }
    }


    function enviaCorreo_confirmacion($idPedido) {
        try {

            // Obtiene conexion a base de datos

            $conexion = obtenConexion();

            // Carga comercio

            $comercio_BD = consulta($conexion, "SELECT * FROM comercio WHERE id = 1");
            $comercio = obtenResultado($comercio_BD);

            $comercio_nombre = $comercio["nombre"];
            $comercio_dominio = $comercio["dominio"];

            // Carga parametros de comercio

            $parametros_BD = consulta($conexion, "SELECT * FROM parametro WHERE id = 1");
            $parametros = obtenResultado($parametros_BD);

            // Carga pedido

            $pedido_BD = consulta($conexion, "SELECT * FROM pedido WHERE id = " . $idPedido);
            $pedido = obtenResultado($pedido_BD);

            // Arma correo para el cliente

            $titulo = $comercio_nombre . " | Pedido registrado #" . $idPedido;

            $mensaje = "
                <html>
                    <head></head>
                    <body>
                        <div style='color: #6f6f6f; font-family: sans-serif; font-size: 14px; line-height: 25px; margin-left: auto; margin-right: auto; max-width: 530px; text-align: center'>
                            <p style='color: " . $parametros["personalizacion_colorPrimario"] . "; font-size: 25px; font-weight: bold; margin-top: 25px'>
                                ¡GRACIAS POR TU COMPRA!
                            </p>

                            <div>
                                <p style='margin-top: 25px'>
                                    Hola <strong>" . $pedido["cliente_nombre"] . "</strong>,
                                    <br />
                                    Recibimos tu orden y estamos preparando tu pedido.
                                </p>

                                <table style='margin-left: auto; margin-right: auto; text-align: left'>
                                    <tr style=''>
                                        <td style='padding: 0 20px; vertical-align: top'>
                                            <span style='color: " . $parametros["personalizacion_colorPrimario"] . "'><strong>DATOS DEL PEDIDO</strong></span>
                                            <br />
                                            Pedido #" . $idPedido . "
                                        </td>
                                        <td style='padding: 0 20px; vertical-align: top'>
                                            <span style='color: " . $parametros["personalizacion_colorPrimario"] . "'><strong>DATOS DE ENVÍO</strong></span>
                                            <br />
                                            " . $pedido["entrega_calle"] . (!estaVacio($pedido["entrega_numeroExterior"]) ? " Ext. " . $pedido["entrega_numeroExterior"] : "") . (!estaVacio($pedido["entrega_numeroInterior"]) ? " Int. " . $pedido["entrega_numeroInterior"] : "") . "
                                            <br />
                                            " . $pedido["entrega_colonia"] . "
                                            <br />
                                            " . $pedido["entrega_municipio"] . ", " . $pedido["entrega_estado"] . "
                                            <br />
                                            " . $pedido["entrega_codigoPostal"] . "
                                        </td>
                                    </tr>
                                </table>

                                <table style='margin-left: auto; margin-right: auto; margin-top: 50px; text-align: left'>
                                    <tr style=''>
                                        <th style='border-bottom: 1px solid " . $parametros["personalizacion_colorSecundario"] . "; color: " . $parametros["personalizacion_colorSecundario"] . "; padding: 0 20px'>PRODUCTO</th>
                                        <th style='border-bottom: 1px solid " . $parametros["personalizacion_colorSecundario"] . "; color: " . $parametros["personalizacion_colorSecundario"] . "; padding: 0 20px'>CANTIDAD</th>
                                        <th style='border-bottom: 1px solid " . $parametros["personalizacion_colorSecundario"] . "; color: " . $parametros["personalizacion_colorSecundario"] . "; padding: 0 20px'>PRECIO</th>
                                    </tr>";

            $productos_BD = consulta($conexion, "SELECT * FROM pedido_producto WHERE idPedido = " . $idPedido . " ORDER BY nombre");

            while ($producto = obtenResultado($productos_BD)) {
                $mensaje .= "
                                    <tr>
                                        <td style='padding: 0 20px'>" . $producto["nombre"] . "</td>
                                        <td style='padding: 0 20px'>" . $producto["cantidad"] . "</td>
                                        <td style='padding: 0 20px'>" . formatoMoneda($producto["precio"]) . "</td>
                                    </tr>";
            }

            $mensaje .= "
                                    <tr>
                                        <td style='border-top: 1px solid " . $parametros["personalizacion_colorSecundario"] . "; padding: 0 20px'></td>
                                        <td style='border-top: 1px solid " . $parametros["personalizacion_colorSecundario"] . "; padding: 0 20px'>ENVÍO</td>
                                        <td style='border-top: 1px solid " . $parametros["personalizacion_colorSecundario"] . "; padding: 0 20px'>" . formatoMoneda($pedido["envio"]) . "</td>
                                    </tr>
                                    <tr>
                                        <td style='padding: 0 20px'></td>
                                        <td style='padding: 0 20px'>TOTAL</td>
                                        <td style='padding: 0 20px'>" . formatoMoneda($pedido["total"]) . "</td>
                                    </tr>
                                </table>

                                <p style='margin-top: 50px'>
                                    Puedes dar seguimiento a tu pedido desde tu perfil en
                                    <br />
                                    <a href='https://" . $comercio_dominio . "'>" . $comercio_dominio . "</a>
                                </p>

                                <p style='margin-top: 25px'>
                                    Cualquier pregunta, no dudes en contactarnos.
                                </p>
                            </div>
                        </div>
                    </body>
                </html>";

            // Notifica al cliente

            enviaCorreoMailer($pedido["cliente_correoElectronico"], $titulo, $mensaje);
            enviaCorreoMailer("manuel@socialware.mx", $titulo, $mensaje);

            // Arma correo para el comercio

            $titulo = $comercio_nombre . " | Pedido registrado #" . $idPedido;

            $mensaje = "
                <html>
                    <head></head>
                    <body>
                        <div style='color: #6f6f6f; font-family: sans-serif; font-size: 14px; line-height: 25px; margin-left: auto; margin-right: auto; max-width: 530px; text-align: center'>
                            <p style='color: " . $parametros["personalizacion_colorPrimario"] . "; font-size: 25px; font-weight: bold; margin-top: 25px'>
                                ¡HAS RECIBIDO UN PEDIDO!
                            </p>

                            <div>
                                <table style='margin-left: auto; margin-right: auto; text-align: left'>
                                    <tr style=''>
                                        <td style='padding: 0 20px; vertical-align: top'>
                                            <span style='color: " . $parametros["personalizacion_colorPrimario"] . "'><strong>DATOS DEL PEDIDO</strong></span>
                                            <br />
                                            Pedido #" . $idPedido . "
                                        </td>
                                        <td style='padding: 0 20px; vertical-align: top'>
                                            <span style='color: " . $parametros["personalizacion_colorPrimario"] . "'><strong>DATOS DE ENVÍO</strong></span>
                                            <br />
                                            " . $pedido["entrega_calle"] . (!estaVacio($pedido["entrega_numeroExterior"]) ? " Ext. " . $pedido["entrega_numeroExterior"] : "") . (!estaVacio($pedido["entrega_numeroInterior"]) ? " Int. " . $pedido["entrega_numeroInterior"] : "") . "
                                            <br />
                                            " . $pedido["entrega_colonia"] . "
                                            <br />
                                            " . $pedido["entrega_municipio"] . ", " . $pedido["entrega_estado"] . "
                                            <br />
                                            " . $pedido["entrega_codigoPostal"] . "
                                        </td>
                                    </tr>
                                </table>

                                <table style='margin-left: auto; margin-right: auto; margin-top: 50px; text-align: left'>
                                    <tr style=''>
                                        <th style='border-bottom: 1px solid " . $parametros["personalizacion_colorSecundario"] . "; color: " . $parametros["personalizacion_colorSecundario"] . "; padding: 0 20px'>PRODUCTO</th>
                                        <th style='border-bottom: 1px solid " . $parametros["personalizacion_colorSecundario"] . "; color: " . $parametros["personalizacion_colorSecundario"] . "; padding: 0 20px'>CANTIDAD</th>
                                        <th style='border-bottom: 1px solid " . $parametros["personalizacion_colorSecundario"] . "; color: " . $parametros["personalizacion_colorSecundario"] . "; padding: 0 20px'>PRECIO</th>
                                    </tr>";

            $productos_BD = consulta($conexion, "SELECT * FROM pedido_producto WHERE idPedido = " . $idPedido . " ORDER BY nombre");

            while ($producto = obtenResultado($productos_BD)) {
                $mensaje .= "
                                    <tr>
                                        <td style='padding: 0 20px'>" . $producto["nombre"] . "</td>
                                        <td style='padding: 0 20px'>" . $producto["cantidad"] . "</td>
                                        <td style='padding: 0 20px'>" . formatoMoneda($producto["precio"]) . "</td>
                                    </tr>";
            }

            $mensaje .= "
                                    <tr>
                                        <td style='border-top: 1px solid " . $parametros["personalizacion_colorSecundario"] . "; padding: 0 20px'></td>
                                        <td style='border-top: 1px solid " . $parametros["personalizacion_colorSecundario"] . "; padding: 0 20px'>ENVÍO</td>
                                        <td style='border-top: 1px solid " . $parametros["personalizacion_colorSecundario"] . "; padding: 0 20px'>" . formatoMoneda($pedido["envio"]) . "</td>
                                    </tr>
                                    <tr>
                                        <td style='padding: 0 20px'></td>
                                        <td style='padding: 0 20px'>TOTAL</td>
                                        <td style='padding: 0 20px'>" . formatoMoneda($pedido["total"]) . "</td>
                                    </tr>
                                </table>

                                <p style='margin-top: 50px'>
                                    Puedes dar seguimiento a este pedido desde tu administrador de contenido
                                    <br />
                                    <a href='https://socialware.mx/cms'>https://socialware.mx/cms</a>
                                </p>
                            </div>
                        </div>
                    </body>
                </html>";

            // Notifica al comercio

            enviaCorreoMailer($parametros["contacto_canales_correoElectronico"], $titulo, $mensaje);
            enviaCorreoMailer("manuel@socialware.mx", $titulo, $mensaje);

            $mensaje = "Has recibido un pedido por un monto de " . formatoMoneda($pedido["total"]) . ".";

            if (!estaVacio($pedido["cliente_telefonoMovil"])) {
                $mensaje .= " El teléfono del destinatario es " . $pedido["cliente_telefonoMovil"] . ".";
            }

            $mensaje .= " Consulta más detalles en tu administrador de contenido.";

            if (!estaVacio($parametros["contacto_canales_whatsapp"]) && !estaVacio(trim($parametros["contacto_canales_whatsapp"]))) {
                //enviaSmsWavy("521" . trim($parametros["contacto_canales_whatsapp"]), $mensaje);
                //enviaSmsWavy("5218119779006", $mensaje);
                enviaSmsWavy(trim($parametros["contacto_canales_whatsapp"]), $mensaje);
                enviaSmsWavy("8119779006", $mensaje);
            }
        } catch (Exception $ex) {
        }
    }


    function reenviaCorreo_confirmacion($idPedido) {
        try {

            // Obtiene conexion a base de datos

            $conexion = obtenConexion();

            // Carga comercio

            $comercio_BD = consulta($conexion, "SELECT * FROM comercio WHERE id = 1");
            $comercio = obtenResultado($comercio_BD);

            $comercio_nombre = $comercio["nombre"];
            $comercio_dominio = $comercio["dominio"];

            // Carga parametros de comercio

            $parametros_BD = consulta($conexion, "SELECT * FROM parametro WHERE id = 1");
            $parametros = obtenResultado($parametros_BD);

            // Carga pedido

            $pedido_BD = consulta($conexion, "SELECT * FROM pedido WHERE id = " . $idPedido);
            $pedido = obtenResultado($pedido_BD);

            // Arma correo para el cliente

            $titulo = $comercio_nombre . " | Pedido registrado #" . $idPedido;

            $mensaje = "
                <html>
                    <head></head>
                    <body>
                        <div style='color: #6f6f6f; font-family: sans-serif; font-size: 14px; line-height: 25px; margin-left: auto; margin-right: auto; max-width: 530px; text-align: center'>
                            <p style='color: " . $parametros["personalizacion_colorPrimario"] . "; font-size: 25px; font-weight: bold; margin-top: 25px'>
                                ¡GRACIAS POR TU COMPRA!
                            </p>

                            <div>
                                <p style='margin-top: 25px'>
                                    Hola <strong>" . $pedido["nombre"] . "</strong>,
                                    <br />
                                    Recibimos tu orden y estamos preparando tu pedido.
                                </p>

                                <table style='margin-left: auto; margin-right: auto; text-align: left'>
                                    <tr style=''>
                                        <td style='padding: 0 20px; vertical-align: top'>
                                            <span style='color: " . $parametros["personalizacion_colorPrimario"] . "'><strong>DATOS DEL PEDIDO</strong></span>
                                            <br />
                                            Pedido #" . $idPedido . "
                                        </td>
                                        <td style='padding: 0 20px; vertical-align: top'>
                                            <span style='color: " . $parametros["personalizacion_colorPrimario"] . "'><strong>DATOS DE ENVÍO</strong></span>
                                            <br />
                                            " . $pedido["entrega_calle"] . (!estaVacio($pedido["entrega_numeroExterior"]) ? " Ext. " . $pedido["entrega_numeroExterior"] : "") . (!estaVacio($pedido["entrega_numeroInterior"]) ? " Int. " . $pedido["entrega_numeroInterior"] : "") . "
                                            <br />
                                            " . $pedido["entrega_colonia"] . "
                                            <br />
                                            " . $pedido["entrega_municipio"] . ", " . $pedido["entrega_estado"] . "
                                            <br />
                                            " . $pedido["entrega_codigoPostal"] . "
                                        </td>
                                    </tr>
                                </table>

                                <table style='margin-left: auto; margin-right: auto; margin-top: 50px; text-align: left'>
                                    <tr style=''>
                                        <th style='border-bottom: 1px solid " . $parametros["personalizacion_colorSecundario"] . "; color: " . $parametros["personalizacion_colorSecundario"] . "; padding: 0 20px'>PRODUCTO</th>
                                        <th style='border-bottom: 1px solid " . $parametros["personalizacion_colorSecundario"] . "; color: " . $parametros["personalizacion_colorSecundario"] . "; padding: 0 20px'>CANTIDAD</th>
                                        <th style='border-bottom: 1px solid " . $parametros["personalizacion_colorSecundario"] . "; color: " . $parametros["personalizacion_colorSecundario"] . "; padding: 0 20px'>PRECIO</th>
                                    </tr>";

            $productos_BD = consulta($conexion, "SELECT * FROM pedido_producto WHERE idPedido = " . $idPedido . " ORDER BY nombre");

            while ($producto = obtenResultado($productos_BD)) {
                $mensaje .= "
                                    <tr>
                                        <td style='padding: 0 20px'>" . $producto["nombre"] . "</td>
                                        <td style='padding: 0 20px'>" . $producto["cantidad"] . "</td>
                                        <td style='padding: 0 20px'>" . formatoMoneda($producto["precio"]) . "</td>
                                    </tr>";
            }

            $mensaje .= "
                                    <tr>
                                        <td style='border-top: 1px solid " . $parametros["personalizacion_colorSecundario"] . "; padding: 0 20px'></td>
                                        <td style='border-top: 1px solid " . $parametros["personalizacion_colorSecundario"] . "; padding: 0 20px'>ENVÍO</td>
                                        <td style='border-top: 1px solid " . $parametros["personalizacion_colorSecundario"] . "; padding: 0 20px'>" . formatoMoneda($pedido["envio"]) . "</td>
                                    </tr>
                                    <tr>
                                        <td style='padding: 0 20px'></td>
                                        <td style='padding: 0 20px'>TOTAL</td>
                                        <td style='padding: 0 20px'>" . formatoMoneda($pedido["total"]) . "</td>
                                    </tr>
                                </table>

                                <p style='margin-top: 50px'>
                                    Puedes dar seguimiento a tu pedido desde tu perfil en
                                    <br />
                                    <a href='https://" . $comercio_dominio . "'>" . $comercio_dominio . "</a>
                                </p>

                                <p style='margin-top: 25px'>
                                    Cualquier pregunta, no dudes en contactarnos.
                                </p>
                            </div>
                        </div>
                    </body>
                </html>";

            enviaCorreoMailer($pedido["cliente_correoElectronico"], $titulo, $mensaje);
            enviaCorreoMailer("manuel@socialware.mx", $titulo, $mensaje);
        } catch (Exception $ex) {
        }
    }

















    /***************************************************************************
     * Comunicacion
     **************************************************************************/


    function enviaCorreoMailer($para, $titulo, $mensaje) {
/*
        try {
            $email = new PHPMailer();
            $email->CharSet = "UTF-8";
            //$email->Encoding = "16bit";
            //$email->isHTML();
            $email->Subject = $titulo;
            //$email->Body = utf8_decode($mensaje);
            $email->MsgHTML($mensaje);
            $email->SetFrom("info@shopware.mx", "Shopware");

            if (strpos($para, ",") !== false) {
                $destinatarios = explode(",", $para);

                foreach ($destinatarios as $destinatario) {
                    $email->AddAddress($destinatario);
                }
            } else {
                $email->AddAddress($para);
            }

            $email->Send();
        } catch (phpmailerException $e) {
        } catch (Exception $e) {
        }
*/
        enviaCorreoAldeamo($para, $titulo, $mensaje);
    }

    function enviaCorreoMailerArchivoAdjunto($para, $titulo, $mensaje, $adjuntoNombre, $adjuntoRuta) {
        try {
            $email = new PHPMailer(true);
            $email->CharSet = "UTF-8";
            $email->Subject = $titulo;
            $email->MsgHTML($mensaje);
            $email->SetFrom("online@consultek.com.mx", "Consultek");

            if (strpos($para, ",") !== false) {
                $destinatarios = explode(",", $para);

                foreach ($destinatarios as $destinatario) {
                    $email->AddAddress($destinatario);
                }
            } else {
                $email->AddAddress($para);
            }

            if (!estaVacio($adjuntoNombre) && !estaVacio($adjuntoRuta)) {
                $myfile = fopen($adjuntoRuta, "r") or die("Unable to open file!");
                // echo $adjuntoRuta;die();
                $email->AddAttachment($adjuntoRuta, $adjuntoNombre);
            }

            $email->Send();
        } catch (phpmailerException $e) {
        } catch (Exception $e) {
        }
    }


    function enviaCorreoAldeamo($para, $titulo, $mensaje, $archivosAdjuntos = null) {
        try {
            $mensaje = preg_replace('/\s+/S', " ", $mensaje);

            // Arma cadena de destinatarios

            $cadenaPara = "";

            if (strpos($para, ",") > -1) {
                $destinatarios = explode(",", $para);

                foreach ($destinatarios as $destinatario) {
                    $cadenaPara .= '{ "email": "' . $destinatario . '" },';
                }

                $cadenaPara = substr($cadenaPara, 0, -1);
            } else {
                $cadenaPara = '{ "email": "' . $para . '" }';
            }

            // Arma cadena de archivos adjuntos

            $cadenaArchivosAdjuntos = "";

            if (!estaVacio($archivosAdjuntos)) {
                $cadenaArchivosAdjuntos .= ',"attachments": [{"path": "' . $archivosAdjuntos . '"}]';
            }

            // Arma cadena de envio

            $parametros = '{
                "from": {
                    "email": "online@consultek.com.mx",
                    "name": "Consultek"
                },
                "to": [
                    ' . $cadenaPara . '
                ],
                "replyTo": {
                    "email": "online@consultek.com.mx",
                    "name": "Consultek"
                },
                "subject": "' . $titulo . '",
                "body": "' . $mensaje . '",
                "options": {}
                ' . $cadenaArchivosAdjuntos . '
            }';

            // Envia correo electronico

            curl_setopt_array($canal = curl_init(), array(
                CURLOPT_URL => "http://email.aldeamo.com:5000/v1/email",
                //CURLOPT_URL => "http://api.ckpnd.com:5000/v1/email",
                CURLOPT_HTTPHEADER => array("Content-Type: application/json", "Authorization: Bearer 78c4191b.ba3a4d5f84831db85521931a"),
                CURLOPT_HEADER => true,
                CURLOPT_POST => true,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POSTFIELDS => $parametros
            ));

            $respuesta = curl_exec($canal);

            $httpCode = curl_getinfo($canal, CURLINFO_HTTP_CODE);

            curl_close($canal);

            return $httpCode;
        } catch (Error $e) {
        } catch (Exception $e) {
        }
    }


    /*
     * SMSs masivos
     */


    function enviaSms($telefono, $mensaje, $idPromotor) {
        curl_setopt_array($ch = curl_init(), array(
                CURLOPT_URL => "http://smsmasivos.com.mx/sms/api.envio.new.php",
                //HTTPS
                //CURLOPT_URL => "https://smsmasivos.com.mx/sms/api.envio.new.php",
                //CURLOPT_SSL_VERIFYPEER => FALSE,
                CURLOPT_POST => TRUE,
                CURLOPT_RETURNTRANSFER => TRUE,
                CURLOPT_POSTFIELDS => array(
                    // 1 via
                    "apikey" => "80607e5c71de572f05dddb716aed6df28702400e",
                    // 2 vias
                    //"apikey" => "367dd2b78953bd42ce135685a20eb1bcff9f0cd4",
                    "mensaje" => $mensaje,
                    "numcelular" => $telefono,
                    "numregion" => "52"/*,
                    "sandbox" => "1",*/
                )
            )
        );

        $respuesta = curl_exec($ch);
        curl_close($ch);

        $respuesta = json_decode($respuesta);

        return $respuesta->mensaje;
    }


    /*
     * Wavy
     */


    function enviaSmsWavy($telefono, $mensaje) {
        $resultado = "";

        try {
            $json = '{
                "destination": "521' . $telefono . '",
                "messageText": "' . $mensaje . '"
            }';

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, "https://api-messaging.wavy.global/v1/send-sms");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_HEADER, FALSE);
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                "Content-Type: application/json",
                "authenticationToken: PNgz5FBL3f2_rZ3GwyTF96vBBxoMseZaDzjnWq_-",
                "username: Socialware"
            ));

            //$respuesta = curl_exec($ch);
            curl_exec($ch);

            curl_close($ch);

            //$respuesta = json_decode($respuesta);

            //$resultado = $respuesta->mensaje;
        } catch (Error $e) {
        } catch (Exception $e) {
        }

        return $resultado;
    }


    /*
     * Aldeamo
     */


    function enviaSmsAldeamo($telefono, $mensaje) {

        // Inicializa variables

        $fechaActual = date("Y-m-d H:i:s");

        // Arma parametros

        $fields = [
            "country" => "MX",
            "dateToSend" => $fechaActual,
            "message" => $mensaje,
            "encoding" => "UTF-8",
            "messageFormat" => 1,
            "addresseeList" => [
                [
                    "mobile" => $telefono
                ]
            ],
        ];

        $jsonfield = json_encode($fields);

        // Envia SMS

        curl_setopt_array($canal = curl_init(), array(
                CURLOPT_URL => "https://apitellit.aldeamo.com/SmsiWS/smsSendPost/",
                //CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_HTTPHEADER => array("Content-Type: application/json", "Authorization: Basic " . base64_encode("user:password")),
                CURLOPT_HEADER => true,
                CURLOPT_POST => true,
                CURLOPT_RETURNTRANSFER => true,
/*
                CURLOPT_POSTFIELDS => [
                    "country" => "MX",
                    "dateToSend" => $fechaActual,
                    "message" => $mensaje,
                    "encoding" => "UTF-8",
                    "messageFormat" => 1,
                    "addresseeList" => [
                        [ "mobile" => $telefono ]
                    ]
                ]
*/
                CURLOPT_POSTFIELDS => $jsonfield
            )
        );

        $respuesta = curl_exec($canal);
        curl_close($canal);
/*
        $respuesta = json_decode($respuesta);

        return $respuesta->status;
*/
    }
?>
