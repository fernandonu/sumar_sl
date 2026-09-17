<?php
require_once("../../config.php");

variables_form_busqueda("param_listado");

$fecha_hoy = date("Y-m-d H:i:s");
$fecha_hoy = fecha($fecha_hoy);

$orden = array(
    "default" => "1",
    "1" => "id_nomenclador_detalle",
    "2" => "descripcion",
    "3" => "modo_facturacion",
    "4" => "fecha_desde",
    "5" => "fecha_hasta",
    "6" => "activonomen",
);
$filtro = array(
    "descripcion" => "Descripción",
    "id_nomenclador_detalle::text" => "ID Nomenclador",
    "modo_facturacion::text" => "Modo Facturación",
);

$sql_tmp = "select * from facturacion.nomenclador_detalle";

echo $html_header;
?>
<style>
/* Estilos modernos y prolijos para param_listado - SUMAR San Luis */
.paf-wrapper {
    max-width: 1120px;
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
    font-size: 13.5px;
}
.paf-table th {
    background-color: #1e293b;
    color: #ffffff;
    font-weight: 600;
    padding: 11px 14px;
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
    padding: 11px 14px;
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
    padding: 3px 8px;
    background-color: #e0f2fe;
    color: #0369a1;
    border-radius: 4px;
    font-weight: 700;
    font-family: 'Consolas', monospace;
}
.paf-badge-active {
    display: inline-block;
    padding: 3px 9px;
    background-color: #dcfce7;
    color: #15803d;
    border-radius: 12px;
    font-weight: 700;
    font-size: 12px;
    border: 1px solid #bbf7d0;
}
.paf-badge-inactive {
    display: inline-block;
    padding: 3px 9px;
    background-color: #f1f5f9;
    color: #64748b;
    border-radius: 12px;
    font-weight: 600;
    font-size: 12px;
    border: 1px solid #cbd5e1;
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
.paf-btn-secondary {
    background: #f1f5f9;
    color: #334155;
    border: 1px solid #cbd5e1;
}
.paf-btn-secondary:hover {
    background: #e2e8f0;
    color: #0f172a;
}
.paf-btn-view {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 10px;
    background-color: #eff6ff;
    color: #1d4ed8;
    border: 1px solid #bfdbfe;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
    transition: all 0.15s ease;
}
.paf-table tr:hover .paf-btn-view {
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    color: #ffffff;
    border-color: #1d4ed8;
    box-shadow: 0 2px 4px rgba(37,99,235,0.25);
}
</style>

<div class="paf-wrapper">

  <form name="form1" action="param_listado.php" method="POST">
    
    <!-- BARRA DE BÚSQUEDA Y ACCIONES -->
    <div class="paf-filter-card">
      <div class="paf-filter-controls">
        <span style="font-size: 15px;">🔍</span>
        <?php list($sql, $total_muletos, $link_pagina, $up) = form_busqueda($sql_tmp, $orden, $filtro, $link_tmp, $where_tmp, "buscar"); ?>
        <button type="submit" name="buscar" class="paf-btn paf-btn-primary">
          Buscar
        </button>
        <?php if (!empty($keyword)): ?>
          <a href="param_listado.php" class="paf-btn paf-btn-secondary" style="text-decoration:none; padding: 6px 12px; font-size: 12.5px;">
            Limpiar Filtro
          </a>
        <?php endif; ?>
      </div>
      <div style="font-size: 12.5px; color: #64748b; display: flex; align-items: center; gap: 6px;">
        <span>💡</span>
        <span>Haga clic en cualquier fila para gestionar sus prestaciones</span>
      </div>
    </div>

    <!-- ENCABEZADO DE LA TABLA -->
    <div class="paf-main-header">
      <div class="paf-main-title">
        <span style="font-size: 22px;">📑</span>
        <span>Nomencladores de Prestaciones - SUMAR San Luis</span>
      </div>
      <div>
        <span style="font-size: 12px; font-weight: 600; background: rgba(255,255,255,0.2); padding: 5px 12px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.3);">
          Total: <?=$total_muletos?> Versiones
        </span>
      </div>
    </div>

    <!-- TABLA DE RESULTADOS -->
    <div class="paf-card">
      <div class="paf-table-toolbar">
        <div><b>Total Nomencladores:</b> <span class="paf-count-pill"><?=$total_muletos?></span></div>
        <div><?=$link_pagina?></div>
      </div>

      <?php
      $result = sql($sql) or die;
      $active_sort = (!empty($sort)) ? $sort : "1";
      $arrow = ($up == '0') ? ' ▼' : ' ▲';
      ?>

      <table class="paf-table">
        <thead>
          <tr>
            <th width="7%"><a href='<?=encode_link("param_listado.php", array("sort" => "1", "up" => $up))?>'>ID <?=($active_sort == "1" ? $arrow : "")?></a></th>
            <th width="35%"><a href='<?=encode_link("param_listado.php", array("sort" => "2", "up" => $up))?>'>Descripción del Período <?=($active_sort == "2" ? $arrow : "")?></a></th>
            <th width="10%" style="text-align: center;"><a href='<?=encode_link("param_listado.php", array("sort" => "3", "up" => $up))?>'>Modo <?=($active_sort == "3" ? $arrow : "")?></a></th>
            <th width="14%"><a href='<?=encode_link("param_listado.php", array("sort" => "4", "up" => $up))?>'>Fecha Desde <?=($active_sort == "4" ? $arrow : "")?></a></th>
            <th width="14%"><a href='<?=encode_link("param_listado.php", array("sort" => "5", "up" => $up))?>'>Fecha Hasta <?=($active_sort == "5" ? $arrow : "")?></a></th>
            <th width="10%" style="text-align: center;"><a href='<?=encode_link("param_listado.php", array("sort" => "6", "up" => $up))?>'>Estado <?=($active_sort == "6" ? $arrow : "")?></a></th>
            <th width="10%" style="text-align: center;">Acción</th>
          </tr>
        </thead>
        <tbody>
          <?php
          if ($result->RecordCount() == 0) {
          ?>
            <tr>
              <td colspan="7" style="text-align: center; padding: 36px 16px; color: #64748b;">
                <div style="font-size: 32px; margin-bottom: 8px;">🔍</div>
                <div style="font-size: 15px; font-weight: 600; color: #334155;">No se encontraron nomencladores</div>
                <div style="font-size: 13px; margin-top: 4px;">Intente ajustar los términos o el filtro de búsqueda.</div>
              </td>
            </tr>
          <?php
          } else {
              while (!$result->EOF) {
                  $id_det = $result->fields['id_nomenclador_detalle'];
                  $modo_fact = $result->fields['modo_facturacion'];
                  $desc = $result->fields['descripcion'];
                  $f_desde = fecha($result->fields['fecha_desde']);
                  $f_hasta = fecha($result->fields['fecha_hasta']);
                  $is_activo = (strtolower($result->fields['activonomen']) == 's');

                  $ref = encode_link("param_admin.php", array(
                      "id_nomenclador_detalle" => $id_det,
                      "modo_facturacion" => $modo_fact
                  ));
                  $onclick_elegir = "location.href='$ref'";
          ?>
                <tr onclick="<?=$onclick_elegir?>" title="Ver prestaciones del nomenclador #<?=$id_det?>">
                  <td><span class="paf-badge-id">#<?=$id_det?></span></td>
                  <td style="font-weight: 600; color: #1e3a8a;"><?=$desc?></td>
                  <td style="text-align: center; color: #64748b; font-weight: 500;">Modo <?=$modo_fact?></td>
                  <td><?=$f_desde?></td>
                  <td><?=$f_hasta?></td>
                  <td style="text-align: center;">
                    <?php if ($is_activo): ?>
                      <span class="paf-badge-active">● ACTIVO</span>
                    <?php else: ?>
                      <span class="paf-badge-inactive">HISTÓRICO</span>
                    <?php endif; ?>
                  </td>
                  <td style="text-align: center;">
                    <span class="paf-btn-view">👁️ Ver</span>
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
