USE labsis_seg;

-- Tipos de técnicas
INSERT INTO techniques_types (nombre) VALUES ('Situaciones comunes');
INSERT INTO techniques_types (nombre) VALUES ('Patrones de seguridad');
INSERT INTO techniques_types (nombre) VALUES ('Técnicas de ataque');

SET @regular_situations = (SELECT id FROM techniques_types WHERE nombre='Situaciones comunes');
SET @security_patterns = (SELECT id FROM techniques_types WHERE nombre='Patrones de seguridad');
SET @attacks_techniques = (SELECT id FROM techniques_types WHERE nombre='Técnicas de ataque');

-- Técnicas padres
INSERT INTO tecnicas (nombre, id_padre, technique_type_id) VALUES ('Inyección', NULL, @attacks_techniques);
INSERT INTO tecnicas (nombre, id_padre, technique_type_id) VALUES ('Sitios cruzados', NULL, @attacks_techniques);
INSERT INTO tecnicas (nombre, id_padre, technique_type_id) VALUES ('Manipulación de datos', NULL, @attacks_techniques);
INSERT INTO tecnicas (nombre, id_padre, technique_type_id) VALUES ('Control de acceso', NULL, @attacks_techniques);
INSERT INTO tecnicas (nombre, id_padre, technique_type_id) VALUES ('Inclusión', NULL, @attacks_techniques);

SET @injection = (SELECT id FROM tecnicas WHERE nombre='Inyección');
SET @tampering = (SELECT id FROM tecnicas WHERE nombre='Manipulación de datos');

-- Sub-técnicas 

INSERT INTO tecnicas (nombre, id_padre) VALUES ('SQL Injection',        @injection);
INSERT INTO tecnicas (nombre, id_padre) VALUES ('SQL Injection ciego',  @injection);
INSERT INTO tecnicas (nombre, id_padre) VALUES ('LDAP Injection',       @injection);
INSERT INTO tecnicas (nombre, id_padre) VALUES ('XML Injection',        @injection);
INSERT INTO tecnicas (nombre, id_padre) VALUES ('XML Injection ciego',  @injection);
INSERT INTO tecnicas (nombre, id_padre) VALUES ('NoSQL Injection',      @injection);
INSERT INTO tecnicas (nombre, id_padre) VALUES ('Format string attack', @injection);

INSERT INTO tecnicas (nombre, id_padre) VALUES ('Web parameter tampering', @tampering);



