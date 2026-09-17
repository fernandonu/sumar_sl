<?php
require_once("../../config.php");
extract($_POST, EXTR_SKIP);
if ($parametros) extract($parametros, EXTR_OVERWRITE);
if (isset($_GET['id_nomenclador_detalle']) && $_GET['id_nomenclador_detalle'] !== '') {
    $id_nomenclador_detalle = $_GET['id_nomenclador_detalle'];
}

$extras = array("id_nomenclador_detalle" => $id_nomenclador_detalle);
variables_form_busqueda("param_admin", $extras);

$fecha_hoy = date("Y-m-d H:i:s");
$fecha_hoy = fecha($fecha_hoy);

$orden = array(
    "default" => "4",
    "1" => "id_nomenclador",
    "2" => "id_nomenclador_detalle",
    "3" => "descripcion",
    "4" => "codigo",
    "5" => "grupo",
    "6" => "subgrupo",
    "7" => "precio",
    "8" => "activo",
);
$filtro = array(
    "codigo" => "Código",
    "id_nomenclador_detalle::text" => "Id Nom. Detalle",
    "descripcion" => "Descripción",
    "grupo" => "Grupo",
    "subgrupo" => "Subgrupo",
    "activo::text" => "Activo",
);

$sql_tmp = "select * from facturacion.nomenclador";
$where_tmp = ($id_nomenclador_detalle != "") ? "id_nomenclador_detalle=$id_nomenclador_detalle" : "";
$link_tmp = array("id_nomenclador_detalle" => $id_nomenclador_detalle);

// Obtener datos del nomenclador detalle para un encabezado contextual más rico
$info_nd = null;
if (!empty($id_nomenclador_detalle)) {
    $res_nd = sql("SELECT descripcion, modo_facturacion, fecha_desde, fecha_hasta, activonomen FROM facturacion.nomenclador_detalle WHERE id_nomenclador_detalle = " . intval($id_nomenclador_detalle));
    if ($res_nd && !$res_nd->EOF) {
        $info_nd = $res_nd->fields;
    }
}

