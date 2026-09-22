# Informe Técnico y Diagnóstico del Módulo SIP

**Ubicación del Módulo:** `modulos/sip/`  
**Página Principal Analizada:** `modulos/sip/lotes_exportacion.php`  
**Fecha de Diagnóstico:** Septiembre 2026  
**Estado General:** Incompleto / No Operativo en Exportación e Importación

---

## 1. Descripción General del Módulo SIP

El módulo **SIP** es la implementación en el sistema provincial del **Sistema de Información Perinatal (SIP-CLAP)**, estándar internacional desarrollado por el Centro Latinoamericano de Perinatología, Salud de la Mujer y Reproductiva (CLAP/SMR - OPS/OMS).

### 1.1. Propósito Clínico y Administrativo
- **Registro Clínico Perinatal:** Digitaliza la **Historia Clínica Perinatal (HCP)**, registrando de forma integral el proceso de atención de la mujer embarazada y el recién nacido:
  - Identificación y datos sociodemográficos.
  - Antecedentes familiares, personales y obstétricos.
  - Gestación actual (consultas antenatales, laboratorio, serologías, curvas de alerta).
  - Parto / Aborto (trabajo de parto, terminación, anestesia).
  - Recién nacido (peso, edad gestacional, Apgar, reanimación, patologías).
  - Puerperio y egreso (materno y neonatal, anticoncepción postparto).
- **Articulación con el Programa SUMAR / Plan Nacer:**
  - Mediante `sipclap_funciones.php`, audita y genera prestaciones facturables (consultas prenatales, partos institucionales vaginales `IT Q001`, cesáreas `IT Q002`).

### 1.2. Mapa de Archivos y Componentes

| Archivo / Carpeta | Función |
| :--- | :--- |
| `ficha_sip.php` | Interfaz interactiva principal que reproduce el diseño de la cartilla perinatal amarilla oficial del CLAP. |
| `01_identificacion.php` a `12_egreso.php` | Módulos visuales que componen las distintas secciones de la Historia Clínica Perinatal. |
| `variables_libres.php` | Campos y variables adicionales configurables localmente. |
| `row_consulta.php` | Renderizador dinámico de filas para la grilla de consultas prenatales. |
| `guardar_ficha_sip.php` | Endpoint AJAX que procesa y almacena las secciones clínicas en PostgreSQL. |
| `sipclap_funciones.php` | Funciones de validación médica, cálculo de semanas gestacionales y reglas de negocio SUMAR. |
| `importacion_sip_form.php` | Formulario de carga para subir archivos de base de datos `.mdb`. |
| `importacion_sip.php` | Script para procesar e importar registros de una base Access externa hacia PostgreSQL. |
| `lotes_exportacion.php` | Consola administrativa para filtrar y solicitar la exportación de lotes de fichas. |
| `procesar_lote.php` | Script backend que genera la base `.mdb` y empaqueta las fichas para exportar. |
| `clases/HCPerinatal.php` | Modelo y persistencia principal de la historia perinatal (`sip_clap.hcperinatal`). |
| `clases/ConsultaPrenatal.php` | Modelo para controles prenatales (`sip_clap.control_prenatal`). |
| `clases/ControlParto.php` | Modelo para seguimiento del parto (`sip_clap.control_parto`). |
| `clases/ControlPuerperio.php` | Modelo para seguimiento postparto (`sip_clap.control_puerperio`). |
| `clases/VariablesLibres.php` | Modelo para variables abiertas (`sip_clap.hclibres`). |
| `clases/LotesProceso.php` | Modelo para registro y consulta de lotes procesados (`sip_clap.lotes_proceso`). |

---

## 2. Detalle de la Página `lotes_exportacion.php`

La página `lotes_exportacion.php` fue diseñada para permitir al usuario exportar fichas perinatales cerradas hacia una base de datos Microsoft Access (`.mdb`), compatible con el software oficial de escritorio del SIP-CLAP.

