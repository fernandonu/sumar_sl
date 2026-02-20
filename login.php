<?
if (ereg("/login.php",$_SERVER["SCRIPT_NAME"])) {
	$tmp=explode("/login.php",$_SERVER["SCRIPT_NAME"]);
	$html_root = $tmp[0];
}

require_once "lib/Browser.php";
$browser = new Browser();
if( $browser->isBrowser("Chrome") ||
  ($browser->isBrowser("Internet Explorer") && $browser->getVersion() >= 11)
  ) 
{
  define("BROWSER_OK", true);
}
else
{
  define("BROWSER_OK", false);
}

?>
<html>

<head>

<meta charset="UTF-8">
<title>Programa SUMAR+</title>
<link rel="icon" href="<? echo ((($_SERVER['HTTPS'])?"https":"https")."://".$_SERVER['HTTP_HOST']).$html_root; ?>/favicon.ico">
<link REL='SHORTCUT ICON' HREF='<? echo ((($_SERVER['HTTPS'])?"https":"https")."://".$_SERVER['HTTP_HOST']).$html_root; ?>/favicon.ico'>



<style>
body{
  margin: 0;
  padding: 0;
  background: #fff;

  color: #fff;
  font-family: Arial;
  font-size: 12px;
}

.body{
position: fixed; /* Mejor que absolute para fondos de pantalla completa */
  top: 15px;
  left: 0px;
  width: 100%;
  height: 100%;
  background-image: url(<?php echo $html_root;?>/imagenes/login/login<?php echo rand(1, 4);?>.jpg);
  background-size: cover;       /* Cubre toda la pantalla sin deformar */
  background-position: center;  /* Centra la imagen */
  background-repeat: no-repeat; /* Evita que la imagen se duplique */
  -webkit-filter: blur(0px);
  z-index: 0;
}

.grad {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: linear-gradient(to bottom, rgba(0,0,0,0) 0%, rgba(0,0,0,0.65) 100%);
  z-index: 1;
  opacity: 0.8; /* Aumentado un poco para mejorar legibilidad */
}

.header{
  position: absolute;
  top: calc(50% - 35px);
  left: calc(50% - 555px);
  z-index: 2;
}

.header div{
  float: left;
  color: #fff;
  font-family: 'Exo', sans-serif;
  font-size: 35px;
  font-weight: 200;
}

.header div span{
  color: #5379fa !important;
}

.login{
  position: absolute;
  top: calc(50% - 75px);
  left: calc(50% - 50px);
  height: 150px;
  width: 350px;
  padding: 10px;
  z-index: 2;
}

.login input[type=text]{
  width: 250px;
  height: 30px;
  background: transparent;
  border: 1px solid rgba(255,255,255,0.6);
  border-radius: 2px;
  color: #fff;
  font-family: 'Exo', sans-serif;
  font-size: 16px;
  font-weight: 400;
  padding: 4px;
}

.login input[type=password]{
  width: 250px;
  height: 30px;
  background: transparent;
  border: 1px solid rgba(255,255,255,0.6);
  border-radius: 2px;
  color: #fff;
  font-family: 'Exo', sans-serif;
  font-size: 16px;
  font-weight: 400;
  padding: 4px;
  margin-top: 10px;
}

.login input[type=button], .login input[type=submit]{
  width: 260px;
  height: 35px;
  background: #fff;
  border: 1px solid #fff;
  cursor: pointer;
  border-radius: 2px;
  color: #a18d6c;
  font-family: 'Exo', sans-serif;
  font-size: 16px;
  font-weight: 400;
  padding: 6px;
  margin-top: 10px;
}

.login input[type=button]:hover, .login input[type=submit]:hover{
  opacity: 0.8;
}

.login input[type=button]:active, .login input[type=submit]:active{
  opacity: 0.6;
}

.login input[type=text]:focus{
  outline: none;
  border: 1px solid rgba(255,255,255,0.9);
}

.login input[type=password]:focus{
  outline: none;
  border: 1px solid rgba(255,255,255,0.9);
}

.login input[type=button]:focus, .login input[type=submit]:focus{
  outline: none;
}

::-webkit-input-placeholder{
   color: rgba(255,255,255,0.6);
}

::-moz-input-placeholder{
   color: rgba(255,255,255,0.6);
}
</style>

<script src="./loginprefixfree.min.js"></script>


</head>

<?if (!BROWSER_OK){?>
<script type="text/javascript">window.open ("https://www.google.com/chrome/browser/desktop/index.html","_blank")</script>
<div class="alert alert-danger" align="center">NAVEGADOR NO COMPATIBLE. <a href="https://www.google.com/chrome/browser/desktop/index.html" target="_blank"> Recomendamos Instalar Google Chrome. </a></div>
<?}?>

<form action='index.php' method='post' name='frm'>
<input type="hidden" name="resolucion_ancho" value="">
<input type="hidden" name="resolucion_largo" value="">
	  
      		    <form method="POST">
                <div class="body"></div>
                <div class="grad"></div>
                <div class="header">
                  <div> SUMAR+ <span> San Luis</span></div>
                </div>
                <br>
                <div class="login">
                    <input type="text" placeholder="Nombre de Usuario" name="username" pattern="[a-zA-Z0-9_]+" title="Letras minúsculas, números y guion bajo (_) sin espacios">                    
                    <input type="password" placeholder="Clave" name="password"><br>
                    <input type="submit" name="loginform" value="Iniciar Sesi&oacute;n">
                    <br>
                    <br>
                    <a href="http://sisweb.msal.gov.ar/consultapyp/index.php" target="_blank" title="Consultas a PUCO. Usuario y contrase&ntilde;a: 'consulta'"> Consultas a PUCO </a>
                    <br>
                    <br>
                    <a href="https://salud.sanluis.gov.ar/" target="_blank" title="Sitio web Ministerio de Salud"> Sitio web Ministerio de Salud </a>
                </div>               
              </form>

<script>
  //guardamos la resolucion de la pantalla del usuario en los hiddens para despues recuperarlas
  //y guardarlas en las variable de sesion $_ses_user
  document.all.resolucion_ancho.value=screen.width;
  document.all.resolucion_largo.value=screen.height;
</script>
<script type="text/javascript" src="<?php echo $html_root;?>/lib/jquery/jquery-1.11.1.min.js"></script>
</form>
</body>
</html>
