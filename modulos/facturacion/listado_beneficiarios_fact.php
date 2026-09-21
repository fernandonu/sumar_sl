<?php
require_once("../../config.php");

variables_form_busqueda("listado_beneficiarios_fact");

$fecha_hoy = date("Y-m-d H:i:s");
$fecha_hoy = fecha($fecha_hoy);

if ($cmd == "") $cmd = "activos";

$orden = array(
    "default" => "1",
    "1" => "afiapellido",
    "2" => "afinombre",
    "3" => "afidni",
    "4" => "afitipocategoria",
    "5" => "nombreefector",
    "6" => "activo",
    "7" => "clavebeneficiario",
    "8" => "activo",
    "9" => "fechainscripcion",
    "10" => "fechacarga"
);

if ($cmd == "inactivos") {
    $filtro = array(
        "afidni" => "DNI",
        "afiapellido" => "Apellido",
        "afinombre" => "Nombre",
        "motivobaja::text" => "Cod Baja",
        "mensajebaja" => "Mensaje Baja"
    );
} else {
    $filtro = array(
        "afidni" => "DNI",
        "afiapellido" => "Apellido",
        "afinombre" => "Nombre"
    );
}

$datos_barra = array(
    array(
        "descripcion" => "Activos",
        "cmd"         => "activos"
    ),
    array(
        "descripcion" => "Inactivos",
        "cmd"         => "inactivos"
    ),
    array(
        "descripcion" => "Todos",
        "cmd"         => "todos"
    )
);

$sql_tmp = "select distinct 
               id_smiafiliados,
               afiapellido,
               afinombre,
               afidni,
               afisexo,
               nombre,
               motivobaja,
               mensajebaja,
               afifechanac,
               clavebeneficiario,
               fechainscripcion,
               smiafiliados.activo
            from nacer.smiafiliados
            left join nacer.efe_conv on (cuieefectorasignado=cuie)";

$where_tmp = "";
if ($cmd == "activos") {
    $where_tmp = " (trim(smiafiliados.activo)='S')";
} else if ($cmd == "inactivos") {
    $where_tmp = " (trim(smiafiliados.activo)='N')";
}

echo $html_header;
?>
<style>
/* Sistema de Diseño PAF para listado_beneficiarios_fact.php (San Luis) */
.paf-wrapper {
    max-width: 1260px;
    margin: 12px auto 40px auto;
    font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, Roboto, Helvetica, Arial, sans-serif;
    color: #1e293b;
}

/* Barra de Estados del Sistema (generar_barra_nav) */
.paf-wrapper .btn-group-justified {
    margin-bottom: 14px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.06);
    border-radius: 8px;
    overflow: hidden;
    display: flex;
    width: 100%;
}
.paf-wrapper .btn-group-justified > .btn,
.paf-wrapper .btn-group-justified > a.btn {
    flex: 1 1 0;
    text-align: center;
    padding: 10px 18px;
    font-weight: 700;
    font-size: 13.5px;
    letter-spacing: 0.3px;
    text-decoration: none;
    border-radius: 0;
    transition: all 0.15s ease;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}
.paf-wrapper .btn-group-justified > .btn-primary,
.paf-wrapper .btn-group-justified > a.btn-primary {
    background: #f8fafc;
    color: #475569;
    border: 1px solid #cbd5e1;
}
.paf-wrapper .btn-group-justified > .btn-primary:hover,
.paf-wrapper .btn-group-justified > a.btn-primary:hover {
    background: #f1f5f9;
    color: #1e3a8a;
    border-color: #94a3b8;
}
.paf-wrapper .btn-group-justified > .btn-primary.active,
.paf-wrapper .btn-group-justified > a.btn-primary.active {
    background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%);
    color: #ffffff;
    border-color: #1e3a8a;
    box-shadow: inset 0 2px 4px rgba(0,0,0,0.15);
}

