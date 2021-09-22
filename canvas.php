<?php
if (!isset($_SESSION)){session_start();};
	if (isset($_SESSION['usr_id'])){
	//	if ($_SESSION['tipo']<>"cliente"){
include('../../includes/conexionrt.php');
$sqlconf = "SELECT * from empresa";
$configEmpresa = $mysqli->query($sqlconf);
while($rowconfig = mysqli_fetch_array($configEmpresa)){
   $nombreempresa=trim($rowconfig['nombre_empresa']);
  $desempresa=trim($rowconfig['descripcion_meta']);
  $logo=trim($rowconfig['logo_empresa']);
  $emaempresa=trim($rowconfig['email']);
  $dirempresa=trim($rowconfig['direccion']);
  $rifempresa=trim($rowconfig['rif_empresa']);
  $telempresa=trim($rowconfig['telefonos']);
  $msj_ingreso=trim($rowconfig['msj_ingreso']);
}
/////////funcion para permisos
$sqlformulario = "SELECT * from cat_formularios where nombre = 'Servicio Técnico'";
$configPermisos = $mysqli->query($sqlformulario);
while($rowper = mysqli_fetch_array($configPermisos)){
	 $categoria=trim($rowper['id_cat']);
}
?>
<!DOCTYPE html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo $nombreempresa; ?> | Servicio Técnico</title>
  <link rel="shortcut icon" href="../../../../favicon.ico" type="image/x-icon">
  <!-- Tell the browser to be responsive to screen width -->
   <script src="../literallycanvas-master/lib/js/literallycanvas.fat.js"></script>
   <!--script src="//cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.js"></script por favor areglar-->
  <?php include "head.html"; ?>
 
   <script type="text/javascript">
      $(document).ready(function () {
         initialize();
      });
 

      // works out the X, Y position of the click inside the canvas from the X, Y position on the page
      function getPosition(mouseEvent, sigCanvas) {
         var x, y;
         if (mouseEvent.pageX != undefined && mouseEvent.pageY != undefined) {
            x = mouseEvent.pageX;
            y = mouseEvent.pageY;
			//alert(sigCanvas.offsetTop);
			
			let posicion = sigCanvas.getBoundingClientRect()
			correccionX = posicion.left;
			correccionY = posicion.y;
			
			//alert(x - sigCanvas.offsetLeft);
			//alert(correccionY);
			return { X: x - sigCanvas.offsetLeft- (correccionX - 15), Y: y - sigCanvas.offsetTop- correccionY };
         } else {
            x = mouseEvent.clientX + document.body.scrollLeft + document.documentElement.scrollLeft;
            y = mouseEvent.clientY + document.body.scrollTop + document.documentElement.scrollTop;
			//alert(x - sigCanvas.offsetLeft);
			 return { X: x - sigCanvas.offsetLeft, Y: y - sigCanvas.offsetTop };
         }
  
         //return { X: x, Y: y };
		
      }
 
      function initialize() {
         // get references to the canvas element as well as the 2D drawing context
         var sigCanvas = document.getElementById("canvasSignature");
         var context = sigCanvas.getContext("2d");
         context.strokeStyle = 'Black';
 
         // This will be defined on a TOUCH device such as iPad or Android, etc.
         var is_touch_device = 'ontouchstart' in document.documentElement;
 
         if (is_touch_device) {
            // create a drawer which tracks touch movements
            var drawer = {
               isDrawing: false,
               touchstart: function (coors) {
                  context.beginPath();
                  context.moveTo(coors.x, coors.y);
                  this.isDrawing = true;
               },
               touchmove: function (coors) {
                  if (this.isDrawing) {
                     context.lineTo(coors.x, coors.y);
                     context.stroke();
                  }
               },
               touchend: function (coors) {
                  if (this.isDrawing) {
                     this.touchmove(coors);
                     this.isDrawing = false;
                  }
               }
            };
 
            // create a function to pass touch events and coordinates to drawer
            function draw(event) {
 
               // get the touch coordinates.  Using the first touch in case of multi-touch
               var coors = {
                  x: event.targetTouches[0].pageX,
                  y: event.targetTouches[0].pageY
               };
 
               // Now we need to get the offset of the canvas location
               var obj = sigCanvas;
 
               if (obj.offsetParent) {
                  // Every time we find a new object, we add its offsetLeft and offsetTop to curleft and curtop.
                  do {
                     coors.x -= obj.offsetLeft;
                     coors.y -= obj.offsetTop;
                  }
				  // The while loop can be "while (obj = obj.offsetParent)" only, which does return null
				  // when null is passed back, but that creates a warning in some editors (i.e. VS2010).
                  while ((obj = obj.offsetParent) != null);
               }
 
               // pass the coordinates to the appropriate handler
               drawer[event.type](coors);
            }
 

            // attach the touchstart, touchmove, touchend event listeners.
            sigCanvas.addEventListener('touchstart', draw, false);
            sigCanvas.addEventListener('touchmove', draw, false);
            sigCanvas.addEventListener('touchend', draw, false);
 
            // prevent elastic scrolling
            sigCanvas.addEventListener('touchmove', function (event) {
               event.preventDefault();
            }, false); 
         }
         else {
 
            // start drawing when the mousedown event fires, and attach handlers to
            // draw a line to wherever the mouse moves to
            $("#canvasSignature").mousedown(function (mouseEvent) {
               var position = getPosition(mouseEvent, sigCanvas);
 
               context.moveTo(position.X, position.Y);
               context.beginPath();
 
               // attach event handlers
               $(this).mousemove(function (mouseEvent) {
                  drawLine(mouseEvent, sigCanvas, context);
               }).mouseup(function (mouseEvent) {
                  finishDrawing(mouseEvent, sigCanvas, context);
               }).mouseout(function (mouseEvent) {
                  finishDrawing(mouseEvent, sigCanvas, context);
               });
            });
 
         }
      }
 
      // draws a line to the x and y coordinates of the mouse event inside
      // the specified element using the specified context
      function drawLine(mouseEvent, sigCanvas, context) {
 
         var position = getPosition(mouseEvent, sigCanvas);
 
         context.lineTo(position.X, position.Y);
         context.stroke();
      }
 
      // draws a line from the last coordiantes in the path to the finishing
      // coordinates and unbind any event handlers which need to be preceded
      // by the mouse down event
      function finishDrawing(mouseEvent, sigCanvas, context) {
         // draw the line to the finishing coordinates
         drawLine(mouseEvent, sigCanvas, context);
 
         context.closePath();
 
         // unbind any events which could draw
         $(sigCanvas).unbind("mousemove")
                     .unbind("mouseup")
                     .unbind("mouseout");
      }
	  
	  function resetFirma(){
		let ctx = canvasSignature.getContext('2d');
		ctx.clearRect(0, 0, canvasSignature.width, canvasSignature.height);
		dibujarLinea = false;
	
		}
   </script> 
<body class="hold-transition skin-blue sidebar-mini">
<div class="loader"><i class="fa fa-circle-o-notch fa-spin fa-5x centrarSpinner" aria-hidden="true"></i></div>
<div class="wrapper">
 <?php
 include 'header.php';
 ?>
  <!--   Menu Lateral        -->
 <?php
include 'menu.php';
 ?>
  <!-- Left side column. contains the logo and sidebar -->
  
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Servicios
		 
        <small><?php if($accion=="E"){echo "Listado General de Servicios"; } if($accion=="N"){echo "Ingreso de Ordenes";} ?></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Servicio Técnico</a></li>
        <li class="active"><?php if($accion=="E"){echo "Servicio"; } if($accion=="N"){echo "Ordenes";} ?></li>
      </ol>
    </section>
	<!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-info">
            <div class="box-header">
              <h3 class="box-title"><?php if($accion=="E"){echo "Edición"; } if($accion=="N"){echo "Ingreso";} ?>
              </h3>
              <!-- tools box -->
              <div class="pull-right box-tools">
                <!--button type="button" class="btn btn-info btn-sm" data-widget="collapse" data-toggle="tooltip"
                        title="Collapse">
                  <i class="fa fa-minus"></i></button-->
                <!--button type="button" class="btn btn-info btn-sm" data-widget="remove" data-toggle="tooltip"
                        title="Remove">
                  <i class="fa fa-times"></i></button-->
              </div>
              <!-- /. tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body pad">
			<!-- Texto -->
			
			<form action="../mpdf/examples/ingrtec.php" method="post" enctype="multipart/form-data" class="form-horizontal form-label-left" name="serviciosform" id="serviciosform" target="_Blank">
				<h3>Canvas test</h3>
					<div class="form-group">
						<div id="canvasDiv" class="col-md-4 col-sm-4 col-xs-12">
							<!-- It's bad practice (to me) to put your CSS here.  I'd recommend the use of a CSS file! -->
							<canvas id="canvasSignature" width="300px" height="150" style="border:2px solid #000000;"></canvas>
							<button class="btn btn-primary" type="button" name="clear" onclick="resetFirma();">Limpiar</button>
						</div>
					
					</div>
					
			</form>
   
			</div>
		</div>
	</div>
</div>
</section>

  </div>
 <?php include 'modals.php'; ?>
    <?php include 'footer.php'; ?>

  <!-- Control Sidebar -->
 <?php
 include 'sidebar.php';
 ?>
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->
<?php include 'scripts.html'; ?>
</body>
</html> 
<?php 
	
}else{
	
		$ids= isset($_GET['IDS']) ? $_GET['IDS'] : "0";    
		if($ids>0){
			header("Location: login.php?F=ordenes&IDS=".$ids."");
		  }else{
			header("Location: login.php"); 
		  }
  }
?>