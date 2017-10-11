<?php

namespace IATBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use IATBundle\Form\InquiryType;
use IATBundle\Entity\Inquiry;

/**
 * @author Ashish Shah (ashish414@gmail.com)
 *
 * Description : This is front controller class
 *
 */
class FrontController extends Controller
{
	
    /**
	 * @author Ashish Shah (ashish414@gmail.com)
	 *
	 * Description : This function is used to set member variables default value
	 *
	 * @param : None
	 *
	 * @return: NULL
	 *
	 * @throws Exception: Null
	 */
	public function __construct()
	{
		$this->request = null;
		$this->responseArray = array();
		$this->entityManager = null;
		$this->recordsPerPage = null;
		$this->session=null;
		$this->dealRepo = null;
		$this->userRepo = null;
	}
	
	/**
	 * @author Ashish Shah(ashish414@gmail.com)
	 *
	 * Description : This function is used to unset member variables
	 *
	 * @param : None
	 *
	 * @return NULL
	 *
	 * @throws Exception: Null
	 */
	public function __destruct()
	{
		unset($this->request);
		unset($this->responseArray);
		unset($this->entityManager);
		unset($this->recordsPerPage);
		unset($this->session);
		unset($this->dealRepo);
		unset($this->userRepo);
	}
	
	/**
	 * @author Ashish Shah (ashish414@gmail.com)
	 *
	 * Description : This function is used to initialize the variables
	 *
	 * @param : None
	 *
	 * @return NULL
	 *
	 * @throws Exception: Null
	 */
	public function initAction()
	{
		$this->request = $this->getRequest();
		$this->entityManager = $this->getDoctrine()->getManager();
		$this->recordsPerPage = 10;
		$this->session = $this->request->getSession();
		$this->dealRepo = $this->entityManager->getRepository('IATBundle:Deal');
		$this->userRepo = $this->entityManager->getRepository('IATBundle:User');
	}
	
    /**
     * @author Ashish Shah (ashish414@gmail.com)
     * 
     * Description : This function is used to display deal page
     * 
     * @param : None
     * 
     * @return: NULL
     * 
     * @throws Exception: Null
     * 
     * @Route("/uk/showDeal/{dealLink}", name = "iatbundle_front_showDeal")
     * 
     * @Template("IATBundle:Front:showDeal.html.twig")
     */
    public function showDealAction()
    {
    	$this->initAction();
    	$dealLink = $this->request->get('dealLink');
    	$destination = explode('-', $dealLink);
    	$destination = $destination[0];
    	$noOfNights = $this->request->get('noOfNights');
    	$departureAirport = $this->request->get('departureAirport');
    	$departureMonth = $this->request->get('departureMonth');
    	//$departureMonth = $this->request->get('departureMonth', array());
    	$extras = $this->request->get('extras');
    	$selectedDealPrice = $this->request->get('selectedDealPrice');
    	$selectedDepartureDate = $this->request->get('selectedDepartureDate');
    	$defaultnoOfnights = $this->request->get('defaultNoOfnights');
    	$criteria = array(
    		'dealLink' => $dealLink,
    		'noOfNights' => $noOfNights,
    		'departureAirport' => $departureAirport,
    		'departureMonth' => $departureMonth,
    		'destination' => $destination,
    		'extras' => $extras,
    		'price' => $selectedDealPrice,
    		'departureDate' => $selectedDepartureDate,
    		'defaultNoOfnights' => $defaultnoOfnights
    	);
    	
    	//echo "Criteria:<pre>";print_r($criteria);echo "</pre><br>";die;
    	
    	$dealData = $this->dealRepo->getDealsByCriteria($criteria);
    	$dealDataForFilters = $this->dealRepo->getDealsByDealLink($dealLink);
    	
    	$dealLinkStatus = $this->dealRepo->getDealLinkStatus($dealLink);
    	$dealTitle = $this->dealRepo->getDealTitleByDealLink($dealLink);
    	$dealTitle = ($dealTitle['dealTitle']) ? $dealTitle['dealTitle'] : '';
    	$this->responseArray['dealLinkStatus'] = $dealLinkStatus;
    	
    	$filters = array();
    	$startMonth = $endMonth = null; //$dealTitle = null;
    	if(!empty($dealDataForFilters)){
    		foreach ($dealDataForFilters as $deal){
    			$filters['noOfNights'][$deal->getNoOfNights()] = $deal->getNoOfNights();
    			$filters['departureAirport'][$deal->getDepartureAirport()] = $deal->getDepartureAirport();
    			$filters['departureMonth'][$deal->getDepartureMonths()] = $deal->getDepartureMonths();
    			$extras = $deal->getExtras();
    			if(!empty($extras)){
    				$filters['extras'][$extras] = $extras;
    			}
    		}
    		if(!empty($dealData)){
    			$startMonth = $dealData[0]->getDepartureMonths();
    			$endMonth = $dealData[count($dealData)-1]->getDepartureMonths();
    			//$dealTitle = $dealData[0]->getDealTitle();
    		}
    	}
    	
    	$this->responseArray['dealData'] = $dealData;
    	$this->responseArray['filters'] = $filters;
    	$this->responseArray['criteria'] = $criteria;
    	$this->responseArray['startMonth'] = $startMonth;
    	$this->responseArray['endMonth'] = $endMonth;
    	$this->responseArray['dealTitle'] = $dealTitle;
    	
    	return $this->responseArray;
    }
    
