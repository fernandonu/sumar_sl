<?php
require_once("../../config.php");

variables_form_busqueda("listado_beneficiarios_leche");

$fecha_hoy=date("Y-m-d H:i:s");
$fecha_hoy=fecha($fecha_hoy);

$orden = array(
        "default" => "1",
        "1" => "a",
        "2" => "b",
        "3" => "c",
        "4" => "d",
        "5" => "e"        
       );
$filtro = array(
		"c" => "DNI",
        "a" => "Apellido"                       
       );


$sql_tmp="
select * from (
select 
  nacer.smiafiliados.id_smiafiliados as id,
  nacer.smiafiliados.afiapellido as a,
  nacer.smiafiliados.afinombre as b,
  nacer.smiafiliados.afidni as c,
  nacer.smiafiliados.afifechanac as d,
  nacer.smiafiliados.afidomlocalidad as e,
  'na' as f,
  nacer.smiafiliados.activo as estado,
  nacer.smiafiliados.afisexo as g
  from nacer.smiafiliados

UNION ALL
  select 
  uad.beneficiarios.id_beneficiarios as id,
  uad.beneficiarios.apellido_benef as a,
  uad.beneficiarios.nombre_benef as b,
  uad.beneficiarios.numero_doc as c,
  uad.beneficiarios.fecha_nacimiento_benef as d,
  uad.beneficiarios.calle as e,
  'nu' as f,
  'S' as estado,
  uad.beneficiarios.sexo as g
  from uad.beneficiarios

  ) as cc";


//extracto de la consulta sobre leche.beneficiarios
/*select 
  leche.beneficiarios.id_beneficiarios as id,
  leche.beneficiarios.apellido as a,
  leche.beneficiarios.nombre as b,
  leche.beneficiarios.documento as c,
  leche.beneficiarios.fecha_nac as d,
  leche.beneficiarios.domicilio as e,
  'nu' as f,
  'S' as estado,
  leche.beneficiarios.sexo as g
  from leche.beneficiarios*/


echo $html_header;
?>
<style>
/* Sistema de Diseño PAF para Entrega de Leche (San Luis) */
.paf-wrapper {
    max-width: 1380px;
    margin: 14px auto 40px auto;
    font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, Roboto, Helvetica, Arial, sans-serif;
    color: #1e293b;
    padding: 0 10px;
}

/* Cabecera Principal */
.paf-main-header {
    background: linear-gradient(135deg, #0369a1 0%, #0284c7 100%);
    color: #ffffff;
    border-radius: 12px 12px 0 0;
    padding: 14px 22px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
}
.paf-main-title {
    font-size: 18px;
    font-weight: 700;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 12px;
}
.paf-main-title .paf-header-icon {
    font-size: 26px;
    line-height: 1;
}
.paf-header-badge {
    background: rgba(255,255,255,0.2);
    padding: 4px 12px;
    border-radius: 14px;
    font-size: 12px;
    font-weight: 600;
    letter-spacing: 0.3px;
}

/* Tarjeta de Filtro y Búsqueda */
.paf-filter-card {
    background: #ffffff;
    border-left: 1px solid #e2e8f0;
    border-right: 1px solid #e2e8f0;
    border-bottom: 1px solid #e2e8f0;
    padding: 14px 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.03);
}
.paf-filter-controls {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}
.paf-filter-controls select,
.paf-filter-controls input[type="text"] {
    padding: 6px 12px;
    border: 1px solid #cbd5e1;
    border-radius: 6px;
    font-size: 13px;
    color: #1e293b;
    background-color: #ffffff;
    outline: none;
    transition: all 0.15s ease;
}
.paf-filter-controls select:focus,
.paf-filter-controls input[type="text"]:focus {
    border-color: #0284c7;
    box-shadow: 0 0 0 3px rgba(2,132,199,0.15);
}

