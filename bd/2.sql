USE labsis_seg;

ALTER TABLE labsis_seg.vulnerabilidades ADD COLUMN disenio INT;
ALTER TABLE labsis_seg.vulnerabilidades ADD COLUMN codigo INT;
ALTER TABLE labsis_seg.vulnerabilidades ADD COLUMN configuracion INT;
ALTER TABLE labsis_seg.vulnerabilidades DROP COLUMN cantidad;

