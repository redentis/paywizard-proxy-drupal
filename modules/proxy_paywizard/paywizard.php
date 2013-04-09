<?php
/**
 * @author 		David Picarra, 2013
 * @company 	TV App Agency, London
 * @contact 	contact@tvappagency.com
 **/

/** RESOLVE
if($_SERVER["HTTPS"] != "on") {
  header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
  exit();
}
*/

/** 
 * PayWizard SOAP service requester.
 * 
 * A new instance of the class must be configured with the following required parameters:
 * - htaccess username
 * - htaccess password
 * - retailer ref
 * - retailer password
 * - submitter code
 * - submitter auth code
 */
class PayWizardSOAPRequester {  
  private $htUser;
  private $htPassword;
  private $retailerRef;
  private $retailerPassword;
  private $submitter;
  private $authCode;

  private $protocol;
  private $rootUrl;

  private $accessTokenServiceEndPoint;
  private $accessTokenServiceWsdl;  
  private $secureSubscriptionServiceEndPoint;
  private $secureSubscriptionServiceWsdl;
  private $secureMerchantDrivenPaymentServiceEndPoint;
  private $secureMerchantDrivenPaymentServiceWsdl;
  private $securePurchaseServiceEndPoint;
  private $securePurchaseServiceWsdl;
  private $secureAccountServiceEndPoint;
  private $secureAccountServiceWsdl;

  /**
   * Constructor, which sets up the 2 service End Points.
   */
  function __construct($htUser,
		       $htPassword,
		       $retailerRef,
		       $retailerPassword,
		       $submitter,
		       $authCode,
		       $rootUrl,
		       $protocol="https://") {

    $this->htUser = $htUser;
    $this->htPassword = $htPassword;
    $this->retailerRef = $retailerRef;
    $this->retailerPassword = $retailerPassword;
    $this->submitter = $submitter;
    $this->authCode = $authCode;
    $this->rootUrl = $rootUrl;
    $this->protocol = $protocol;


    $this->accessTokenServiceEndPoint = $this->protocol . $this->rootUrl . 'AccessTokenService';
    $this->accessTokenServiceWsdl = $this->protocol . urlencode($this->htUser) . ':' . urlencode($this->htPassword) . '@' . $this->rootUrl . 'AccessTokenService?wsdl';

    $this->secureSubscriptionServiceEndPoint = $this->protocol . $this->rootUrl . 'SubscriptionService';
    $this->secureSubscriptionServiceWsdl = $this->protocol . urlencode($this->htUser) . ':' . urlencode($this->htPassword) . '@' . $this->rootUrl . 'SubscriptionService?wsdl';

    $this->secureMerchantDrivenPaymentServiceEndPoint = $this->protocol . $this->rootUrl . 'MerchantDrivenPaymentService';
    $this->secureMerchantDrivenPaymentServiceWsdl = $this->protocol . urlencode($this->htUser) . ':' . urlencode($this->htPassword) . '@' . $this->rootUrl . 'MerchantDrivenPaymentService?wsdl';	

    $this->securePurchaseServiceEndPoint = $this->protocol . $this->rootUrl . 'PurchaseService';
    $this->securePurchaseServiceWsdl = $this->protocol . urlencode($this->htUser) . ':' . urlencode($this->htPassword) . '@' . $this->rootUrl . 'PurchaseService?wsdl';	

    $this->secureAccountServiceEndPoint = $this->protocol . $this->rootUrl . 'AccountService';
    $this->secureAccountServiceWsdl = $this->protocol . urlencode($this->htUser) . ':' . urlencode($this->htPassword) . '@' . $this->rootUrl . 'AccountService?wsdl';	
  }
  
  /**
   * Returns the submitter details used to send to all the web services.
   *
   * @return submitterDetails The submitter details to send across to the web service
   */
  private function getSubmitterDetails() {
    $submitterDetails = array('AuthenticationCode' => $this->authCode,  'Submitter' => $this->submitter, 'SubmitterRef' => 'TVAppAgency', 'Platform'=>'TVAppAgency');

    return $submitterDetails;
  }  
  
