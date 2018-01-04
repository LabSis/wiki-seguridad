USE labsis_seg;

CREATE TABLE usuarios(
    id INT NOT NULL AUTO_INCREMENT,
    nombre VARCHAR(70) NOT NULL,
    clave BLOB NOT NULL,
    PRIMARY KEY(id)
)ENGINE MyISAM DEFAULT CHARACTER SET=utf8;

INSERT INTO usuarios (nombre, clave) VALUES('admin', SHA2('admin', 256));
