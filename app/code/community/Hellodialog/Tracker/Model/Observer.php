<?php
	class HelloDialog_Tracker_Model_Observer {

		private $_data;
		private $_order;
		private $_value_location = array(
			'id'          => 'getIncrementId',
			'firstname'   => 'getCustomerFirstname',
			'lastname'    => 'getCustomerLastname',
			'name'        => 'getCustomerName',
			'company'     => 'getBillingAddress/getCompany',
			'address'     => 'getBillingAddress/getStreetFull',
			'zipcode'     => 'getBillingAddress/getPostcode',
			'city'        => 'getBillingAddress/getCity',
			'countrycode' => 'getBillingAddress/getCountry',
			'country'     => 'getBillingAddress/getCountry',
			'telephone'   => 'getBillingAddress/getTelephone',
			'email'       => 'getCustomerEmail',
			'price'       => 'getBaseGrandTotal',
			'discount'    => 'getBaseDiscountAmount',
			'coupon'      => 'getDiscountDescription',
			'timestamp'   => 'getCreatedAt',
			'status'      => 'getStatus',
			'status_raw'  => 'getStatus',
			'payment'     => 'getPayment/getMethodInstance/getTitle',
		);

		public function test() {
			$this->_set_order(Mage::getModel('sales/order')->loadByIncrementId('145000018'));
			$this->_sync_order();
			$this->_update_order();
		}

		public function sync_order($order) {
			$this->_set_order($order);
			$this->_sync_order();
		}

		public function trackOrder($observer) {
			$this->_set_order($observer->getEvent()->getOrder());
			$this->_sync_order();
		}

		public function updateOrder($observer) {
			$order_id = $observer->getData('order_ids');
			$this->_set_order(Mage::getModel('sales/order')->load($order_id));
			$this->_update_order();
		}

		private function _set_order($order) {
			$this->_order = $order;
			$this->_data  = null;
		}

		private function _load_dependencies() {
			if (!class_exists('HDApi', false)) {
				require_once 'HDApi.php';
			}

			if (!class_exists('HDEcommerce', false)) {
				require_once 'HDEcommerce.php';
			}
		}

		private function _createHellodialogContact() {
			$contact = array();
			$contact['_state'] = 'Contact';

			// load fieldmapping from config, and ...
			$mapping = unserialize(Mage::getStoreConfig('hellodialog/general/fieldmapping'));

			if (is_array($mapping)) {
				// ... add hardcoded required field: email
				$mapping[] = array('magento' => 'email', 'hellodialog' => 'email');
				foreach ($mapping as $map) {
					if (isset($map['hellodialog'])) {
						$contact[$map['hellodialog']] = $this->_value($map['magento']);
					}
				}
			}

			return $contact;
		}

		private function _createHellodialogOrder() {
			$order = array(
				'order_number'   => $this->_value('id'),
				'created_on'     => $this->_value('timestamp'),
				'payment_status' => $this->_value('status'),
				'payment_method' => $this->_value('payment'),
				'price'          => $this->_value('price'),
				'discount'       => $this->_value('discount'),
				'coupon'         => $this->_value('coupon'),
				'country'        => $this->_value('countrycode'),
				'zip_code'       => $this->_value('zipcode'),
			);

			if (Mage::getStoreConfig('hellodialog/general/geocode')) {
				list($lat, $lng) = $this->_get_geocode();
				$order['lat'] = $lat;
				$order['lng'] = $lng;
			}

			return $order;
		}

		private function _get_geocode() {
			$address = array(
				$this->_value('address'),
				$this->_value('zipcode'),
				$this->_value('city'),
				$this->_value('country'),
			);
			
			$url = 'http://maps.googleapis.com/maps/api/geocode/json?sensor=false&address=';
			$url .= urlencode(implode(", ", array_filter($address)));

			$result = json_decode($this->_get_url($url));

			if (is_object($result) && isset($result->status) && $result->status == 'OK') {
				$lat = $result->results[0]->geometry->location->lat ?: null;
				$lng = $result->results[0]->geometry->location->lng ?: null;
				return array($lat, $lng);
			}

			return array(0,0);
		}

		private function _get_url($url) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
			$data = curl_exec($ch);
			curl_close($ch);
			return $data;
		}

		private function _value($key) {
			if (isset($this->_data[$key])) {
				return $this->_data[$key];
			}

			if (!isset($this->_value_location[$key])) {
				return '';
			}
			
			$target = $this->_order;
			$calls  = explode("/", $this->_value_location[$key]);

			foreach ($calls as $call) {
				$target = $target->{$call}();
			}

			// postprocessing
			switch ($key) {
				case 'countrycode':
					$target = strtolower($target);
					break;

				case 'country':
					$target = Mage::getModel('directory/country')->loadByCode($target)->getName();
					break;

				case 'timestamp':
					$target = strtotime($target);
					break;

				case 'status':
					$target = strtoupper($target);

					switch ($target) {
						case 'CANCELED':
						case 'CANCEL_OGONE':
						case 'PAYPAL_CANCELED_REVERSAL':
						case 'PAYPAL_REVERSED':
							$target = 'CANCELLED';
							break;

						case 'DECLINE_OGONE':
						case 'FRAUD':
							$target = 'DENIED';
							break;
						
						case 'CLOSED':
							$target = 'REFUND';
							break;

						case 'COMPLETE':
							$target = 'PAID';
							break;

						case 'HOLDED':
						case 'PROCESSED_OGONE':
						case 'PAYMENT_REVIEW':
						case 'PENDING':
						case 'PENDING_OGONE':
						case 'PENDING_PAYMENT':
						case 'PENDING_PAYPAL':
						case 'PROCESSING':
						case 'PROCESSING_OGONE':
						case 'WAITING_AUTHOROZATION':
							$target = 'PENDING';
							break;
					}

					if (!in_array($target, array('PAID', 'ERROR', 'REFUND', 'CANCELLED', 'PENDING', 'DENIED'))) {
						$target = 'PENDING';
					}
					break;
			}

			// cache
			$this->_data[$key] = $target;

			return $target;
		}

		private function _sync_order() {

			$this->_load_dependencies();

			HDApi::setToken(Mage::getStoreConfig('hellodialog/general/apikey'));

			// Create or Update contact in Hellodialog
			Mage::log(">>> HelloDialog_Tracker_Model_Observer->_sync_order()", null, 'hellodialog.log');
			Mage::log("Asserting existance of contact with email: ".$this->_value('email'), null, 'hellodialog.log');
			$contact = $this->_createHellodialogContact();
			$api     = new HDApi('contacts');
			$result  = $api->data($contact)->post();

			// if duplicate, update:
			if ($result->result->code == 612) {
				$result = $api->data($contact)->put($result->result->data->id_of_duplicate);
				Mage::log("... UPDATED (Contact already existed, updated information)", null, 'hellodialog.log');
			} else {
				Mage::log("... OK (Contact created)", null, 'hellodialog.log');
			}


			// eCommerce plugin
			// ---------------------------------
			// Only synch the actual order when
			// eCommerce plugin is enabled.
			
			if (Mage::getStoreConfig('hellodialog/general/ecommerce')) {

				Mage::log("eCommerce plugin enabled: posting order to Hellodialog", null, 'hellodialog.log');

				//HD Ecommerce
				$HDEcommerce = new HDEcommerce();

				try {
					$HDEcommerce->addOrder($this->_createHellodialogOrder(), $this->_value('email'));
					$HDEcommerce->setContactData($contact, $this->_value('email'), true);
				} catch (Exception $e) {
					Mage::log("... ERROR setting up HDEcommerce class with order and contact data (".$e->getMessage().")", null, 'hellodialog.log');
					return;
				}

				// retrieve all items in this order
				$items = $this->_order->getAllVisibleItems();

				foreach ($items as $item){
					try {
						 $HDEcommerce->addProduct(array(
							'product_code' => $item->getSku(),
							'name'         => $item->getName(),
							'quantity'     => $item->getQty(),
							'price'        => $item->getPrice(),
							'discount'     => $item->getDiscountAmount(),
						), $this->_value('id'));
					} catch (Exception $e) {
						Mage::log("... SKIPPING product because it throws an error (".$e->getMessage().")", null, 'hellodialog.log');
					}
				}

				try {
					$ordersResult = $HDEcommerce->postOrders();

					if (is_object($ordersResult) && isset($ordersResult->result) && is_object($ordersResult->result) && isset($ordersResult->result->code)) {
						if ($ordersResult->result->code == '200') {
							Mage::log("... OK (Orders created)", null, 'hellodialog.log');
						} else {
							Mage::log("... FAILED (".$ordersResult->result->data->errors[0]->message.")", null, 'hellodialog.log');
						}
					} else {
						Mage::log("... FAILED (unexpected response)", null, 'hellodialog.log');
					}
				} catch (Exception $e) {
					Mage::log("... FAILED (error while creating order: duplicate or other error)", null, 'hellodialog.log');
				}
			} else {
				Mage::log("eCommerce plugin disabled: not posting order to Hellodialog", null, 'hellodialog.log');
			}
		}

		private function _update_order() {

			$this->_load_dependencies();
			
			HDApi::setToken(Mage::getStoreConfig('hellodialog/general/apikey'));

			$api      = new HDApi('orders');
			$hd_order = $api->addParam('order_number', $this->_value('id'))->get();

			Mage::log("Trying to update payment status for order: ".$this->_value('id')." ('".$this->_value('status_raw')."', interpreted as '".$this->_value('status')."')", null, 'hellodialog.log');

			if (is_object($hd_order) && isset($hd_order->id)) {
				$response = $api->clear()->data(array(
					'payment_status' => $this->_value('status'),
				))->put($hd_order->id);
				Mage::log("... OK (Updated status)", null, 'hellodialog.log');
			} else {
				Mage::log("... FAILED (Unknown order or other error)", null, 'hellodialog.log');
			}
		}
	}