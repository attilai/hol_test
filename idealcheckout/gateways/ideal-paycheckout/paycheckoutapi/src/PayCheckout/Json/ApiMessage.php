<?php

namespace PayCheckout\Json;

use DateTime;
use Exception;
use PayCheckout\ApiAction;
use PayCheckout\Api\HelpFunction;
use PayCheckout\ItemType;
use PayCheckout\Gender;
use PayCheckout\Json\Generic\Order\Order;
use PayCheckout\Json\Notification\Notification;
use PayCheckout\Json\Response\Response;
use PayCheckout\Json\Request\Request;
use PayCheckout\Json\Request\Transaction\Transaction;

/**
 * Release notes:
 * Date         Version     Description
 * 2016-05-18	1.1.0.13	Improvements to support PHP 7 and 64 bit versions.
 *                          ApiMessage payment_overrideConfiguredReturnUrls is now valid to get called for getAvailablePaymentMethods service call
 * 2016-04-05   1.1.0.12    Due to certificate update for sandbox.paycheckout.com and secure.paycheckout.com a problem occured with the tls connection.
 *                          The new SHA2 required new intermediate certificates. On linux/unix systems these are supplied by our library.
 *                          These required intermediate certificates where added to file curl/cacert.perm file.
 * 2016-03-30   1.1.0.11    Now returns different associative array SurchargeV2 collection when calling GetCurrentConfiguration. The array is associated with isoCountry instead of currency.
 *                          Bug fix in getting min/max values (assosiative array was not correctly indexed)
 * 2016-02-29   1.1.0.10    Added capability to send moduleinformation. 
 *                          ApiExecutor constructor has a new optional parameter string to accomodate a free string to identify the shopmodule used.
 * 2016-01-16   1.1.0.9     Method payment_setQuantityMultiplyFactor was blocked for service call getAvailablePaymentMethods, now you can pass this paramter too
 * 2016-01-06   1.1.0.7a    Patched autoloader to spl_autoload_register('PayCheckout_autoloader',true,true);
 *                          Added validation for webshopId and encryptionPassword.
 * 2015-12-12   1.1.0.7     Introduced SofortBanking paymentmethod
 *                          Addded SOFORTBANKING to PaymentMethod.php
 *                          Added class PayCheckout/Json/Generic/Configuration/SofortBanking/SofortBanking
 *                          Added reference to SofortBanking in PayCheckout/Json/Generic/Configuration/Configuration class.
 *                          Added class PayCheckout/Json/PaymentSpecific/SofortBanking
 *                          Added property SofortBanking to PayCheckout/Json/Response/TransactionResult class
 *                          Added property PaymentStatus to PayCheckout/Json/Generic/PaymentInfo/TransactionInfo class
 *                          Added property SofortBanking to PayCheckout/Json/Generic/PaymentInfo/TransactionInfo class
 *                          
 * 2015-10-15   1.1.0.6     No relevant changes just to keep up with the other libraries
 * 2015-10-07   1.1.0.5     PayCheckout\PaymentStatus::REVERSED and PayCheckout\TransactionStatus::REVERSED added
 *                          PaymentMethod PayPal added
 *                          PayCheckout\Json\Response\TransactionResult expanded with PayPal specific data
 *                          PayCheckout\Json\Generic\PaymentSpecific\PayPal class added (contains PayPal PaymentTransactionId on success)
 *							PayCheckout\Json.Configuration\Configuration expanded with PayPal specific data and Txt descriptions
 *							PayCheckout\Json\Configuration\PayPal\PayPal class added
 *                          
 * 
 * 2015-09-17   1.1.0.4a    Changed constructor of Response class (no array is constructed when nothing is there)
 * 2015-09-16   1.1.0.4     Class PayCheckout\Json\PaymentInfo\TransactionInfo expanded with getPaymentMethodDescription()
 *                          Class PayCheckout\Json\Response\Response expanded with getTransactionReference()
 *                          Service call GetLastNotificationContent added
 * 
 * 2015-09-11   1.1.0.3a    Validation error message that checks variable type shows better explanation of what is wrong
 * 2015-08-26   1.1.0.3	    Expanded CancelOrder with parameter "processOffline"
 *							Now cancelOrder also refunds items that are paid but not yet invoiced.
 * 
 * 2015-08-12   1.1.0.2     Introduced connectionTimout of 2 seconds, to avoid waiting too long if there are internet problems reaching transaction system
 *                          Fixed validation error for paymentmethod in generic payment returning confusing error message that ipaddress was wrong
 *                          GetAvailablePaymentMethods and all paymentmethods added parameter 'enforceNoVAT'
 *  						Now flexible parameters in UpdateOrder method
 *							New parameter "ProcessOffline","True" in UpdateOrder for offline processing of orders                        
 *							New parameter "ProcessOffline in servicecall Refund 
 *							Following classes have flag ProcessedOffline added:
 *							Json.Invoice.Discount
 *							Json.Invoice.Invoice
 *							Json.Invoice.Refund
 *							Json.Notification.Notification
 *							Json.PaymentInfo.TransactionInfo
 * 2015-07-23   1.1.0.1     Expanded to support all currencies
 *                          subStatus in TransactionInfo object added
 *                          PaymentCostExclVat,PaymentCostInclVat,PaymentCostVatDisplayPercentage added to service call response GetAvailablePaymentMethods
 *                          Created class Json/Generic/Invoice/TrackAndTraceInfo that can be passed to UpdateOrder
 *                          This class is now also included in an Json/Generic/Invoice/Invoice
 *                          TrackAndTraceInfo class can be passed as a parmeter with updateOrder so supply TrackAndTrace information when performing billing
 *                          Enabled setting QuantityMultiplyFactor on order
 *                          Introduced Paycheckout\Api\Payment\Generic::create Api call
 * 2015-05-09   1.0.12.7c   Bug fix getPaymentVatCostDisplayPercentage in notification was wrong functionname and returned wrong variable
 * 2015-04-21   1.0.12.7    Added new error code REMOTE_PARTY_REPORTS_UNSPECIFIED_ERROR (For handling IDeal failure status)
 * 2015-04-17   1.0.12.6    Added new service call SendManualNotification
 * 2015-04-16   1.0.12.5    New hosted status HOSTED_INITATED and HOSTED_EXPIRED introduced, to statistically see the difference for unfinished hosted payments
 * 2015-04-09   1.0.12.4    Added method ApiMessage->payment_overrideConfiguredReturnUrls
 *                          All highlevel api calls have typechecking/validation added
 *                          Ideal, KlarnaAccount and KlarnaInvoice have parameter (string) $customerIpAddress added 
 *                          GetAvailablePaymentMethods also has parameter $customerIpAddress added
 *                          ApiMessage->addOrderItem has parameter $unitPriceMultiplyFactorOverride when set the scaling of unitprices can be overriden, range is 1..8
 *                          GetConfiguration class Klarna has two methods added isSendKlarnaEmailWhenInvoiceIsCreated and isSendKlarnaEmailWhenInvoiceIsAltered
 *                          PayCheckout\HelpFunction\Help::getCurrencyMultiplyFactor($currency) method added to aid in determine the scale factor
 *                          New class PayCheckout\Json\Generic\Invoice\RefundItem which replaces ..\OrderItem class, is now has getRefundAmount() which shows the scaled amount refunded
 *                          Bug fixes in classes PayCheckout\Json\Generic\Invoice\Invoice and PayCheckout\Json\Generic\Invoice\Refund
 * 2015-03-19	1.0.12.3	Bug fix - empty subclasses where passed as a json formatted array instead of leaving them out of the string. Frustrating json decoding at txn processing
 *                          Added new ErrorCodes RemotePartyReportsInsufficientFunds(3008), RemotePartyReportsCommunicationProblemThirdParty(3009)
 * 2015-03-18	1.0.12.2	Bug fix - Telephonenumber was not passed correctly to billing address class
 * 2015-03-10   1.0.12.1    Api.Service.GetModuleVersion and Api.Service.GetAvailablePaymentMethods functions added
 * 2015-03-03   1.0.12.0	Notification classes OrderChange,PaymentStatusChange and RefundInformation now contain MerchantOrderRef if available
 *							Json.PaymentInfo.TransactionInfo now contains field RelatedTransactionIndex attribute to show relationship between statusrequest/payments for example
 *							and attribute HostedExpirationTime
 *							Api.Payment.Hosted function call has additional parameter "expiryTimeInMinutesOverride" to be able to override portal configured expiry
 * 2015-02-13   1.0.11      Notification expanded with more types
 * 2015-02-10   1.0.10      Added PaymentMethod and RelatedPaymentReference to TransactionInfo class
 **/

