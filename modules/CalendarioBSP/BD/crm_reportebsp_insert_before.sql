DROP TRIGGER IF EXISTS calendariobsp_before_insert;
DELIMITER |
CREATE TRIGGER calendariobsp_before_insert BEFORE INSERT ON vtiger_calendariobsp
FOR EACH ROW BEGIN  	
	CALL getNameReporte(NEW.fecha_desde,NEW.fecha_hasta);
	SET NEW.nombre = @NOMBREREPORTE;
END |
DELIMITER ;
