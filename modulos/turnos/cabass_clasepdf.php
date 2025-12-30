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

function envia_declaracion($nro_cabos, $f_atencion, $nom_hopital, $codigo_hpgd, $nom_ape, $tipo_dni, $dni, $t_benef,  $parentesco, $sexo, $edad, $t_atencion, $n_servico,$f_atencion, $nom_financer, $code,$nro_titular, $nro_benef , $l_trabajo, $furs, $d_trabajo, $cuit_trabajo ,$diag ){
	
	//Inicializo las bases:
	$this->asignar_base1(0.5);  
	
	$this->asignar_base2(0.7);
	$this->setxy(1,$this->recuperar_base1());

	$this->SetFont('Arial','B',9);
	//----------------encabesado***-------------------------------------------------------------------------------
	$this->setxy(0.7,$this->recuperar_base1()+0.25);
	$this->cell(0.14,0.2,"ANEXO II ");	
	$this->setxy(11.5,$this->recuperar_base1()+0.25);
	$this->cell(10,0.2,"Nº: $nro_cabos");	
	$this->SetLineWidth(0.03);
	$this->line(0.7,$this->recuperar_base1()+3,0.7,$this->recuperar_base1()+0.5);//linea Izquierda vetical
	$this->line(14,$this->recuperar_base1()+3,14,$this->recuperar_base1()+0.5);//linea derecha vetical
	$this->line(0.7,$this->recuperar_base1()+0.5,14,$this->recuperar_base1()+0.5);// linea larga horizontal superior
	$this->line(0.7,$this->recuperar_base1()+1.5,14,$this->recuperar_base1()+1.5);// linea larga horizontal inferior
	$this->setxy(0.7,$this->recuperar_base1()+0.7);
	$this->multicell(9,0.35,"COMPROBANTE DE ATENCION DE BENEFICIARIOS DE AGENTES DEL SEGURO DE SALUD",0,'C',0,0);
	$this->line(11,$this->recuperar_base1()+2.78,11,$this->recuperar_base1()+0.5);//linea derecha vetical
	$this->setxy(11.5,$this->recuperar_base1()+0.6);
	$this->multicell(2,0.35,"Fecha  $f_atencion",0,'C',0,0);
	
//---------------DATOS DE HOSPITAL--------------------------------------------------------------------------------------	

	$this->setxy(0.7,$this->recuperar_base1()+2);
	$this->cell(9,0.35,"$nom_hopital");
	$this->setxy(11,$this->recuperar_base1()+1.55);
	$this->multicell(3,0.35,"Codigo RPdGD-REFES $codigo_hpgd",0,'C',0,0);
	
//****************DATOS DEL BENEFICIARIO Titulo*****************************************************************
	$this->line(0.7,$this->recuperar_base1()+2.75,14,$this->recuperar_base1()+2.75);//linea Izquierda vetical
	$this->line(14,$this->recuperar_base1()+2.75,14,$this->recuperar_base1()+3.2);//linea derecha vetical
	$this->line(0.7,$this->recuperar_base1()+2.75,0.7,$this->recuperar_base1()+3.2);// linea larga horizontal superior
	$this->line(0.7,$this->recuperar_base1()+3.2,14,$this->recuperar_base1()+3.2);// linea larga horizontal inferior
	$this->setxy(0.6,$this->recuperar_base1()+2);
	$this->cell(14,2,"Datos del Beneficiario",0,1,'C');
	
//------------------SUBTITULO------1---------------------------------------------	
	$this->setxy(0.7,$this->recuperar_base1()+3.2);
	$this->cell(6.5,0.4,"Apellido y Nombre",1,1,'C');
	$this->setxy(7.2,$this->recuperar_base1()+3.2);
	$this->cell(3.3,0.4,"Tipo Doc",1,1,'C');
	$this->setxy(10.5,$this->recuperar_base1()+3.2);
	$this->cell(3.5,0.4,"Nº Documento",1,1,'C');
	//---------------------------1------------------fin titulo--datos a mostras------------------	
	$nom_ape=strtoupper($nom_ape);
	$this->SetFont('Arial','I',8);
	$this->setxy(0.7,$this->recuperar_base1()+3.6);
	$this->cell(6.5,0.4,"$nom_ape",1,1,'L');
	$this->setxy(7.2,$this->recuperar_base1()+3.6);
	$this->cell(3.3,0.4,"$tipo_dni ",1,1,'L');
	$this->setxy(10.5,$this->recuperar_base1()+3.6);
	$this->cell(3.5,0.4,"$dni",1,1,'L');
	
	//----------------------titulo 2------
	$this->SetFont('Arial','B',8);
	$this->setxy(0.7,$this->recuperar_base1()+4);
	$this->cell(3.15,0.4,"Tipo de Beneficiario",1,1,'C');
	$this->setxy(3.85,$this->recuperar_base1()+4);
	$this->cell(3.36,0.4,"Parentesco",1,1,'C');
	$this->setxy(7.20,$this->recuperar_base1()+4);
	$this->cell(3.3,0.4,"Sexo",1,1,'C');
	$this->setxy(10.5,$this->recuperar_base1()+4);
	$this->cell(3.5,0.4,"Edad",1,1,'C');
	
	//----------------------fin titulo 2------
	
	$this->SetFont('Arial','I',8);
	$this->setxy(0.7,$this->recuperar_base1()+4.4);
	$this->cell(3.15,0.4,"$t_benef",1,1,'L');
	$this->setxy(3.85,$this->recuperar_base1()+4.4);
	if($parentesco==0)$parentesco='';
	$this->cell(3.36,0.4,"$parentesco",1,1,'L');
	$this->setxy(7.20,$this->recuperar_base1()+4.4);	
	$this->cell(3.3,0.4,"$sexo",1,1,'L');	
	$this->setxy(10.5,$this->recuperar_base1()+4.4);
	$this->cell(3.5,0.4,"$edad ",1,1,'L');	
	
//****************DATOS tipo e Atencion titlo*****************************************************************
	$this->SetFont('Arial','B',8);
	$this->setxy(0.7,$this->recuperar_base1()+5);
	$this->cell(11.3,0.5,"Tipo de Atencion",1,1,'C');
	$this->setxy(12,$this->recuperar_base1()+5);
	$this->cell(2,0.5,"Fecha",1,1);
//------------------SUBTITULO------1---------------------------------------------	
	$this->SetFont('Arial','B',8);	//$t_atencion
	$this->setxy(0.7,$this->recuperar_base1()+5.5);
	$this->cell(1.7,1,"Consulta",1,1);
	$this->setxy(0.7,$this->recuperar_base1()+6.5);
	$this->cell(1.7,1,"Practica",1,1);
	$this->setxy(0.7,$this->recuperar_base1()+7.5);
	$this->cell(1.7,1,"Internacion",1,1);
	//-----------------------------------------
	if($t_atencion=='Consulta')$t_a1='X';elseif($t_atencion=='Practica')$t_a2='X';elseif($t_atencion=='Internacion')$t_a3='X';
	$this->setxy(2.4,$this->recuperar_base1()+5.5);
	$this->cell(0.5,1,"$t_a1",1,1);
	$this->setxy(2.4,$this->recuperar_base1()+6.5);
	$this->cell(0.5,1,"$t_a2",1,1);
	$this->setxy(2.4,$this->recuperar_base1()+7.5);
	$this->cell(0.5,1,"$t_a3",1,1);
	//----------------------------------------------
	$this->SetFont('Arial','B',7);	
	$this->setxy(2.9,$this->recuperar_base1()+5.5);
	$this->cell(1.7,0.5,"Especialidad",1,1);

	$this->setxy(2.9,$this->recuperar_base1()+6);
	$this->cell(1.7,0.5,"Diagnostico",1,1);
	$this->setxy(2.9,$this->recuperar_base1()+6.5);
	//$this->line(4.6,$this->recuperar_base1()+6.9,4.6,$this->recuperar_base1()+6);//linea derecha vetical
	$this->multicell(1.7,0.65,"Codigos NHPdGD",1,1);
	$this->SetFont('Arial','B',7);
	$this->setxy(2.9,$this->recuperar_base1()+7.8);
	$this->multicell(2.3,0.35,"Diagnostico de Egresos CIE 10",1,1,'C');
		$this->setxy(5.2,$this->recuperar_base1()+7.8);
		$this->multicell(1.5,0.35,"Codigo Principal",1,1,'C');
		$this->setxy(6.7,$this->recuperar_base1()+7.8);
		$this->cell(1.5,0.7,"",1,1);
		$this->setxy(8.2,$this->recuperar_base1()+7.8);
		$this->multicell(1.5,0.35,"Otros Codigos",1,1,'C');
		$this->setxy(9.7,$this->recuperar_base1()+7.8);
		$this->cell(1.4,0.7,"",1,1);
		$this->setxy(11.1,$this->recuperar_base1()+7.8);
		$this->cell(1.4,0.7,"",1,1);
		$this->setxy(12.5,$this->recuperar_base1()+7.8);
		$this->cell(1.5,0.7,"",1,1);

	//----------------------------------------------
	$this->SetFont('Arial','I',8);	
	$this->setxy(4.6,$this->recuperar_base1()+5.5);
	$this->cell(7.425,0.5,"$n_servico",1,1);
	$this->setxy(12,$this->recuperar_base1()+5.5);
	$this->cell(2,0.5,"$f_atencion",1,1);
	$this->setxy(4.6,$this->recuperar_base1()+6);
	$this->cell(9.4,0.5,"$diag",1,1);
	$this->setxy(4.6,$this->recuperar_base1()+6.5);
	$this->cell(9.4,1.3,"",1,1);
	$this->SetLineWidth(0.01);
//$this->line(6,$this->recuperar_base1()+7.8,6,$this->recuperar_base1()+6.5);//linea derecha vetical
	//$this->line(7.5,$this->recuperar_base1()+7.8,7.5,$this->recuperar_base1()+6.5);//linea derecha vetical
	//$this->line(9,$this->recuperar_base1()+7.8,9,$this->recuperar_base1()+6.5);//linea derecha vetical
//	$this->line(10.5,$this->recuperar_base1()+7.8,10,$this->recuperar_base1()+6.5);//linea derecha vetical
//	$this->line(12,$this->recuperar_base1()+7.8,12,$this->recuperar_base1()+6.5);//linea derecha vetical
	$this->SetLineWidth(0.03);

	$this->setxy(0.7,$this->recuperar_base1()+8.6);
	$this->cell(13.3,4,"",1,1);
	$this->SetFont('Arial','I',5.8);	
	$this->setxy(0.6,$this->recuperar_base1()+8.6);
	$this->multicell(9,0.3,"Cod RNHPGD: Codigo de Registro Nacional de Hospital Publico de Gestion Descentralizada - CIE 10: Clasificacion Nacional de Enfermedades",0,0,'');
 
  $this->setxy(9.5,$this->recuperar_base1()+8.6);
  $this->multicell(4,0.3,"Fecha Ultimo Recibo de Sueldo 
                   $furs",0,0,'C');
 $this->setxy(10,$this->recuperar_base1()+8.6);
 $this->multicell(4,0.3,"

                  ",1,1,'C');

	 $this->SetFont('Arial','I',5.8);
	$this->setxy(0.7,$this->recuperar_base1()+9.8);
	$this->cell(13.3,4.5,"Firma del medico y sello con codigo de matricula",0,0,'C');
	$this->setxy(0.7,$this->recuperar_base1()+9.8);
	$this->cell(21,4.5,"(Ley 110/2004)",0,0,'C');
//-----------------------------------------------------
	$this->SetFont('Arial','B',8);
	$this->setxy(0.7,$this->recuperar_base1()+12.8);
	$this->cell(10.3,0.5,"NOMBRE DE AGENTE DEL SEGURO DE SALUD: Nombre Completo",1,1,'C');
	$this->setxy(11,$this->recuperar_base1()+12.8);
	$this->cell(3,0.5,"Codigo",1,1,'C');
	$this->SetFont('Arial','I',8);
	$this->setxy(0.7,$this->recuperar_base1()+13.3);
	$this->multiCell(9.5,0.5,"$nom_financer");
	$this->setxy(0.7,$this->recuperar_base1()+13.3);	
	$this->Cell(10.3,1,"",1,1,'C');
	$this->setxy(11,$this->recuperar_base1()+13.3);
	$this->Cell(3,1,"",1,1,'C');
	$this->setxy(11,$this->recuperar_base1()+13.3);
	$this->cell(3,0.5,"$code",0,0,'C');	
	
//----------------------------------------------------------
	$this->SetFont('Arial','B',7);
	$this->setxy(0.7,$this->recuperar_base1()+14.5);		
	$this->multiCell(4.43,0.5,"Firma Responsable Administrativo / Contable",1,'C',0);
	$this->setxy(0.7,$this->recuperar_base1()+15.5);		
	$this->Cell(4.43,1.9,"",1,1,'C');//4.43
	$this->setxy(5.13,$this->recuperar_base1()+14.5);	
	$this->Cell(4.43,1,"Aclaracion de Firma",1,1,'C');
	$this->setxy(5.13,$this->recuperar_base1()+15.5);		
	$this->Cell(4.43,1.9,"",1,1,'C');
	$this->setxy(9.56,$this->recuperar_base1()+14.5);	
	$this->Cell(4.43,1,"Firma del Beneficiario",1,1,'C');		
	$this->setxy(9.56,$this->recuperar_base1()+15.5);		
	$this->Cell(4.43,1.9,"",1,1,'C');
	$this->SetFont('Arial','B',6);

//************************          COPIA DE ANEXO II     *******^********************************
	
$this->asignar_base1(0.5);  
	
	$this->asignar_base2(0.7);
	$this->setxy(1,$this->recuperar_base1());

	$this->SetFont('Arial','B',9);
	//----------------encabesado***-------------------------------------------------------------------------------

	///
	$this->setxy(15,$this->recuperar_base1()+0.25);
	$this->cell(15,0.2,"ANEXO II ");	
	$this->setxy(25.8,$this->recuperar_base1()+0.25);
	$this->cell(10,0.2,"Nº: $nro_cabos");	
	$this->SetLineWidth(0.03);
	$this->line(15,$this->recuperar_base1()+3,15,$this->recuperar_base1()+0.5);//linea Izquierda vetical
	$this->line(28.3,$this->recuperar_base1()+3,28.3,$this->recuperar_base1()+0.5);//linea derecha vetical
	$this->line(15,$this->recuperar_base1()+0.5,28.3,$this->recuperar_base1()+0.5);// linea larga horizontal superior
	$this->line(15,$this->recuperar_base1()+1.5,28.3,$this->recuperar_base1()+1.5);// linea larga horizontal inferior
	$this->setxy(15,$this->recuperar_base1()+0.7);
	$this->multicell(9,0.35,"COMPROBANTE DE ATENCION DE BENEFICIARIOS DE AGENTES DEL SEGURO DE SALUD",0,'C',0,0);
	$this->line(25.4,$this->recuperar_base1()+2.78,25.4,$this->recuperar_base1()+0.5);//linea derecha vetical
	$this->setxy(25.9,$this->recuperar_base1()+0.6);
	$this->multicell(2,0.35,"Fecha  $f_atencion",0,'C',0,0);
//---------------DATOS DE HOSPITAL--------------------------------------------------------------------------------------	

	$this->setxy(15,$this->recuperar_base1()+2);
	$this->multicell(9,0.35,"$nom_hopital");
	$this->setxy(25.5,$this->recuperar_base1()+1.55);
	$this->multicell(3,0.35,"Codigo RPdGD-REFES $codigo_hpgd",0,'C',0,0);
	
//****************DATOS DEL BENEFICIARIO Titulo*****************************************************************
	$this->line(15,$this->recuperar_base1()+2.75,28.3,$this->recuperar_base1()+2.75);//linea Izquierda vetical
	$this->line(28.3,$this->recuperar_base1()+2.75,28.3,$this->recuperar_base1()+3.2);//linea derecha vetical
	$this->line(15,$this->recuperar_base1()+2.75,15,$this->recuperar_base1()+3.2);// linea larga horizontal superior
	$this->line(15,$this->recuperar_base1()+3.2,28.3,$this->recuperar_base1()+3.2);// linea larga horizontal inferior
	$this->setxy(15,$this->recuperar_base1()+2);
	$this->cell(14,2,"Datos del Beneficiario",0,1,'C');
	
//------------------SUBTITULO------1---------------------------------------------	
	$this->setxy(15,$this->recuperar_base1()+3.2);
	$this->cell(6.5,0.4,"Apellido y Nombre",1,1,'C');
	$this->setxy(21.5,$this->recuperar_base1()+3.2);
	$this->cell(3.3,0.4,"Tipo Doc",1,1,'C');
	$this->setxy(24.8,$this->recuperar_base1()+3.2);
	$this->cell(3.5,0.4,"Nº Documento",1,1,'C');
	//---------------------------1------------------fin titulo--datos a mostras------------------	
	$nom_ape=strtoupper($nom_ape);
	$this->SetFont('Arial','I',8);
	$this->setxy(15,$this->recuperar_base1()+3.6);
	$this->cell(6.5,0.4,"$nom_ape",1,1,'L');
	$this->setxy(21.5,$this->recuperar_base1()+3.6);
	$this->cell(3.3,0.4,"$tipo_dni ",1,1,'L');
	$this->setxy(24.8,$this->recuperar_base1()+3.6);
	$this->cell(3.5,0.4,"$dni",1,1,'L');
	
	//----------------------titulo 2------
	$this->SetFont('Arial','B',8);
	$this->setxy(15,$this->recuperar_base1()+4);
	$this->cell(3.15,0.4,"Tipo de Beneficiario",1,1,'C');
	$this->setxy(18.13,$this->recuperar_base1()+4);
	$this->cell(3.36,0.4,"Parentesco",1,1,'C');
	$this->setxy(21.5,$this->recuperar_base1()+4);
	$this->cell(3.3,0.4,"Sexo",1,1,'C');
	$this->setxy(24.8,$this->recuperar_base1()+4);
	$this->cell(3.5,0.4,"Edad",1,1,'C');
	
	//----------------------fin titulo 2------
	if($sexo=='1')$sexo='FEMENINO';elseif($sexo=='2')$sexo='MASCULINO';else$sexo='';
	$this->SetFont('Arial','I',8);
	$this->setxy(15,$this->recuperar_base1()+4.4);
	$this->cell(3.15,0.4,"$t_benef",1,1,'L');
	$this->setxy(18.13,$this->recuperar_base1()+4.4);
	if($parentesco==0)$parentesco='';
	$this->cell(3.36,0.4,"$parentesco",1,1,'L');
	$this->setxy(21.5,$this->recuperar_base1()+4.4);	
	$this->cell(3.3,0.4,"$sexo",1,1,'L');	
	$this->setxy(24.8,$this->recuperar_base1()+4.4);
	$this->cell(3.5,0.4,"$edad ",1,1,'L');	
	
//****************DATOS tipo e Atencion titlo*****************************************************************
	$this->SetFont('Arial','B',8);
	$this->setxy(15,$this->recuperar_base1()+5);
	$this->cell(11.3,0.5,"Tipo de Atencion",1,1,'C');
	$this->setxy(26.3,$this->recuperar_base1()+5);
	$this->cell(2,0.5,"Fecha",1,1);
//------------------SUBTITULO------1---------------------------------------------	
	$this->SetFont('Arial','B',8);	//$t_atencion
	$this->setxy(15,$this->recuperar_base1()+5.5);
	$this->cell(1.7,1,"Consulta",1,1);
	$this->setxy(15,$this->recuperar_base1()+6.5);
	$this->cell(1.7,1,"Practica",1,1);
	$this->setxy(15,$this->recuperar_base1()+7.5);
	$this->cell(1.7,1,"Internacion",1,1);
	//-----------------------------------------
	if($t_atencion=='Consulta')$t_a1='X';elseif($t_atencion=='Practica')$t_a2='X';elseif($t_atencion=='Internacion')$t_a3='X';
	$this->setxy(16.7,$this->recuperar_base1()+5.5);
	$this->cell(0.5,1,"$t_a1",1,1);
	$this->setxy(16.7,$this->recuperar_base1()+6.5);
	$this->cell(0.5,1,"$t_a2",1,1);
	$this->setxy(16.7,$this->recuperar_base1()+7.5);
	$this->cell(0.5,1,"$t_a3",1,1);
	//----------------------------------------------
	$this->SetFont('Arial','B',7);	
	$this->setxy(17.2,$this->recuperar_base1()+5.5);
	$this->cell(1.7,0.5,"Especialidad",1,1);

	$this->setxy(17.2,$this->recuperar_base1()+6);
	$this->cell(1.7,0.5,"Diagnostico",1,1);
	$this->setxy(17.2,$this->recuperar_base1()+6.5);
	//$this->line(4.6,$this->recuperar_base1()+6.9,4.6,$this->recuperar_base1()+6);//linea derecha vetical
	$this->multicell(1.7,0.65,"Codigos NHPdGD",1,1);
	$this->SetFont('Arial','B',7);
	$this->setxy(17.2,$this->recuperar_base1()+7.8);
	$this->multicell(2.3,0.35,"Diagnostico de Egresos CIE 10",1,1,'C');
		$this->setxy(19.5,$this->recuperar_base1()+7.8);
		$this->multicell(1.5,0.35,"Codigo Principal",1,1,'C');
		$this->setxy(21,$this->recuperar_base1()+7.8);
		$this->cell(1.5,0.7,"",1,1);
		$this->setxy(22.5,$this->recuperar_base1()+7.8);
		$this->multicell(1.5,0.35,"Otros Codigos",1,1,'C');
		$this->setxy(24,$this->recuperar_base1()+7.8);
		$this->cell(1.4,0.7,"",1,1);
		$this->setxy(25.4,$this->recuperar_base1()+7.8);
		$this->cell(1.4,0.7,"",1,1);
		$this->setxy(26.8,$this->recuperar_base1()+7.8);
		$this->cell(1.5,0.7,"",1,1);

	//----------------------------------------------
	$this->SetFont('Arial','I',8);	
	$this->setxy(18.9,$this->recuperar_base1()+5.5);
	$this->cell(7.425,0.5,"$n_servico",1,1);
	$this->setxy(26.3,$this->recuperar_base1()+5.5);
	$this->cell(2,0.5,"$f_atencion",1,1);
	$this->setxy(18.9,$this->recuperar_base1()+6);
	$this->cell(9.4,0.5,"$diag",1,1);
	$this->setxy(18.9,$this->recuperar_base1()+6.5);
	$this->cell(9.4,1.3,"",1,1);
	$this->SetLineWidth(0.01);
	$this->line(21,$this->recuperar_base1()+7.8,21,$this->recuperar_base1()+6.5);//linea derecha vetical
	$this->line(22.5,$this->recuperar_base1()+7.8,22.5,$this->recuperar_base1()+6.5);//linea derecha vetical
	$this->line(24,$this->recuperar_base1()+7.8,24,$this->recuperar_base1()+6.5);//linea derecha vetical
	$this->line(25.5,$this->recuperar_base1()+7.8,25.5,$this->recuperar_base1()+6.5);//linea derecha vetical
	$this->line(27,$this->recuperar_base1()+7.8,27,$this->recuperar_base1()+6.5);//linea derecha vetical
	$this->SetLineWidth(0.03);

	$this->setxy(15,$this->recuperar_base1()+8.6);
	$this->cell(13.3,4,"",1,1);
	$this->SetFont('Arial','I',5.8);	
	$this->setxy(15,$this->recuperar_base1()+8.6);
	$this->multicell(9,0.3,"Cod RNHPGD: Codigo de Registro Nacional de Hospital Publico de Gestion Descentralizada - CIE 10: Clasificacion Nacional de Enfermedades",0,0,'');
  $this->setxy(24,$this->recuperar_base1()+8.6);
  $this->multicell(4,0.3,"Fecha Ultimo Recibo de Sueldo 
                   $furs",0,0,'C');
  $this->setxy(24.3,$this->recuperar_base1()+8.6);
  $this->multicell(4,0.3,"

                  ",1,1,'C');

	$this->SetFont('Arial','B',7);
	$this->setxy(15,$this->recuperar_base1()+9.8);
	$this->cell(13.3,4.5,"Firma del medico y sello con codigo de matricula",0,0,'C');
	$this->setxy(15,$this->recuperar_base1()+9.8);
	$this->cell(21,4.5,"(Ley 110/2004)",0,0,'C');
//-----------------------------------------------------
	$this->SetFont('Arial','B',8);
	$this->setxy(15,$this->recuperar_base1()+12.8);
	$this->cell(10.3,0.5,"NOMBRE DE AGENTE DEL SEGURO DE SALUD: Nombre Completo",1,1,'C');
	$this->setxy(25.3,$this->recuperar_base1()+12.8);
	$this->cell(3,0.5,"Codigo",1,1,'C');
	$this->SetFont('Arial','I',8);
	$this->setxy(15,$this->recuperar_base1()+13.3);
	$this->multiCell(9.5,0.5,"$nom_financer");
	$this->setxy(15,$this->recuperar_base1()+13.3);	
	$this->Cell(10.3,1,"",1,1,'C');
	$this->setxy(25.3,$this->recuperar_base1()+13.3);
	$this->Cell(3,1,"",1,1,'C');
	$this->setxy(25.3,$this->recuperar_base1()+13.3);
	$this->cell(3,0.5,"$code",0,0,'C');	
	
//----------------------------------------------------------
	$this->SetFont('Arial','B',7);
	$this->setxy(15,$this->recuperar_base1()+14.5);		
	$this->multiCell(4.43,0.5,"Firma Responsable Administrativo / Contable",1,'C',0);
	$this->setxy(15,$this->recuperar_base1()+15.5);		
	$this->Cell(4.43,1.9,"",1,1,'C');//4.43
	$this->setxy(19.43,$this->recuperar_base1()+14.5);	
	$this->Cell(4.43,1,"Aclaracion de Firma",1,1,'C');
	$this->setxy(19.43,$this->recuperar_base1()+15.5);		
	$this->Cell(4.43,1.9,"",1,1,'C');
	$this->setxy(23.86,$this->recuperar_base1()+14.5);	
	$this->Cell(4.43,1,"Firma del Beneficiario",1,1,'C');		
	$this->setxy(23.86,$this->recuperar_base1()+15.5);		
	$this->Cell(4.43,1.9,"",1,1,'C');
	$this->SetFont('Arial','B',6);



	$aux=$this->recuperar_base1()+17;
	$this->asignar_base1($aux);  
}

function dibujar_planilla() {
		
	$this->Open();
	$this->AliasNbPages();
	$this->AddPage();
	$this->SetAutoPageBreak(0);
	//$this->AliasNbPages("total_pag");
	$this->asignar_base1(60);
	$this->asignar_base1(70);
//	$this->Image('../../imagenes/encabezado.jpg',15,4,175);
	$this->asignar_base2(100);
	
} //de la funcion dibujar_planilla

function guardar_servidor($string) {
 $this->output("$string",true,false);                                                   
}//fin de funcion

function final_pagina($base1_aux){
	//$this->line(15,$this->recuperar_base1()+70,290,$this->recuperar_base1()+70);
}

function control_pagina($base1_aux){
//Control para que haga un salto de pagina, si el producto generado
	//no entra en la pagina actual.
	
	if(($this->recuperar_base1()+$nro_pixeles_posibles)>120)
	{$this->SetAutoPageBreak(0);
		$this->AddPage();
		$this->asignar_base2(50);
		$this->flag=0;
	 //$this->SetAutoPageBreak(1);	
	}
	return true;
}

function Footer()
{
 //ir a 1.2 cm del final de la hoja
    $this->SetY(-12);
    $this->line(15,$this->recuperar_base2()+235.5,185,$this->recuperar_base2()+235.5);
    //letra italica
  	//$this->SetFont('Arial','I',8);
    //imprime nro de pagina y total de paginas 
   // $this->Cell(0,10,'ANEXO II '.$this->PageNo().'/total_pag',0,0,'C');
	
}   
                               
}//fin de la clase
?>