class ApiMessage extends JsonBase
{
    const CURRENT_VERSION   = 1;
    const LIB_NAME          = 'PHP-PayCheckoutApi';
    const LIB_VERSION       = '1.1.0.13';
    
    /**
	 * @var int
	 */
    protected $version;
    
    /**
     * @var string
     */
    protected $libName;

    /**
     * @var string
     */
    protected $libVersion;

    /**
     * @var string
     */
    protected $libModuleInfo;
    
    /**
     * @var int
     */  
    protected $action;
    
    /**
	 * @var int
	 */
    protected $webshopId;
    
    /**
	 * @var Request
	 */
    protected $request;
    
    /**
	 * @var Response
	 */
    protected $response;
    
    /**
     * 
     * @var \PayCheckout\Json\Notification\Notification
     */
    protected $notification;
    
    /**
     * @var array of strings
     */
    protected $validationErrors;
    
    /**
	 * Create new API message
	 * 
	 * @param int $action 
	 * @param Request|null $request 
	 * @param Response|null $response
	 */
    public function __construct($action = null, Request $request = null, Response $response = null)
    {
        $this->version      = self::CURRENT_VERSION;
        $this->libName      = self::LIB_NAME;
        $this->libVersion   = self::LIB_VERSION;
        $this->action       = $action;
        $this->request      = $request;
        $this->response     = $response;
    }
        
