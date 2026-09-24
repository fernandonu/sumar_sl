-- =============================================================================
-- SCRIPT DE MIGRACIÓN: Nuevos Atributos de Consultas SIP Web Service
-- Esquema: sip_clap
-- Tablas: ws_consultas_datos, ws_variables_diccionario
-- Base de datos: sumar_sl (PostgreSQL)
-- Objetivo: Soporte para generación de archivos de indicadores sanitarios
--           y proceso futuro de facturación automática.
-- =============================================================================

BEGIN;

-- 1. Agregar nuevas columnas a sip_clap.ws_consultas_datos
--    Se agregan tanto con nomenclatura estándar SIP-CLAP (var_XXXX)
--    como con nombres descriptivos de negocio para facilitar consultas SQL directas.

ALTER TABLE sip_clap.ws_consultas_datos
    -- Códigos oficiales SIP-CLAP solicitados
    ADD COLUMN IF NOT EXISTS var_0119 VARCHAR(50),  -- Edad gestacional (semanas)
    ADD COLUMN IF NOT EXISTS var_0121 VARCHAR(50),  -- Presión arterial sistólica (mmHg)
    ADD COLUMN IF NOT EXISTS var_0394 VARCHAR(50),  -- Presión arterial diastólica (mmHg)
    ADD COLUMN IF NOT EXISTS var_0182 VARCHAR(50),  -- Parto / Aborto
    ADD COLUMN IF NOT EXISTS var_0113 VARCHAR(50),  -- Sífilis confirmada por FTA (treponémica)
    ADD COLUMN IF NOT EXISTS var_0115 VARCHAR(50),  -- Tratamiento sífilis
    ADD COLUMN IF NOT EXISTS var_0101 VARCHAR(50),  -- Chagas
    ADD COLUMN IF NOT EXISTS var_0092 VARCHAR(50),  -- VIH < 20 sem. realizado
    ADD COLUMN IF NOT EXISTS var_0094 VARCHAR(50),  -- VIH >= 20 sem. realizado
    ADD COLUMN IF NOT EXISTS var_0091 VARCHAR(50),  -- VIH < 20 sem. solicitado
    ADD COLUMN IF NOT EXISTS var_0093 VARCHAR(50),  -- VIH >= 20 sem. solicitado
    
    -- Variables complementarias del control prenatal y embarazo
    ADD COLUMN IF NOT EXISTS var_0116 VARCHAR(50),  -- Fecha del control prenatal
    ADD COLUMN IF NOT EXISTS var_0126 TEXT,         -- Observaciones / indicaciones del control prenatal
    ADD COLUMN IF NOT EXISTS var_0200 VARCHAR(50),  -- FUM (Fecha de última menstruación - F200)
    ADD COLUMN IF NOT EXISTS var_0201 VARCHAR(50),  -- FPP (Fecha probable de parto - F201)

    -- Columnas con nombres descriptivos (alias de negocio para facturación e indicadores)
    ADD COLUMN IF NOT EXISTS edad_gestacional VARCHAR(50),
    ADD COLUMN IF NOT EXISTS pa_sistolica VARCHAR(50),
    ADD COLUMN IF NOT EXISTS pa_diastolica VARCHAR(50),
    ADD COLUMN IF NOT EXISTS parto VARCHAR(50),
    ADD COLUMN IF NOT EXISTS sifilis_fta VARCHAR(50),
    ADD COLUMN IF NOT EXISTS tto_sifilis VARCHAR(50),
    ADD COLUMN IF NOT EXISTS chagas VARCHAR(50),
    ADD COLUMN IF NOT EXISTS vih_menor20_realizado VARCHAR(50),
    ADD COLUMN IF NOT EXISTS vih_mayor20_realizado VARCHAR(50),
    ADD COLUMN IF NOT EXISTS vih_menor20_solicitado VARCHAR(50),
    ADD COLUMN IF NOT EXISTS vih_mayor20_solicitado VARCHAR(50),
    ADD COLUMN IF NOT EXISTS fecha_control_prenatal VARCHAR(50),
    ADD COLUMN IF NOT EXISTS fum VARCHAR(50),
    ADD COLUMN IF NOT EXISTS fpp VARCHAR(50);