### Flujo previsto:
1. **Filtro temporal:** Selector de mes/año Desde y Hasta mediante jQuery UI Datepicker (formato `MM yy`).
2. **Filtro por Efector:** Menú desplegable con los hospitales asignados al usuario en sesión.
3. **Disparo:** El botón **PROCESAR LOTE** envía los datos vía AJAX POST a `procesar_lote.php`.
4. **Procesamiento backend (`procesar_lote.php`):**
   - Busca fichas perinatales finalizadas en el rango de fechas (`finalizado = 1`).
   - Copia la base plantilla `sip_clap_base.mdb` a `sip_clap_envio.mdb`.
   - Abre conexión OLEDB (`ado_access`) hacia el archivo Access.
   - Inserta los registros en las tablas Access (`nivel_01` a `nivel_06`).
   - Marca las fichas en PostgreSQL como procesadas (`procesado = 1`).
   - Registra el lote en `sip_clap.lotes_proceso`.
5. **Respuesta al usuario:** Agrega la fila a la tabla histórica y abre automáticamente el enlace para descargar el archivo `.mdb`.

---

## 3. Diagnóstico y Determinación: ¿Funciona o no?

> ### **DETERMINACIÓN TÉCNICA: NO FUNCIONA**
> La página y el circuito de exportación se encuentran en un estado **completamente inoperable**. En la base de datos existen **0 registros** en `sip_clap.lotes_proceso`, lo que corrobora que nunca pudo completarse una exportación en este entorno.

---

## 4. Causas Técnicas y Errores Detectados

### 4.1. Errores Bloqueantes Críticos

1. **Ambigüedad de columna SQL en `getFichasByPeriodo()`**:
   - **Archivo:** `clases/HCPerinatal.php` (línea 2429).
   - **Consulta:**
     ```sql
     SELECT id_hcperinatal FROM sip_clap.hcperinatal
     inner join sip_clap.hcparto_aborto using(id_hcperinatal)
     WHERE var_0284 between '$desde' AND '$hasta' AND finalizado = 1
     ```
   - **Falla:** La columna `var_0284` (fecha de terminación) existe tanto en `sip_clap.hcperinatal` como en `sip_clap.hcparto_aborto`. PostgreSQL rechaza la consulta con el error fatal:
     `ERROR: column reference "var_0284" is ambiguous`.
   - **Consecuencia:** La consulta siempre falla y retorna `false`. El backend interpreta que no hay registros y responde `0`, mostrando al usuario el alerta: *"No hay fichas para procesar con el criterio seleccionado."* (a pesar de que hay más de 12.000 fichas finalizadas en la base).

2. **Inexistencia de la plantilla Access (`sip_clap_base.mdb`) y de la carpeta `sip_base`**:
   - **Archivo:** `procesar_lote.php` (línea 34).
   - **Código:**
     ```php
     $folder_path = MOD_DIR . "/sip_clap/sip_base/";
     $base_sip = $folder_path . 'sip_clap_base.mdb';
     ```
   - **Falla:** La ruta apunta a `/modulos/sip_clap/` (el módulo real se llama `/modulos/sip/`), el directorio `sip_base/` no existe, y el archivo `sip_clap_base.mdb` no existe en ningún directorio del servidor.
   - **Consecuencia:** Al no existir la plantilla base, el script responde `'No se puede encontrar Base origen'`.

3. **Bloqueo y congelamiento de la interfaz AJAX por error de parseo JSON**:
   - **Archivo:** `lotes_exportacion.php` (líneas 157-197).
   - **Falla:** AJAX espera respuesta con `dataType: "JSON"`. Cuando el backend devuelve respuestas en texto plano (`"No se puede encontrar Base origen"`, `"No se puede conectar a Access"`, etc.), jQuery lanza un `parsererror`.
   - **Consecuencia:** La función `success:` no se dispara, no existe manejador `error:`, y la animación de carga (`loading.gif`) queda girando indefinidamente sin informar el error.