    /**
     * @Route("/uk/getDeal/{selectedCriteria}", name = "iatbundle_front_getDeal")
     * @Template("IATBundle:Front:getDeal.html.twig")
     */
    public function getDealAction()
    {
    	$this->initAction();
    	$selectedCriteria = json_decode($this->request->get('selectedCriteria'), true);
    	/* $selectedCriteria['departureDate'] = '2017-01-10';
    	$selectedCriteria['price'] = '299'; */
    	$dealObject = $this->dealRepo->getDealsByDealLinkForForm($selectedCriteria['dealLink']);
    	$form = $this->createForm(new InquiryType($selectedCriteria, $dealObject));
    	
    	$form->handleRequest($this->request);
    	 
    	if ($form->isValid()) {
    		$formData = $form->getData();
    		
    		$cust1GroupOnCodeFile = $formData->getCust1GroupOnCodeFile();
    		$cust2GroupOnCodeFile = $formData->getCust2GroupOnCodeFile();
    		
    		if(!empty($cust1GroupOnCodeFile)){
    			$file1Name = md5(uniqid()).'.'.$cust1GroupOnCodeFile->guessExtension();
    			$cust1GroupOnCodeFile->move(
    				$this->getParameter('group_on_code_file_directory'),
    				$file1Name
    			);
    			$formData->setCust1GroupOnCodeFile($file1Name);
    		}
    		
    		if(!empty($cust2GroupOnCodeFile)){
    			$file2Name = md5(uniqid()).'.'.$cust2GroupOnCodeFile->guessExtension();
    			$cust2GroupOnCodeFile->move(
    				$this->getParameter('group_on_code_file_directory'),
    				$file2Name
    			);
    			$formData->setCust2GroupOnCodeFile($file2Name);
    		}
    		
    		$formData->setStatus(1);
    		$formData->setCreatedAt(new \DateTime("now"));
    		
    		$this->entityManager->persist($formData);
    		$this->entityManager->flush();
    		
    		$this->sendMail($formData, $selectedCriteria);
    		
    		//$this->session->getFlashBag()->add("success_message", "Inquiry submitted succesfully.");
    	
    		//return $this->redirect($this->generateUrl('iatbundle_front_showDeal', array('dealLink' => $selectedCriteria['dealLink'])));
    		$this->responseArray['showThankYouPopup'] = true;
    	}/* else{
    		$errors = $this->getErrorMessages($form);
    		echo "Error:<pre>";print_r($errors);echo "</pre>";
    	} */
    	 
    	$this->responseArray['form'] = $form->createView();
    	$this->responseArray['selectedCriteria'] = $selectedCriteria;
    	 
    	unset($form);
    	return $this->responseArray;
    }
    
    protected function getErrorMessages(\Symfony\Component\Form\Form $form)
    {
    	//$placeHolders = array('userRole' => 'senderName', 'email' => 'senderEmail', 'phone' => 'senderTelephone', 'comment' => 'message', 'selfCopy' => 'tomyself');
    
    	$errors = array();
    
    	foreach ($form->getErrors() as $key => $error) {
    
    		$template   = $error->getMessageTemplate();
    		$parameters = $error->getMessageParameters();
    
    		foreach ($parameters as $var => $value) {
    
    			$template = str_replace($var, $value, $template);
    		}
    
    		$errors[$key] = $template;
    	}
    
    	if ($form->count()) {
    
    		foreach ($form as $child) {
    
    			if (!$child->isValid()) {
    
    				if ($child->getName()) {
    
    					$errors[$child->getName()] = $this->getErrorMessages($child);
    				} else {
    
    					$errors[$child->getName()] = $this->getErrorMessages($child);
    				}
    			}
    		}
    	}
    
    	return $errors;
    }
    