/* Botones */
.paf-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 7px 18px;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    border: none;
    transition: all 0.15s ease;
    text-decoration: none;
}
.paf-btn-primary {
    background: linear-gradient(135deg, #0369a1 0%, #0284c7 100%);
    color: #ffffff;
    box-shadow: 0 2px 4px rgba(2,132,199,0.25);
}
.paf-btn-primary:hover {
    background: linear-gradient(135deg, #075985 0%, #0369a1 100%);
    color: #ffffff;
}
.paf-btn-secondary {
    background: #ffffff;
    color: #334155;
    border: 1px solid #cbd5e1;
}
.paf-btn-secondary:hover {
    background: #f1f5f9;
    border-color: #94a3b8;
    color: #1e293b;
}
.paf-btn-success {
    background: linear-gradient(135deg, #15803d 0%, #16a34a 100%);
    color: #ffffff;
    box-shadow: 0 2px 4px rgba(22,163,74,0.25);
}
.paf-btn-success:hover {
    background: linear-gradient(135deg, #166534 0%, #15803d 100%);
    color: #ffffff;
}

/* Tarjeta Contenedora de Tabla */
.paf-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-top: none;
    border-radius: 0 0 10px 10px;
    margin-bottom: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.04);
    overflow: hidden;
}

/* Barra de herramientas / Paginación */
.paf-table-toolbar {
    background-color: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    padding: 10px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 13px;
    color: #475569;
    flex-wrap: wrap;
    gap: 10px;
}
.paf-count-pill {
    display: inline-block;
    padding: 2px 10px;
    background-color: #e0f2fe;
    border-radius: 12px;
    font-weight: 700;
    color: #0369a1;
    font-size: 12.5px;
}
.paf-table-toolbar a {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 26px;
    height: 26px;
    padding: 0 6px;
    background: #ffffff;
    border: 1px solid #cbd5e1;
    border-radius: 4px;
    color: #0284c7 !important;
    text-decoration: none;
    font-weight: 700;
    font-size: 12px;
    margin: 0 2px;
    transition: all 0.15s ease;
}
.paf-table-toolbar a:hover {
    background: #f0f9ff;
    border-color: #0284c7;
}
.paf-table-toolbar input[name="page"] {
    border: 1px solid #cbd5e1 !important;
    border-radius: 4px !important;
    padding: 2px 6px !important;
    font-size: 12px !important;
    background: #ffffff !important;
    font-weight: 600;
    color: #1e293b;
}

/* Tabla Principal */
.paf-table-container {
    overflow-x: auto;
}
.paf-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 12.5px;
}
.paf-table th {
    background-color: #0f172a;
    color: #ffffff;
    font-weight: 600;
    padding: 10px 10px;
    text-align: left;
    border-right: 1px solid #1e293b;
    white-space: nowrap;
}
.paf-table th a {
    color: #ffffff !important;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}
.paf-table th a:hover {
    color: #7dd3fc !important;
}
.paf-table td {
    padding: 8px 10px;
    border-bottom: 1px solid #e2e8f0;
    color: #334155;
    vertical-align: middle;
}
.paf-table tr.paf-row-clickable {
    cursor: pointer;
    transition: background-color 0.15s ease;
}
.paf-table tr.paf-row-clickable:hover {
    background-color: #f0f9ff;
}
.paf-table tr.paf-row-clickable:hover td {
    color: #0f172a;
}

/* Badges de Información */
.paf-badge-dni {
    font-family: 'Consolas', monospace;
    background: #e0e7ff;
    color: #3730a3;
    padding: 2px 7px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 700;
    display: inline-block;
}
.paf-badge-nacer-act {
    background: #dcfce7;
    color: #15803d;
    padding: 2px 8px;
    border-radius: 10px;
    font-size: 11px;
    font-weight: 700;
    border: 1px solid #bbf7d0;
    display: inline-block;
    white-space: nowrap;
}
.paf-badge-nacer-ina {
    background: #fee2e2;
    color: #991b1b;
    padding: 2px 8px;
    border-radius: 10px;
    font-size: 11px;
    font-weight: 700;
    border: 1px solid #fecaca;
    display: inline-block;
    white-space: nowrap;
}
.paf-badge-externo {
    background: #f1f5f9;
    color: #475569;
    padding: 2px 8px;
    border-radius: 10px;
    font-size: 11px;
    font-weight: 700;
    border: 1px solid #cbd5e1;
    display: inline-block;
    white-space: nowrap;
}

/* Botones de Acción en Fila */
.paf-btn-action {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 4px;
    padding: 4px 8px;
    border-radius: 5px;
    font-size: 11px;
    font-weight: 600;
    text-decoration: none !important;
    transition: all 0.15s ease;
    white-space: nowrap;
}
.paf-btn-action:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.paf-act-edit {
    background: #eff6ff;
    color: #1d4ed8 !important;
    border: 1px solid #bfdbfe;
    padding: 3px 6px;
    border-radius: 4px;
    text-decoration: none !important;
}
.paf-act-edit:hover {
    background: #dbeafe;
}
.paf-act-locked {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #94a3b8;
    font-size: 13px;
}

.paf-act-leche {
    background: #e0f2fe;
    color: #0369a1 !important;
    border: 1px solid #bae6fd;
}
.paf-act-leche:hover {
    background: #bae6fd;
    color: #075985 !important;
}

.paf-act-fichero {
    background: #fef3c7;
    color: #92400e !important;
    border: 1px solid #fde68a;
}
.paf-act-fichero:hover {
    background: #fde68a;
    color: #78350f !important;
}

.paf-act-vacunas {
    background: #ecfdf5;
    color: #047857 !important;
    border: 1px solid #a7f3d0;
}
.paf-act-vacunas:hover {
    background: #d1fae5;
    color: #065f46 !important;
}

.paf-act-tzr {
    background: #f5f3ff;
    color: #6d28d9 !important;
    border: 1px solid #ddd6fe;
}
.paf-act-tzr:hover {
    background: #ede9fe;
    color: #5b21b6 !important;
}

.paf-act-der {
    background: #faf5ff;
    color: #7e22ce !important;
    border: 1px solid #f3e8ff;
}
.paf-act-der:hover {
    background: #f3e8ff;
    color: #6b21a8 !important;
}

.paf-act-sexual {
    background: #fff1f2;
    color: #be123c !important;
    border: 1px solid #fecdd3;
}
.paf-act-sexual:hover {
    background: #ffe4e6;
    color: #9f1239 !important;
}

.paf-empty-state {
    text-align: center;
    padding: 38px 20px;
}
</style>

<div class="paf-wrapper">

    <!-- CABECERA PRINCIPAL -->
    <div class="paf-main-header">
        <div class="paf-main-title">
            <span class="paf-header-icon">🥛</span>
            <div>
                <div>Entrega de Leche - Gestión de Beneficiarios</div>
                <div style="font-size:12px; font-weight:normal; opacity:0.88; margin-top:2px;">
                    Padrón Integrado de Beneficiarios (Plan SUMAR y UAD)
                </div>
            </div>
        </div>
        <div class="paf-header-badge">
            San Luis - Programa Nutricional
        </div>
    </div>

    <!-- FORMULARIO DE BÚSQUEDA Y FILTROS -->
    <form name="form1" action="listado_beneficiarios_leche.php" method="POST">
        <div class="paf-filter-card">
            <div class="paf-filter-controls">
                <span style="font-size:16px;">🔍</span>
                <?
                $contar_sql = (empty($keyword) ? "select count(*) from (select id_smiafiliados from nacer.smiafiliados union all select id_beneficiarios from uad.beneficiarios) as cc" : "buscar");
                list($sql,$total_muletos,$link_pagina,$up) = form_busqueda($sql_tmp,$orden,$filtro,$link_tmp,$where_tmp,$contar_sql);
                ?>
                <input type="submit" name="buscar" value="Buscar" class="paf-btn paf-btn-primary">
                <?if (!empty($keyword)):?>
                    <a href="listado_beneficiarios_leche.php" class="paf-btn paf-btn-secondary" style="padding:6px 12px; font-size:12px;">
                        Limpiar Filtro
                    </a>
                <?endif;?>
                <button type="button" class="paf-btn paf-btn-success" onclick="document.location='../inscripcion/ins_admin_old.php'" title="Inscribir con Planilla de Inscripción">
                    ➕ Nuevo Dato
                </button>
            </div>
            <div style="font-size:12px; color:#64748b; display:flex; align-items:center; gap:6px; margin-top:8px;">
                <span>💡</span>
                <span>Haga clic sobre un beneficiario activo de SUMAR para acceder a su facturación, o utilice los accesos directos a Leche, Fichero, Vacunas, etc.</span>
            </div>
        </div>

        <?
        $hay_busqueda = (!empty($_POST['buscar']) || !empty($_POST['form_busqueda']) || isset($_GET['page']) || isset($parametros['page']) || !empty($keyword));
        if ($hay_busqueda) $result = sql($sql) or die;
        ?>

        <!-- TARJETA CONTENEDORA DE RESULTADOS -->
        <div class="paf-card">
            <!-- Barra de herramientas / Paginación -->
            <div class="paf-table-toolbar">
                <div>
                    <b>Total de Beneficiarios:</b> 
                    <span class="paf-count-pill"><?=number_format($total_muletos, 0, ',', '.')?></span>
                </div>
                <div>
                    <?=$link_pagina?>
                </div>
            </div>

            <!-- Tabla Principal -->
            <div class="paf-table-container">
                <table class="paf-table">
                    <thead>
                        <tr>
                            <th style="width:140px;"><a href="<?=encode_link("listado_beneficiarios_leche.php",array("sort"=>"1","up"=>$up))?>">Apellido <?=($sort=='1'?($up=='1'?'▲':'▼'):'')?></a></th>
                            <th style="width:140px;"><a href="<?=encode_link("listado_beneficiarios_leche.php",array("sort"=>"2","up"=>$up))?>">Nombre <?=($sort=='2'?($up=='1'?'▲':'▼'):'')?></a></th>
                            <th style="width:95px; text-align:center;"><a href="<?=encode_link("listado_beneficiarios_leche.php",array("sort"=>"3","up"=>$up))?>">DNI <?=($sort=='3'?($up=='1'?'▲':'▼'):'')?></a></th>
                            <th style="width:110px; text-align:center;"><a href="<?=encode_link("listado_beneficiarios_leche.php",array("sort"=>"4","up"=>$up))?>">F. Nacimiento <?=($sort=='4'?($up=='1'?'▲':'▼'):'')?></a></th>
                            <th><a href="<?=encode_link("listado_beneficiarios_leche.php",array("sort"=>"5","up"=>$up))?>">Domicilio / Localidad <?=($sort=='5'?($up=='1'?'▲':'▼'):'')?></a></th>
                            <th style="width:115px; text-align:center;">Entidad Alta</th>
                            <th style="width:45px; text-align:center;" title="Modificar planilla UAD">Editar</th>
                            <th style="width:75px; text-align:center;" title="Entrega de Leche">Leche</th>
                            <th style="width:75px; text-align:center;" title="Fichero Cronológico">Fichero</th>
                            <th style="width:75px; text-align:center;" title="Control de Vacunas">Vacunas</th>
                            <th style="width:85px; text-align:center;" title="Trazadoras">Trazadoras</th>
                            <th style="width:80px; text-align:center;" title="Derivaciones">Derivación</th>
                            <th style="width:90px; text-align:center;" title="Programa Sexual y Reproductivo">Prog. Sex.</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?if (!$hay_busqueda):?>
                        <tr>
                            <td colspan="13" class="paf-empty-state">
                                <div style="font-size:32px; margin-bottom:8px;">🥛</div>
                                <div style="font-weight:700; font-size:15px; color:#1e293b;">Búsqueda de Beneficiarios de Leche</div>
                                <div style="font-size:13px; color:#64748b; margin-top:4px;">
                                    Ingrese un número de DNI o Apellido en el buscador superior para consultar entre los beneficiarios de SUMAR y UAD.
                                </div>
                            </td>
                        </tr>
                    <?elseif ($total_muletos == 0):?>
                        <tr>
                            <td colspan="13" class="paf-empty-state">
                                <div style="font-size:30px; margin-bottom:8px;">🔍</div>
                                <div style="font-weight:700; font-size:15px; color:#1e293b;">No se encontraron beneficiarios</div>
                                <div style="font-size:13px; color:#64748b; margin-top:4px;">
                                    No existen registros que coincidan con el criterio de búsqueda ingresado.
                                </div>
                            </td>
                        </tr>
                    <?else:?>
                        <?while (!$result->EOF):
                            $id_ben = $result->fields['id'];
                            $entidad_f = $result->fields['f'];
                            $estado_act = trim($result->fields['estado']);
                            $is_sumar_activo = ($entidad_f == 'na' && $estado_act == 'S');

                            $onclick_elegir = "";
                            if ($is_sumar_activo) {
                                $ref = encode_link("../facturacion/comprobante_admin.php", array(
                                    "id_smiafiliados" => $id_ben,
                                    "entidad_alta" => $entidad_f,
                                    "pagina_listado" => "listado_beneficiarios_leche.php"
                                ));
                                $onclick_elegir = "location.href='$ref';";
                            }
                        ?>
                            <tr class="<?=($is_sumar_activo ? 'paf-row-clickable' : '')?>">
                                <td onclick="<?=$onclick_elegir?>"><b><?=$result->fields['a']?></b></td>
                                <td onclick="<?=$onclick_elegir?>"><?=$result->fields['b']?></td>
                                <td align="center" onclick="<?=$onclick_elegir?>">
                                    <span class="paf-badge-dni"><?=$result->fields['c']?></span>
                                </td>
                                <td align="center" onclick="<?=$onclick_elegir?>"><?=Fecha($result->fields['d'])?></td>
                                <td onclick="<?=$onclick_elegir?>"><?=$result->fields['e']?></td>

                                <!-- Entidad Alta -->
                                <td align="center" onclick="<?=$onclick_elegir?>">
                                    <?if ($entidad_f == 'na'):?>
                                        <?if ($estado_act == 'S'):?>
                                            <span class="paf-badge-nacer-act" title="Afiliado activo en Plan NACER / SUMAR">NACER Activo</span>
                                        <?else:?>
                                            <span class="paf-badge-nacer-ina" title="Afiliado inactivo en Plan NACER / SUMAR">NACER Inactivo</span>
                                        <?endif;?>
                                    <?else:?>
                                        <span class="paf-badge-externo" title="Beneficiario empadronado por UAD">Externo</span>
                                    <?endif;?>
                                </td>

                                <!-- Modificar (UAD) -->
                                <td align="center">
                                    <?if ($entidad_f == 'nu'):?>
                                        <?$link_mod = encode_link("leche_nuevo_admin.php", array("id_planilla" => $id_ben));?>
                                        <a href="<?=$link_mod?>" class="paf-act-edit" title="Modificar planilla UAD">✏️</a>
                                    <?else:?>
                                        <span class="paf-act-locked" title="Afiliado de Plan SUMAR">🔒</span>
                                    <?endif;?>
                                </td>

                                <!-- Entrega Leche -->
                                <?$ref_leche = encode_link("comprobante_admin_leche.php", array("id" => $id_ben, "entidad_alta" => $entidad_f));?>
                                <td align="center">
                                    <a href="<?=$ref_leche?>" class="paf-btn-action paf-act-leche" title="Registrar Entrega de Leche">🥛 Leche</a>
                                </td>

                                <!-- Fichero Cronológico -->
                                <?$ref_fichero = encode_link("../fichero/comprobante_fichero.php", array("id" => $id_ben, "entidad_alta" => $entidad_f));?>
                                <td align="center">
                                    <a href="<?=$ref_fichero?>" class="paf-btn-action paf-act-fichero" title="Fichero Cronológico">📁 Fichero</a>
                                </td>

                                <!-- Vacunas -->
                                <?$ref_vac = encode_link("../trazadoras/vac_admin.php", array("id" => $id_ben, "entidad_alta" => $entidad_f, "aux_dni" => $result->fields['c']));?>
                                <td align="center">
                                    <a href="<?=$ref_vac?>" class="paf-btn-action paf-act-vacunas" title="Control de Vacunas">💉 Vacunas</a>
                                </td>

                                <!-- Trazadoras -->
                                <?$ref_tzr = encode_link("../trazadorassps/tzr_admin.php", array("id" => $id_ben, "entidad_alta" => $entidad_f, "apellido" => $result->fields['a'], "nombre" => $result->fields['b'], "dni" => $result->fields['c'], "fecha_nac" => $result->fields['d'], "localidad" => $result->fields['e'], "sexo" => $result->fields['g']));?>
                                <td align="center">
                                    <a href="<?=$ref_tzr?>" class="paf-btn-action paf-act-tzr" title="Trazadoras">🎯 Trazadoras</a>
                                </td>

                                <!-- Derivaciones -->
                                <?$ref_der = encode_link("../derivaciones/der_admin.php", array("id" => $id_ben, "entidad_alta" => $entidad_f, "aux_dni" => $result->fields['c']));?>
                                <td align="center">
                                    <a href="<?=$ref_der?>" class="paf-btn-action paf-act-der" title="Derivaciones">🔀 Deriv.</a>
                                </td>

                                <!-- Programa Sexual -->
                                <?$ref_prog_sexual = encode_link("../programa_sexual/planilla_prog_sexual.php", array("id" => $id_ben, "entidad_alta" => $entidad_f, "aux_dni" => $result->fields['c']));?>
                                <td align="center">
                                    <a href="<?=$ref_prog_sexual?>" class="paf-btn-action paf-act-sexual" title="Programa Sexual y Reproductivo">⚕️ Sexual</a>
                                </td>
                            </tr>
                            <?$result->MoveNext();?>
                        <?endwhile;?>
                    <?endif;?>
                    </tbody>
                </table>
            </div>
        </div>
    </form>
</div>

<?=fin_pagina();?>