  /**
   * Function to call AccessTokenService to get the retailerAccessToken
   */
  public function getRetailerAccessToken($retailerCustRef) {  
    // Create a SOAP Client.
    $client = new SoapClient($this->accessTokenServiceWsdl, array(
								  'login' => $this->htUser,
								  'password' => $this->htPassword,
								  'exceptions' => true,
								  'cache_wsdl' => WSDL_CACHE_NONE,
								  'features' => SOAP_SINGLE_ELEMENT_ARRAYS
								  ));
		
    // Set correct endpoint
    $client->__setLocation($this->accessTokenServiceEndPoint);
		
    // Define the submission details.
    $submitterDetails = $this->getSubmitterDetails();
		
    // Define the request.
    $requestDetails = array('retailerCustomerRef' => $retailerCustRef, 'retailerRef' => $this->retailerRef, 'username' => $this->submitter, 'password' => $this->authCode, 'submission' => $submitterDetails);
		
    // Call the operation
    $result = $client->getRetailerAccessToken($requestDetails);
    $retailerAccessToken = $result->retailerAccessToken;		
	   
    // Return the Retailer Access Token
    return $retailerAccessToken;
  }
  
  
  /**
   * Function to call AccessTokenService to get the customerAccessTokenWithPassword
   */
  public function getCustomerAccessTokenWithPassword($username,$password) {  
    // Create a SOAP Client.
    $client = new SoapClient($this->accessTokenServiceWsdl, array(
								  'login' => $this->htUser,
								  'password' => $this->htPassword,
								  'exceptions' => true,
								  'cache_wsdl' => WSDL_CACHE_NONE,
								  'features' => SOAP_SINGLE_ELEMENT_ARRAYS
								  ));
		
    // Set correct endpoint
    $client->__setLocation($this->accessTokenServiceEndPoint);
	
    // Define the submission details.
    $submitterDetails = $this->getSubmitterDetails();
		
    // Define the request.
    $requestDetails = array('username' => $username, 'password' => $password, 'submission' => $submitterDetails);
		
    // Call the operation
    $result = $client->getCustomerAccessTokenWithPassword($requestDetails);
	   
    return $result;
  }
  
  
  /**
   * Function to call AccessTokenService to get the customerAccessTokenWithPIN
   */
  public function getCustomerAccessTokenWithPIN($username,$pin) {  
    // Create a SOAP Client.
    $client = new SoapClient($this->accessTokenServiceWsdl, array(
								  'login' => $this->htUser,
								  'password' => $this->htPassword,
								  'exceptions' => true,
								  'cache_wsdl' => WSDL_CACHE_NONE,
								  'features' => SOAP_SINGLE_ELEMENT_ARRAYS
								  ));
		
    // Set correct endpoint
    $client->__setLocation($this->accessTokenServiceEndPoint);
	
    // Define the submission details.
    $submitterDetails = $this->getSubmitterDetails();
		
    // Define the request.
    $requestDetails = array('username' => $username, 'pin' => $pin, 'submission' => $submitterDetails);
		
    // Call the operation
    $result = $client->getCustomerAccessTokenWithPIN($requestDetails);			
	   
    return $result;
  }
  
  /**
   * Function to call SubscriptionService to get the ContractStatus
   */
  public function getContractDetails($retailerAccessToken, $contractId) {
    // Create a SOAP Client.
    $client = new SoapClient($this->secureSubscriptionServiceWsdl, array(
									 'login' => $this->htUser,
									 'password' => $this->htPassword,
									 'exceptions' => true,
									 'cache_wsdl' => WSDL_CACHE_NONE,
									 'features' => SOAP_SINGLE_ELEMENT_ARRAYS
									 ));
		
    // Set correct endpoint
    $client->__setLocation($this->secureSubscriptionServiceEndPoint);
		
    $contractDetails = array();
	
    // Define the submission details.
    $submitterDetails = $this->getSubmitterDetails();
		
    // Define the request.
    $requestDetails = array('retailerAccessToken' => $retailerAccessToken, 'retailerContractId' => $contractId, 'submission' => $submitterDetails);
		
    // Call the operation
    $result = $client-> getContractStatus($requestDetails);
		
    $contractDetails['subscribed'] = $result->subscribed;
    $contractDetails['validTo'] = $result->validTo;
			

    // Return the Contract Details
    return $contractDetails;
  }
  