    /**
     * @author Ashish Shah (ashish414@gmail.com)
     *
     * Description : This function is used to send inquiry email to admin and customer
     *
     * @param : None
     *
     * @return: NULL
     *
     * @throws Exception: Null
     */
    protected function sendMail($formData, $prefilledData){
    	$deals= $this->dealRepo->getDealsByDealLink($prefilledData['dealLink']);
    	$merchantEmailId = $deals[0]->getUserId()->getEmail();
    	$emailFields = array(
    		'destination' => $prefilledData['destination'],
    		'noOfNights' => $prefilledData['noOfNights'],
    		'price' => $prefilledData['price'],
    		'customer1FirstName' => $formData->getCust1FName(),
    		'customer1LastName' => $formData->getCust1LName(),
    		'customer1dob' => $formData->getCust1dob(),
    		'customer2FirstName' => $formData->getCust2FName(),
    		'customer2LastName' => $formData->getCust2LName(),
    		'customer2dob' => $formData->getCust2dob(),
    		'departureDate' => $formData->getDepartureDate(),
    		'departureAirport' => $prefilledData['departureAirport'],
    		'destination' => $formData->getDealId()->getDestination(),
    		'email' => $formData->getEmail(),
    		'phone' => $formData->getPhone(),
    		'prefferedCallTime' => $formData->getPrefferedCallTime(),
    		'address1' => $formData->getAddress1(),
    		'address2' => $formData->getAddress2(),
    		'city' => $formData->getCity(),
    		'state' => $formData->getState(),
    		'cust1GroupOnCode' => $formData->getCust1GroupOnCode(),
    		'cust2GroupOnCode' => $formData->getCust2GroupOnCode()
    	);
    	
    	$cust1GroupOnCodeFile = $cust2GroupOnCodeFile = '';
    	if(!empty($formData->getCust1GroupOnCodeFile())){
    		$cust1GroupOnCodeFile = $this->getParameter('group_on_code_file_directory'). '/' . $formData->getCust1GroupOnCodeFile();
    	}
    	
    	if(!empty($formData->getCust2GroupOnCodeFile())){
    		$cust2GroupOnCodeFile = $this->getParameter('group_on_code_file_directory'). '/' . $formData->getCust2GroupOnCodeFile();
    	}
    	
    	/* if($cust1GroupOnCodeFile != '' && $cust2GroupOnCodeFile != ''){
    		$attachment = \Swift_Attachment::fromPath($cust1GroupOnCodeFile, $cust2GroupOnCodeFile);
    	}elseif($cust1GroupOnCodeFile != ''){
    		$attachment = \Swift_Attachment::fromPath($cust1GroupOnCodeFile);
    	}elseif ($cust2GroupOnCodeFile != ''){
    		$attachment = \Swift_Attachment::fromPath($cust2GroupOnCodeFile);
    	} */
    	
    	$message = \Swift_Message::newInstance()
    		->setSubject('Enquiry Email')
    		->setFrom($this->getParameter('webmaster_email'))
    		->setTo($formData->getEmail())
    		//->setCc($merchantEmailId)
    		->setBcc(
    			array(
    				//'ajayarora2687@gmail.com',
    				//'aarora@groupon.com',
    				'ajy@boowk.com',
    				$merchantEmailId
    			)
    		)
    		->setBody(
    			$this->renderView(
    				'IATBundle:Front:inquiryEmailTemplate.html.twig',
    					$emailFields
    					),
    			'text/html'
    			)
    			/*
    			 * If you also want to include a plaintext version of the message
    	->addPart(
    			$this->renderView(
    					'Emails/registration.txt.twig',
    					array('name' => $name)
    					),
    			'text/plain'
    			)
    	*/
    	//->attach($attachment)
    	;
    	if($cust1GroupOnCodeFile != ''){
    		$message = $message->attach(\Swift_Attachment::fromPath($cust1GroupOnCodeFile));
    	}
    	
    	if ($cust2GroupOnCodeFile != ''){
    		$message = $message->attach(\Swift_Attachment::fromPath($cust2GroupOnCodeFile));
    	}
    	$this->get('mailer')->send($message);
    }

}