4. **Inexistencia del script de descarga (`lib/ver_archivo.php`)**:
   - **Archivos:** `procesar_lote.php` (líneas 585 y 602) y `lotes_exportacion.php` (línea 106).
   - **Código:**
     ```php
     $linkMdb = encode_link("../../lib/ver_archivo.php", $path_envio_zip);
     ```
   - **Falla:** El archivo `c:\sistemas\nacer_sl\lib\ver_archivo.php` no existe en el sistema.
   - **Consecuencia:** Si se generase el archivo, al intentar abrir o descargar la URL se produce un error HTTP 404.

---

### 4.2. Errores Secundarios y Defectos de Construcción

5. **Inversión en la inclusión de cabeceras:**
   - En `lotes_exportacion.php` (líneas 1-3), se hace `echo $html_header;` antes de `require_once("../../config.php");`. En esa línea `$html_header` aún no existe, disparando un aviso `Notice: Undefined variable` y rompiendo el envío de headers HTTP.
6. **Archivos CSS y sprites inexistentes:**
   - Las líneas 27 y 28 referencian `../../lib/css/sprites.css` y `../../lib/css/general.css`. Dicho directorio no existe, por lo que las clases `.sprite-gral.icon-download` no muestran ningún icono.
7. **Botón de descarga comentado en la tabla histórica:**
   - La línea 106 de `lotes_exportacion.php` tiene el enlace de descarga comentado con código PHP roto dentro del comentario HTML. El usuario no tiene forma de descargar lotes de la tabla.
8. **Variables sin inicializar:**
   - `$listEfectores` no se declara como arreglo vacío previo al ciclo, provocando `Warning: Invalid argument supplied for foreach()` si el usuario no tiene hospitales asignados.
   - En `clases/LotesProceso.php`, el método `getLotes()` no inicializa `$lotes = array();`. Con 0 lotes en la base retorna `null`, disparando advertencias con `count($lotes)`.
9. **Colisión de concurrencia:**
   - El archivo temporal se llama de forma fija `sip_clap_envio.mdb`. Si dos usuarios procesaran a la vez, se sobreescribirían mutuamente.
10. **Falla en el módulo de Importación (`importacion_sip.php`):**
    - En la línea 3 ejecuta `require_once("../inmunizacion/Clases/clases.php");`, pero la carpeta `modulos/inmunizacion` no existe en el sistema, produciendo un `Fatal Error` inmediato.

---

## 5. Acciones Requeridas para su Puesta en Marcha

Para que el proceso de exportación sea completamente funcional se debe:

1. **Corregir la consulta SQL en `clases/HCPerinatal.php`**:
   - Calificar explícitamente `hcparto_aborto.var_0284` para resolver la ambigüedad.
2. **Proveer la base Access plantilla**:
   - Crear el directorio `modulos/sip/sip_base/` y colocar el archivo `sip_clap_base.mdb` con la estructura de tablas estándar del CLAP (`nivel_01` a `nivel_06`).
   - Actualizar la ruta en `procesar_lote.php` a `MOD_DIR . "/sip/sip_base/"`.
3. **Crear o conectar el script de descarga**:
   - Crear `lib/ver_archivo.php` que reciba los parámetros cifrados con `encode_link` y despache el archivo mediante cabeceras `Content-Type: application/octet-stream` y `Content-Disposition: attachment`.
4. **Normalizar el protocolo AJAX / JSON**:
   - Asegurar que `procesar_lote.php` siempre responda JSON válido (`{"status": "ok", ...}` o `{"status": "error", "message": "..."}`).
   - Agregar el callback `error:` en `lotes_exportacion.php` para apagar el indicador de carga y mostrar errores de red/servidor.
5. **Corregir inclusión de `$html_header`**:
   - Colocar `require_once("../../config.php");` antes de imprimir `$html_header`.
