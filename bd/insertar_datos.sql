USE labsis_seg;

-- Técnicas padres
INSERT INTO tecnicas (nombre, id_padre) VALUES ("Injection", NULL);
INSERT INTO tecnicas (nombre, id_padre) VALUES ("Sitios cruzados", NULL);
INSERT INTO tecnicas (nombre, id_padre) VALUES ("Control de acceso", NULL);
INSERT INTO tecnicas (nombre, id_padre) VALUES ("Inclusión", NULL);

SET @injection = (SELECT id FROM tecnicas WHERE nombre="Injection");

-- Sub-técnicas 

INSERT INTO tecnicas (nombre, id_padre) VALUES ("SQL Injection", @injection);
INSERT INTO tecnicas (nombre, id_padre) VALUES ("LDAP Injection", @injection);
INSERT INTO tecnicas (nombre, id_padre) VALUES ("XML Injection", @injection);
INSERT INTO tecnicas (nombre, id_padre) VALUES ("NoSQL Injection", @injection);