-- 2. Documentar columnas
COMMENT ON COLUMN sip_clap.ws_consultas_datos.var_0119 IS 'Edad gestacional en semanas al momento del control prenatal (VAR_0119)';
COMMENT ON COLUMN sip_clap.ws_consultas_datos.var_0121 IS 'Presión arterial sistólica en mmHg (VAR_0121)';
COMMENT ON COLUMN sip_clap.ws_consultas_datos.var_0394 IS 'Presión arterial diastólica en mmHg (VAR_0394)';
COMMENT ON COLUMN sip_clap.ws_consultas_datos.var_0182 IS 'Terminación del embarazo: Parto o Aborto (VAR_0182)';
COMMENT ON COLUMN sip_clap.ws_consultas_datos.var_0113 IS 'Diagnóstico de sífilis confirmada por FTA-Abs / prueba treponémica (VAR_0113)';
COMMENT ON COLUMN sip_clap.ws_consultas_datos.var_0115 IS 'Tratamiento de sífilis administrado en el embarazo (VAR_0115)';
COMMENT ON COLUMN sip_clap.ws_consultas_datos.var_0101 IS 'Tamizaje antenatal para enfermedad de Chagas (VAR_0101)';
COMMENT ON COLUMN sip_clap.ws_consultas_datos.var_0092 IS 'Prueba VIH < 20 semanas realizada (VAR_0092)';
COMMENT ON COLUMN sip_clap.ws_consultas_datos.var_0094 IS 'Prueba VIH >= 20 semanas realizada (VAR_0094)';
COMMENT ON COLUMN sip_clap.ws_consultas_datos.var_0091 IS 'Prueba VIH < 20 semanas solicitada (VAR_0091)';
COMMENT ON COLUMN sip_clap.ws_consultas_datos.var_0093 IS 'Prueba VIH >= 20 semanas solicitada (VAR_0093)';
COMMENT ON COLUMN sip_clap.ws_consultas_datos.var_0116 IS 'Fecha en que se realizó el control prenatal (VAR_0116)';
COMMENT ON COLUMN sip_clap.ws_consultas_datos.var_0126 IS 'Observaciones, indicaciones médicas o estudios del control prenatal (VAR_0126)';
COMMENT ON COLUMN sip_clap.ws_consultas_datos.var_0200 IS 'Fecha de última menstruación (FUM / F200)';
COMMENT ON COLUMN sip_clap.ws_consultas_datos.var_0201 IS 'Fecha probable de parto (FPP / F201)';

-- 3. Índices de alto rendimiento para agilizar consultas de indicadores y facturación
CREATE INDEX IF NOT EXISTS idx_ws_consultas_eg ON sip_clap.ws_consultas_datos(var_0119);
CREATE INDEX IF NOT EXISTS idx_ws_consultas_pa_sis ON sip_clap.ws_consultas_datos(var_0121);
CREATE INDEX IF NOT EXISTS idx_ws_consultas_pa_dia ON sip_clap.ws_consultas_datos(var_0394);
CREATE INDEX IF NOT EXISTS idx_ws_consultas_parto ON sip_clap.ws_consultas_datos(var_0182);
CREATE INDEX IF NOT EXISTS idx_ws_consultas_chagas ON sip_clap.ws_consultas_datos(var_0101);
CREATE INDEX IF NOT EXISTS idx_ws_consultas_sifilis ON sip_clap.ws_consultas_datos(var_0113);
CREATE INDEX IF NOT EXISTS idx_ws_consultas_vih91 ON sip_clap.ws_consultas_datos(var_0091);
CREATE INDEX IF NOT EXISTS idx_ws_consultas_fec_ctrl ON sip_clap.ws_consultas_datos(var_0116);

