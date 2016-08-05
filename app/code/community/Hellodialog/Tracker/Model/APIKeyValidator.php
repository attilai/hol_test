<?php
	class APIKeyValidator {

		public static function validate($key) {
			$key = trim($key);
			if ($key == '') {
				return self::render("Please provide an API Key.", "warning");
			}

			if (!class_exists('HDApi', false)) {
				require_once dirname(__FILE__).'/HDApi.php';
			}

			HDApi::setToken($key);
			$ping = new HDApi('ping');
			$result = $ping->get();

			if (!is_object($result)) {
				return self::render("Could not validate API settings, unexpected response from API to 'ping'-command.", "warning");
			}

			if (isset($result->result) && is_object($result->result) && isset($result->result->message)) {
				return self::render("Unexpected result from API:", "warning")."<pre>".$result->result->message."</pre>";
			}

			if (isset($result->access) && is_object($result->access)) {
				$access = array();

				if (isset($result->access->all) && is_array($result->access->all) && in_array('*', $result->access->all)) {
					$access[] = self::render("Access OK (Full access)", "ok");
				} else {
					// separately validate access to "contacts" and "orders"

					$orders   = (isset($result->access->orders) && is_array($result->access->orders) && (in_array('*', $result->access->orders) || in_array('POST', $result->access->orders)));
					$contacts = (isset($result->access->contacts) && is_array($result->access->contacts) && (in_array('*', $result->access->contacts) || (in_array('GET', $result->access->contacts) && in_array('POST', $result->access->contacts) && in_array('PUT', $result->access->contacts))));
					$fields   = (isset($result->access->fields) && is_array($result->access->fields) && (in_array('*', $result->access->fields) || in_array('GET', $result->access->fields)));

					if ($orders && $contacts && $fields) {
						$access[] = self::render("Access OK", "ok");
					} else {
						if ($orders) {
							$access[] = self::render("API has access to eCommerce", "ok");
						} else {
							$access[] = self::render("API can't access eCommerce", "warning");
						}

						if ($contacts) {
							$access[] = self::render("API has access to Contacts", "ok");
						} else {
							$access[] = self::render("API can't access Contacts", "warning");
						}

						if ($fields) {
							$access[] = self::render("API has access to Fields", "ok");
						} else {
							$access[] = self::render("API can't access Fields", "warning");
						}
					}
				}

				if (is_array($result->modules)) {
					if (!in_array("ECOMMERCE", $result->modules)) {
						$access[] = self::render("Plan without eCommerce Plugin", "warning");
					}
				} else {
					$access[] = self::render("Error checking plan features", "warning");
				}

				return "<table cellspacing='0' cellpadding='10' border='0' style='line-height: 20px;'>
							<tr><td>Status</td><td>".implode("<br/>", $access)."</td></tr>
							<tr><td style='width: 60px;'>Account</td><td>".$result->account."</td></tr>
							<tr><td>Plan</td><td>".$result->plan."</td></tr>
						</table>";
			}

			return self::render("Unexpected response format from API.<br/>Please contact our <a style='color: blue;' href='mailto:support@hellodialog.com'>support-team</a>.", "warning");
		}

		protected static function render($txt, $status) {
			switch ($status) {
				case 'notice':
					$color = 'blue'; break;
				case 'warning':
				case 'error':
				case 'problem':
					$color = 'red'; break;
				case 'ok':
				case 'success':
					$color = 'green'; break;
			}

			return "<span style='color: ".$color.";'>".$txt."</span>";
		}
	}