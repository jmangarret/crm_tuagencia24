<?php
	require_once 'modules/Emails/mail.php';
	$parent_email="infotuagencia24@gmail.com";
	$HELPDESK_SUPPORT_NAME="Prueba name";
	$HELPDESK_SUPPORT_EMAIL_ID="informatica@tuagencia24.com";
	$subject="Correo de prueba";
	$email_body="<h3>Prueba contenido";
	send_mail(
		'HelpDesk',
		$parent_email,
		$HELPDESK_SUPPORT_NAME,
		$HELPDESK_SUPPORT_EMAIL_ID,
		$subject,
		$email_body
	);
?>