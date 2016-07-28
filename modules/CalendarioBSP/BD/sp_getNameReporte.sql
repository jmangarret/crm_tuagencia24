DROP PROCEDURE IF EXISTS getNameReporte;
DELIMITER |
CREATE DEFINER=`root`@`localhost` PROCEDURE `getNameReporte`(
    IN _desde DATE,
    IN _hasta DATE
	)
BEGIN 
	DECLARE NOMBREREPORTE VARCHAR(255);
    DECLARE _dia1 INT(50);
	DECLARE _dia2 INT(50); 
	DECLARE _mes VARCHAR(50);
    DECLARE _año INT(50);
    SET lc_time_names = 'es_VE';
    
    
    SET _dia1 = (SELECT DAY(_desde));
    SET _dia2 = (SELECT DAY(_hasta));
    SET _mes = (SELECT DATE_FORMAT(_desde, '%M'));
    SET _año = (SELECT YEAR(_desde));
    

    SET NOMBREREPORTE=  concat("Reporte del ",_dia1," al ",_dia2," de ",_mes," del ",_año);
    
	SET @NOMBREREPORTE= NOMBREREPORTE;
    
END |
DELIMITER ;