/* Tarjeta de Búsqueda y Filtros */
.paf-filter-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 12px 20px;
    margin-bottom: 14px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.04);
}
.paf-filter-controls {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}
.paf-filter-controls select,
.paf-filter-controls input[type="text"] {
    padding: 6px 11px;
    border: 1px solid #cbd5e1;
    border-radius: 6px;
    font-size: 12.5px;
    color: #1e293b;
    background-color: #ffffff;
    outline: none;
    transition: all 0.15s ease;
}
.paf-filter-controls select:focus,
.paf-filter-controls input[type="text"]:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59,130,246,0.15);
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
    background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%);
    color: #ffffff;
    box-shadow: 0 2px 4px rgba(37,99,235,0.25);
}
.paf-btn-primary:hover {
    background: linear-gradient(135deg, #1e40af 0%, #1d4ed8 100%);
}
.paf-btn-secondary {
    background: #ffffff;
    color: #334155;
    border: 1px solid #cbd5e1;
}
.paf-btn-secondary:hover {
    background: #f1f5f9;
    border-color: #94a3b8;
}

/* Cabecera Principal */
.paf-main-header {
    background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
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
    gap: 10px;
}
.paf-header-badge {
    background: rgba(255,255,255,0.2);
    padding: 3px 10px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
    letter-spacing: 0.3px;
}

/* Tarjeta Contenedora de Tabla */
.paf-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 0 0 10px 10px;
    margin-bottom: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.04);
    overflow: hidden;
}

/* Barra de herramientas / Paginación */
.paf-table-toolbar {
    background-color: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    padding: 10px 18px;
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
    padding: 2px 9px;
    background-color: #e0e7ff;
    border-radius: 12px;
    font-weight: 700;
    color: #3730a3;
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
    color: #2563eb !important;
    text-decoration: none;
    font-weight: 700;
    font-size: 12px;
    margin: 0 2px;
    transition: all 0.15s ease;
}
.paf-table-toolbar a:hover {
    background: #eff6ff;
    border-color: #3b82f6;
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
.paf-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 12.5px;
}
.paf-table th {
    background-color: #1e293b;
    color: #ffffff;
    font-weight: 600;
    padding: 10px 12px;
    text-align: left;
    border-right: 1px solid #334155;
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
    color: #93c5fd !important;
}
.paf-table td {
    padding: 9px 12px;
    border-bottom: 1px solid #e2e8f0;
    color: #334155;
    vertical-align: middle;
}
.paf-table tr.paf-row-clickable {
    cursor: pointer;
    transition: background-color 0.15s ease;
}
.paf-table tr.paf-row-clickable:hover {
    background-color: #f8fafc;
}

/* Badges y Pastillas */
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
.paf-badge-clave {
    font-family: 'Consolas', monospace;
    color: #475569;
    font-size: 11.5px;
    font-weight: 600;
}
.paf-badge-activo {
    background: #dcfce7;
    color: #15803d;
    padding: 2px 8px;
    border-radius: 10px;
    font-size: 11px;
    font-weight: 700;
    border: 1px solid #bbf7d0;
    display: inline-block;
}
.paf-badge-inactivo {
    background: #fee2e2;
    color: #991b1b;
    padding: 2px 8px;
    border-radius: 10px;
    font-size: 11px;
    font-weight: 700;
    border: 1px solid #fecaca;
    display: inline-block;
}
.paf-badge-baja {
    background: #fef2f2;
    color: #991b1b;
    padding: 1px 6px;
    border-radius: 4px;
    font-size: 11px;
    font-weight: 700;
    border: 1px solid #fecaca;
    font-family: 'Consolas', monospace;
}

/* Botones de acción en tabla */
.paf-btn-action-go {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 10px;
    background: #eff6ff;
    color: #1d4ed8;
    border: 1px solid #bfdbfe;
    border-radius: 5px;
    font-size: 11.5px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.15s ease;
}
.paf-btn-action-go:hover {
    background: #dbeafe;
    color: #1e40af;
    border-color: #93c5fd;
}
.paf-btn-action-excep {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 10px;
    background: #fef3c7;
    color: #92400e;
    border: 1px solid #fde68a;
    border-radius: 5px;
    font-size: 11.5px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.15s ease;
}
.paf-btn-action-excep:hover {
    background: #fde68a;
    color: #78350f;
    border-color: #fcd34d;
}
</style>