    /**
	 * @return int
	 */
    public function getVersion()
    {
        return $this->version;
    }
    
    /**
	 * @param int $version
	 */
    public function setVersion($version)
    {
        $this->version = $version;
    }
    
    /**
     * @return string
     */
    public function getLibName()
    {
        return $this->libName;
    }
       
    /**
     * @return string
     */
    public function getLibVersion()
    {
        return $this->libVersion;
    }
    
    /**
     * @param string $moduleInfo 
     */
    public function setModuleInfo($moduleInfo)
    {
        $this->libModuleInfo = $moduleInfo;
    }
    
    /**
	 * @return int
	 */
    public function getAction()
    {
        return $this->action;
    }
    
    /**
	 * @param int $action
	 */
    public function setAction($action)
    {
        $this->action = $action;
    }
    
    /**
	 * @return int
	 */
    public function getWebshopId()
    {
        return $this->webshopId;
    }
    
    /**
	 * @param int $webshopId
	 */
    public function setWebshopId($webshopId)
    {
        if (!HelpFunction::is32bitInt($webshopId))
        {
            $this->addValidationError('webshopId [' . $webshopId . '] must be of type integer (32 bit) and not of type ' . gettype($webshopId));
        }
        else if ($webshopId <= 0 || $webshopId > PHP_INT_MAX)
        {
            $this->addValidationError('webshopId [' . $webshopId . '] must be greater than 0 and smaller than ' . (PHP_INT_MAX + 1));
        }
        else
        {
            $this->webshopId = $webshopId;
        }
    }
    
    /**
	 * @return Request
	 */
    public function getRequest()
    {
        return $this->request;
    }

    /**
	 * @param Request $request
	 */
    public function setRequest(Request $request)
    {
        $this->request = $request;
    }
     
    /**
	 * @return Response
	 */
    public function getResponse()
    {
        return $this->response;
    }
    
    /**
	 * @param Response $response
	 */
    public function setResponse(Response $response)
    {
        $this->response = $response;
    }

    /**
	 * @return Notification
	 */
    public function getNotification()
    {
        return $this->notification;
    }
    
    /**
	 * @param /Notification $notification
	 */
    public function setNotification(Notification $notification)
    {
        $this->notification = $notification;
    }
         
    /**
     * @param string $errorSeen 
     */
    public function addValidationError($errorSeen)
    {
        $this->validationErrors[] = $errorSeen;
    }
        
    /**
     * getValidationError
     * @return array of strings
     */
    public function getValidationError()
    {
        return $this->validationErrors;
    }
    
    /**
	 * Set order info of payment
	 * 
	 * @param string $customerOrderReference 
	 * @param string $customerNote 
	 * @throws Exception If the ApiMessage wasn't created with a PayCheckout\Api\Payment\XXX call.
	 */
    public function payment_setOrderInfo($customerOrderReference = null, $customerNote = null)
    {
        if ($this->getRequest() == null || !in_array($this->getAction(), array(ApiAction::PAYMENT, ApiAction::UPDATE_ORDER)))
        {
            throw new Exception('ApiMessage was not created with a PayCheckout\Api\Payment\XXX api call');
        }
        
        if ($this->getRequest()->getOrder() == null)
        {
            $this->getRequest()->setOrder(new Order());
        }

        if ($this->getRequest()->getTransaction() != null)
        {
            $this->getRequest()->getOrder()->setCurrency($this->getRequest()->getTransaction()->getCurrency());
        }

        $this->getRequest()->getOrder()->setInfo($customerOrderReference, $customerNote);
    }
    
