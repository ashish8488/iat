<?php

namespace IATBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\SecurityContext;

use IATBundle\Form\UserType;
use IATBundle\Form\DealType;
use IATBundle\Entity\DealFiles;
use IATBundle\Entity\Deal;

/**
 * @author Ashish Shah (ashish414@gmail.com)
 *
 * Description : This is admin controller class
 *
 * @Route("/admin/uk")
 */
class AdminController extends Controller
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
		$this->inquiryRepo = null;
		$this->knpPaginator = null;
		$this->dealRepo = null;
		$this->userRepo = null;
		$this->loggedInUser = null;
		$this->dealFilesRepo = null;
		$this->dealFilePath = null;
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
		unset($this->inquiryRepo);
		unset($this->knpPaginator);
		unset($this->dealRepo);
		unset($this->userRepo);
		unset($this->loggedInUser);
		unset($this->dealFilesRepo);
		unset($this->dealFilePath);
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
		$this->inquiryRepo = $this->entityManager->getRepository('IATBundle:Inquiry');
		$this->knpPaginator = $this->get('knp_paginator');
		$this->dealRepo = $this->entityManager->getRepository('IATBundle:Deal');
		$this->userRepo = $this->entityManager->getRepository('IATBundle:User');
		$this->dealFilesRepo = $this->entityManager->getRepository('IATBundle:DealFiles');
		$this->loggedInUser = $this->getUser();
		$this->dealFilePath = $this->getParameter('deal_file_directory');
	}
	
	/**
	 * @author Ashish Shah (ashish414@gmail.com)
	 *
	 * Description : This function is used to show login path
	 *
	 * @param : None
	 *
	 * @return: NULL
	 *
	 * @throws Exception: Null
	 *
	 * @Route("/login", name = "iatbundle_admin_login")
	 *
	 * @Template("IATBundle:Admin:login.html.twig")
	 */
	public function loginAction()
	{
		$this->initAction();
		
		if ($this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) { //IS_AUTHENTICATED_FULLY
			return $this->redirect($this->generateUrl('iatbundle_admin_registerMerchant'));
		}
		
		$data = array();
		
		if ($this->request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
			$error = $this->request->attributes->get(
					SecurityContext::AUTHENTICATION_ERROR
					);
		} else {
			$error = $this->session->get(SecurityContext::AUTHENTICATION_ERROR);
			$this->session->remove(SecurityContext::AUTHENTICATION_ERROR);
		}
		
		$data['username'] = $this->session->get(SecurityContext::LAST_USERNAME);
		$data['error'] = $error;
		if (isset($_COOKIE['adminuserdata'])) {
			$data['user_cookie'] = unserialize($_COOKIE['adminuserdata']);
		}
		//  $data['csrf_token'] = $this->container->get('form.csrf_provider')->generateCsrfToken('authenticate');
		return $data;
	}
	
    /**
     * @author Ashish Shah (ashish414@gmail.com)
     * 
     * Description : This function is used to register a merchant
     * 
     * @param : None
     * 
     * @return: NULL
     * 
     * @throws Exception: Null
     * 
     * @Route("/registerMerchant", name = "iatbundle_admin_registerMerchant")
     * 
     * @Template("IATBundle:Admin:registerMerchant.html.twig")
     */
    public function registerMerchantAction()
    {
    	$this->initAction();
    	
    	$form = $this->createForm(new UserType());
    	
    	$form->handleRequest($this->request);
    	
    	if ($form->isValid()) {
    		
    		$user = $this->userRepo->setUser($form->getData());
    		
    		$this->session->getFlashBag()->add("success_message", "Merchant Registered Successfully.");
    		
    		return $this->redirect($this->generateUrl('iatbundle_admin_uploadDeals'));
    	}
    	
    	$this->responseArray['form'] = $form->createView();
    	$this->responseArray['loggedInUser'] = $this->loggedInUser;
    	
    	unset($form);
    	return $this->responseArray;
    }

    /**
     * @author Ashish Shah (ashish414@gmail.com)
     * 
     * Description : This function is used to save a deal file
     * 
     * @param : None
     * 
     * @return: NULL
     * 
     * @throws Exception: Null
     * 
     * @Route("/uploadDeals", name = "iatbundle_admin_uploadDeals")
     * 
     * @Template("IATBundle:Admin:uploadDeals.html.twig")
     */
    public function uploadDealsAction()
    {
    	$this->initAction();
    	$form = $this->createForm(new DealType());
    	 
    	$form->handleRequest($this->request);
    	 
    	if ($form->isValid()) {
    	
    		$formData = $form->getData();
    		
    		$dealFile = $this->dealFilePath . '/' . $this->dealFilesRepo->getLatestUploadedFile();
    		
    		$phpExcelObject = $this->get('phpexcel')->createPHPExcelObject($dealFile);
    		
    		foreach ($phpExcelObject->getWorksheetIterator() as $worksheet) {
    			//echo 'Worksheet - ' , $worksheet->getTitle(), '<br>';
    			foreach ($worksheet->getRowIterator() as $row) {
    				//echo "\t" . 'Row number - ' , $row->getRowIndex() , "<br>";
    				if($row->getRowIndex() > 1){
	    				$cellIterator = $row->getCellIterator();
	    				$cellIterator->setIterateOnlyExistingCells(false); // Loop all cells, even if it is not set
	    				$dealObj = new Deal();
	    				$dealObj->setUserId($formData->getUserId());
	    				$dealObj->setDestination($formData->getDestination());
	    				$dealObj->setDealTitle($formData->getDealTitle());
	    				foreach ($cellIterator as $cell) {
	    					if (!is_null($cell)) {
	    						//echo "&nbsp;&nbsp;&nbsp;&nbsp;" . 'Cell - ' , $cell->getCoordinate() , ' - ' , $cell->getCalculatedValue() , "<br>";
	    						switch ($cell->getColumn()){
	    							case 'A':
	    								$dealObj->setDepartureAirport($cell->getCalculatedValue());
	    								break;
	    							case 'B':
	    								
	    								$startDate = $cell->getFormattedValue();
	    								$dateObj = new \DateTime();
	    								$date = $dateObj->createFromFormat ('d.m.Y' , $startDate);
	    								$dealObj->setStartDate($date);
	    								break;
	    							case 'C':
	    								$endDate = $cell->getFormattedValue();
	    								$dateObj = new \DateTime();
	    								$date = $dateObj->createFromFormat ('d.m.Y' , $endDate);
	    								$dealObj->setEndDate($date);
	    								//$formData->setEndDate(new \DateTime($cell->getCalculatedValue()));
	    								break;
	    							case 'D':
	    								$dealObj->setNoOfNights($cell->getCalculatedValue());
	    								break;
	    							case 'E':
	    								//$formData->setStartDate($cell->getCalculatedValue());
	    								break;
	    							case 'F':
	    								$dealObj->setHotelName($cell->getCalculatedValue());
	    								break;
	    							case 'G':
	    								//$formData[] = $mainFormData->setStartDate($cell->getCalculatedValue());
	    								break;
	    							case 'H':
	    								$extras = ($cell->getCalculatedValue()) ? $cell->getCalculatedValue() : '';
	    								$dealObj->setExtras($extras);
	    								break;
	    							case 'I':
	    								//$formData[] = $mainFormData->setStartDate($cell->getCalculatedValue());
	    								break;
	    							case 'J':
	    								$dealObj->setPrice($cell->getCalculatedValue());
										break;
									default:
										break;
	    						}
	    					}
	    				}
	    				$dealObj->setcreatedAt(new \DateTime("now"));
	    				$dealObj->setDealLink($dealObj->getDestination().'-'.$dealObj->getUserId()->getId().'-'.$dealObj->getcreatedAt()->format('Ymd'));
	    				$dm = new \DateTime($dealObj->getStartDate()->format('Y-m-d'));
	    				$dealObj->setDepartureMonths($dm->format('Y-m'));
	    				$dealObj->setStatus(1);
	    				$this->entityManager->persist($dealObj);
	    			}
    			}
    		}
    		
			$this->entityManager->flush();
    	
    		$this->session->getFlashBag()->add("success_message", "Merchant Registered Successfully.");
    	
    		return $this->redirect($this->generateUrl('iatbundle_admin_uploadDeals'));
    	}
    	 
    	$this->responseArray['form'] = $form->createView();
    	$this->responseArray['loggedInUser'] = $this->loggedInUser;
    	 
    	unset($form);
    	return $this->responseArray;
    }
    
    /**
     * @author Ashish Shah (ashish414@gmail.com)
     *
     * Description : This function is used to upload a deal file
     *
     * @param : None
     *
     * @return: NULL
     *
     * @throws Exception: Null
     *
     * @Route("/uploadDealsFile", name = "iatbundle_admin_uploadDealsFile")
     *
     * @Template("")
     */
    public function uploadDealsFileAction()
    {
    	$this->initAction();
    	//$this->responseArray['loggedInUser'] = $this->loggedInUser;
    	
    	
    	if (isset($_FILES['dealFile']) && !empty($_FILES['dealFile'])) {
    		$this->responseArray = $this->uploadMediaFile($_FILES);
    	}
    	
    	if (isset($this->responseArray[0]['error'])) {
    		$errorMessage = '';
    		/* if ($this->responseArray[0]['error'] == 'MEDIA_FILE_TYPE_NOT_ALLOWED') {
    			$errorMessage = $this->translator->trans('cms.media_library.upload.file_upload_error.file_type');
    		}
    	
    		if ($this->responseArray[0]['error'] == 'MEDIA_FILE_TOO_LARGE') {
    			$errorMessage = $this->translator->trans('cms.media_library.upload.file_upload_error.file_size');
    		} */
    	
    		return new JsonResponse(array('status' => 'error', 'error_message' => $errorMessage));
    	} else {
    		return new JsonResponse(array('status' => 'success', 'success_message' => "File Has beenuploaded successfully."));
    	}
    	return $this->responseArray;
    }

    /**
     * @author Ashish Shah (ashish414@gmail.com)
     * 
     * Description : This function is used to list all inquiries for the merchant
     * 
     * @param : None
     * 
     * @return: NULL
     * 
     * @throws Exception: Null
     * 
     * @Route("/listInquiry/{page}", name = "iatbundle_admin_listInquiry",
     * requirements={"page"="\d+"},
     * defaults={"page"=1})
     * 
     * @Template("IATBundle:Admin:listInquiry.html.twig")
     */
    public function listInquiryAction()
    {
    	$this->initAction();
    	$this->responseArray['loggedInUser'] = $this->loggedInUser;
    	$page = $this->request->get('page', 1);
    	$userId = (!empty($this->loggedInUser)) ? $this->loggedInUser->getId() : null;
    	$dealInquiries = $this->inquiryRepo->getInquiriesByUserId($userId);
    	//$deals = $this->dealsRepo->getDealsByUserId($userId);
    	$this->responseArray['dealInquiries'] = $this->knpPaginator->paginate($dealInquiries, $page, $this->recordsPerPage, array(), count($dealInquiries));
    	return $this->responseArray;
    }
    
    /**
     * @author Ashish Shah (ashish414@gmail.com)
     *
     * Description : This function is used to list deals
     *
     * @param : None
     *
     * @return: NULL
     *
     * @throws Exception: Null
     *
     * @Route("/listDeal/{page}", name = "iatbundle_admin_listDeal",
     * requirements={"page"="\d+"},
     * defaults={"page"=1})
     *
     * @Template("IATBundle:Admin:listDeal.html.twig")
     */
    public function listDealAction()
    {
    	$this->initAction();
    	$this->responseArray['loggedInUser'] = $this->loggedInUser;
    	$page = $this->request->get('page', 1);
    	$userId = (!empty($this->loggedInUser)) ? $this->loggedInUser->getId() : null;
    	$isAdmin = (!empty($this->loggedInUser) && $this->loggedInUser->getType() == 'admin') ? true : false;
    	
    	
    	
    	$filterUserId = $this->request->get('merchant_id', '');
    	if($isAdmin){
    		$merchantNameFilter = $this->dealRepo->getMerchantNameForFilter();
    		$deals = $this->dealRepo->getAllDeals($filterUserId);
    		$this->responseArray['merchantNameFilter'] = $merchantNameFilter;
    	}else{
    		//$merchantDealFilter = $this->dealRepo->getMerchantDealForFilter();
    		$deals = $this->dealRepo->getDealsByUserId($userId);
    	}
    	
    	$this->responseArray['deals'] = $this->knpPaginator->paginate($deals, $page, $this->recordsPerPage, array(), count($deals));
    	return $this->responseArray;
    }
    
    /**
     * @author Ashish Shah (ashish414@gmail.com)
     *
     * Description : This function is used to list deals
     *
     * @param : None
     *
     * @return: NULL
     *
     * @throws Exception: Null
     *
     * @Route("/listDealLink/{page}", name = "iatbundle_admin_dealLinkList",
     * requirements={"page"="\d+"},
     * defaults={"page"=1})
     *
     * @Template("IATBundle:Admin:listDealLink.html.twig")
     */
    public function listDealLinkAction()
    {
    	$this->initAction();
    	$this->responseArray['loggedInUser'] = $this->loggedInUser;
    	$page = $this->request->get('page', 1);
    	$userId = (!empty($this->loggedInUser)) ? $this->loggedInUser->getId() : null;
    	$isAdmin = (!empty($this->loggedInUser) && $this->loggedInUser->getType() == 'admin') ? true : false;
    	 
    	$filterUserId = $this->request->get('merchant_id', '');
    	if($isAdmin){
    		$merchantNameFilter = $this->dealRepo->getMerchantNameForFilter();
    		$dealLinks = $this->dealRepo->getAllDealLinks($filterUserId);
    		$this->responseArray['merchantNameFilter'] = $merchantNameFilter;
    	}else{
    		//$merchantDealFilter = $this->dealRepo->getMerchantDealForFilter();
    		$dealLinks = $this->dealRepo->getDealLinksByUserId($userId);
    	}
    	
    	if(!empty($dealLinks) && count($dealLinks) > 0){
    		foreach ($dealLinks as $dealLink){
    			$this->responseArray['dealLinkStatus'][$dealLink->getDealLink()]['status'] = $this->dealRepo->getDealLinkStatus($dealLink->getDealLink());
    		}
    	}
    	 
    	$this->responseArray['dealLinks'] = $this->knpPaginator->paginate($dealLinks, $page, $this->recordsPerPage, array(), count($dealLinks));
    	return $this->responseArray;
    }
    
    /**
     * @author Ashish Shah (ashish414@gmail.com)
     *
     * Description : This function is used to change inquiry status
     *
     * @param : None
     *
     * @return: NULL
     *
     * @throws Exception: Null
     *
     * @Route("/changeInquiryStatus/{id}", name = "iatbundle_admin_changeInquiryStatus",
     * requirements={"id"="\d+"},
     * options={"expose"=true})
     *
     * @Template()
     */
    public function changeInquiryStatusAction()
    {
    	$this->initAction();
    	$id = $this->request->get('id', 1);
    	$inquiry = $this->inquiryRepo->getInquiryById($id);
    	if(!empty($inquiry->getStatus()) && $inquiry->getStatus() == 1){
    		$inquiry->setStatus(0);
    	}else{
    		$inquiry->setStatus(1);
    	}
    	
    	$this->entityManager->persist($inquiry);
    	$this->entityManager->flush();
    	
    	$this->responseArray['status'] = 'success';
    	$this->responseArray['message'] = "Enquiy Status has been changed successfully.";
    	
    	return new JsonResponse($this->responseArray);
    }
    
    /**
     * @author Ashish Shah (ashish414@gmail.com)
     *
     * Description : This function is used to change deal status
     *
     * @param : None
     *
     * @return: NULL
     *
     * @throws Exception: Null
     *
     * @Route("/changeDealStatus/{id}", name = "iatbundle_admin_changeDealStatus",
     * requirements={"id"="\d+"},
     * options={"expose"=true})
     *
     * @Template()
     */
    public function changeDealStatusAction()
    {
    	$this->initAction();
    	$id = $this->request->get('id', 1);
    	$deal = $this->dealRepo->getDealById($id);
    	if(!empty($deal->getStatus()) && $deal->getStatus() == 1){
    		$deal->setStatus(0);
    	}else{
    		$deal->setStatus(1);
    	}
    	 
    	$this->entityManager->persist($deal);
    	$this->entityManager->flush();
    	 
    	$this->responseArray['status'] = 'success';
    	$this->responseArray['message'] = "Deal Status has been changed successfully.";
    	 
    	return new JsonResponse($this->responseArray);
    }
    
    /**
     * @author Ashish Shah (ashish414@gmail.com)
     *
     * Description : This function is used to change dealLink status
     *
     * @param : None
     *
     * @return: NULL
     *
     * @throws Exception: Null
     *
     * @Route("/changeDealLinkStatus/{dealLink}", name = "iatbundle_admin_changeDealLinkStatus",
     * requirements={"id"="\d+"},
     * options={"expose"=true})
     *
     * @Template()
     */
    public function changeDeallinkStatusAction()
    {
    	$this->initAction();
    	$dealLink = $this->request->get('dealLink');
    	if(empty($dealLink)){
    		$this->responseArray['status'] = 'error';
    		$this->responseArray['message'] = "Deallink Status could not been changed.";
    		return new JsonResponse($this->responseArray);
    	}
    	$dealLinkStatus = $this->dealRepo->getDealLinkStatus($dealLink);
    	//$this->responseArray['dealLinkStatus'][$dealLink]['status'] = $this->dealRepo->getDealLinkStatus($dealLink->getDealLink());
    	
    	$deals = $this->dealRepo->getAllDealsByDealLink($dealLink);
    	if(count($deals) > 0){
    		foreach ($deals as $deal){
    			if($dealLinkStatus == 'active'){
    				$deal->setStatus(0);
    				$this->entityManager->persist($deal);
    			}else{
    				$deal->setStatus(1);
    				$this->entityManager->persist($deal);
    			}
    		}
    		$this->entityManager->flush();
    		$this->responseArray['status'] = 'success';
    		$this->responseArray['message'] = "Deal Link Status has been changed successfully.";
    	}else{
    		$this->responseArray['status'] = 'error';
    		$this->responseArray['message'] = "Deal Link Status could not been changed.";
    	}
    
    	return new JsonResponse($this->responseArray);
    }
    
    /**
     * @author Ashish Shah (ashish414@gmail.com)
     *
     * Description : This function is used to delete inquiry
     *
     * @param : None
     *
     * @return: NULL
     *
     * @throws Exception: Null
     *
     * @Route("/deleteInquiry", name = "iatbundle_admin_deleteInquiry",
     * options={"expose"=true})
     *
     * @Template()
     */
    public function deleteInquiryAction()
    {
    	$this->initAction();
    	$id = $this->request->get('id');
    	$inquiry = $this->inquiryRepo->getInquiryById($id);
    	if(!empty($inquiry)){
    		$this->entityManager->remove($inquiry);
    		$this->entityManager->flush();
    		
    		$this->responseArray['status'] = 'success';
    		$this->responseArray['message'] = "Enquiy has been deleted successfully.";
    	}else{
    		$this->responseArray['status'] = 'error';
    		$this->responseArray['message'] = "Enquiy could not be deleted.";
    	}
    	 
    	return new JsonResponse($this->responseArray);
    }
    
    /**
     * @author Ashish Shah (ashish414@gmail.com)
     *
     * Description : This function is used to delete deal
     *
     * @param : None
     *
     * @return: NULL
     *
     * @throws Exception: Null
     *
     * @Route("/deleteDeal", name = "iatbundle_admin_deleteDeal",
     * options={"expose"=true})
     *
     * @Template()
     */
    public function deleteDealAction()
    {
    	$this->initAction();
    	$id = $this->request->get('id');
    	$deal = $this->dealRepo->getDealById($id);
    	if(!empty($deal)){
    		$this->inquiryRepo->deleteAllInquiriesByDealId($id);
    		$this->entityManager->remove($deal);
    		$this->entityManager->flush();
    
    		$this->responseArray['status'] = 'success';
    		$this->responseArray['message'] = "Deal has been deleted successfully.";
    	}else{
    		$this->responseArray['status'] = 'error';
    		$this->responseArray['message'] = "Deal could not be deleted.";
    	}
    
    	return new JsonResponse($this->responseArray);
    }
    
    /**
     * @author Ashish Shah (ashish414@gmail.com)
     *
     * Description : This function is used to view inquiry details
     *
     * @param : None
     *
     * @return: NULL
     *
     * @throws Exception: Null
     *
     * @Route("/viewInquiry/{id}", name = "iatbundle_admin_viewInquiry",
     * requirements={"id"="\d+"},
     * options={"expose"=true})
     *
     * @Template("IATBundle:Admin:viewInquiry.html.twig")
     */
    public function viewInquiryAction()
    {
    	$this->initAction();
    	$id = $this->request->get('id');
    	$this->responseArray['inquiry'] = $this->inquiryRepo->getInquiryById($id);
    
    	return $this->responseArray;
    }
    
    /**
     * @author Ashish Shah (ashish414@gmail.com)
     *
     * Description : This function is used to view deal details
     *
     * @param : None
     *
     * @return: NULL
     *
     * @throws Exception: Null
     *
     * @Route("/viewDeal/{id}", name = "iatbundle_admin_viewDeal",
     * requirements={"id"="\d+"},
     * options={"expose"=true})
     *
     * @Template("IATBundle:Admin:viewDeal.html.twig")
     */
    public function viewDealAction()
    {
    	$this->initAction();
    	$id = $this->request->get('id');
    	$this->responseArray['deal'] = $this->dealRepo->getDealById($id);
    
    	return $this->responseArray;
    }
    
    /**
     * @author Ashish Shah (ashish414@gmail.com)
     * 
     * Function created to logout
     * 
     * @Route("admin/logout", name="iatbundle_admin_logout",
     * options={"expose"=true})
     * 
     * @Template()
     */
    
    public function logoutAction()
    {
    }
    
    /**
     * @author Ashish Shah (ashish414@gmail.com)
     *
     * Description : This function is used to validate the uploaded file and move it to the targeted directory
     *
     * @param : $formData
     *
     * @return Null
     *
     * @throws Exception : Null
     *
     **/
    public function uploadMediaFile($file)
    {
    	if (is_array($file)) {
    		foreach ($file as $fileOBject) {
    			$result[] = $this->moveFile($fileOBject);
    		}
    	} else {
    		$result[] = $this->moveFile($file);
    	}
    	unset($file);
    	return $result;
    }
    
    /**
     * @author Ashish Shah (ashish414@gmail.com)
     *
     * Description : This function is used to validate the uploaded file and move it to the targeted directory
     *
     * @param : $formData
     *
     * @return Null
     *
     * @throws Exception : Null
     *
     **/
    public function moveFile($file, $folderPath='', $addtoDb='yes')
    {
    	$error = '';
    	$status = $this->createMediaFileDirectory();
    	if (isset($status['error'])) {
    		$error = $status['error'];
    	}
    
    	if (empty($error)) {
    		$fileName = $this->getNewFileNameIfFileExists($file['name']);
    		if ($folderPath!='') {
    			$targetDir = $folderPath.'/'.$fileName;
    		} else {
    			$targetDir = $this->dealFilePath.'/'.$fileName;
    		}
    		move_uploaded_file($file['tmp_name'], $targetDir);
    		//$uploadedMediaInfo = array('name' => $fileName, 'path' => $targetDir, 'type' => $fileType);
    		$uploadedMediaInfo = array('name' => $fileName, 'type' => $file['type']);
    		
    		//if ($addtoDb=='yes') {
    			$result = $this->saveFileName($uploadedMediaInfo);
    			
    		//}
    
    		//unset($fileName);
    		unset($targetDir);
    		unset($uploadedMediaInfo);
    		unset($result);
    	}
    
    	unset($file);
    	unset($fileType);
    	unset($fileSize);
    
    	if (!empty($error)) {
    		return array('error' => $error);
    	}
    
    	unset($error);
    	return $fileName;
    }
    
    /**
     * @author Ashish Shah (ashish414@gmail.com)
     *
     * Description : This function is used to view the image
     *
     * @param : Null
     *
     * @return Null
     *
     * @throws Exception : Null
     *
     **/
    public function createMediaFileDirectory()
    {
    	if (!is_dir($this->dealFilePath)) {
    		if (!file_exists($this->dealFilePath)) {
    			if (false === @mkdir($this->dealFilePath, 0777, true)) {
    				$error[]='Unable to create the directory';
    				return array('error' => $error);
    			}
    		}
    	}
    }
    
    /**
     * @author Ashish Shah (ashish414@gmail.com)
     *
     * Description : This function checks if the file exists or not.
     *
     * @param : $fileName
     *
     * @return $fileName
     *
     * @throws Exception : Null
     *
     **/
    public function getNewFileNameIfFileExists($fileName)
    {
    	/* $i = 1;
    	 if (file_exists($this->dealFilePath.'/'.$fileName)) {
    	 $fileInfo = array();
    	 $newFileName = $fileName;
    	 while (file_exists($this->dealFilePath.'/'.$newFileName)) {
    	 $fileInfo = pathinfo($this->dealFilePath.'/'.$fileName);
    	 $newFileName = $fileInfo['filename'].$i.'.'.$fileInfo['extension'];
    	 $i++;
    	 }
    	 $fileName = $newFileName;
    	 unset($newFileName);
    	 unset($fileInfo);
    	 }
    	 unset($i); */
    
    	$currentDateTime = new \DateTime();
    
    	$fileInfo = pathinfo($this->dealFilePath.'/'.$fileName);
    	$fileName = $currentDateTime->format('YmdHis').'_'.$fileInfo['filename'].'.'.$fileInfo['extension'];
    
    	unset($currentDateTime);
    	unset($fileInfo);
    
    	return $fileName;
    }
    
    /**
     * @author Ashish Shah (ashish414@gmail.com)
     *
     * Description : This function is used to store the uploaded media's data into database
     *
     * @param : $uploadedMediaInfo
     *
     * @return Null
     *
     * @throws Exception : Null
     *
     **/
    public function saveFileName($uploadedMediaInfo)
    {
    	$dealFiles = new DealFiles();
    	$dealFiles->setFileName($uploadedMediaInfo['name']);
    	$dealFiles->setCreatedAt(new \DateTime("now"));
    	
    	$this->entityManager->persist($dealFiles);
    	$this->entityManager->flush();
    	
    	return $dealFiles->getId();
    }
    
    function excelToPHP($dateValue = 0) {
    	$myExcelBaseDate = 25569;
    	//  Adjust for the spurious 29-Feb-1900 (Day 60)
    	if ($dateValue < 60) {
    		--$myExcelBaseDate;
    	}
    	// Perform conversion
    	if ($dateValue >= 1) {
    		$utcDays = $dateValue - $myExcelBaseDate;
    		$returnValue = round($utcDays * 86400);
    		if (($returnValue <= PHP_INT_MAX) && ($returnValue >= -PHP_INT_MAX)) {
    			$returnValue = (integer) $returnValue;
    		}
    	} else {
    		$hours = round($dateValue * 24);
    		$mins = round($dateValue * 1440) - round($hours * 60);
    		$secs = round($dateValue * 86400) - round($hours * 3600) - round($mins * 60);
    		$returnValue = (integer) gmmktime($hours, $mins, $secs);
    	}
    
    	// Return
    	return $returnValue;
    }
}
