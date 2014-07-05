<?php

/**
 * Ebay controller to fetch usertoken, sellerlist and myebaylisting
 * 
 * @package Ebay
 * @date 23-Jan-2014
 * @author Rahul P R <rahul.pr@cubettech.com>
 * 
 */

class Ebay {
    
    protected $devID = "";
    protected $appID = "";
    protected $certID = "";
    protected $serverUrl = "";
    protected $userToken = "";
    protected $runame = "";
    protected $siteID = "";
    protected $compatabilityLevel = "";
    protected $StartTimeFrom= "";
    protected $StartTimeTo= "";
    protected $EntriesPerPage= "";
    protected $timeTail= "";
    protected $UserID= "";

    /**
     * Get config values 
     * 
     * @author Rahul P R <rahul.pr@cubettech.com>
     * @date 22-Jan-2014
     */
    
    public function __construct()
    {
    		$keys = User::find(Auth::user()->id)->keys;
        $this->devID			=	$keys->devname;
        $this->appID 			= $keys->appname;
        $this->certID 		= $keys->cert;
        $this->userToken 	= "AgAAAA**AQAAAA**aAAAAA**tlOuUw**nY+sHZ2PrBmdj6wVnY+sEZ2PrA2dj6AFmYWoDJCDpwidj6x9nY+seQ**Zz8CAA**AAMAAA**VLvTphMI/tOBmrN2H8rQCszU0N8kTB89crZGt/N9qFXdyLkYced3af3K/qGXmQEl0NQw6j1AR5pRaz0nkWughw4CQC+qXJagXQE1QapcwiU5JQHZCpJb2G6z1QS7eiobYwVEB+UDNbVSws7DFETeo9OcW8dcXi5nVZqQ8a5FbggIbhxl/rslusA0Rm9eI73G2g9c/3ohoCtJUD3IqRQ6zQ8srltqprTQXYAlO/em4pRHBIchu2ln+/kvcyqZj6R5mVr/oUp9rsDUSBZ+oq7grhwRYwZjmjTo/bHpmADLlU3/DkGRq8qpbKrrGVJmL0NKJlNBQS/0pkPGEw0/TfF6EUL6d4KEdTpnz1soXBNY2Wc2bGDYh7m2wMpNBst5BSTaiIAGAGA1EultP3oUJofkgxagzVGYA5ODoLh5ajJ/nJjau0W9PslgdhDGhyn5OzFEf8WtK06EpMwMc8LiIkhWENciBFscLRMtnXGqdLw1QV8E8wn/H77wPlI+ZM74t1rFN4p02luJ9Jxkp+nmHNO4zURyMP3ly6WrDhC7oGcROA3U8SKlM1FLuzSy7Qn4G7l0M+QtG9hvJMH7BYHuo7UKkYfSaSt4sP4DlBm/dho1zE8SlldO7eV1yCOfdzYFBnNVwzAdwnCGU652/o1ghp4VJlPb5+19yXJcNZCViAqTDgTdI1pAOAo7SM+MZsSVgQOBoDU9rlj00S0Ayv8YwnxwXVKImrGMesm6atS3fYYeVkCKPNrKUg58SvTuDURgdHDh";

        $this->compatabilityLevel= $keys->complvl;
        $this->siteID= $keys->siteID;
        $this->serverUrl= $keys->serverUrl;
        $this->runame= $keys->runame;
    }
    
    
    /**
     * returns an array from an xml string
     * 
     * @param \Cubet\Ebay\SimpleXMLElement $parent
     * @return array
     */
    function XML2Array($xmlSrting){
        $xml = simplexml_load_string($xmlSrting);
        $json = json_encode($xml);
        $array = json_decode($json,TRUE);
        return $array;
    }

    
    /**
     * Parse XML content to Object
     * 
     * @author Rahul P R <rahul.pr@cubettech.com>
     * @date 24-Jan-2014
     * @param type $xml
     * @return response Object
     */
    
    public function parseXml($responseXml){
        if(stristr($responseXml, 'HTTP 404') || $responseXml == '')
                die('<P>Error sending request');
        //Xml string is parsed and creates a DOM Document object
        $responseDoc = new \DomDocument();
        $responseDoc->loadXML($responseXml);
        return $responseDoc;
    }
   
