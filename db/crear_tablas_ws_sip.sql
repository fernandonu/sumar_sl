-- =============================================================================
-- MÓDULO SIP: Tablas para Importación de Consultas Perinatales vía Web Service
-- Esquema: sip_clap
-- Base de datos: sumar_sl (PostgreSQL)
-- Incluye persistencia integral JSONB y columnas de alto rendimiento para
-- indicadores sanitarios y facturación automática.
-- =============================================================================

-- Asegurar existencia del esquema
CREATE SCHEMA IF NOT EXISTS sip_clap;

-- -----------------------------------------------------------------------------
-- 1. Tabla de Auditoría / Log de Transacciones de Importación
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS sip_clap.ws_consultas_log (
    id_log SERIAL PRIMARY KEY,
    fecha_ejecucion TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    fecha_desde VARCHAR(10) NOT NULL,
    fecha_hasta VARCHAR(10) NOT NULL,
    registros_importados INTEGER DEFAULT 0,
    registros_guardados INTEGER DEFAULT 0,
    registros_errores INTEGER DEFAULT 0,
    estado VARCHAR(30) DEFAULT 'EN_PROCESO',
    usuario VARCHAR(100),
    mensaje TEXT,
    duracion_seg NUMERIC(8,2) DEFAULT 0,
    parametros_json TEXT
);

COMMENT ON TABLE sip_clap.ws_consultas_log IS 'Registro persistido de ejecuciones de importación de consultas SIP vía Web Service';
COMMENT ON COLUMN sip_clap.ws_consultas_log.estado IS 'Estado final de la transacción: EXITO, PARCIAL, ERROR o EN_PROCESO';