    /**
	 * Set billing address of order of payment
	 * 
	 * @param string $countryIso3166Alpha2 
	 * @param string $firstName 
	 * @param string $lastName 
	 * @param string $addressLine1 
	 * @param string $zipCode 
	 * @param string $city 
	 * @param string $title 
	 * @param string $addressLine2 
	 * @param string $stateProvince 
	 * @param DateTime|null $dateOfBirth 
	 * @param string $emailAddress 
	 * @param int $gender 
	 * @param string $phoneNumber 
	 * @param string $phoneNumber2
	 * @param string $cellPhoneNumber
	 * @param string $socialSecurityNumber 
	 * @param string $organisation 
	 * @param string $department 
	 * @param string $chamberOfCommerceNumber 
	 * @param string $vatNumber 
	 * @throws Exception If the ApiMessage wasn't created with a PayCheckout\Api\Payment\XXX call.
	 */
    public function payment_setOrderBillingAddress($countryIso3166Alpha2, $firstName, $lastName, $addressLine1, $zipCode, $city,
        $title = null, $addressLine2 = null, $stateProvince = null, DateTime $dateOfBirth = null, $emailAddress = null, $gender = null,
        $phoneNumber = null, $phoneNumber2 = null, $cellPhoneNumber = null, $socialSecurityNumber = null, $organisation = null, $department = null, $chamberOfCommerceNumber = null, $vatNumber = null)
    {
        if ($this->getRequest() == null || !in_array($this->getAction(), array(ApiAction::PAYMENT, ApiAction::UPDATE_ORDER, ApiAction::GET_AVAILABLE_PAYMENT_METHODS)))
        {
            throw new Exception('ApiMessage was not created with a PayCheckout\Api\Payment\XXX api call');
        }
        
        if ($this->getRequest()->getOrder() == null)
        {
            $this->getRequest()->setOrder(new Order());
        }

        if ($this->getRequest()->getTransaction() != null)
        {
            $this->getRequest()->getOrder()->setCurrency($this->getRequest()->getTransaction()->getCurrency());
        }

        
        /*
         * ----------
         * Validation
         * ----------
         */
        if ($countryIso3166Alpha2 != null && !is_string($countryIso3166Alpha2))
        {
            $this->addValidationError('In method payment_setOrderBillingAddress parameter countryIso3166Alpha2[' . $countryIso3166Alpha2 . '] is supposed to be a string and not a ' . gettype($countryIso3166Alpha2));
        }
        if ($firstName != null && !is_string($firstName))
        {
            $this->addValidationError('In method payment_setOrderBillingAddress parameter firstName[' . $firstName . '] is supposed to be a string and not a ' . gettype($firstName));
        }
        if ($lastName != null && !is_string($lastName))
        {
            $this->addValidationError('In method payment_setOrderBillingAddress parameter lastName[' . $lastName . '] is supposed to be a string and not a ' . gettype($lastName));
        }
        if ($addressLine1 != null && !is_string($addressLine1))
        {
            $this->addValidationError('In method payment_setOrderBillingAddress parameter addressLine1[' . $addressLine1 . '] is supposed to be a string and not a ' . gettype($addressLine1));
        }
        if ($zipCode != null && !is_string($zipCode))
        {
            $this->addValidationError('In method payment_setOrderBillingAddress parameter zipCode[' . $zipCode . '] is supposed to be a string and not a ' . gettype($zipCode));
        }
        if ($city != null && !is_string($city))
        {
            $this->addValidationError('In method payment_setOrderBillingAddress parameter city[' . $city . '] is supposed to be a string and not a ' . gettype($city));
        }
        if ($title != null && !is_string($title))
        {
            $this->addValidationError('In method payment_setOrderBillingAddress parameter title[' . $title . '] is supposed to be a string and not a ' . gettype($title));
        }
        if ($addressLine2 != null && !is_string($addressLine2))
        {
            $this->addValidationError('In method payment_setOrderBillingAddress parameter addressLine2[' . $addressLine2 . '] is supposed to be a string and not a ' . gettype($addressLine2));
        }
        if ($stateProvince != null && !is_string($stateProvince))
        {
            $this->addValidationError('In method payment_setOrderBillingAddress parameter stateProvince[' . $stateProvince . '] is supposed to be a string and not a ' . gettype($stateProvince));
        }
        if ($dateOfBirth != null && !($dateOfBirth instanceof DateTime))
        {
            $this->addValidationError('In method payment_setOrderBillingAddress parameter dateOfBirth[' . $dateOfBirth . '] is supposed to be a an instance of DateTime and not a ' . gettype($dateOfBirth));
        }
        if ($emailAddress != null && !is_string($emailAddress))
        {
            $this->addValidationError('In method payment_setOrderBillingAddress parameter emailAddress[' . $emailAddress . '] is supposed to be a string and not a ' . gettype($emailAddress));
        }
        if ( $gender != null && !in_array( $gender, array( Gender::MALE, Gender::FEMALE) ) )
        {
            $this->addValidationError('In method payment_setOrderBillingAddress parameter gender[' . $gender . '] is supposed to be an integer constant of Gender::MALE or Gender::FEMALE');
        }
        if ($phoneNumber != null && !is_string($phoneNumber))
        {
            $this->addValidationError('In method payment_setOrderBillingAddress parameter phoneNumber[' . $phoneNumber . '] is supposed to be a string and not a ' . gettype($phoneNumber));
        }
        if ($phoneNumber2 != null && !is_string($phoneNumber2))
        {
            $this->addValidationError('In method payment_setOrderBillingAddress parameter phoneNumber2[' . $phoneNumber2 . '] is supposed to be a string and not a ' . gettype($phoneNumber2));
        }
        if ($cellPhoneNumber != null && !is_string($cellPhoneNumber))
        {
            $this->addValidationError('In method payment_setOrderBillingAddress parameter cellPhoneNumber[' . $cellPhoneNumber . '] is supposed to be a string and not a ' . gettype($cellPhoneNumber));
        }
        if ($socialSecurityNumber != null && !is_string($socialSecurityNumber))
        {
            $this->addValidationError('In method payment_setOrderBillingAddress parameter socialSecurityNumber[' . $socialSecurityNumber . '] is supposed to be a string and not a ' . gettype($socialSecurityNumber));
        }
        if ($organisation != null && !is_string($organisation))
        {
            $this->addValidationError('In method payment_setOrderBillingAddress parameter organisation[' . $organisation . '] is supposed to be a string and not a ' . gettype($organisation));
        }
        if ($department != null && !is_string($department))
        {
            $this->addValidationError('In method payment_setOrderBillingAddress parameter department[' . $department . '] is supposed to be a string and not a ' . gettype($department));
        }
        if ($chamberOfCommerceNumber != null && !is_string($chamberOfCommerceNumber))
        {
            $this->addValidationError('In method payment_setOrderBillingAddress parameter chamberOfCommerceNumber[' . $chamberOfCommerceNumber . '] is supposed to be a string and not a ' . gettype($chamberOfCommerceNumber));
        }
        if ($vatNumber != null && !is_string($vatNumber))
        {
            $this->addValidationError('In method payment_setOrderBillingAddress parameter vatNumber[' . $vatNumber . '] is supposed to be a string and not a ' . gettype($vatNumber));
        }
        /*
         * -----------------
         * End of validation
         * -----------------
         */
        
        
        $this->getRequest()->getOrder()->setBillingAddress(
            $countryIso3166Alpha2,
			$firstName,
			$lastName,
			$addressLine1,
			$zipCode,
			$city,
            $title,
			$addressLine2,
			$stateProvince,
			$dateOfBirth,
			$emailAddress,
			$gender,
            $phoneNumber,
			$phoneNumber2,
			$cellPhoneNumber,
			$socialSecurityNumber,
			$organisation,
			$department,
			$chamberOfCommerceNumber,
			$vatNumber
        );
    }
    
