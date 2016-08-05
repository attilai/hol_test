<?php
class HDEcommerce {

	protected $_orders       = array();
	protected $_contacts     = array();
	protected $_contactData  = array();
	protected $_unsentOrders = array();

	protected $_contactsProcessed = false;
	protected $_ordersProcessed   = false;

	protected $_strict          = true;
	protected $_orderRequired   = array('order_number', 'created_on', 'price');
	protected $_productRequired = array('product_code', 'price', 'name');

	public function __construct($strict = true){
		// Strict defines whether unknown fields in order/product data throw an Exception or are simply ignored
		$this->_strict = $strict;
	}

	public function addOrder(Array $order, $contactEmail){
		$contactEmail = strtolower(trim($contactEmail));

		$this->_checkRequired($this->_orderRequired, $order);

		// Validate email
		if(!filter_var($contactEmail, FILTER_VALIDATE_EMAIL)){
			throw new Exception('Invalid contactEmail ('.$contactEmail.'), must be a valid e-mail address');
		}

		$order = $this->_validateAndParseOrder($order);
		
		// Set working variables
		$order['contact_email']                = $contactEmail;			
		$this->_orders[$order['order_number']] = $order;
		$this->_contactEmails[]                = $contactEmail;		
		$this->_ordersProcessed                = false;
	}

	protected function _validateAndParseOrder(Array $order){
		$data = array();

		// Loop through, validate and parse order fields
		foreach($order as $field => $value){
			switch($field){
				case 'order_number':
				case 'payment_method':
				case 'zip_code':
				case 'coupon':
					$data[$field] = $value;
					break;

				case 'price':
				case 'discount':
				case 'lat':
				case 'lng':
					if(!is_null($value) && !is_numeric($value)){
						throw new Exception('Invalid '.$field.' ('.$value.'), must be numeric');
					}
					$data[$field] = $value;
					break;

				case 'payment_status':
					if(is_null($value)){
						$data[$field] = null;
						break;
					}

					if(!in_array(strtoupper($value), array('PAID','ERROR','REFUND','CANCELLED','PENDING', 'DENIED'))){
						throw new Exception('Invalid payment status ('.$value.'), accepted: [PAID, ERROR, REFUND, CANCELLED, PENDING, DENIED]');
					}
					
					$data[$field] = strtoupper($value);
					break;

				case 'created_on':
					if(!is_null($value) && !is_numeric($value)){
						throw new Exception('Invalid created_on ('.$value.'), must be a timestamp');
					}
					$data[$field] = $value;
					break;

				case 'country':
					if(is_null($value)){
						$data[$field] = null;
						break;
					}

					if(strlen($value) !== 2){
						throw new Exception('Invalid country ('.$value.'), needs to be ISO 3166-1 Alpha-2 compatible');
					}
					$data[$field] = strtoupper($value);
					break;

				case 'language':
					if(is_null($value)){
						$data[$field] = null;
						break;
					}

					if(strlen($value) !== 2){
					throw new Exception('Invalid language ('.$value.'), needs to be ISO 639-1 Alpha-2 compatible');
					}
					$data[$field] = strtoupper($value);
					break;

				default:
					if($this->_strict){
						throw new Exception('Unknown field "'.$field.'"');
					}
					break;
			}

		}

		// Check if order number has been used before in this cycle
		if(!empty($this->_orders[$data['order_number']])){
			throw new Exception('Invalid order_number ('.$data['order_number'].'), must be unique');
		}

		return $data;
	}

	public function addProduct(Array $product, $orderNumber){
		$this->_checkRequired($this->_productRequired, $product);

		// Set working variables
		$product                                   = $this->_validateAndParseProduct($product);		
		$this->_orders[$orderNumber]['products'][] = $product;		
		$this->_ordersProcessed                    = false;
	}