  /**
   * Function to call MerchantDrivenPaymentService to get the MerchantPaymentAgreements
   */
  public function getMerchantPaymentAgreements($accessToken, $retailerAccessToken, $merchantPaymentAgreementCode="", $activeOnly=true) {
    // Create a SOAP Client.
    $client = new SoapClient($this->secureMerchantDrivenPaymentServiceWsdl, array(
										  'login' => $this->htUser,
										  'password' => $this->htPassword,
										  'exceptions' => true,
										  'cache_wsdl' => WSDL_CACHE_NONE,
										  'features' => SOAP_SINGLE_ELEMENT_ARRAYS
										  ));
		
    // Set correct endpoint
    $client->__setLocation($this->secureMerchantDrivenPaymentServiceEndPoint);
		
    // Define the submission details.
    $submitterDetails = $this->getSubmitterDetails();
			 
    // Define the request.
    $requestDetails = array(
			    'retailerAccessToken' => $retailerAccessToken,
			    'accessToken' => $accessToken,
			    'activeOnly' => $activeOnly,
			    'submission' => $submitterDetails
			    );
    if($merchantPaymentAgreementCode!="")
      $requestDetails['merchantPaymentAgreementCode'] = $merchantPaymentAgreementCode;
		
    // Call the operation
    $result = $client->getMerchantPaymentAgreements($requestDetails);
		
    return $result;
  }
  
  /**
   * Function to call MerchantDrivenPaymentService to get the getMerchantDrivenPaymentContractStatus
   */
  public function getMerchantDrivenPaymentContractStatus($retailerAccessToken, $merchantPaymentAgreementCode="", $activeOnly=true) {
    // Create a SOAP Client.
    $client = new SoapClient($this->secureMerchantDrivenPaymentServiceWsdl, array(
										  'login' => $this->htUser,
										  'password' => $this->htPassword,
										  'exceptions' => true,
										  'cache_wsdl' => WSDL_CACHE_NONE,
										  'features' => SOAP_SINGLE_ELEMENT_ARRAYS
										  ));
		
    // Set correct endpoint
    $client->__setLocation($this->secureMerchantDrivenPaymentServiceEndPoint);
		
    // Define the submission details.
    $submitterDetails = $this->getSubmitterDetails();
			 
    // Define the request.
    $requestDetails = array(
			    'retailerAccessToken' => $retailerAccessToken,
			    'activeOnly' => $activeOnly,
			    'submission' => $submitterDetails
			    );
    if($merchantPaymentAgreementCode!="")
      $requestDetails['merchantPaymentAgreementCode'] = $merchantPaymentAgreementCode;
		
    // Call the operation
    $result = $client->getMerchantDrivenPaymentContractStatus($requestDetails);
		
    return $result;
  }
  
  /**
   * Function to call MerchantDrivenPaymentService to get the getAccountPurchaseHistoryRequest
   */
  public function getAccountPurchaseHistoryRequest($accessToken, $retailerAccessToken, $searchPeriod=1000, $validFor=1000) {
    // Create a SOAP Client.
    $client = new SoapClient($this->secureAccountServiceWsdl, array(
								    'login' => $this->htUser,
								    'password' => $this->htPassword,
								    'exceptions' => true,
								    'cache_wsdl' => WSDL_CACHE_NONE,
								    'features' => SOAP_SINGLE_ELEMENT_ARRAYS
								    ));
		
    // Set correct endpoint
    $client->__setLocation($this->secureAccountServiceEndPoint);
		
    // Define the submission details.
    $submitterDetails = $this->getSubmitterDetails();
	 
    // Define the request.
    $requestDetails = array(
			    'accessToken' => $accessToken,				
			    'retailerAccessToken' => $retailerAccessToken,
			    'searchPeriod' => $searchPeriod,
			    'validFor' => $validFor,
			    'submission' => $submitterDetails
			    );
		
    // Call the operation
    $result = $client->getAccountPurchaseHistoryRequest($requestDetails);

    return $result;
  }
  
