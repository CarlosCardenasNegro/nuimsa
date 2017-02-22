CREATE DATABASE `u525741712_pacientes`;

--
-- Table structure for table `paciente`
--

CREATE TABLE paciente (
    id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    iniciales CHAR(10) NOT NULL,
    fecha DATE NOT NULL,
    hora TIME,
    exploracion varchar(255) NOT NULL,
    codigo_SAP CHAR(10)
);

--
-- Table structure for table `solicitud`
-- Un paciente solo podrá portar una solicitud
-- (Relationship --> One-to-One)
--

CREATE TABLE solicitud (
    id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    orden VARCHAR(1000),
    prioridad ENUM('ordinario','preferente','urgente'),
    estado ENUM('ambulatorio','ingresado'),
    interes BOOLEAN,
    paciente_id int(11) NOT NULL,
    FOREIGN KEY paciente_key_1 (paciente_id) REFERENCES paciente(id)
);  

--
-- Table structure for table `documento`
-- Una solicitud puede venir acompañada de uno o varios documentos
-- (Relationship --> One-to-Many)
--

CREATE TABLE documento (
    id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    url VARCHAR(255),
    solicitud_id int(11) NOT NULL,
    FOREIGN KEY documento_key_1 (solicitud_id) REFERENCES solicitud(id)
);

--
-- Table structure for table `anamnesis`
-- Información recogida al paciente
-- (Relationship --> One-to-One)
--

CREATE TABLE anamnesis (
    id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    acude_por SET ('poliartralgias', 'dolor', 'molestias', 'dificultad', 'sensaciones', 'protesis', 'artrodesis', 'fractura', 'oncologico'),
    otras VARCHAR(1000),
    precipitado VARCHAR(1000),
    paciente_id int(11) NOT NULL,
    FOREIGN KEY paciente_key_2 (paciente_id) REFERENCES paciente(id)    
);

--
-- Table structure for table `califica`
-- Información recogida al paciente
-- (Relationship --> One-to-One)
--

CREATE TABLE califica (
    id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    califica_como ENUM ('vago', 'leve', 'leve_moderado', 'moderado', 'moderado_severo', 'severo', 'severo_muy_sevedro', 'muy_severo', 'muy_severo_invalidante', 'invalidante'),
    paciente_id int(11) NOT NULL,
    FOREIGN KEY paciente_key_3 (paciente_id) REFERENCES paciente(id)    
);

--
-- Table structure for table `localiza`
-- Información recogida al paciente
-- (Relationship --> One-to-One)
--

CREATE TABLE localiza (
    id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    loc_anatomina VARCHAR(1000),
    loc_region VARCHAR(1000),
    paciente_id int(11) NOT NULL,
    FOREIGN KEY paciente_key_4 (paciente_id) REFERENCES paciente(id)    
);

--
-- Table structure for table `evolucion`
-- Información recogida al paciente
-- (Relationship --> One-to-One)
--

CREATE TABLE evolucion (
    id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    fecha_precisa DATE,
    tiempo_evolucion VARCHAR(255),
    temporalidad ENUM ('aguda', 'subaguda', 'cronica', 'larga', 'siempre'),
    desde_otra VARCHAR(1000),                                        
    paciente_id int(11) NOT NULL,
    FOREIGN KEY paciente_key_5 (paciente_id) REFERENCES paciente(id)
);

--
-- Table structure for table `motivo`
-- Información recogida al paciente
-- (Relationship --> One-to-One)
--

CREATE TABLE motivo (
    id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    no_antecedentes BOOLEAN,
    motivo_info VARCHAR(1000),
    paciente_id int(11) NOT NULL,
    FOREIGN KEY paciente_key_6 (paciente_id) REFERENCES paciente(id)
);

--
-- Table structure for table `clinica`
-- Información recogida al paciente
-- (Relationship --> One-to-One)
--

CREATE TABLE clinica (
    id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    clinica_info VARCHAR(1000),
    paciente_id int(11) NOT NULL,
    FOREIGN KEY paciente_key_7 (paciente_id) REFERENCES paciente(id)
);

--
-- Table structure for table `clinica`
-- Información recogida al paciente
-- (Relationship --> One-to-One)
--

CREATE TABLE indicedencias (
    id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    observaciones VARCHAR(1000),
    paciente_id int(11) NOT NULL,
    FOREIGN KEY paciente_key_8 (paciente_id) REFERENCES paciente(id)
);

--
-- Table structure for table `localiza_protesis`
-- Información recogida al paciente
-- (Relationship --> One-to-One)
--

CREATE TABLE localiza_protesis (
    id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    lat_pro ENUM ('hombro', 'codo', 'mano', 'cadera', 'rodilla', 'tobillo', 'pie'),
    paciente_id int(11) NOT NULL,
    FOREIGN KEY paciente_key_9 (paciente_id) REFERENCES paciente(id)
);
--
-- Table structure for table `lateralidad_protesis`
-- Información recogida al paciente
-- (Relationship --> One-to-Many)
--

CREATE TABLE lateralidad_protesis (
    id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    lat_pro ENUM ('derecha', 'izquierda', 'ambas'),
    paciente_id int(11) NOT NULL,
    FOREIGN KEY paciente_key_10 (paciente_id) REFERENCES paciente(id)
);

--
-- Table structure for table `evolucion_protesis`
-- Información recogida al paciente
-- (Relationship --> One-to-Many)
--

CREATE TABLE evolucion_protesis (
    id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    no_molestias BOOLEAN,
    fecha_recambio DATE,
    intensidad ENUM ('vago', 'leve', 'leve_moderado', 'moderado', 'moderado_severo', 'severo', 'severo_muy_sevedro', 'muy_severo', 'muy_severo_invalidante', 'invalidante'),            
    paciente_id int(11) NOT NULL,
    FOREIGN KEY paciente_key_11 (paciente_id) REFERENCES paciente(id)
);

--
-- Table structure for table `SDRC`
-- Información recogida al paciente
-- (Relationship --> One-to-One)
--

CREATE TABLE SDRC (
    id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    motivo ENUM ('trauma', 'cirugia', 'neuro', 'desconocido'),
    tiempo ENUM ('nada', 'corta','media', 'larga'),
    sdrc_evolucion ENUM ('menos', 'corto', 'medio', 'largo', 'muy_largo'),
    sensaciones SET ('dolor', 'temperatura', 'color', 'hinchazon', 'sudoracion', 'movimiento', 'faneras', 'temblor', 'funcion'),
    resultado BOOLEAN,
    paciente_id int(11) NOT NULL,
    FOREIGN KEY paciente_key_12 (paciente_id) REFERENCES paciente(id)
);