    /**
     * Get get session id
     * 
     * @author Rahul P R <rahul.pr@cubettech.com>
     * @date 22-Jan-2014
     * @param \Cubet\Ebay\type $this->runame
     * @return type xml
     */
    
    public function getSessionId()
    {
            $session = new eBaySession('GetSessionID');
            //Build the request Xml string
            $requestXmlBody = '<?xml version="1.0" encoding="utf-8"?>
                                <GetSessionIDRequest xmlns="urn:ebay:apis:eBLBaseComponents">
                                    <RuName>'.$this->runame.'</RuName>
                                </GetSessionIDRequest>';
            //Create a new eBay session with all details pulled in from included keys.php
            $responseXml = $session->sendHttpRequest($requestXmlBody);
            
            return $responseXml;
    }
    
    /**
     * Get get session id
     * 
     * @author Rahul P R <rahul.pr@cubettech.com>
     * @date 22-Jan-2014
     * @param \Cubet\Ebay\type $this->userToken
     * @return type xml
     */
    
    public function GetUser()
    {
            $session = new eBaySession('GetUser');
            //Build the request Xml string
            $requestXmlBody = '<?xml version="1.0" encoding="utf-8"?>
                                <GetUserRequest xmlns="urn:ebay:apis:eBLBaseComponents">
                                <RequesterCredentials>
                                <eBayAuthToken>'.$this->userToken.'</eBayAuthToken>
                                </RequesterCredentials>
                                </GetUserRequest>';

            //Create a new eBay session with all details pulled in from included keys.php
            $responseXml = $session->sendHttpRequest($requestXmlBody);
                        
            return $responseXml;
    }
    
    /**
     * Get Token Status
     * 
     * @author Rahul P R <rahul.pr@cubettech.com>
     * @date 22-Jan-2014
     * @param type $this->userToken
     * @return type xml
     */
    public function GetTokenStatus()
    {
        $session = new eBaySession('GetTokenStatus');
        $requestXmlBody = '<?xml version="1.0" encoding="utf-8"?>
                            <GetTokenStatusRequest xmlns="urn:ebay:apis:eBLBaseComponents">
                                <RequesterCredentials>
                                    <eBayAuthToken>'.$this->userToken.'</eBayAuthToken>
                                </RequesterCredentials>
                            </GetTokenStatusRequest>';
	//Create a new eBay session with all details pulled in from included keys.php
	$responseXml = $session->sendHttpRequest($requestXmlBody);
        
        return $responseXml;
    }
    
    /**
     * Fetch User Token
     * 
     * @author Rahul P R <rahul.pr@cubettech.com>
     * @date 22-Jan-2014
     * @param type $sessionId
     * @return type xml
     */
    public function fetchToken($sessionId)
    {
        $session = new eBaySession('FetchToken');

        $requestXmlBody = '<?xml version="1.0" encoding="utf-8"?>
                            <FetchTokenRequest xmlns="urn:ebay:apis:eBLBaseComponents">
                              <SessionID>'.$sessionId.'</SessionID>
                            </FetchTokenRequest>';
        //Create a new eBay session with all details pulled in from included keys.php
        $responseXml = $session->sendHttpRequest($requestXmlBody);

        return $responseXml;
    }
    
    
    /**
     * returns sellers list
     * 
     * @author Rahul P R <rahul.pr@cubettech.com>
     * @date 22-Jan-2014
     * @param type $userToken
     * @param type $StartTimeFrom
     * @param type $StartTimeTo
     * @param type $EntriesPerPage
     * @return type xml
     */
    