  /**
   * Function to call MerchantDrivenPaymentService to get the getAccountPurchaseHistoryRequest
   *
   *
   * Each product on $PRODUCTS needs to be like this:
   *
   *           <cor:productRef>3442</cor:productRef>
   *           <cor:description>The Incredible Hulk</cor:description>
   *           <cor:quantity>1</cor:quantity>
   *           <cor:monetaryAmount>
   *              <core:amount>0.00</core:amount>
   *              <core:currencyCode>GBP</core:currencyCode>
   *           </cor:monetaryAmount>
   */
  public function purchase($accessToken, $retailerAccessToken, $retailerTransactionRef, $product, $username) {
    // Create a SOAP Client.
    $client = new SoapClient($this->securePurchaseServiceWsdl, array(
								     'login' => $this->htUser,
								     'password' => $this->htPassword,
								     'exceptions' => true,
								     'cache_wsdl' => WSDL_CACHE_NONE,
								     'features' => SOAP_SINGLE_ELEMENT_ARRAYS
								     ));
		
    // Set correct endpoint
    $client->__setLocation($this->securePurchaseServiceEndPoint);
		
    // Define the submission details.
    $submitterDetails = $this->getSubmitterDetails();
		
    /*
      retailerCustomerRef: 	SOAP1@mgt.com
      retailerTransactionRef: SOAP1@mgt.com_1361358667_3442_samsung
      productRef:				3442
      retailerItemCost:		0.00
      currencyCode:			GBP
      retailerItemQuantiy:	1
      retailerPassword:		XVzrV1mY5

      USING:	SOAP1@mgt.comSOAP1@mgt.com_1361358667_3442_samsung34420.00GBP1XVzrV1mY5
      DIGEST: c451b36ca743b3ed0c2124de23ba81e0f40e688470258d697e4eb4c0fbc3b6cf
    */
		
    $plainText='';
    $product->monetaryAmount->amount = number_format((float)$product->monetaryAmount->amount, 2, '.', ''); // always 2 decimal
    $product->monetaryAmount->currencyCode = strtoupper($product->monetaryAmount->currencyCode); // always uppercase
    $plainText .= $username.$retailerTransactionRef.$product->productRef.$product->monetaryAmount->amount.$product->monetaryAmount->currencyCode.$product->quantity.$this->retailerPassword;
    $product = (array) $product;
    $product['monetaryAmount'] = (array) $product['monetaryAmount'];
    $digest = hash('sha256', $plainText);
		
    // Define the request.
    $requestDetails = array(
			    'accessToken' => $accessToken,				
			    'retailerAccessToken' => $retailerAccessToken,
			    'retailerTransactionRef' => $retailerTransactionRef,
			    'products' => array("product" => $product),
			    'digest' => $digest,
			    'submission' => $submitterDetails
			    );
    // Call the operation
    $result = $client->purchase($requestDetails);		

    return $result;
  }
}


/**
 * TVAppAgency specific decorator around the PayWizard SOAP requestor.
 * This class configures the requestor and also provides some application-specific logic
 * to determine whether a subsciber is in good standing based on information retrieved
 * from PayWizard.
 */
class TVAppAgency {
  public $requester;	

  function __construct($config) {
    $this->requester = new PayWizardSOAPRequester($config['htUser'],
						  $config['htPassword'],
						  $config['retailerRef'],
						  $config['retailerPassword'],
						  $config['submitter'],
						  $config['authCode'],
						  $config['rootUrl']);
  }
	
  public function writeJSONResponse($response) {
    if($_GET['callback'] && $_GET['callback']!="")
      echo $_GET['callback']."(".json_encode($response).")";
    else
      echo json_encode($response);
  }
	
  public function getMerchantDrivenPaymentContractStatus($username, $accessToken, $merchantAgreementCode="") {
    $retailerAccessToken = $this->requester->getRetailerAccessToken($username);
    if($merchantAgreementCode=="") {
      $response = $this->requester->getMerchantPaymentAgreements($accessToken, $retailerAccessToken);
      if(!$response->merchantPaymentAgreements || !$response->merchantPaymentAgreements->merchantPaymentAgreement)
	return null;
      foreach($response->merchantPaymentAgreements->merchantPaymentAgreement as $mpa) {
	if($mpa->status=="ACTIVE" || $mpa->status=="CANCELLED_SCHEDULED") {
	  $merchantAgreementCode = $mpa->mpaCode;
	}
      }
      if($merchantAgreementCode=="")
	return null;
    }
    return $this->requester->getMerchantDrivenPaymentContractStatus($retailerAccessToken, $merchantAgreementCode);
/*
  if(!$response->contractStatuses || !$response->contractStatuses->contractStatus)
  return null;
  foreach($response->contractStatuses->contractStatus as $cs) {
  if($cs->status=="ACTIVE" || $cs->status=="CANCEL_SCHEDULED")
  return $cs;
  }
  return null;
*/
  }
}