    /**
	 * Set shipping address of order of payment
	 * 
	 * @param string $countryIso3166Alpha2 
	 * @param string $firstName 
	 * @param string $lastName 
	 * @param string $addressLine1 
	 * @param string $zipCode 
	 * @param string $city 
	 * @param string $title 
	 * @param string $addressLine2 
	 * @param string $stateProvince 
	 * @param string $phoneNumber 
	 * @param string $cellPhoneNumber
	 * @param string $organisation 
	 * @param string $department 
	 * @throws Exception If the ApiMessage wasn't created with a PayCheckout\Api\Payment\XXX call. 
	 */
    public function payment_setOrderShippingAddress($countryIso3166Alpha2, $firstName, $lastName, $addressLine1, $zipCode, $city,
         $title = null, $addressLine2 = null, $stateProvince = null, $phoneNumber = null, $cellPhoneNumber = null, $organisation = null, $department = null)
    {
        if ($this->getRequest() == null || !in_array($this->getAction(), array(ApiAction::PAYMENT, ApiAction::UPDATE_ORDER, ApiAction::GET_AVAILABLE_PAYMENT_METHODS)))
        {
            throw new Exception('ApiMessage was not created with a PayCheckout\Api\Payment\XXX api call');
        }
        
        if ($this->getRequest()->getOrder() == null)
        {
            $this->getRequest()->setOrder(new Order());
        }

        if ($this->getRequest()->getTransaction() != null)
        {
            $this->getRequest()->getOrder()->setCurrency($this->getRequest()->getTransaction()->getCurrency());
        }

        
        /*
         * ----------
         * Validation
         * ----------
         */
        if ($countryIso3166Alpha2 != null && !is_string($countryIso3166Alpha2))
        {
            $this->addValidationError('In method payment_setOrderShippingAddress parameter countryIso3166Alpha2[' . $countryIso3166Alpha2 . '] is supposed to be a string and not a ' . gettype($countryIso3166Alpha2));
        }
        if ($firstName != null && !is_string($firstName))
        {
            $this->addValidationError('In method payment_setOrderShippingAddress parameter firstName[' . $firstName . '] is supposed to be a string and not a ' . gettype($firstName));
        }
        if ($lastName != null && !is_string($lastName))
        {
            $this->addValidationError('In method payment_setOrderShippingAddress parameter lastName[' . $lastName . '] is supposed to be a string and not a ' . gettype($lastName));
        }
        if ($addressLine1 != null && !is_string($addressLine1))
        {
            $this->addValidationError('In method payment_setOrderShippingAddress parameter addressLine1[' . $addressLine1 . '] is supposed to be a string and not a ' . gettype($addressLine1));
        }
        if ($zipCode != null && !is_string($zipCode))
        {
            $this->addValidationError('In method payment_setOrderShippingAddress parameter zipCode[' . $zipCode . '] is supposed to be a string and not a ' . gettype($zipCode));
        }
        if ($city != null && !is_string($city))
        {
            $this->addValidationError('In method payment_setOrderShippingAddress parameter city[' . $city . '] is supposed to be a string and not a ' . gettype($city));
        }
        if ($title != null && !is_string($title))
        {
            $this->addValidationError('In method payment_setOrderShippingAddress parameter title[' . $title . '] is supposed to be a string and not a ' . gettype($title));
        }
        if ($addressLine2 != null && !is_string($addressLine2))
        {
            $this->addValidationError('In method payment_setOrderShippingAddress parameter addressLine2[' . $addressLine2 . '] is supposed to be a string and not a ' . gettype($addressLine2));
        }
        if ($stateProvince != null && !is_string($stateProvince))
        {
            $this->addValidationError('In method payment_setOrderShippingAddress parameter stateProvince[' . $stateProvince . '] is supposed to be a string and not a ' . gettype($stateProvince));
        }
        if ($phoneNumber != null && !is_string($phoneNumber))
        {
            $this->addValidationError('In method payment_setOrderShippingAddress parameter phoneNumber[' . $phoneNumber . '] is supposed to be a string and not a ' . gettype($phoneNumber));
        }
        if ($cellPhoneNumber != null && !is_string($cellPhoneNumber))
        {
            $this->addValidationError('In method payment_setOrderShippingAddress parameter cellPhoneNumber[' . $cellPhoneNumber . '] is supposed to be a string and not a ' . gettype($cellPhoneNumber));
        }
        if ($organisation != null && !is_string($organisation))
        {
            $this->addValidationError('In method payment_setOrderShippingAddress parameter organisation[' . $organisation . '] is supposed to be a string and not a ' . gettype($organisation));
        }
        if ($department != null && !is_string($department))
        {
            $this->addValidationError('In method payment_setOrderShippingAddress parameter department[' . $department . '] is supposed to be a string and not a ' . gettype($department));
        }
        /*
         * -----------------
         * End of validation
         * -----------------
         */
        
        
        
        $this->getRequest()->getOrder()->setShippingAddress(
            $countryIso3166Alpha2,
			$firstName,
			$lastName,
			$addressLine1,
			$zipCode,
			$city,
            $title,
			$addressLine2,
			$stateProvince,
			$phoneNumber,
			$cellPhoneNumber,
			$organisation,
			$department
        );
    }
    