<div class="paf-wrapper">

    <!-- BARRA DE ESTADOS DEL SISTEMA (ACTIVOS / INACTIVOS / TODOS) -->
    <?generar_barra_nav($datos_barra);?>

    <!-- FORMULARIO DE BÚSQUEDA Y FILTROS -->
    <form name="form1" action="listado_beneficiarios_fact.php" method="POST">
        <input type="hidden" name="cmd" value="<?=$cmd?>">

        <div class="paf-filter-card">
            <div class="paf-filter-controls">
                <span style="font-size:15px;">🔍</span>
                <?list($sql, $total_muletos, $link_pagina, $up) = form_busqueda($sql_tmp, $orden, $filtro, $link_tmp, $where_tmp, "buscar");?>
                <input type="submit" name="buscar" value="Buscar" class="paf-btn paf-btn-primary">
                <?if (!empty($keyword)):?>
                    <a href="<?=encode_link("listado_beneficiarios_fact.php", array("cmd" => $cmd))?>" class="paf-btn paf-btn-secondary" style="padding:6px 12px; font-size:12px;">
                        Limpiar Filtro
                    </a>
                <?endif;?>
            </div>
            <div style="font-size:12px; color:#64748b; display:flex; align-items:center; gap:6px;">
                <span>💡</span>
                <span>Haga clic sobre un beneficiario activo para gestionar sus comprobantes</span>
            </div>
        </div>

        <?$result = sql($sql) or fin_pagina();?>

        <!-- CABECERA PRINCIPAL -->
        <div class="paf-main-header">
            <div class="paf-main-title">
                <span>📑</span> Facturación: Listado de Beneficiarios
            </div>
            <div class="paf-header-badge">
                <?if ($cmd == 'activos'):?>
                    🟢 Beneficiarios Activos
                <?elseif ($cmd == 'inactivos'):?>
                    🔴 Beneficiarios Inactivos (Bajas)
                <?else:?>
                    👥 Padrón General
                <?endif;?>
            </div>
        </div>

        <!-- CONTENEDOR DE TABLA -->
        <div class="paf-card">
            <div class="paf-table-toolbar">
                <div style="display:flex; align-items:center; gap:8px;">
                    <span style="font-weight:600;">Total Registrados:</span>
                    <span class="paf-count-pill"><?=$total_muletos?></span>
                </div>
                <div style="display:flex; align-items:center; gap:6px;">
                    <?=$link_pagina?>
                </div>
            </div>

            <div style="overflow-x:auto;">
                <table class="paf-table">
                    <thead>
                        <tr>
                            <th><a href="<?=encode_link("listado_beneficiarios_fact.php", array("sort" => "1", "up" => $up))?>">Apellido</a></th>
                            <th><a href="<?=encode_link("listado_beneficiarios_fact.php", array("sort" => "2", "up" => $up))?>">Nombre</a></th>
                            <th><a href="<?=encode_link("listado_beneficiarios_fact.php", array("sort" => "3", "up" => $up))?>">DNI</a></th>
                            <th style="width:40px; text-align:center;">Sexo</th>
                            <th><a href="<?=encode_link("listado_beneficiarios_fact.php", array("sort" => "5", "up" => $up))?>">Efector Asignado</a></th>
                            <?if (($cmd == "todos") || ($cmd == "inactivos")):?>
                                <th style="width:80px; text-align:center;">Cód. Baja</th>
                                <th>Mensaje de Baja</th>
                            <?endif;?>
                            <th style="width:90px;">F. Nacimiento</th>
                            <th><a href="<?=encode_link("listado_beneficiarios_fact.php", array("sort" => "7", "up" => $up))?>">Clave Beneficiario</a></th>
                            <th style="width:90px;"><a href="<?=encode_link("listado_beneficiarios_fact.php", array("sort" => "9", "up" => $up))?>">F. Inscripción</a></th>
                            <th style="width:80px; text-align:center;"><a href="<?=encode_link("listado_beneficiarios_fact.php", array("sort" => "6", "up" => $up))?>">Estado</a></th>
                            <th style="width:130px; text-align:center;">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?if ($total_muletos == 0):?>
                        <tr>
                            <td colspan="12" style="text-align:center; padding:30px; color:#64748b; font-size:13.5px;">
                                No se encontraron beneficiarios que coincidan con el criterio de búsqueda especificado.
                            </td>
                        </tr>
                    <?else:?>
                        <?
                        while (!$result->EOF) {
                            $id_smi = $result->fields['id_smiafiliados'];
                            $clave_b = $result->fields['clavebeneficiario'];
                            $is_activo = (trim($result->fields['activo']) == 'S');
                            
                            $onclick_elegir = "";
                            $ref_comprobante = "";
                            if ($is_activo) {
                                $ref_comprobante = encode_link("comprobante_admin.php", array(
                                    "id_smiafiliados" => $id_smi,
                                    "clavebeneficiario" => $clave_b,
                                    "pagina_listado" => "listado_beneficiarios_fact.php",
                                    "estado" => $result->fields['activo']
                                ));
                                $onclick_elegir = "location.href='$ref_comprobante';";
                            }

                            // Enlace de excepción para inactivos
                            $onclick_excepcion = "";
                            $ref_excepcion = "";
                            if (!$is_activo) {
                                if (permisos_check('inicio', 'factura_exepciones')) {
                                    $ref_excepcion = encode_link("comprobante_admin.php", array(
                                        "id_smiafiliados" => $id_smi,
                                        "clavebeneficiario" => $clave_b,
                                        "pagina" => "listado_beneficiario_fact",
                                        "flag_inactivo" => "S"
                                    ));
                                    $onclick_excepcion = "alert('ESTA OPCIÓN ES USADA SOLO SI LLEGA UN COMPROBANTE QUE SE PUEDE FACTURAR DEBIDO A QUE FUE REALIZADO ANTES QUE EL BENEFICIARIO SE DIERA DE BAJA.'); location.href='$ref_excepcion';";
                                } else {
                                    $onclick_excepcion = "alert('Debe Tener Permisos Especiales para poder Facturar.');";
                                }
                            }
                        ?>
                            <tr class="<?=($is_activo ? 'paf-row-clickable' : '')?>">
                                <td onclick="<?=$onclick_elegir?>"><b><?=utf8_decode($result->fields['afiapellido'])?></b></td>
                                <td onclick="<?=$onclick_elegir?>"><?=utf8_decode($result->fields['afinombre'])?></td>
                                <td onclick="<?=$onclick_elegir?>"><span class="paf-badge-dni"><?=$result->fields['afidni']?></span></td>
                                <td onclick="<?=$onclick_elegir?>" style="text-align:center;"><?=utf8_decode($result->fields['afisexo'])?></td>
                                <td onclick="<?=$onclick_elegir?>"><?=utf8_decode($result->fields['nombre'])?></td>
                                <?if (($cmd == "todos") || ($cmd == "inactivos")):?>
                                    <td style="text-align:center;">
                                        <?if (!empty($result->fields['motivobaja'])):?>
                                            <span class="paf-badge-baja"><?=$result->fields['motivobaja']?></span>
                                        <?else:?>
                                            <span style="color:#94a3b8;">-</span>
                                        <?endif;?>
                                    </td>
                                    <td><?=($result->fields['mensajebaja'] != '' ? utf8_decode($result->fields['mensajebaja']) : '<span style="color:#94a3b8;">-</span>')?></td>
                                <?endif;?>
                                <td onclick="<?=$onclick_elegir?>"><?=fecha($result->fields['afifechanac'])?></td>
                                <td onclick="<?=$onclick_elegir?>"><span class="paf-badge-clave"><?=$clave_b?></span></td>
                                <td onclick="<?=$onclick_elegir?>"><?=fecha($result->fields['fechainscripcion'])?></td>
                                <td style="text-align:center;" onclick="<?=$onclick_elegir?>">
                                    <?if ($is_activo):?>
                                        <span class="paf-badge-activo">Activo</span>
                                    <?else:?>
                                        <span class="paf-badge-inactivo">Inactivo</span>
                                    <?endif;?>
                                </td>
                                <td style="text-align:center;">
                                    <?if ($is_activo):?>
                                        <a href="<?=$ref_comprobante?>" class="paf-btn-action-go" title="Cargar o consultar comprobantes de este beneficiario">
                                            ➕ Comprobantes
                                        </a>
                                    <?else:?>
                                        <button type="button" class="paf-btn-action-excep" onclick="<?=$onclick_excepcion?>" title="Factura Excepciones (Embarazadas - Puérperas con ciclo cumplido y Niños con 6 años Cumplidos)">
                                            ⚡ Fact. Excepción
                                        </button>
                                    <?endif;?>
                                </td>
                            </tr>
                        <?
                            $result->MoveNext();
                        }
                        ?>
                    <?endif;?>
                    </tbody>
                </table>
            </div>
        </div>
    </form>
</div>

<?=fin_pagina();?>
