USE labsis_seg;

CREATE TABLE IF NOT EXISTS autores(
	id INT NOT NULL AUTO_INCREMENT,
	nombre VARCHAR(100) NOT NULL,
	id_usuario INT,
	PRIMARY KEY (id)
)ENGINE=MyISAM DEFAULT CHARACTER SET=utf8;


-- Creo un autor por cada usuario del sistema
INSERT INTO autores(nombre, id_usuario)
SELECT nombre, id FROM usuarios;

ALTER TABLE articulos ADD COLUMN autor_creador INT;
ALTER TABLE historial_articulos ADD COLUMN id_autor INT;