-- -----------------------------------------------------------------------------
-- 2. Tabla de Datos de Consultas Perinatales (Almacena datos completos y campos indexados)
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS sip_clap.ws_consultas_datos (
    id_ws_consulta BIGSERIAL PRIMARY KEY,
    id_log INTEGER REFERENCES sip_clap.ws_consultas_log(id_log) ON DELETE SET NULL,
    id_consulta_ws VARCHAR(50) UNIQUE NOT NULL,
    fecha_consulta TIMESTAMP WITHOUT TIME ZONE,
    fecha_consulta_raw VARCHAR(50),
    efector_codigo VARCHAR(50),
    efector_nombre VARCHAR(255),
    especialidad VARCHAR(255),
    paciente_documento VARCHAR(50),
    paciente_tipo_doc VARCHAR(20),
    paciente_nombre VARCHAR(255),
    paciente_sexo VARCHAR(10),
    paciente_fecha_nac VARCHAR(50),
    paciente_domicilio TEXT,
    paciente_localidad VARCHAR(255),
    paciente_provincia VARCHAR(255),
    profesional_matricula VARCHAR(50),
    profesional_nombre VARCHAR(255),
    motivo_consulta TEXT,

    -- Variables extraídas de control prenatal e indicadores (nomenclatura estándar SIP-CLAP)
    var_0119 VARCHAR(50),  -- Edad gestacional (semanas)
    var_0121 VARCHAR(50),  -- Presión arterial sistólica (mmHg)
    var_0394 VARCHAR(50),  -- Presión arterial diastólica (mmHg)
    var_0182 VARCHAR(50),  -- Parto / Aborto
    var_0113 VARCHAR(50),  -- Sífilis confirmada por FTA (treponémica)
    var_0115 VARCHAR(50),  -- Tratamiento sífilis
    var_0101 VARCHAR(50),  -- Chagas
    var_0092 VARCHAR(50),  -- VIH < 20 sem. realizado
    var_0094 VARCHAR(50),  -- VIH >= 20 sem. realizado
    var_0091 VARCHAR(50),  -- VIH < 20 sem. solicitado
    var_0093 VARCHAR(50),  -- VIH >= 20 sem. solicitado
    var_0116 VARCHAR(50),  -- Fecha del control prenatal
    var_0126 TEXT,         -- Observaciones / indicaciones control prenatal
    var_0200 VARCHAR(50),  -- FUM (Fecha de última menstruación - F200)
    var_0201 VARCHAR(50),  -- FPP (Fecha probable de parto - F201)

    -- Columnas con nombres descriptivos de negocio (para reportes y facturación directa)
    edad_gestacional VARCHAR(50),
    pa_sistolica VARCHAR(50),
    pa_diastolica VARCHAR(50),
    parto VARCHAR(50),
    sifilis_fta VARCHAR(50),
    tto_sifilis VARCHAR(50),
    chagas VARCHAR(50),
    vih_menor20_realizado VARCHAR(50),
    vih_mayor20_realizado VARCHAR(50),
    vih_menor20_solicitado VARCHAR(50),
    vih_mayor20_solicitado VARCHAR(50),
    fecha_control_prenatal VARCHAR(50),
    fum VARCHAR(50),
    fpp VARCHAR(50),

    -- Persistencia integral JSONB
    diagnosticos_json JSONB,
    datos_adicionales_json JSONB,
    sip_plus_values_json JSONB,
    datos_completos_json JSONB NOT NULL,
    fecha_insercion TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

COMMENT ON TABLE sip_clap.ws_consultas_datos IS 'Consultas perinatales importadas del Web Service SIP-CLAP con persistencia JSONB íntegra';
COMMENT ON COLUMN sip_clap.ws_consultas_datos.datos_completos_json IS 'Copia 100% íntegra del JSON recibido del endpoint de búsqueda';
COMMENT ON COLUMN sip_clap.ws_consultas_datos.id_consulta_ws IS 'Identificador único del registro en el Web Service del servidor de salud';

-- -----------------------------------------------------------------------------
-- 3. Índices de Alto Rendimiento para Consultas y Reportes
-- -----------------------------------------------------------------------------
CREATE INDEX IF NOT EXISTS idx_ws_consultas_fecha ON sip_clap.ws_consultas_datos(fecha_consulta);
CREATE INDEX IF NOT EXISTS idx_ws_consultas_doc ON sip_clap.ws_consultas_datos(paciente_documento);
CREATE INDEX IF NOT EXISTS idx_ws_consultas_efector ON sip_clap.ws_consultas_datos(efector_codigo);
CREATE INDEX IF NOT EXISTS idx_ws_consultas_log ON sip_clap.ws_consultas_datos(id_log);
CREATE INDEX IF NOT EXISTS idx_ws_consultas_ws_id ON sip_clap.ws_consultas_datos(id_consulta_ws);
CREATE INDEX IF NOT EXISTS idx_ws_consultas_eg ON sip_clap.ws_consultas_datos(var_0119);
CREATE INDEX IF NOT EXISTS idx_ws_consultas_pa_sis ON sip_clap.ws_consultas_datos(var_0121);
CREATE INDEX IF NOT EXISTS idx_ws_consultas_pa_dia ON sip_clap.ws_consultas_datos(var_0394);
CREATE INDEX IF NOT EXISTS idx_ws_consultas_parto ON sip_clap.ws_consultas_datos(var_0182);
CREATE INDEX IF NOT EXISTS idx_ws_consultas_chagas ON sip_clap.ws_consultas_datos(var_0101);
CREATE INDEX IF NOT EXISTS idx_ws_consultas_sifilis ON sip_clap.ws_consultas_datos(var_0113);
CREATE INDEX IF NOT EXISTS idx_ws_consultas_vih91 ON sip_clap.ws_consultas_datos(var_0091);
CREATE INDEX IF NOT EXISTS idx_ws_consultas_fec_ctrl ON sip_clap.ws_consultas_datos(var_0116);

-- -----------------------------------------------------------------------------
-- 4. Diccionario de Equivalencias de Variables SIP Plus (SIP-CLAP)
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS sip_clap.ws_variables_diccionario (
    codigo VARCHAR(10) PRIMARY KEY,
    nombre VARCHAR(120) NOT NULL,
    descripcion TEXT,
    ubicacion_respuesta VARCHAR(150),
    tabla_sip_relacionada VARCHAR(60),
    campo_sip_relacionado VARCHAR(60),
    tipo_dato VARCHAR(50),
    ejemplo TEXT
);

COMMENT ON TABLE sip_clap.ws_variables_diccionario IS 'Diccionario de equivalencias de códigos SIP Plus con el estándar SIP-CLAP';

INSERT INTO sip_clap.ws_variables_diccionario 
(codigo, nombre, descripcion, ubicacion_respuesta, tabla_sip_relacionada, campo_sip_relacionado, tipo_dato, ejemplo)
VALUES 
('0002', 'Apellido de la madre/paciente', 'Primer y segundo apellido materno registrado en la cartilla perinatal', 'sipPlusValues.0002 / patient.lastName', 'sip_clap.hcperinatal', 'var_0002', 'TEXT', 'GONZALEZ BECERRA'),
('0006', 'Fecha de nacimiento de la madre/paciente', 'Fecha de nacimiento de la gestante', 'sipPlusValues.0006 / patient.birthDate', 'sip_clap.hcperinatal', 'var_0006', 'DATE (DD/MM/YY)', '27/04/00'),
('0019', 'Documento de identidad de la madre/paciente', 'Número de DNI u otro documento identificatorio de la paciente', 'sipPlusValues.0019 / patient.document', 'sip_clap.hcperinatal', 'var_0019', 'VARCHAR(20)', '42486767'),
('0091', 'VIH < 20 sem. solicitado', 'Prueba de tamizaje de VIH solicitada antes de las 20 semanas de gestación', 'sipPlusValues.pregnancies.[n].0091', 'sip_clap.hcgestacion_actual', 'var_0091', 'VARCHAR(10)', '1'),
('0092', 'VIH < 20 sem. realizado', 'Prueba de tamizaje de VIH realizada antes de las 20 semanas de gestación', 'sipPlusValues.pregnancies.[n].0092', 'sip_clap.hcgestacion_actual', 'var_0092', 'VARCHAR(10)', '1'),
('0093', 'VIH >= 20 sem. solicitado', 'Prueba de tamizaje de VIH solicitada a partir de las 20 semanas de gestación', 'sipPlusValues.pregnancies.[n].0093', 'sip_clap.hcgestacion_actual', 'var_0093', 'VARCHAR(10)', '1'),
('0094', 'VIH >= 20 sem. realizado', 'Prueba de tamizaje de VIH realizada a partir de las 20 semanas de gestación', 'sipPlusValues.pregnancies.[n].0094', 'sip_clap.hcgestacion_actual', 'var_0094', 'VARCHAR(10)', '1'),
('0101', 'Chagas', 'Tamizaje antenatal para enfermedad de Chagas', 'sipPlusValues.pregnancies.[n].0101', 'sip_clap.hcgestacion_actual', 'var_0101', 'VARCHAR(10)', '0'),
('0113', 'Sífilis confirmada por FTA', 'Diagnóstico de sífilis confirmado por prueba treponémica (FTA-Abs / MHA-TP / ELISA)', 'sipPlusValues.pregnancies.[n].0113', 'sip_clap.hcgestacion_actual', 'var_0113', 'VARCHAR(10)', '0'),
('0115', 'Tratamiento sífilis', 'Tratamiento para sífilis administrado a la gestante durante el embarazo', 'sipPlusValues.pregnancies.[n].0115', 'sip_clap.hcgestacion_actual', 'var_0115', 'VARCHAR(10)', '3'),
('0116', 'Fecha de control prenatal', 'Fecha en la que se realizó la consulta o control prenatal antenatal', 'sipPlusValues.pregnancies.[n].prenatal.[n].0116', 'sip_clap.control_prenatal', 'var_0116', 'DATE (DD/MM/YY)', '10/10/24'),
('0119', 'Edad gestacional', 'Edad gestacional al momento del control prenatal (expresada en semanas)', 'sipPlusValues.pregnancies.[n].prenatal.[n].0119', 'sip_clap.control_prenatal', 'var_0119', 'INTEGER', '8'),
('0121', 'Presión arterial sistólica', 'Tensión / Presión arterial sistólica en el control prenatal (mmHg)', 'sipPlusValues.pregnancies.[n].prenatal.[n].0121', 'sip_clap.control_prenatal', 'var_0121', 'INTEGER', '110'),
('0126', 'Observaciones/indicaciones del control prenatal', 'Estudios solicitados, indicaciones médicas, ecografías, vacunas o tratamientos', 'sipPlusValues.pregnancies.[n].prenatal.[n].0126', 'sip_clap.control_prenatal', 'var_0126', 'TEXT', 'ANTIG FE+ SCAN F.'),
('0182', 'Parto / Aborto', 'Tipo de terminación del embarazo: Parto o Aborto', 'sipPlusValues.pregnancies.[n].0182', 'sip_clap.hcparto_aborto / hcperinatal', 'var_0182', 'VARCHAR(10)', 'A'),
('0283', 'Hora de nacimiento del recién nacido', 'Hora en que se produjo el nacimiento (terminación del parto)', 'sipPlusValues.pregnancies.[n].children.[n].0283', 'sip_clap.hcparto_aborto', 'var_0283', 'TIME (HH:MM)', '01:24'),
('0284', 'Fecha de nacimiento del recién nacido', 'Fecha del parto o terminación del embarazo', 'sipPlusValues.pregnancies.[n].children.[n].0284', 'sip_clap.hcparto_aborto / hcperinatal', 'var_0284', 'DATE (DD/MM/YY)', '21/5/25'),
('0332', 'Apellido del recién nacido', 'Apellido asignado al recién nacido', 'sipPlusValues.pregnancies.[n].children.[n].0332', 'sip_clap.hcrecien_nacido', 'var_0332', 'TEXT', 'MACIULIS'),
('0334', 'Nombre del recién nacido', 'Nombre de pila asignado al recién nacido', 'sipPlusValues.pregnancies.[n].children.[n].0334', 'sip_clap.hcrecien_nacido', 'var_0334', 'TEXT', 'ferrara'),
('0394', 'Presión arterial diastólica', 'Tensión / Presión arterial diastólica en el control prenatal (mmHg)', 'sipPlusValues.pregnancies.[n].prenatal.[n].0394', 'sip_clap.control_prenatal', 'var_0394', 'INTEGER', '70'),
('1018', 'País de nacimiento / Nacionalidad', 'Código del país de origen o nacionalidad de la madre (código ISO)', 'sipPlusValues.1018', 'sip_clap.hcperinatal', 'var_1018', 'VARCHAR(5)', 'AR'),
('F200', 'Fecha de última menstruación (FUM)', 'Fecha del primer día de la última menstruación conocida', 'sipPlusValues.F200', 'sip_clap.hcperinatal', 'var_0200', 'DATE (DD/MM/YY)', '11/12/24'),
('F201', 'Fecha probable de parto (FPP)', 'Fecha estimada de probable parto calculada por FUM o ecografía', 'sipPlusValues.F201', 'sip_clap.hcperinatal', 'var_0201', 'DATE (DD/MM/YY)', '23/5/25')
ON CONFLICT (codigo) DO UPDATE SET
    nombre = EXCLUDED.nombre,
    descripcion = EXCLUDED.descripcion,
    ubicacion_respuesta = EXCLUDED.ubicacion_respuesta,
    tabla_sip_relacionada = EXCLUDED.tabla_sip_relacionada,
    campo_sip_relacionado = EXCLUDED.campo_sip_relacionado,
    tipo_dato = EXCLUDED.tipo_dato,
    ejemplo = EXCLUDED.ejemplo;
