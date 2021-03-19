<?php
    /*
     * Controla el ciclo de vida de sesion de un usuario
     */

    session_start();

    if (isset($_SESSION["cms_sesion_horaDeExpiracion"])) {
	if ($_SESSION["cms_sesion_horaDeExpiracion"] >= time()) {

	    // Renueva la sesion

	    //$_SESSION["sesion_horaDeExpiracion"] = time() + (60 * 30);
	    $_SESSION["cms_sesion_horaDeExpiracion"] = time() + (60 * 60 * 24 * 365);
	} else {

	    // Destruye la sesion

	    session_unset();

            header("Location: acceso.php");
            die();
	}
    } else {
        header("Location: acceso.php");
        die();
    }
?>
