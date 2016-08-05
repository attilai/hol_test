<?php

	require_once(dirname(__FILE__) . '/include.php');

	$sHtml = '
<html>
	<head>
		<style type="text/css">

			h2
			{
				color: #000000;
				font-family: Arial;
				font-size: 15px;
				
				margin: 48px 0px 3px 0px;
			}

			p
			{
				color: #000000;
				font-family: Arial;
				font-size: 11px;
				
				margin: 0px 0px 12px 0px;
			}
			
			a
			{
				color: #000000;
				font-family: Arial;
				font-size: 11px;
			}
		
		</style>
	</head>
	<body>
		<p>Via deze plugin kunt u iDEAL betalingen ontvangen in uw webshop via diverse Payment Service Providers.</p>
		<p>Deze plugin is momenteel geconfigureerd om iDEAL transacties te verwerken via <b>' . htmlspecialchars($aGatewaySettings['GATEWAY_NAME']) . '</b>.<br>Meer informatie over deze Payment Service Provider vind u op <a href="' . htmlspecialchars($aGatewaySettings['GATEWAY_WEBSITE']) . '" target="_blank">' . htmlspecialchars($aGatewaySettings['GATEWAY_WEBSITE']) . '</a></p>';
	
	if($aGatewaySettings['GATEWAY_VALIDATION'])
	{
		$sHtml .= '
		<h2>Transacties Controleren</h2>
		<p>Controleer de status van alle openstaande transacties bij uw Payment Service Provider.<br><br><input type="button" value="Controleer openstaande transacties." onclick="javascript: window.open(\'validate.php\', \'popup\', \'directories=no,height=550,location=no,menubar=no,resizable=yes,scrollbars=yes,status=no,toolbar=no,width=750\');"></p>';
	}

	$sHtml .= '
		<h2>Over deze plugin</h2>
		<p>Deze iDEAL plugin is ontwikkeld door <a href="http://www.php-solutions.nl" target="_blank">PHP Solutions</a> en is GRATIS te downloaden via <a href="http://www.ideal-checkout.nl" target="_blank">http://www.ideal-checkout.nl</a>.<br>- Feedback en donaties worden zeer op prijs gesteld.<br>- Het gebruik van onze plugins/scripts is geheel op eigen risico.</p>

	</body>
</html>';
	
	echo $sHtml;

?>