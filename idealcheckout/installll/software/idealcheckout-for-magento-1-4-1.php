<?php

	class IDEALCHECKOUT_FOR_MAGENTO_1_4_1
	{
		// Return the software name
		public static function getSoftwareName()
		{
			return 'Magento 1.4.1+';
		}



		// Return the software code
		public static function getSoftwareCode()
		{
			return str_replace('_', '-', substr(basename(__FILE__), 0, -4));
		}



		// Return path to main cinfig file (if any)
		public static function getConfigFile()
		{
			return SOFTWARE_PATH . DS . 'app' . DS . 'etc' . DS . 'local.xml';
		}



		// Return path to main cinfig file (if any)
		public static function getConfigData()
		{
			$sConfigFile = self::getConfigFile();

			// Detect DB settings via configuration file
			if(is_file($sConfigFile))
			{
				return file_get_contents($sConfigFile);
			}

			return '';
		}



		// Find default database settings
		public static function getDatabaseSettings($aSettings)
		{
			$aSettings['db_prefix'] = 'mage_';
			$sConfigData = self::getConfigData();

			if(!empty($sConfigData))
			{
				$aSettings['db_host'] = self::cleanupCdata(IDEALCHECKOUT_INSTALL::getFileValue($sConfigData, '/<host>(.+)<\/host>/'));
				$aSettings['db_user'] = self::cleanupCdata(IDEALCHECKOUT_INSTALL::getFileValue($sConfigData, '/<username>(.+)<\/username>/'));
				$aSettings['db_pass'] = self::cleanupCdata(IDEALCHECKOUT_INSTALL::getFileValue($sConfigData, '/<password>(.+)<\/password>/'));
				$aSettings['db_name'] = self::cleanupCdata(IDEALCHECKOUT_INSTALL::getFileValue($sConfigData, '/<dbname>(.+)<\/dbname>/'));
				$aSettings['db_prefix'] = self::cleanupCdata(IDEALCHECKOUT_INSTALL::getFileValue($sConfigData, '/<table_prefix>(.+)<\/table_prefix>/'));
				$aSettings['db_type'] = self::cleanupCdata(IDEALCHECKOUT_INSTALL::getFileValue($sConfigData, '/<pdoType>(.+)<\/pdoType>/'));

				if(!in_array(strtolower($aSettings['db_type']), array('mysql', 'mysqli')))
				{
					$aSettings['db_type'] = (version_compare(PHP_VERSION, '5.3', '>') ? 'mysqli' : 'mysql');
				}
			}

			return $aSettings;
		}

		public static function cleanupCdata($sString)
		{
			$sString = trim($sString);

			if(strcasecmp(substr($sString, 0, 9), '<![CDATA[') === 0)
			{
				$sString = substr($sString, 9);
			}

			if(strcasecmp(substr($sString, -3), ']]>') === 0)
			{
				$sString = substr($sString, 0, -3);
			}

			$sString = str_replace(']]]]><![CDATA[>', ']]>', $sString);

			return $sString;
		}



		// See if current software == self::$sSoftwareCode
		public static function isSoftware()
		{
			$aFiles = array();
			$aFiles[] = SOFTWARE_PATH . DS . 'app/etc/local.xml';
			$aFiles[] = SOFTWARE_PATH . DS . 'app' . DS . 'code' . DS . 'community';
			$aFiles[] = SOFTWARE_PATH . DS . 'app' . DS . 'code' . DS . 'core' . DS . 'Mage';
			$aFiles[] = SOFTWARE_PATH . DS . 'app' . DS . 'code' . DS . 'core' . DS . 'Zend';

			foreach($aFiles as $sFile)
			{
				if(!is_file($sFile) && !is_dir($sFile))
				{
					return false;
				}
			}

			return true;
		}




		// Install plugin, return text
		public static function doInstall($aSettings)
		{
			IDEALCHECKOUT_INSTALL::doInstall($aSettings);
			return true;
		}



		// Install plugin, return text
		public static function getInstructions($aSettings)
		{
			$sHtml = '';
			$sHtml .= '<ol>';
			$sHtml .= '<li>Log in op de beheeromgeving van uw webshop.</li>';
			$sHtml .= '<li>Ga in het hoofdmenu naar System / Cache Management, en klik op "Flush Magento Cache".</li>';
			$sHtml .= '<li>Ga in het hoofdmenu naar System / Configuration, en klik vervolgens in het linker menu op Payment Methods.</li>';
			$sHtml .= '<li>Klik op de blauwe balk met de tekst "Ideal" om de algemene iDEAL instellingen te beheren.</li>';
			$sHtml .= '<li>Schakel de iDEAL betaalmethode in (Enabled=Yes) en vul een titel in voor de betaalmethode (Title=iDEAL). Klik daarna op de "Save Config"-knop</li>';
			$sHtml .= '<li>Herhaal stap 4 en 5 voor de overige betaalmethoden die u wilt aanbieden in uw webshop.</li>';
			$sHtml .= '</ol>';
			$sHtml .= '<p>&nbsp;</p>';
			$sHtml .= '<p>&nbsp;</p>';
			$sHtml .= '<h1>F.A.Q.</h1>';
			$sHtml .= '<h3>Magento URL Optimalisatie</h3>';
			$sHtml .= '<p>Deze module is ontwikkeld om te werken met geoptimaliseerde Magento URL\'s. Hierdoor is het noodzakelijk om URL optimalisatie in te schakelen voor een correcte werking van deze plug-in.</p>';
			$sHtml .= '<p>1. Log in op je beheeromgeving van <em>Magento</em>.</p>';
			$sHtml .= '<p>2. Ga in het hoofdmenu naar System / Configuration, en klik vervolgens in het linker menu op Web.</p>';
			$sHtml .= '<p>3. Klik op de blauwe balk met de tekst "Search Engines Optimization".</p>';
			$sHtml .= '<p>4. Zet "Use Web Server Rewrites" aan (Yes/True).</p>';
			$sHtml .= '<p>5. Controleer op de FTP omgeving dat de .htaccess die met Magento wordt meegeleverd actief is.</p>';
			$sHtml .= '<p>&nbsp;</p>';
			$sHtml .= '<h3>Magento in een subfolder</h3>';
			$sHtml .= '<p>Heb je Magento geinstalleerd in een subfolder, en niet in de hoofdmap van jou domein, dan worden bepaalde iframes in de beheeromgeving niet goed geladen. De betaalmethoden blijven gewoon werken, maar het ziet er raar uit.</p>';
			$sHtml .= '<p><i>Let op: Gebruik beslist geen taalcode als naam van je subfolder!!!</i></p>';
			$sHtml .= '<p>1. Open "/app/code/community/Mage/Idealcheckoutideal/etc/system.xml".</p>';
			$sHtml .= '<p>2. Op regel 15 wordt verwezen naar de URL "/idealcheckout/validate/ideal.php". Pas dit pad aan zodat deze verwijst naar de juiste locatie (/magento-folder/idealcheckout/validate/ideal.php).</p>';
			$sHtml .= '<p>3. Herhaal deze stap voor de overige betaalmethoden.</p>';
			$sHtml .= '<p>&nbsp;</p>';
			$sHtml .= '<h3>Magento Voorraadbeheer</h3>';
			$sHtml .= '<p>Maak je gebruik van Magento\'s voorraadbeheer, dan kun je aangeven dat bij het annuleren van een bestelling de voorraad moet worden teruggeboekt. Dit doe je als volgt:</p>';
			$sHtml .= '<p>1. Login op de beheeromgeving van Magento.</p>';
			$sHtml .= '<p>2. Ga in het hoofd menu naar Systeem / Configuratie (System/Configuration).</p>';
			$sHtml .= '<p>3. Ga in het menu rechts naar Catalogus / Vooraad (Catalog/Inventory).</p>';
			$sHtml .= '<p>4. Vul bij het onderdeel "Set Items\' Status to be In Stock When Order is Cancelled." Ja/Yes in.</p>';

			return $sHtml;
		}
	}

?>