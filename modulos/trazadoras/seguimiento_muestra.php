<?
require_once ("../../config.php");

extract($_POST,EXTR_SKIP);
if ($parametros) extract($parametros,EXTR_OVERWRITE);

$query="SELECT nacer.efe_conv.nombre as nom_efe,
				nacer.efe_conv.cuie as cuiee,
				uad.beneficiarios.apellido_benef as apellido,
				uad.beneficiarios.nombre_benef as nombre,
				uad.beneficiarios.fecha_nacimiento_benef as fechanac,
				uad.beneficiarios.numero_doc as dni,
				uad.beneficiarios.sexo as sexo,
				trazadoras.seguimiento_remediar.*,
				p.*,
				q.*,
				r.*,
				s.*
				FROM
				trazadoras.seguimiento_remediar
				LEFT JOIN trazadoras.seguimiento_inmunizacion p ON (seguimiento_remediar.id_seguimiento_remediar=p.id_seguimiento)
				LEFT JOIN trazadoras.seguimiento_interconsulta q ON (seguimiento_remediar.id_seguimiento_remediar=q.id_seguimiento)
				LEFT JOIN trazadoras.seguimiento_tratamiento r ON (seguimiento_remediar.id_seguimiento_remediar=r.id_seguimiento)
				LEFT JOIN trazadoras.seguimiento_consejeria s ON (seguimiento_remediar.id_seguimiento_remediar=s.id_seguimiento)
				INNER JOIN nacer.efe_conv ON seguimiento_remediar.efector = nacer.efe_conv.cuie
				INNER JOIN uad.beneficiarios ON seguimiento_remediar.clave_beneficiario = beneficiarios.clave_beneficiario
				where trazadoras.seguimiento_remediar.id_seguimiento_remediar=$id_seguimiento_remediar";

$res_comprobante=sql($query,"<br>Error al traer los comprobantes<br>") or fin_pagina();
	
$afiapellido=$res_comprobante->fields["apellido"];		
$afinombre=$res_comprobante->fields["nombre"];
$afidni=$res_comprobante->fields["dni"];
$afisexo=$res_comprobante->fields["sexo"];
$afifechanac=$res_comprobante->fields["fechanac"];	
	
echo $html_header;
?>


<form name='form1' action='seguimiento_muestra.php' method='POST' >

