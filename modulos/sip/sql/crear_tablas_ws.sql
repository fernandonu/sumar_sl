-- Esquema sip_clap: Tablas para Importación de Consultas vía Web Service

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
    diagnosticos_json JSONB,
    datos_adicionales_json JSONB,
    sip_plus_values_json JSONB,
    datos_completos_json JSONB NOT NULL,
    fecha_insercion TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX IF NOT EXISTS idx_ws_consultas_fecha ON sip_clap.ws_consultas_datos(fecha_consulta);
CREATE INDEX IF NOT EXISTS idx_ws_consultas_doc ON sip_clap.ws_consultas_datos(paciente_documento);
CREATE INDEX IF NOT EXISTS idx_ws_consultas_efector ON sip_clap.ws_consultas_datos(efector_codigo);
CREATE INDEX IF NOT EXISTS idx_ws_consultas_log ON sip_clap.ws_consultas_datos(id_log);
CREATE INDEX IF NOT EXISTS idx_ws_consultas_ws_id ON sip_clap.ws_consultas_datos(id_consulta_ws);

-- -----------------------------------------------------------------------------
-- Diccionario de Equivalencias de Variables SIP Plus (SIP-CLAP)
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
('0116', 'Fecha de control prenatal', 'Fecha en la que se realizó la consulta o control prenatal antenatal', 'sipPlusValues.pregnancies.[n].prenatal.[n].0116', 'sip_clap.control_prenatal', 'var_0116', 'DATE (DD/MM/YY)', '10/10/24'),
('0126', 'Observaciones/indicaciones del control prenatal', 'Estudios solicitados, indicaciones médicas, ecografías, vacunas o tratamientos', 'sipPlusValues.pregnancies.[n].prenatal.[n].0126', 'sip_clap.control_prenatal', 'var_0126', 'TEXT', 'ANTIG FE+ SCAN F.'),
('0283', 'Hora de nacimiento del recién nacido', 'Hora en que se produjo el nacimiento (terminación del parto)', 'sipPlusValues.pregnancies.[n].children.[n].0283', 'sip_clap.hcparto_aborto', 'var_0283', 'TIME (HH:MM)', '01:24'),
('0284', 'Fecha de nacimiento del recién nacido', 'Fecha del parto o terminación del embarazo', 'sipPlusValues.pregnancies.[n].children.[n].0284', 'sip_clap.hcparto_aborto / hcperinatal', 'var_0284', 'DATE (DD/MM/YY)', '21/5/25'),
('0332', 'Apellido del recién nacido', 'Apellido asignado al recién nacido', 'sipPlusValues.pregnancies.[n].children.[n].0332', 'sip_clap.hcrecien_nacido', 'var_0332', 'TEXT', 'MACIULIS'),
('0334', 'Nombre del recién nacido', 'Nombre de pila asignado al recién nacido', 'sipPlusValues.pregnancies.[n].children.[n].0334', 'sip_clap.hcrecien_nacido', 'var_0334', 'TEXT', 'ferrara'),
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
