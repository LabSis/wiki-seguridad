USE labsis_seg;

ALTER TABLE labsis_seg.tecnicas DROP COLUMN technique_type_id;
DROP TABLE techniques_types;

ALTER TABLE articulos MODIFY id_tecnica INT NULL;
ALTER TABLE articulos ADD COLUMN id_vulnerabilidad INT NULL;

CREATE TABLE IF NOT EXISTS vulnerabilidades(
    id INT NOT NULL AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    cantidad INT NOT NULL,
    PRIMARY KEY(id)
)ENGINE=MyISAM DEFAULT CHARACTER SET=utf8;