    /**
	 * Add item to order of payment
	 * 
	 * @param int $orderLineNumber
	 * @param string $name 
	 * @param string $description 
	 * @param int $quantityOrdered
	 * @param int $quantityInvoiced
	 * @param int $quantityRefunded
	 * @param int $quantityCancelled
	 * @param int $unitPriceExclusiveVat 
	 * @param int $unitPriceInclusiveVat 
	 * @param int $vatDisplayPercentage
	 * @param int $itemType 
	 * @param int $discountDisplayPercentage 
	 * @param string $skuCode 
     * @param int $unitPriceMultiplyFactorOverride
	 * @throws Exception If the ApiMessage wasn't created with a PayCheckout\Api\Payment\XXX call.
	 */
    public function payment_addOrderItem($orderLineNumber, $name, $description, $quantityOrdered, $quantityCancelled, $quantityInvoiced, 
        $quantityRefunded, $unitPriceExclusiveVat, $unitPriceInclusiveVat, $vatDisplayPercentage, 
        $itemType = ItemType::ARTICLE, $discountDisplayPercentage = null, $skuCode = null, $unitPriceMultiplyFactorOverride = null)
    {
        if ($this->getRequest() == null || !in_array($this->getAction(), array(ApiAction::PAYMENT, ApiAction::UPDATE_ORDER, ApiAction::GET_AVAILABLE_PAYMENT_METHODS)))
        {
            throw new Exception('ApiMessage was not created with a PayCheckout\Api\Payment\XXX api call');
        }
        
        if ($this->getRequest()->getOrder() == null)
        {
            $this->getRequest()->setOrder(new Order());
        }

        if ($this->getRequest()->getTransaction() != null)
        {
            $this->getRequest()->getOrder()->setCurrency($this->getRequest()->getTransaction()->getCurrency());
        }
        
        /*
         * ----------
         * Validation
         * ----------
         */
        if ($orderLineNumber != null && !is_int($orderLineNumber))
        {
            $this->addValidationError('In method payment_addOrderItem parameter orderLineNumber[' . $orderLineNumber . '] is supposed to be an integer and not a ' . gettype($orderLineNumber));
        }
        if ($name != null && !is_string($name))
        {
            $this->addValidationError('In method payment_addOrderItem parameter name[' . $name . '] is supposed to be a string and not a ' . gettype($name));
        }
        if ($description != null && !is_string($description))
        {
            $this->addValidationError('In method payment_addOrderItem parameter description[' . $description . '] is supposed to be a string and not a ' . gettype($description));
        }
        if ($quantityOrdered != null && !HelpFunction::is32bitInt($quantityOrdered))
        {
            $this->addValidationError('In method payment_addOrderItem parameter quantityOrdered[' . $quantityOrdered . '] is supposed to be a 32 bit integer and not a ' . gettype($quantityOrdered));
        }
        if ($quantityCancelled != null && !HelpFunction::is32bitInt($quantityCancelled))
        {
            $this->addValidationError('In method payment_addOrderItem parameter quantityCancelled[' . $quantityCancelled .'] is supposed to be a 32 bit integer and not a ' . gettype($quantityCancelled));
        }
        if ($quantityInvoiced != null && !HelpFunction::is32bitInt($quantityInvoiced))
        {
            $this->addValidationError('In method payment_addOrderItem parameter quantityInvoiced[' . $quantityInvoiced . '] is supposed to be a 32 bit integer and not a ' . gettype($quantityInvoiced));
        }
        if ($quantityRefunded != null && !HelpFunction::is32bitInt($quantityRefunded))
        {
            $this->addValidationError('In method payment_addOrderItem parameter quantityRefunded[' . $quantityRefunded . '] is supposed to be a 32 bit integer and not a ' . gettype($quantityRefunded));
        }
        if ($unitPriceExclusiveVat != null && !HelpFunction::is32bitInt($unitPriceExclusiveVat))
        {
            $this->addValidationError('In method payment_addOrderItem parameter unitPriceExclusiveVat[' . $unitPriceExclusiveVat . '] is supposed to be a 32 bit integer and not a ' . gettype($unitPriceExclusiveVat));
        }
        if ($unitPriceInclusiveVat != null && !HelpFunction::is32bitInt($unitPriceInclusiveVat))
        {
            $this->addValidationError('In method payment_addOrderItem parameter unitPriceInclusiveVat[' . $unitPriceInclusiveVat . '] is supposed to be a 32 bit integer and not a ' . gettype($unitPriceInclusiveVat));
        }
        if ($vatDisplayPercentage != null && !HelpFunction::is32bitInt($vatDisplayPercentage))
        {
            $this->addValidationError('In method payment_addOrderItem parameter vatDisplayPercentage[' . $vatDisplayPercentage . '] is supposed to be a 32 bit integer and not a ' . gettype($vatDisplayPercentage));
        }
        if ($itemType != null && !in_array($itemType,array ( 
            ItemType::ARTICLE, 
            ItemType::ADDITIONAL_COST,
            ItemType::DISCOUNT,
            ItemType::PAYMENT_COST,
            ItemType::REFUND_COST,
            ItemType::SHIPPING_COST)))
        {
            $this->addValidationError('In method payment_addOrderItem parameter itemType[' . $itemType . '] is supposed to be an ItemType');
        }
        if ($discountDisplayPercentage != null && !HelpFunction::is32bitInt($discountDisplayPercentage))
        {
            $this->addValidationError('In method payment_addOrderItem parameter discountDisplayPercentage[' . $discountDisplayPercentage . '] is supposed to be a 32 bit integer and not a ' . gettype($discountDisplayPercentage));
        }
        if ($skuCode != null && !is_string($skuCode))
        {
            $this->addValidationError('In method payment_addOrderItem parameter skuCode[' . $skuCode . '] is supposed to be a string and not a ' . gettype($skuCode));
        }
        if ($unitPriceMultiplyFactorOverride != null && !HelpFunction::is32bitInt($unitPriceMultiplyFactorOverride))
        {
            $this->addValidationError('In method payment_addOrderItem parameter unitPriceMultiplyFactorOverride[' . $unitPriceMultiplyFactorOverride . '] is supposed to be a 32 bit integer and not a ' . gettype($unitPriceMultiplyFactorOverride));
        }
        /*
         * -----------------
         * End of validation
         * -----------------
         */
         
        $this->getRequest()->getOrder()->addNewItem(
            $orderLineNumber,
			$name,
			$description,
			$quantityOrdered,
			$quantityCancelled, 
			$quantityInvoiced,
			$quantityRefunded,
            $unitPriceExclusiveVat,
			$unitPriceInclusiveVat,
			$vatDisplayPercentage,
			$itemType,
			$discountDisplayPercentage,
			$skuCode,
            $unitPriceMultiplyFactorOverride
        );
    }
    
