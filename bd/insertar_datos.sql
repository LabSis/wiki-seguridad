USE labsis_seg;
INSERT INTO tecnicas (nombre, id_padre) VALUES ("Injection", NULL);
INSERT INTO tecnicas (nombre, id_padre) VALUES ("SQL Injection", (SELECT id FROM tecnicas WHERE nombre="Injection"));
INSERT INTO tecnicas (nombre, id_padre) VALUES ("LDAP Injection", (SELECT id FROM tecnicas WHERE nombre="Injection"));
INSERT INTO tecnicas (nombre, id_padre) VALUES ("XML Injection", (SELECT id FROM tecnicas WHERE nombre="Injection"));
INSERT INTO tecnicas (nombre, id_padre) VALUES ("NoSQL Injection", (SELECT id FROM tecnicas WHERE nombre="Injection"));
INSERT INTO tecnicas (nombre, id_padre) VALUES ("Sitios cruzados", NULL);
INSERT INTO tecnicas (nombre, id_padre) VALUES ("Control de acceso", NULL);
INSERT INTO tecnicas (nombre, id_padre) VALUES ("Inclusión", NULL);