echo $html_header;
?>
<style>
/* Estilos modernos y prolijos para param_admin - SUMAR San Luis */
.paf-wrapper {
    max-width: 1260px;
    margin: 15px auto 40px auto;
    font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, Roboto, Helvetica, Arial, sans-serif;
    color: #1e293b;
}
.paf-main-header {
    background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
    color: #ffffff;
    border-radius: 12px 12px 0 0;
    padding: 16px 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
    flex-wrap: wrap;
    gap: 12px;
}
.paf-main-title {
    font-size: 19px;
    font-weight: 700;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 12px;
}
.paf-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 0 0 10px 10px;
    margin-bottom: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.04);
    overflow: hidden;
}
.paf-filter-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 12px 20px;
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}
.paf-filter-controls {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}
.paf-filter-controls b {
    color: #475569;
    font-size: 13px;
    font-weight: 600;
}
.paf-filter-controls select {
    padding: 5px 8px;
    border: 1px solid #cbd5e1;
    border-radius: 6px;
    font-size: 12px;
    color: #1e293b;
    outline: none;
    background-color: #ffffff;
    transition: all 0.15s ease;
}
.paf-filter-controls select option {
    font-size: 12px;
}
.paf-filter-controls input[type="text"] {
    padding: 6px 10px;
    border: 1px solid #cbd5e1;
    border-radius: 6px;
    font-size: 12.5px;
    color: #1e293b;
    outline: none;
    background-color: #ffffff;
    transition: all 0.15s ease;
}
.paf-filter-controls select:focus, .paf-filter-controls input[type="text"]:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59,130,246,0.15);
}
.paf-actions-group {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}
.paf-table-toolbar {
    background-color: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    padding: 10px 18px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 13px;
    color: #475569;
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
    font-size: 12.5px !important;
    background: #ffffff !important;
    font-weight: 600;
    color: #1e293b;
}
.paf-count-pill {
    display: inline-block;
    padding: 2px 9px;
    background-color: #e2e8f0;
    border-radius: 12px;
    font-weight: 700;
    color: #1e293b;
}
.paf-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
}
.paf-table th {
    background-color: #1e293b;
    color: #ffffff;
    font-weight: 600;
    padding: 11px 12px;
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
    padding: 10px 12px;
    border-bottom: 1px solid #e2e8f0;
    color: #334155;
    cursor: pointer;
    transition: background-color 0.15s ease;
}
.paf-table tr:hover td {
    background-color: #f8fafc;
}
.paf-badge-id {
    display: inline-block;
    padding: 2px 7px;
    background-color: #f1f5f9;
    color: #475569;
    border-radius: 4px;
    font-weight: 600;
    font-size: 11.5px;
}
.paf-badge-code {
    display: inline-block;
    padding: 2px 8px;
    background-color: #e0e7ff;
    color: #3730a3;
    border-radius: 4px;
    font-weight: 700;
    font-family: 'Consolas', monospace;
    font-size: 12.5px;
}
.paf-badge-group {
    display: inline-block;
    padding: 2px 7px;
    background-color: #f3e8ff;
    color: #6b21a8;
    border-radius: 4px;
    font-weight: 700;
    font-size: 12px;
}
.paf-badge-active {
    display: inline-block;
    padding: 3px 8px;
    background-color: #dcfce7;
    color: #15803d;
    border-radius: 12px;
    font-weight: 700;
    font-size: 11.5px;
    border: 1px solid #bbf7d0;
}
.paf-badge-inactive {
    display: inline-block;
    padding: 3px 8px;
    background-color: #fee2e2;
    color: #991b1b;
    border-radius: 12px;
    font-weight: 600;
    font-size: 11.5px;
    border: 1px solid #fca5a5;
}
.paf-price-col {
    font-weight: 700;
    color: #0f766e;
    text-align: right;
    white-space: nowrap;
}
.paf-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 7px 16px;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    border: none;
    transition: all 0.15s ease;
}
.paf-btn-primary {
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    color: #ffffff;
}
.paf-btn-primary:hover {
    background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
}
.paf-btn-create {
    background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%);
    color: #ffffff;
}
.paf-btn-create:hover {
    background: linear-gradient(135deg, #6d28d9 0%, #5b21b6 100%);
}
.paf-btn-secondary {
    background: #f1f5f9;
    color: #334155;
    border: 1px solid #cbd5e1;
}
.paf-btn-secondary:hover {
    background: #e2e8f0;
    color: #0f172a;
}
.paf-btn-edit {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 3px 8px;
    background-color: #eff6ff;
    color: #1d4ed8;
    border: 1px solid #bfdbfe;
    border-radius: 6px;
    font-size: 11.5px;
    font-weight: 600;
    transition: all 0.15s ease;
}
.paf-table tr:hover .paf-btn-edit {
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    color: #ffffff;
    border-color: #1d4ed8;
    box-shadow: 0 2px 4px rgba(37,99,235,0.25);
}
</style>

<div class="paf-wrapper">

  <form name="form1" action="param_admin.php" method="POST">
    <input type="hidden" name="id_nomenclador_detalle" value="<?=$id_nomenclador_detalle?>">

    <!-- BARRA DE BÚSQUEDA Y ACCIONES -->
    <div class="paf-filter-card">
      <div class="paf-filter-controls">
        <span style="font-size: 15px;">🔍</span>
        <?php list($sql, $total_muletos, $link_pagina, $up) = form_busqueda($sql_tmp, $orden, $filtro, $link_tmp, $where_tmp, "buscar"); ?>
        <button type="submit" name="buscar" class="paf-btn paf-btn-primary">
          Buscar
        </button>
        <?php if (!empty($keyword)): ?>
          <a href="<?=encode_link("param_admin.php", array("id_nomenclador_detalle" => $id_nomenclador_detalle))?>" class="paf-btn paf-btn-secondary" style="text-decoration:none; padding: 6px 12px; font-size: 12.5px;">
            Limpiar Filtro
          </a>
        <?php endif; ?>
      </div>

      <div class="paf-actions-group">
        <button type="button" class="paf-btn paf-btn-create" onclick="location.href='<?=encode_link("param_admin_fin.php", array("id_nomenclador_detalle" => $id_nomenclador_detalle, "nuevo" => 1))?>'">
          <span>✨</span> Nueva Prestación
        </button>
        <button type="button" class="paf-btn paf-btn-secondary" onclick="location.href='<?=encode_link("param_listado.php", array())?>'">
          <span>↩️</span> Volver a Nomencladores
        </button>
      </div>
    </div>

    <!-- ENCABEZADO DE LA TABLA -->
    <div class="paf-main-header">
      <div class="paf-main-title">
        <span style="font-size: 22px;">📋</span>
        <div>
          <div>Listado de Prestaciones</div>
          <?php if ($info_nd): ?>
            <div style="font-size: 13px; font-weight: 500; opacity: 0.95; margin-top: 2px;">
              <?=$info_nd['descripcion']?> &bull; Vigencia: <?=fecha($info_nd['fecha_desde'])?> al <?=fecha($info_nd['fecha_hasta'])?> (Modo <?=$info_nd['modo_facturacion']?>)
            </div>
          <?php endif; ?>
        </div>
      </div>
      <div style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
        <?php if (!empty($id_nomenclador_detalle)): ?>
          <span style="font-size: 12px; font-weight: 600; background: rgba(255,255,255,0.2); padding: 5px 12px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.3);">
            Nomenclador: <b>#<?=$id_nomenclador_detalle?></b>
          </span>
        <?php endif; ?>
        <span style="font-size: 12px; font-weight: 600; background: #fef08a; color: #854d0e; padding: 5px 12px; border-radius: 12px;">
          Total: <?=$total_muletos?> Prestaciones
        </span>
      </div>
    </div>

    <!-- TABLA DE RESULTADOS -->
    <div class="paf-card">
      <div class="paf-table-toolbar">
        <div><b>Total Prestaciones:</b> <span class="paf-count-pill"><?=$total_muletos?></span></div>
        <div><?=$link_pagina?></div>
      </div>

      <?php
      $result = sql($sql) or die;
      $active_sort = (!empty($sort)) ? $sort : "4";
      $arrow = ($up == '0') ? ' ▼' : ' ▲';
      ?>

      <table class="paf-table">
        <thead>
          <tr>
            <th width="6%"><a href='<?=encode_link("param_admin.php", array("sort" => "1", "up" => $up, "id_nomenclador_detalle" => $id_nomenclador_detalle))?>'>ID <?=($active_sort == "1" ? $arrow : "")?></a></th>
            <th width="8%"><a href='<?=encode_link("param_admin.php", array("sort" => "4", "up" => $up, "id_nomenclador_detalle" => $id_nomenclador_detalle))?>'>Código <?=($active_sort == "4" ? $arrow : "")?></a></th>
            <th width="6%"><a href='<?=encode_link("param_admin.php", array("sort" => "5", "up" => $up, "id_nomenclador_detalle" => $id_nomenclador_detalle))?>'>Grupo <?=($active_sort == "5" ? $arrow : "")?></a></th>
            <th width="15%"><a href='<?=encode_link("param_admin.php", array("sort" => "6", "up" => $up, "id_nomenclador_detalle" => $id_nomenclador_detalle))?>'>Subgrupo <?=($active_sort == "6" ? $arrow : "")?></a></th>
            <th width="39%"><a href='<?=encode_link("param_admin.php", array("sort" => "3", "up" => $up, "id_nomenclador_detalle" => $id_nomenclador_detalle))?>'>Descripción de la Prestación <?=($active_sort == "3" ? $arrow : "")?></a></th>
            <th width="11%" style="text-align: right;"><a href='<?=encode_link("param_admin.php", array("sort" => "7", "up" => $up, "id_nomenclador_detalle" => $id_nomenclador_detalle))?>'>Precio ($) <?=($active_sort == "7" ? $arrow : "")?></a></th>
            <th width="8%" style="text-align: center;"><a href='<?=encode_link("param_admin.php", array("sort" => "8", "up" => $up, "id_nomenclador_detalle" => $id_nomenclador_detalle))?>'>Estado <?=($active_sort == "8" ? $arrow : "")?></a></th>
            <th width="7%" style="text-align: center;">Acción</th>
          </tr>
        </thead>
        <tbody>
          <?php
          if ($result->RecordCount() == 0) {
          ?>
            <tr>
              <td colspan="8" style="text-align: center; padding: 36px 16px; color: #64748b;">
                <div style="font-size: 32px; margin-bottom: 8px;">🔍</div>
                <div style="font-size: 15px; font-weight: 600; color: #334155;">No se encontraron prestaciones</div>
                <div style="font-size: 13px; margin-top: 4px;">Intente ajustar el término de búsqueda o el filtro seleccionado.</div>
              </td>
            </tr>
          <?php
          } else {
              while (!$result->EOF) {
                  $id_nom = $result->fields['id_nomenclador'];
                  $cod = $result->fields['codigo'];
                  $grp = $result->fields['grupo'];
                  $subgrp = $result->fields['subgrupo'];
                  $desc = $result->fields['descripcion'];
                  $precio = number_format($result->fields['precio'], 2, ',', '.');
                  $is_activo = (strtolower(trim($result->fields['activo'])) != 'f' && $result->fields['activo'] != '0');

                  $ref = encode_link("param_admin_fin.php", array(
                      "id_nomenclador" => $id_nom,
                      "id_nomenclador_detalle" => $id_nomenclador_detalle
                  ));
                  $onclick_elegir = "location.href='$ref'";
          ?>
                <tr onclick="<?=$onclick_elegir?>" title="Haga clic para editar la prestación #<?=$id_nom?> (<?=$cod?>)">
                  <td><span class="paf-badge-id">#<?=$id_nom?></span></td>
                  <td><span class="paf-badge-code"><?=$cod?></span></td>
                  <td><span class="paf-badge-group"><?=$grp?></span></td>
                  <td style="color: #475569;"><?=$subgrp?></td>
                  <td style="font-weight: 500;"><?=$desc?></td>
                  <td class="paf-price-col">$ <?=$precio?></td>
                  <td style="text-align: center;">
                    <?php if ($is_activo): ?>
                      <span class="paf-badge-active">● ACTIVO</span>
                    <?php else: ?>
                      <span class="paf-badge-inactive">● INACTIVO</span>
                    <?php endif; ?>
                  </td>
                  <td style="text-align: center;">
                    <span class="paf-btn-edit">✏️ Editar</span>
                  </td>
                </tr>
          <?php
                  $result->MoveNext();
              }
          }
          ?>
        </tbody>
      </table>
    </div>

  </form>

</div>

<?php echo fin_pagina(); ?>