-- 4. Actualizar tabla sip_clap.ws_variables_diccionario con las 11 nuevas variables
INSERT INTO sip_clap.ws_variables_diccionario 
(codigo, nombre, descripcion, ubicacion_respuesta, tabla_sip_relacionada, campo_sip_relacionado, tipo_dato, ejemplo)
VALUES 
('0119', 'Edad gestacional', 'Edad gestacional al momento del control prenatal (expresada en semanas)', 'sipPlusValues.pregnancies.[n].prenatal.[n].0119', 'sip_clap.control_prenatal', 'var_0119', 'INTEGER', '8'),
('0121', 'Presión arterial sistólica', 'Tensión / Presión arterial sistólica en el control prenatal (mmHg)', 'sipPlusValues.pregnancies.[n].prenatal.[n].0121', 'sip_clap.control_prenatal', 'var_0121', 'INTEGER', '110'),
('0394', 'Presión arterial diastólica', 'Tensión / Presión arterial diastólica en el control prenatal (mmHg)', 'sipPlusValues.pregnancies.[n].prenatal.[n].0394', 'sip_clap.control_prenatal', 'var_0394', 'INTEGER', '70'),
('0182', 'Parto / Aborto', 'Tipo de terminación del embarazo: Parto o Aborto', 'sipPlusValues.pregnancies.[n].0182', 'sip_clap.hcparto_aborto / hcperinatal', 'var_0182', 'VARCHAR(10)', 'A'),
('0113', 'Sífilis confirmada por FTA', 'Diagnóstico de sífilis confirmado por prueba treponémica (FTA-Abs / MHA-TP / ELISA)', 'sipPlusValues.pregnancies.[n].0113', 'sip_clap.hcgestacion_actual', 'var_0113', 'VARCHAR(10)', '0'),
('0115', 'Tratamiento sífilis', 'Tratamiento para sífilis administrado a la gestante durante el embarazo', 'sipPlusValues.pregnancies.[n].0115', 'sip_clap.hcgestacion_actual', 'var_0115', 'VARCHAR(10)', '3'),
('0101', 'Chagas', 'Tamizaje antenatal para enfermedad de Chagas', 'sipPlusValues.pregnancies.[n].0101', 'sip_clap.hcgestacion_actual', 'var_0101', 'VARCHAR(10)', '0'),
('0091', 'VIH < 20 sem. solicitado', 'Prueba de tamizaje de VIH solicitada antes de las 20 semanas de gestación', 'sipPlusValues.pregnancies.[n].0091', 'sip_clap.hcgestacion_actual', 'var_0091', 'VARCHAR(10)', '1'),
('0092', 'VIH < 20 sem. realizado', 'Prueba de tamizaje de VIH realizada antes de las 20 semanas de gestación', 'sipPlusValues.pregnancies.[n].0092', 'sip_clap.hcgestacion_actual', 'var_0092', 'VARCHAR(10)', '1'),
('0093', 'VIH >= 20 sem. solicitado', 'Prueba de tamizaje de VIH solicitada a partir de las 20 semanas de gestación', 'sipPlusValues.pregnancies.[n].0093', 'sip_clap.hcgestacion_actual', 'var_0093', 'VARCHAR(10)', '1'),
('0094', 'VIH >= 20 sem. realizado', 'Prueba de tamizaje de VIH realizada a partir de las 20 semanas de gestación', 'sipPlusValues.pregnancies.[n].0094', 'sip_clap.hcgestacion_actual', 'var_0094', 'VARCHAR(10)', '1')
ON CONFLICT (codigo) DO UPDATE SET
    nombre = EXCLUDED.nombre,
    descripcion = EXCLUDED.descripcion,
    ubicacion_respuesta = EXCLUDED.ubicacion_respuesta,
    tabla_sip_relacionada = EXCLUDED.tabla_sip_relacionada,
    campo_sip_relacionado = EXCLUDED.campo_sip_relacionado,
    tipo_dato = EXCLUDED.tipo_dato,
    ejemplo = EXCLUDED.ejemplo;

-- 5. Backfill seguro para registros existentes en ws_consultas_datos
--    Extrae FUM, FPP y valores ya persistidos en el JSON sip_plus_values_json
UPDATE sip_clap.ws_consultas_datos
SET 
    var_0200 = COALESCE(var_0200, sip_plus_values_json->>'F200'),
    fum = COALESCE(fum, sip_plus_values_json->>'F200'),
    var_0201 = COALESCE(var_0201, sip_plus_values_json->>'F201'),
    fpp = COALESCE(fpp, sip_plus_values_json->>'F201')
WHERE sip_plus_values_json IS NOT NULL 
  AND (var_0200 IS NULL OR var_0201 IS NULL);

COMMIT;