    public function GetSellerList($StartTimeFrom,$StartTimeTo,$EntriesPerPage,$pageNumber)
    {
            $session = new eBaySession('GetSellerList');
            //Build the request Xml string
            $requestXmlBody = '<?xml version="1.0" encoding="utf-8"?>
                                <GetSellerListRequest xmlns="urn:ebay:apis:eBLBaseComponents">
                                <RequesterCredentials>
                                <eBayAuthToken>'.$this->userToken.'</eBayAuthToken>
                                </RequesterCredentials>
                                 <ErrorLanguage>en_US</ErrorLanguage>
                                  <WarningLevel>High</WarningLevel>
                                  <GranularityLevel>Fine</GranularityLevel>
                                  <StartTimeFrom>'.$StartTimeFrom.'</StartTimeFrom>
                                  <StartTimeTo>'.$StartTimeTo.'</StartTimeTo>
                                  <IncludeWatchCount>true</IncludeWatchCount>
                                  <Pagination>
                                    <EntriesPerPage>'.$EntriesPerPage.'</EntriesPerPage>
                                    <PageNumber>'.$pageNumber.'</PageNumber>    
                                  </Pagination>
                                </GetSellerListRequest>';
            
            //Create a new eBay session with all details pulled in from included keys.php
            $responseXml = $session->sendHttpRequest($requestXmlBody);
            
            return $responseXml;
    }
        
    /**
     * returns Orders Tranaction list
     * 
     * @author Abdullah Bashir <mabdullah353@gmail.com>
     * @date 03-07-2014
     * @param type $userToken
     * @param type $StartTimeFrom
     * @param type $StartTimeTo
     * @param type $EntriesPerPage
     * @return type xml
     */
    
    public function GetOrderTransactions($orderId)
    {
            $session = new eBaySession('GetOrderTransactions');
            //Build the request Xml string
            $requestXmlBody = '<?xml version="1.0" encoding="utf-8"?>
																<GetOrderTransactionsRequest xmlns="urn:ebay:apis:eBLBaseComponents">
																  <RequesterCredentials>
																    <eBayAuthToken>'.$this->userToken.'</eBayAuthToken>
																  </RequesterCredentials>
																  <OrderIDArray>
																    <OrderID>'.$orderId.'</OrderID>
																  </OrderIDArray>
																</GetOrderTransactionsRequest>';
            
            //Create a new eBay session with all details pulled in from included keys.php
            $responseXml = $session->sendHttpRequest($requestXmlBody);
            
            return $responseXml;
    }
            
    /**
     * returns Orders Tranaction list
     * 
     * @author Abdullah Bashir <mabdullah353@gmail.com>
     * @date 03-07-2014
     * @param type $itemId
     * @return type xml
     */
    
    public function GetItemTransactions($itemId)
    {
            $session = new eBaySession('GetItemTransactions');
            //Build the request Xml string
            $requestXmlBody = '<?xml version="1.0" encoding="utf-8"?>
																<GetItemTransactionsRequest xmlns="urn:ebay:apis:eBLBaseComponents">
																	<RequesterCredentials>
																		<eBayAuthToken>'.$this->userToken.'</eBayAuthToken>
																	</RequesterCredentials>
																	<ItemID ComplexType="ItemIDType">'.$itemId.'</ItemID>
																</GetItemTransactionsRequest>​';
            
            //Create a new eBay session with all details pulled in from included keys.php
            $responseXml = $session->sendHttpRequest($requestXmlBody);
            
            return $responseXml;
    }
    
    /**
     * Get my ebay selling details
     * 
     * @author Rahul P R <rahul.pr@cubettech.com>
     * @date 22-Jan-2014
     * @param type $userToken
     * @param type $EntriesPerPage
     * @return type xml
     */

    public function GetMyeBaySelling($EntriesPerPage,$pageNumber)
    {
            $session = new eBaySession('GetMyeBaySelling');
            //Build the request Xml string
            $requestXmlBody = '<?xml version="1.0" encoding="utf-8"?>
                            <GetMyeBaySellingRequest xmlns="urn:ebay:apis:eBLBaseComponents">
                              <RequesterCredentials>
                                <eBayAuthToken>'.$this->userToken.'</eBayAuthToken>
                              </RequesterCredentials>
                              <Version>'.$this->compatabilityLevel.'</Version>
                              <ActiveList>
                                <Sort>TimeLeft</Sort>
                                <Pagination>
                                  <EntriesPerPage>'.$EntriesPerPage.'</EntriesPerPage>
                                  <PageNumber>'.$pageNumber.'</PageNumber>  
                                </Pagination>
                              </ActiveList>
                            </GetMyeBaySellingRequest>';

            //Create a new eBay session with all details pulled in from included keys.php
            $responseXml = $session->sendHttpRequest($requestXmlBody);
            
            return $responseXml;

    }

