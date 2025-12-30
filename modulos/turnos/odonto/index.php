<!doctype html>
<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!-- Consider adding an manifest.appcache: h5bp.com/d/Offline -->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->

<?php include("../../../scripts_automaticos/funciones_generales.php"); $id_paciente=$_GET['id_paciente'];?>

<head>
  <meta charset="utf-8">

  <!-- Use the .htaccess and remove these lines to avoid edge case issues.
       More info: h5bp.com/b/378 -->
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

  <title></title>
  <meta name="description" content="">
  <meta name="author" content="">

  <!-- Mobile viewport optimized: j.mp/bplateviewport -->
  <meta name="viewport" content="width=device-width,initial-scale=1">

  <!-- Place favicon.ico and apple-touch-icon.png in the root directory: mathiasbynens.be/notes/touch-icons -->

  <!-- CSS: implied media=all -->
  <!-- CSS concatenated and minified via ant build script-->
  <link rel="stylesheet" href="public/css/style.css">
  <link rel="stylesheet" href="public/css/jquery-ui-1.8.17.custom.css">
  <link rel="stylesheet" href="public/css/jquery.svg.css">
  <link rel="stylesheet" href="public/css/odontograma.css">
  <!-- end CSS-->

  <!-- More ideas for your <head> here: h5bp.com/d/head-Tips -->

  <!-- All JavaScript at the bottom, except for Modernizr / Respond.
       Modernizr enables HTML5 elements & feature detects; Respond is a polyfill for min/max-width CSS3 Media Queries
       For optimal performance, use a custom Modernizr build: www.modernizr.com/download/ -->
  <script src="public/js/modernizr-2.0.6.min.js"></script>
</head>

<body>

  <div id="container">
    <div id="main" role="main"> 
          
      <div id="tratamiento">
        <h2>Tratamiento</h2>
        <select 
          data-bind=" options: tratamientosPosibles, 
                      value: tratamientoSeleccionado, 
                      optionsText: function(item){ return item.nombre; },
                      optionsCaption: 'Seleccione un tratamiento...'">
        </select>
        
        <input type="hidden" value="<?php echo $id_paciente; ?>" name="id_paciente" id="id_paciente"> 
        <div class="alert alert-danger" align="center">   
          <b><span id="resultado"></span></b>
          <b><span id="resultado1"></span></b>
        </div> 
        <ul data-bind="foreach: tratamientosAplicados">
          <li>
            P<span data-bind="text: diente.id"></span><span data-bind="text: cara"></span>
            -            
            <span data-bind="text: tratamiento.nombre"></span>
            <!--| <a href="#" data-bind="click: $parent.quitarTratamiento">Eliminar</a>-->
          </li>          
        </ul>
      </div>
      <div id="odontograma-wrapper">
        <h2>Odontograma</h2>
        <div id="odontograma"></div>
      </div>      
    </div>

    <div id="main" role="main"> 
      <div id="tratamiento">        
        <div id="odontograma-wrapper">
        <input class="btn btn-info btn-large" type=button name="cerrar" value="Cerrar Pantalla" onclick="window.opener.location.reload();window.close()" title="Cerrar Pantalla" >
          <h2>Tratamientos</h2>
          <div id="odontograma">
            <?php $sql = "select * FROM ficha_consumo.odontograma where id_paciente=$id_paciente";
            $res = $db->Execute($sql) or die("error\n");
            $ret = '';
            if ($res->recordCount() > 0) {
              $ret .= '<ul class="list-group">';
              while (!$res->EOF) {
                $ret .= '<li class="list-group-item"><b>';
                $ret .= 'Tratamiento: '.$res->fields['tratamiento'].'<br/>';
                $ret .= '</b>';
                $ret .= 'Pieza: '.$res->fields['pieza'].'<br/>';
                $ret .= 'Cara: '.$res->fields['cara'].'<br/>';
                $ret .= '</li>';
                $res->MoveNext();
              }
              $ret .= '</ul>';
            } else {
              $ret .= '<ul class="list-group"><li class="list-group-item alert alert-info" role="alert">No hay datos</li></ul>';
            }
            echo $ret;
            ?>            
          </div>
        </div>      
      </div>
    </div>

  </div> <!--! end of #container -->  

  <!-- scripts concatenated and minified via ant build script-->
  <script defer src="public/js/jquery-1.7.1.min.js"></script>
  <script defer src="public/js/plugins.js"></script>
  <script defer src="public/js/jquery-ui-1.8.17.custom.min.js"></script>
  <script defer src="public/js/jquery.tmpl.js"></script>
  <script defer src="public/js/knockout-2.0.0.js"></script>
  <script defer src="public/js/jquery.svg.min.js"></script>  
  <script defer src="public/js/jquery.svggraph.min.js"></script>  
  <script defer src="public/js/odontograma.js"></script>
  <!-- end scripts-->

	
    
</body>
</html>