    /**
     * Set return urls override
     * 
     * @param string $returnUrlSuccess 
     * @param string $returnUrlCancelled 
     * @param string $returnUrlFailed 
     * @throws Exception 
     */
    public function payment_overrideConfiguredReturnUrls($returnUrlSuccess = null, $returnUrlCancelled = null, $returnUrlFailed = null)
    {
        if ($this->getRequest() == null || 
            ($this->getRequest()->getTransaction() == null && $this->getAction() == \PayCheckout\ApiAction::PAYMENT ) ||
            ($this->getAction() != \PayCheckout\ApiAction::PAYMENT && $this->getAction() != \PayCheckout\ApiAction::GET_AVAILABLE_PAYMENT_METHODS))
        {
            throw new Exception('ApiMessage was not created with a PayCheckout\Api\Payment\XXX api call or a call to PayCheckout\Api\GetAvailablePaymentMethods');
        }

			
		if ($this->getRequest()->getTransaction() == null && $this->getAction() == \PayCheckout\ApiAction::GET_AVAILABLE_PAYMENT_METHODS)
		{
			if ($this->getRequest()->getOrder() == null)
			{
				$this->getRequest()->setTransaction(new \PayCheckout\Json\Request\Transaction\Transaction());
                $this->getRequest()->getTransaction()->setCurrency(\PayCheckout\Currency::EUR);
			}
			else
			{
                $this->getRequest()->setTransaction(new \PayCheckout\Json\Request\Transaction\Transaction());
					
                if ($this->getRequest()->getOrder()->getCurrency() == null)
                {
                    $this->getRequest()->getTransaction()->setCurrency(\PayCheckout\Currency::EUR);
                }
                else
                {
                    $this->getRequest()->getTransaction()->setCurrency($this->getRequest()->getOrder()->getCurrency());
                }
			}
		}
        
        // Validation
        if ($returnUrlSuccess != null && !is_string($returnUrlSuccess))
        {
            $this->addValidationError('In method payment_overrideConfiguredReturnUrls parameter returnUrlSuccess[' . $returnUrlSuccess . '] is supposed to be a string and not a ' . gettype($returnUrlSuccess));
        }
        if ($returnUrlCancelled != null && !is_string($returnUrlCancelled))
        {
            $this->addValidationError('In method payment_overrideConfiguredReturnUrls parameter returnUrlCancelled[' . $returnUrlCancelled . '] is supposed to be a string and not a ' . gettype($returnUrlCancelled));
        }
        if ($returnUrlFailed != null && !is_string($returnUrlFailed))
        {
            $this->addValidationError('In method payment_overrideConfiguredReturnUrls parameter returnUrlFailed[' . $returnUrlFailed . '] is supposed to be a string and not a ' . gettype($returnUrlFailed));
        }
        // Validation end

        if ($this->getRequest()->getOrder() != null)
        {
            $this->getRequest()->getOrder()->setCurrency($this->getRequest()->getTransaction()->getCurrency());
        }
        
        $this->getRequest()->getTransaction()->setReturnUrlsOverride( $returnUrlSuccess, $returnUrlCancelled, $returnUrlFailed);
    }