    /**
     * output result to view page
     * 
     * @author Rahul P R <rahul.pr@cubettech.com>
     * @date 28-Jan-2014
     * @param type $input 
     * @return type
     */
    
    public function ebayManagement($input=array())
    {
        $sellerList = array();
        $myeBaySelling = array();
        $getUser = array();
        $tokenStatus = "";
        $sessionId = "";
        $showLogin = true;
        $StartTimeFrom = $this->StartTimeFrom;
        $StartTimeTo = $this->StartTimeTo;
        $EntriesPerPage = $this->EntriesPerPage;
        $pageNumber = 1;
        $error = "";
        $formInput = array( 'StartTimeFrom' =>  $StartTimeFrom,
                            'StartTimeTo'   =>  $StartTimeTo,
                            'EntriesPerPage'=>  $EntriesPerPage,
                            'pageNumber'    =>  $pageNumber ) ;
        
        $sessionIdXml = $this->getSessionId($this->runame) ;
        $sessionIdResponse = $this->parseXml($sessionIdXml);
        $sessionId = $sessionIdResponse->getElementsByTagName('SessionID')->item(0)->nodeValue;
        \Session::put('new',$sessionId);
        
        // GET userToken
        // 
        // Check if usertoken is getting using the sessionId(passed to the ebay pop up form)
        // if success save that userToken to $this->userToken
        // else set $this->userToken to the token value stored in session
        
        $fetchTokenXml = $this->fetchToken(\Session::get('passed4login','')) ;
        $fetchTokenResponse = $this->parseXml($fetchTokenXml);
        
        if($fetchTokenResponse->getElementsByTagName('Ack')->item(0)->nodeValue=='Success'){
            $this->userToken =  $fetchTokenResponse->getElementsByTagName('eBayAuthToken')->item(0)->nodeValue;
        } else {
            $this->userToken = \Session::get('userToken');
        }
        
        if($this->userToken) {
            
            //get token Status
            $tokenStatusXml = $this->GetTokenStatus($this->userToken) ;
            $tokenStatusResponse = $this->parseXml($tokenStatusXml);
            $tokenStatus = $tokenStatusResponse->getElementsByTagName('Ack')->item(0)->nodeValue=='Success'
                            ? $tokenStatusResponse->getElementsByTagName('Status')->item(0)->nodeValue
                            : 'Inactive' ;

            $GetUserXml = $this->GetUser($this->userToken);
            $getUser = $this->XML2Array($GetUserXml);

            //  if form submitted
            if(isset($input['sellerListSubmit'])){
                
                //echo $input['pageNumber'];

                $StartTimeFrom = isset($input['StartTimeFrom']) && $input['StartTimeFrom']!=''
                                ? $input['StartTimeFrom']
                                :$StartTimeFrom ;
                $StartTimeTo = isset($input['StartTimeTo']) && $input['StartTimeTo']!=''
                                ? $input['StartTimeTo'] 
                                : $StartTimeTo;
                $EntriesPerPage = isset($input['EntriesPerPage']) && $input['EntriesPerPage']!=''
                                ? $input['EntriesPerPage'] 
                                : $EntriesPerPage;
                $pageNumber = isset($input['pageNumber']) && $input['pageNumber']!=''
                                ? $input['pageNumber'] 
                                : $pageNumber;
                
                $formInput = array( 'StartTimeFrom' =>  $StartTimeFrom,
                                    'StartTimeTo'   =>  $StartTimeTo,
                                    'EntriesPerPage'=>  $EntriesPerPage,
                                    'pageNumber'    =>  $pageNumber) ;
                
                $sellerListXml = $this->GetSellerList($this->userToken, $StartTimeFrom, $StartTimeTo, $EntriesPerPage,$pageNumber);
                $sellerList = $this->XML2Array($sellerListXml);
                
                $myeBaySellingXml = $this->GetMyeBaySelling($this->userToken,$EntriesPerPage,$pageNumber); 
                $myeBaySelling = $this->XML2Array($myeBaySellingXml);
            }
            
        } else {
            //'no usertoken';  
        }
        
        \Session::put('passed4login',\Session::get('new',''));
        \Session::put('userToken',$this->userToken);
        
        return array(   'sellerList'    =>  $sellerList,
                        'myeBaySelling' =>  $myeBaySelling,
                        'tokenStatus'   =>  $tokenStatus,
                        'runame'        =>  $this->runame,
                        'sessionId'     =>  urlencode(\Session::get('passed4login')),
                        'userToken'     =>  $this->userToken,
                        'showLogin'     =>  $showLogin,
                        'formInput'     =>  $formInput,
                        'getUser'        => $getUser,
                        'error'         =>  $error
                    ) ;  
    }

}

	/**
	* AUTHOR: Michael Hawthornthwaite - Acid Computer Services (www.acidcs.co.uk) 
	* 
	* Modified by Rahul P R <rahul.pr@cubettech.com>
	* @package Ebay
	* @date 22-Jan-2014
	*/

