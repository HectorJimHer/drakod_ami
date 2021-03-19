<script>

            $(".suscribeBoletin_ami").on("click",function(){
                var correo = $("#campoBoletin").val();
                if(validateEmail(correo)){
                    $.ajax({
                        data: {correoElectronico: correo},
                        type: "post",
                        url: "http://104.239.174.172/proyectos_desarrollo/ami/personalizado/php/ajax/registraNewsletterAmi.php",
                        success: function(resultado) {
                            if (resultado === "ok") {
                                $("#campoBoletin").val("");
                                if (consultaMemoria("idioma") === "Ingles"){
                                    alert("Thanks for subscribing!");
                                }else{
                                    alert("Gracias por suscribirte!");
                                }
                            } else {
                                alert(resultado);
                            }
                        }
                    });
                }else{
                    if (consultaMemoria("idioma") === "Ingles"){
                        alert("Please, type a correct email.");
                    }else{
                        alert("El email colocado no tiene un formato válido.");
                    }
                }
            });

            $(".suscribeBoletin_farfarela").on("click",function(){
                var correo = $("#campoBoletin").val();
                if(validateEmail(correo)){
                    $.ajax({
                        data: {correoElectronico: correo},
                        type: "post",
                        url: "http://104.239.174.172/proyectos_desarrollo/ami/personalizado/php/ajax/registraNewsletterFarfarela.php",
                        success: function(resultado) {
                            if (resultado === "ok") {
                                $("#campoBoletin").val("");
                                if (consultaMemoria("idioma") === "Ingles"){
                                    alert("Thanks for subscribing!");
                                }else{
                                    alert("Gracias por suscribirte!");
                                }
                            } else {
                                alert(resultado);
                            }
                        }
                    });
                }else{
                    if (consultaMemoria("idioma") === "Ingles"){
                        alert("Please, type a correct email.");
                    }else{
                        alert("El email colocado no tiene un formato válido.");
                    }
                }
            });

            $(".enlace_espanol").on("click", function() {
                actualizaMemoria("idioma", "Espanol");

                var height = $("ul.blog-grid.contenido_ingles").height();
                $("ul.blog-grid.contenido_espanol").height(height);

                var height = $("ul.blog-wrapper").height();
                $("ul.blog-wrapper").height(height);

                $(".contenido_espanol").show();
                $(".contenido_ingles").hide();
                $('.contenido_ingles').attr('style','display:none !important');



            });

            $(".enlace_ingles").on("click", function() {
                actualizaMemoria("idioma", "Ingles");

                var height = $("ul.blog-grid.contenido_espanol").height();
                $("ul.blog-grid.contenido_ingles").height(height);

                var height = $("ul.blog-wrapper").height();
                $("ul.blog-wrapper").height(height);

                $(".contenido_espanol").hide();
                $('.contenido_espanol').attr('style','display:none !important');
                $(".contenido_ingles").show();


            });

            var prefijoMemoria = "socialware_ami_";

            function validateEmail(email) {
                const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                return re.test(String(email).toLowerCase());
            }

            function consultaMemoria(llave) {
                return localStorage.getItem(prefijoMemoria + llave);
            }

            function actualizaMemoria(llave, valor) {
                localStorage.setItem(prefijoMemoria + llave, valor);
            }

            $( document ).ready(function() {

            	$(function() {

	                // Manejo de idioma

	                if (!consultaMemoria("idioma")) {
	                    actualizaMemoria("idioma", "Espanol");
	                }

	                if (consultaMemoria("idioma") === "Ingles") {
	                    $(".contenido_espanol").hide();
                        $('.contenido_espanol').attr('style','display:none !important');
	                    $(".contenido_ingles").show();
	                } else {
	                    $(".contenido_espanol").show();
	                    $(".contenido_ingles").hide();
                        $('.contenido_ingles').attr('style','display:none !important');
	                }
	            });
			});

</script>