<table width="95%" cellspacing=0 border=1 bordercolor=#E0E0E0 align="center" bgcolor='<?=$bgcolor_out?>' class="bordes">
 <?/*----------primero datos---------------*/?>
	<tr>
	   <td colspan=9>			      
	      <table width=100% align=center class=bordes>
			<tr id=ma >	
		 		<td >Efector</td>
		 		<td >Medico</td>
		 		<td >Comentario</td>
		 		<td >Fecha Control</td>	 		
		 		<td >Periodo</td>
			</tr>
			<tr>
		 		<td align="center" class="bordes" ><?=$res_comprobante->fields['cuiee'].' - '.$res_comprobante->fields['nom_efe']?></td>
		 		<td align="center" class="bordes" ><?if ($res_comprobante->fields['profesional']!="") echo $res_comprobante->fields['profesional']; else echo "&nbsp"?></td>
		 		<td align="center"  class="bordes"><?if ($res_comprobante->fields['comentario']!="") echo $res_comprobante->fields['comentario']; else echo "&nbsp"?></td>
		 		<td align="center"  class="bordes"><?=fecha($res_comprobante->fields['fecha_comprobante'])?></td>		 		
		 	</tr> 	
	 	
			<?/*----------primero datos---------------*/?> 	
			<tr>
	          <td colspan=9>					  
	           <div id=<?=$id_tabla?> style='display:none'>
	            <table width=100% align=center class=bordes>
				  	<tr id=ma>		                               
					   <td>Peso</td>
					   <td>Talla</td>
					   <td>IMC</td>
					   <td>TA</td>
					   <td>Perim.Abdom.</td>
					                         
				  	</tr>
				  	<tr>
						<td align="center" class="bordes"><?if ($res_comprobante->fields['peso']=="") echo "&nbsp"; else echo $res_comprobante->fields["peso"]?></td>			                                 
						<td align="center" class="bordes"><?if ($res_comprobante->fields['talla']=="") echo "&nbsp"; else echo $res_comprobante->fields["talla"]?></td>
						<td align="center" class="bordes"><?if ($res_comprobante->fields['imc']=="") echo "&nbsp"; else echo$res_comprobante->fields["imc"]?></td>
						<?$ta=$res_comprobante->fields['ta_sist']."/".$res_comprobante->fields['ta_diast'];?>
						<td align="center" class="bordes"><?if ($ta=="") echo "&nbsp";else echo  $ta?></td>
						<td align="center" class="bordes"><?if ($res_comprobante->fields['p_abd']=="") echo "&nbsp"; else echo$res_comprobante->fields["p_abd"]?></td>
								   
					</tr>   
			 	</table>
	          </div>
			</td>
         </tr> 
         <?/*----------segundos datos(gral)---------------*/?> 	
    	 <tr>
		  <td colspan=9>					  
	           <div id=<?=$id_tabla?> style='display:none'>
	            <table width=100% align=center class=bordes>
				<tr id=ma >	
			 		<td >Diabetes</td>	 		
			 		<td >Hipertencion</td>
			 		<td >Tabaquismo</td>
			 		<td >Sedentarismo</td>
			 		<td >Examen de Pie</td>
				</tr>
				<tr>
			 		<td align="center" class="bordes" ><?if ($res_comprobante->fields["dtm2"]!="") echo $res_comprobante->fields["dtm2"]; else echo "&nbsp"?></td>
			 		<td align="center" class="bordes" ><?if ($res_comprobante->fields['hta']!="") echo $res_comprobante->fields['hta']; else echo "&nbsp"?></td>
			 		<td align="center"  class="bordes"><?if ($res_comprobante->fields['tabaquismo']!="") echo $res_comprobante->fields['tabaquismo']; else echo "&nbsp"?></td>
			 		<td align="center"  class="bordes"><?if ($res_comprobante->fields['sedentarismo']!="") echo $res_comprobante->fields['sedentarismo']; else echo "&nbsp"?></td> 	
			 		<td align="center"  class="bordes"><?if ($res_comprobante->fields['examendepie']!="") echo $res_comprobante->fields['examendepie']; else echo "&nbsp"?></td> 	
			 	</tr>  
				</table>
	          </div>
			</td>
         </tr>    
         <?/*----------tercero datos (inmunizaciones)---------------*/?> 	
			<tr>
	          <td colspan=9>					  
	           <div id=<?=$id_tabla?> style='display:none'>
	            <table width=100% align=center class=bordes>
				  	<tr id=ma>		                               
					   <td>Antigripal</td>
					   <td>Neumococo 13</td>
					   <td>Neumococo 23</td>  
					   <td>Doble Adulto</td>    
					   <td>Hepatitis B</td>             
				  	</tr>
				  	<tr>
						<td align="center" class="bordes"><?if ($res_comprobante->fields['antigripal']=="") echo "&nbsp"; else echo $res_comprobante->fields["antigripal"]?></td>			                                 
						<td align="center" class="bordes"><?if ($res_comprobante->fields['neumococo13']=="") echo "&nbsp"; else echo $res_comprobante->fields["neumococo13"]?></td>
						<td align="center" class="bordes"><?if ($res_comprobante->fields['neumococo23']=="") echo "&nbsp"; else echo$res_comprobante->fields["neumococo23"]?></td>
						<td align="center" class="bordes"><?if ($res_comprobante->fields['doble_adulto']=="") echo "&nbsp"; else echo$res_comprobante->fields["doble_adulto"]?></td>
						<td align="center" class="bordes"><?if ($res_comprobante->fields['hepatitis_b']=="") echo "&nbsp"; else echo$res_comprobante->fields["hepatitis_b"]?></td>
					</tr>   
			 	</table>
	          </div>
			</td>
         </tr> 
         <?/*----------segundos datos---------------*/?> 
          <?/*----------tercero datos---------------*/?> 	
		
		<?/*----------Laboratorios---------------*/?> 	
			<tr>
	          <td colspan=9>					  
	           <div id=<?=$id_tabla?> style='display:none'>
	            <table width=100% align=center class=bordes>
				  	<tr id=ma>		                               
					   <td>Glucemia</td>
					   <td>Col.Total</td>
					   <td>HDL</td> 
					   <td>LDL</td>
					   <td>Tags</td>
					   <td>HbA1c</td>
					   <td>Crt</td>
					   <td>IFG</td>
					   <td>Ind.A/C</td>
					   <td>Ind.P/C</td>
					</tr>
				  	<tr>
						<td align="center" class="bordes"><?if (trim($res_comprobante->fields['gluc'])=="") echo "S/D"; else echo $res_comprobante->fields['gluc']?></td>	                                
						<td align="center" class="bordes"><?if (trim($res_comprobante->fields['col_tot'])=="") echo "S/D"; else echo $res_comprobante->fields["col_tot"]?></td>
						<td align="center" class="bordes"><?if (trim($res_comprobante->fields['hdl'])=="") echo "S/D"; else echo $res_comprobante->fields["hdl"]?></td>
						<td align="center" class="bordes"><?if (trim($res_comprobante->fields['ldl'])=="") echo "S/D"; else echo $res_comprobante->fields["ldl"]?></td>
						<td align="center" class="bordes"><?if (trim($res_comprobante->fields['tags'])=="") echo "S/D"; else echo $res_comprobante->fields["tags"]?></td>
						<td align="center" class="bordes"><?if (trim($res_comprobante->fields['hba1c'])=="") echo "S/D"; else echo $res_comprobante->fields["hba1c"]?></td>
						<td align="center" class="bordes"><?if (trim($res_comprobante->fields['creatininemia'])=="") echo "S/D"; else echo$res_comprobante->fields["creatininemia"]?></td>
						<td align="center" class="bordes"><?if (trim($res_comprobante->fields['ifg'])=="") echo "S/D"; else echo $res_comprobante->fields["ifg"]?></td>
						<td align="center" class="bordes"><?if (trim($res_comprobante->fields['indice_ac'])=="") echo "S/D"; else echo $res_comprobante->fields["indice_ac"]?></td>
						<td align="center" class="bordes"><?if (trim($res_comprobante->fields['indice_pc'])=="") echo "S/D"; else echo $res_comprobante->fields["indice_pc"]?></td>
					</tr>   
			 	</table>
	          </div>
			</td>
         </tr> 
         	
			<tr>
	          <td colspan=9>					  
	           <div id=<?=$id_tabla?> style='display:none'>
	            <table width=100% align=center class=bordes>
				  	<tr id=ma>		                               
					   <td>Ecocardiograma</td>
					   <td>ECG</td>  
					   <td>fondo de Ojo</td>
					   <td>Estudio Urinario</td>              
				  	</tr>
				  	<tr>
						<td align="center" class="bordes"><?if ($res_comprobante->fields['ecocardiograma']=="") echo "&nbsp"; else echo $res_comprobante->fields["ecocardiograma"]?></td>
						<td align="center" class="bordes"><?if ($res_comprobante->fields['ecg']=="") echo "&nbsp"; else echo $res_comprobante->fields["ecg"]?></td>
						<td align="center" class="bordes"><?if ($res_comprobante->fields['fondodeojo']=="") echo "&nbsp"; else echo $res_comprobante->fields["fondodeojo"]?></td>                  
						<td align="center" class="bordes"><?if ($res_comprobante->fields['estudiourinario']=="") echo "&nbsp"; else echo $res_comprobante->fields["estudiourinario"]?></td>
					</tr>   
			 	</table>
	          </div>
			</td>
         </tr> 
         <?/*----------segundos datos---------------*/?>    
          <?/*----------tercero datos---------------*/?> 	
			
			<?/*----------Interconsulta---------------*/?>
			<tr>
	          <td colspan=9>					  
	           <div id=<?=$id_tabla?> style='display:none'>
	            <table width=100% align=center class=bordes>
				  	<tr id=ma>		                               
					   <td>Oftalmologia</td>
					   <td>Cardiologia</td>  
					   <td>Nefrologia</td>
					   <td>Laboratorio</td>
					   <td>Fonoaudiologia</td>
					   <td>Psicologia</td>
					   <td>Kinesiologia</td>
					   <td>Activ.Fis.Adapt.</td>
					   <td>Nutricion</td>
					   <td>Odontologia</td>
					   <td>Psiquiatria</td>
					   <td>Farmacia</td>              
				  	</tr>
				  	<tr>
						<td align="center" class="bordes"><?if ($res_comprobante->fields['oftalmologia']=="") echo "&nbsp"; else echo $res_comprobante->fields["oftalmologia"]?></td>	              
						<td align="center" class="bordes"><?if ($res_comprobante->fields['cardiologia']=="") echo "&nbsp"; else echo $res_comprobante->fields["cardiologia"]?></td>
						<td align="center" class="bordes"><?if ($res_comprobante->fields['nefrologia']=="") echo "&nbsp"; else echo $res_comprobante->fields["nefrologia"]?></td>	              
						<td align="center" class="bordes"><?if ($res_comprobante->fields['laboratorio']=="") echo "&nbsp"; else echo $res_comprobante->fields["laboratorio"]?></td>
						<td align="center" class="bordes"><?if ($res_comprobante->fields['fonoaudiologia']=="") echo "&nbsp"; else echo $res_comprobante->fields["fonoaudiologia"]?></td>	              
						<td align="center" class="bordes"><?if ($res_comprobante->fields['psicologia']=="") echo "&nbsp"; else echo $res_comprobante->fields["psicologia"]?></td>
						<td align="center" class="bordes"><?if ($res_comprobante->fields['kinesiologia']=="") echo "&nbsp"; else echo $res_comprobante->fields["kinesiologia"]?></td>	              
						<td align="center" class="bordes"><?if ($res_comprobante->fields['activ_fis_adapt']=="") echo "&nbsp"; else echo $res_comprobante->fields["activ_fis_adapt"]?></td>
						<td align="center" class="bordes"><?if ($res_comprobante->fields['nutricion']=="") echo "&nbsp"; else echo $res_comprobante->fields["nutricion"]?></td>	              
						<td align="center" class="bordes"><?if ($res_comprobante->fields['odontologia']=="") echo "&nbsp"; else echo $res_comprobante->fields["odontologia"]?></td>
						<td align="center" class="bordes"><?if ($res_comprobante->fields['psiquiatria']=="") echo "&nbsp"; else echo $res_comprobante->fields["psiquiatria"]?></td>	              
						<td align="center" class="bordes"><?if ($res_comprobante->fields['farmacia']=="") echo "&nbsp"; else echo $res_comprobante->fields["farmacia"]?></td>
					</tr>   
			 	</table>
	          </div>
			</td>
         </tr> 
         <?/*----------segundos datos---------------*/?>       
          	
			<tr>
	          <td colspan=9>					  
	           <div id=<?=$id_tabla?> style='display:none'>
	            <table width=100% align=center class=bordes>
				  	<tr id=ma>		                               
					   <td>Tratamientos</td>
					  </tr>   
			 	</table>
	          </div>
			</td>
         </tr> 
          <?/*----------tercero datos---------------*/?> 

          <?/*----------Tratamientos---------------*/?>	
			<tr>
	          <td colspan=9>					  
	           <div id=<?=$id_tabla?> style='display:none'>
	            <table width=100% align=center class=bordes>
				  	<tr id=ma>		                               
					   <td>Enalapril</td>
					   <td>Losartán</td>
					   <td>Amlodipina</td>      
					   <td>Atenolol</td>
					   <td>Hidroclorotiazida</td>
					   <td>Estatina</td>     
					   <td>AAS</td>     
					   <td>Hipoglucemiante Oral</td>
					   <td>Insulina</td>
					   <td>Metfomina</td>

				  	</tr>
				  	<tr>
						<td align="center" class="bordes"><?if ($res_comprobante->fields['enalapril']=="") echo "&nbsp"; else echo $res_comprobante->fields["enalapril"]?></td>
						<td align="center" class="bordes"><?if ($res_comprobante->fields['losartan']=="") echo "&nbsp"; else echo $res_comprobante->fields["losartan"]?></td>
						<td align="center" class="bordes"><?if ($res_comprobante->fields['amlodipina']=="") echo "&nbsp"; else echo $res_comprobante->fields["amlodipina"]?></td>
						<td align="center" class="bordes"><?if ($res_comprobante->fields['atenolol']=="") echo "&nbsp"; else echo $res_comprobante->fields["atenolol"]?></td>
						<td align="center" class="bordes"><?if ($res_comprobante->fields['hidroclorotiazida']=="") echo "&nbsp"; else echo $res_comprobante->fields["hidroclorotiazida"]?></td>
						<td align="center" class="bordes"><?if ($res_comprobante->fields['estatina']=="") echo "&nbsp"; else echo $res_comprobante->fields["estatina"]?></td>
						<td align="center" class="bordes"><?if ($res_comprobante->fields['ass']=="") echo "&nbsp"; else echo $res_comprobante->fields["ass"]?></td>
						<td align="center" class="bordes"><?if ($res_comprobante->fields['hipoglusemiante_oral']=="") echo "&nbsp"; else echo $res_comprobante->fields["hipoglusemiante_oral"]?></td>
						<td align="center" class="bordes"><?if ($res_comprobante->fields['insulina']=="") echo "&nbsp"; else echo $res_comprobante->fields["insulina"]?></td>
						<td align="center" class="bordes"><?if ($res_comprobante->fields['metformina']=="") echo "&nbsp"; else echo $res_comprobante->fields["metformina"]?></td>
						
					</tr>   
			 	</table>
	          </div>
			</td>
         </tr> 
         <?/*----------segundos datos---------------*/?>      
          
          <?/*----------Consejeria---------------*/?> 	
			<tr>
	          <td colspan=9>					  
	           <div id=<?=$id_tabla?> style='display:none'>
	            <table width=100% align=center class=bordes>
				  	<tr id=ma>		                               
					   <td>Consejerias</td>
					  </tr>   
			 	</table>
	          </div>
			</td>
         </tr> 
          <?/*----------tercero datos---------------*/?> 	
			<tr>
	          <td colspan=9>					  
	           <div id=<?=$id_tabla?> style='display:none'>
	            <table width=100% align=center class=bordes>
				  	<tr id=ma>		                               
					   <td>Alimentación Saludable</td>
					   <td>Práctica Regular de Actividad Física</td>
					   <td>rastreo de tabaquismo y consejo breve para dejar de fumar</td>      
					</tr>
				  	<tr>
						<td align="center" class="bordes"><?if ($res_comprobante->fields['alimentacion_saludable']=="") echo "&nbsp"; else echo $res_comprobante->fields["alimentacion_saludable"]?></td>			                                 
						<td align="center" class="bordes"><?if ($res_comprobante->fields['actividad_fisica']=="") echo "&nbsp"; else echo $res_comprobante->fields["actividad_fisica"]?></td>
						<td align="center" class="bordes"><?if ($res_comprobante->fields['rastreo_tabaquismo']=="") echo "&nbsp"; else echo $res_comprobante->fields["rastreo_tabaquismo"]?></td>
					</tr>   
			    </div>
			</td>
         </tr> 
      
</table> 
 <tr><td><table width=100% align="center" class="bordes">
  
 </table></td></tr>

</td></tr></table>
 <tr align="center">
    <td> 	
   	 	<input type=button align="center" name="cerrar" value="Cerrar"   class="btn btn-success"" onclick="window.close()">
    </td>
  </tr>
</table>

</form>
<?=fin_pagina();// aca termino ?>