class eBaySession
{
	private $requestToken;
	private $devID;
	private $appID;
	private $certID;
	private $serverUrl;
	private $complvl;
	private $siteID;
	private $verb;
  private $runame;
    
  /**
  * Fetch ebay configuration values
  * 
  * @author Rahul P R <rahul.pr@cubettech.com>
  * @date 22-Jan-2014
  * @param type $callName
  */
  public function __construct($callName)
	{
		$keys = User::find(Auth::user()->id)->keys;
		$this->verb = $callName;
    $this->devID = $keys->devname;
    $this->appID= $keys->appname;
    $this->certID= $keys->cert;

    $this->complvl = $keys->complvl;
    $this->siteID= $keys->siteID;
    $this->serverUrl= $keys->serverUrl;
    $this->runame= $keys->runame;
	}
	
	
	/**	
  * sendHttpRequest
  * Sends a HTTP request to the server for this session
  * Input: $requestBody
  * Output:The HTTP Response as a String
  * 
  * @author Rahul P R <rahul.pr@cubettech.com>
  * @date 22-Jan-2014
  * @param type $requestBody
  * @return type response
	*/
	public function sendHttpRequest($requestBody)
	{
		//build eBay headers using variables passed via constructor
		$headers = $this->buildEbayHeaders();
		//initialise a CURL session
		$connection = curl_init();
		//set the server we are using (could be Sandbox or Production server)
		curl_setopt($connection, CURLOPT_URL, $this->serverUrl);
		
		//stop CURL from verifying the peer's certificate
		curl_setopt($connection, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($connection, CURLOPT_SSL_VERIFYHOST, 0);
		
		//set the headers using the array of headers
		curl_setopt($connection, CURLOPT_HTTPHEADER, $headers);
		
		//set method as POST
		curl_setopt($connection, CURLOPT_POST, 1);
		
		//set the XML body of the request
		curl_setopt($connection, CURLOPT_POSTFIELDS, $requestBody);
		
		//set it to return the transfer as a string from curl_exec
		curl_setopt($connection, CURLOPT_RETURNTRANSFER, 1);
		
		//Send the Request
		$response = curl_exec($connection);
		
		//close the connection
		curl_close($connection);
		
		//return the response
		return $response;
	}
	
	
	
	/**	
  * BuildEbayHeaders
  * Generates an array of string to be used as the headers for the HTTP request to eBay
  * Output:String Array of Headers applicable for this call
  * 
  * @author Rahul P R <rahul.pr@cubettech.com>
  * @date 22-Jan-2014
  * @return type XML Headers
  */
	private function buildEbayHeaders()
	{
		$headers = array (
			//Regulates versioning of the XML interface for the API
			'X-EBAY-API-COMPATIBILITY-LEVEL: ' . $this->complvl,
			
			//set the keys
			'X-EBAY-API-DEV-NAME: ' . $this->devID,
			'X-EBAY-API-APP-NAME: ' . $this->appID,
			'X-EBAY-API-CERT-NAME: ' . $this->certID,
			
			//the name of the call we are requesting
			'X-EBAY-API-CALL-NAME: ' . $this->verb,			
			
			//SiteID must also be set in the Request's XML
			//SiteID = 0  (US) - UK = 3, Canada = 2, Australia = 15, ....
			//SiteID Indicates the eBay site to associate the call with
			'X-EBAY-API-SITEID: ' . $this->siteID
		);
		
		return $headers;
	}
}