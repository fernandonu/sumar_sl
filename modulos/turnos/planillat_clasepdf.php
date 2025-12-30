<?php
define('FPDF_FONTPATH','font/');
require('../../lib/fpdf.php'); 
class orden_compra extends FPDF
{
	var $base1;
	var $base2;
	var $cant;
	var $flag;

function asignar_base1($x) {
	 $this->base1=$x;
}

function recuperar_base1(){
	return $this->base1;
}

function asignar_base2($x) {
	 $this->base2=$x;
}

function recuperar_base2(){
	return $this->base2;
}

function Header(){
	global $efector_nombre,$direccion,$fecha,$provincia,$zona;
	$this->cant=0;
	$this->flag=1;
	//Inicializo las bases:
	//$this->Image('../../imagenes/frente.jpg',210,4,175);
	$this->asignar_base1(35);  
	$this->setxy(15,$this->recuperar_base1());
	$this->SetFont('Arial','B',10);
	$this->setxy(15,$this->recuperar_base1()+3);
	$this->cell(150,"Programa Atencion Primaria para la Salud",C,0,1);	
	$this->setxy(130,$this->recuperar_base1()+10);
	$this->cell(15,6," Fecha: $fecha");
	$this->setxy(15,$this->recuperar_base1()+24);
	$this->multicell(145,5,"$efector_nombre, $direccion");

	$this->setxy(15,$this->recuperar_base1()+50);
	/*$this->multicell(175,5,"    texto abierto                   
Detalle de Facturas: ");	*/
	//$this->line(60,$this->recuperar_base1()+95,185,$this->recuperar_base1()+95);

	$this->SetFont('Arial','B',10);
	$this->setxy(15,$this->recuperar_base1()+110);
	$this->MultiCell(10,3,"Tipo y Nro de Documento",1,1,'C');
	$this->setxy(135,$this->recuperar_base1()+110);
	$this->cell(21,6,"Apellido y Nombre",1,1,'C');	
	$this->setxy(156,$this->recuperar_base1()+110);
	$this->MultiCell(25,6,"Edad",1,1,'C');	


	$this->asignar_base2(190); 

		$this->Ln(20);	
	$this->SetAutoPageBreak(2);
}
	
function paciente($t_doc, $ape_nom, $domicilio, $inicio, $sexo, $fecha_nacimiento){
	$nro_pixeles_posibles=($res+$cant_nl)*6;
	if(($this->recuperar_base2()+ $nro_pixeles_posibles)>270)
	{$this->SetAutoPageBreak(30);
		$this->AddPage();
		$this->asignar_base2(145);
		$this->flag=0;
	 $this->SetAutoPageBreak(30);	
	}
	$this->setxy(10,$this->recuperar_base2());
	$this->SetFont('Arial','B',9);

	$this->setxy(15,$this->recuperar_base2()+6);
			$y_inicial=$this->GetY();
	$this->cell(120,6,"$t_doc",1,1,'C');
		$y_posterior=$this->GetY();
	$this->setxy(135,$this->recuperar_base2()+6);
	$this->cell(21,6,"$ape_nom",1,1,'C');	
	$this->setxy(156,$this->recuperar_base2()+6);
	$this->cell(25,6,$domicilio,1,1,'R');	


	$aux=$this->recuperar_base2() + ($y_posterior-$y_inicial);
	$this->asignar_base2($aux);
	$this->cont++;
				
}

function dibujar_planilla() {
		
	$this->Open();
	$this->AliasNbPages();
	$this->AddPage();
	$this->SetAutoPageBreak(0);
	$this->AliasNbPages("total_pag");
	$this->asignar_base1(60);
	
	$this->asignar_base2(145);
	
} //de la funcion dibujar_planilla

function nro_orden_compra($nro) {
	
	$this->SetFont('Arial','B',14);
    $this->setxy(23,23);
	$this->cell(20,5,$nro,1);
		
}

function nro_orden_compra1($nro) {
	
	$this->SetFont('Arial','B',14);
    $this->setxy(75,23);
	$this->cell(19,5,'',1);
		
}
function guardar_servidor($string) {
 $this->output("$string",true,false);                                                   
}//fin de funcion

function _final($obs) {

	$this->SetFont('Arial','B',10);
	$this->recuperar_base2();
	$this->setxy(15,$this->recuperar_base2()+10);
	if($obs!= ''){
	$this->MultiCell(170,6,"Se adjunta la siguiente documentacion: $obs",´´,'L');	
	
	}

}	
   
function Footer()
{
 //ir a 1.2 cm del final de la hoja
    $this->SetY(-12);
    //letra italica
    $this->SetFont('Arial','I',8);
    //imprime nro de pagina y total de paginas 
    $this->Cell(0,10,' Pagina Nro'. $this->PageNo().' /total_pag',0,0,'C');
	
}   
                               
}//fin de la clase
?>