	protected function _validateAndParseProduct(Array $product){
		$data = array();

		// Loop through, validate and parse product fields
		foreach($product as $field => $value){
			switch($field){
				case 'product_code':
				case 'name':
				case 'category':
				case 'subcategory':
					$data[$field] = $value;
					break;

				case 'price':
				case 'discount':
					if(!is_numeric($value)){
						throw new Exception('Invalid '.$field.' ('.$value.'), must be numeric');
					}
					$data[$field] = $value;
					break;

				case 'quantity':
					if(is_null($value)){
						$data[$field] = null;
						break;
					}

					if(!is_int($value)){
						throw new Exception('Invalid quantity ('.$value.'), must be integer');
					}
					$data[$field] = $value;
					break;

				default:
					if($this->_strict){
						throw new Exception('Unknown field "'.$field.'"');
					}
					break;
			}
		}

		return $data;
	}

	public function setContactData(Array $data, $contactEmail, $overwriteData = false){
		$this->_contactData[$contactEmail] = array(
			'overwrite' => $overwriteData,
			'data'      => $data
		);
		$this->_contactsProcessed = false;
	}

	protected function _checkRequired(Array $requiredFields, Array $data){
		foreach($requiredFields as $field){
			if(empty($data[$field])){
				throw new Exception($field.' is required');
			}
		}
	}

	protected function _processContacts(){
		$this->_retrieveContacts();
		$this->_decorateContacts();

		$this->_contactsProcessed = true;
	}

	protected function _processOrders(){
		$this->_linkContactsWithOrders();

		$this->_ordersProcessed = true;
	}

	protected function _retrieveContacts(){
		if(empty($this->_contactEmails)){
			// No contacts added
			return;
		}

		// Retrieve contacts in chunks via HDApi
		$hdContacts = new HDApi('contacts');

		foreach(array_chunk($this->_contactEmails, 10) as $contactChunk){
			$contactsResult = $hdContacts->condition('email', implode(",", $contactChunk), 'equals-any')->get();
			if(!empty($contactsResult)){
				foreach($contactsResult as $contact){
					$this->_contacts[$contact->email] = $contact;
				}
			}
		}
	}

	protected function _decorateContacts(){
		// Decorate retrieved contacts with order data
		if(!empty($this->_contactData)){
			foreach($this->_contactData as $contactEmail => $data){
				if(!isset($this->_contacts[$contactEmail])){
					continue;
				}
				$contact = $this->_contacts[$contactEmail];
				foreach($data['data'] as $field => $value){
					if($data['overwrite'] || !isset($contact->{$field})){
						$contact->{$field} = $value;
					}
				}
			}
		}
	}

	protected function _linkContactsWithOrders(){
		if(empty($this->_orders)){
			// No orders found
			return;
		}elseif(empty($this->_contacts)){
			// No contacts found, no orders will be sent
			$this->_unsentOrders = $this->_orders;
			return;
		}

		foreach($this->_orders as $key => &$order){
			// If there's no contact for this order, skip and remove it
			if(!isset($this->_contacts[$order['contact_email']])){
				$this->_unsentOrders[] = $order;
				unset($this->_orders[$key]);
				continue;
			} else {
				$contact = $this->_contacts[$order['contact_email']];

				// Link contact to order and unset email from order
				$order['contact'] = $contact->id;
				unset($order['contact_email']);
			}
		}
	}

	public function putContacts(){
		if(!$this->_contactsProcessed){
			$this->_processContacts();
		}

		if(empty($this->_contacts)) return;

		// PUT contacts to HDApi
		$hdContacts = new HDApi('contacts');
		$result     = $hdContacts->data(array_values($this->_contacts))->put();

		return $result;
	}

	public function postOrders(){
		if(!$this->_contactsProcessed){
			$this->_processContacts();
		}
		if(!$this->_ordersProcessed){
			$this->_processOrders();
		}

		if(empty($this->_orders) || empty($this->_contacts)) return;

		// POST orders to HDApi
		$hdOrders = new HDApi('orders');
		$result   = $hdOrders->data($this->_orders)->post();

		return $result;
	}

	public function getUnsentOrders(){
		return $this->_unsentOrders;
	}

}

?>