    public function payment_setQuantityMultiplyFactor($quantityMultiplyFactor)
    {
        if ( $this->getRequest() == null || ( $this->getRequest()->getTransaction() == null && $this->getAction() == ApiAction::PAYMENT ) || 
             !in_array($this->getAction(), array(ApiAction::PAYMENT, ApiAction::GET_AVAILABLE_PAYMENT_METHODS))
           )
            
        {
            throw new Exception('ApiMessage was not created with a PayCheckout\Api\Payment\XXX api call or is not a payment or call to getAvailablePaymentMethods');
        }

        
        // Validation
        if ($quantityMultiplyFactor != null && !HelpFunction::is32bitInt($quantityMultiplyFactor))
        {
            $this->addValidationError('In method payment_setQuantityMultiplyFactor parameter quantityMultiplyFactor[' . $quantityMultiplyFactor . '] is supposed to be a 32 bit integer value and not a ' . gettype($quantityMultiplyFactor));
        }

        if ($this->getRequest()->getOrder() != null && $this->getAction() == ApiAction::PAYMENT)
        {
            $this->getRequest()->getOrder()->setCurrency($this->getRequest()->getTransaction()->getCurrency());
        }

        $this->getRequest()->getOrder()->setQuantityMultiplyFactor($quantityMultiplyFactor);
    }

    /**
	 * {@inheritDoc}
	 */
    protected function setJsonData($name, $value)
    {
        if (is_object($value))
        {
            switch($name)
            {
                case 'request':
                    $this->request = new Request();
                    $this->request->jsonDeserialize($value);
                    break;
                case 'response':
                    $this->response = new Response();
                    $this->response->jsonDeserialize($value);
                    break;
                case 'notification':
                    $this->notification = new Notification();
                    $this->notification->jsonDeserialize($value);
                    break;
            }
        }
        else
        {
            parent::setJsonData($name, $value);
        }
    }
}