<?php

namespace IATBundle\Entity;

/**
 * InquiryRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class InquiryRepository extends \Doctrine\ORM\EntityRepository
{
	/**
	 * @author Ashish Shah (ashish414@gmail.com)
	 * 
	 * Description : This function is used to get all inquiries by merchant id
	 * 
	 * @param 1 : $userId
	 * 
	 * @return : deal inquiries
	 * 
	 * @throws Exception: Null
	 *
	 **/
	public function getInquiriesByUserId($userId)
	{
		$queryBuilder = $this->createQueryBuilder('i');
		
		$queryBuilder = $queryBuilder
			->innerJoin('IATBundle:Deal','d', 'With', 'i.dealId=d.id')
			->where('d.userId = :user_id')
			->setParameter(':user_id', $userId)
			->orderBy('i.createdAt', 'DESC');
		
		return $queryBuilder->getQuery()->getResult();
	}
	
	/**
	 * @author Ashish Shah (ashish414@gmail.com)
	 *
	 * Description : This function is used to get all inquiries by deal id
	 *
	 * @param 1 : $dealId
	 *
	 * @return : deal inquiries
	 *
	 * @throws Exception: Null
	 *
	 **/
	public function getInquiriesByDealId($dealId)
	{
		$queryBuilder = $this->createQueryBuilder('i');
	
		$queryBuilder = $queryBuilder
		->innerJoin('IATBundle:Deal','d', 'With', 'i.dealId=d.id')
		->where('d.id = :deal_id')
		->setParameter(':deal_id', $dealId)
		->orderBy('i.createdAt', 'DESC');
	
		return $queryBuilder->getQuery()->getResult();
	}
	
	/**
	 * @author Ashish Shah (ashish414@gmail.com)
	 *
	 * Description : This function is used to get inquiry by id
	 *
	 * @param 1 : $id
	 *
	 * @return : inquiry
	 *
	 * @throws Exception: Null
	 *
	 **/
	public function getInquiryById($id)
	{
		$queryBuilder = $this->createQueryBuilder('i');
	
		$queryBuilder = $queryBuilder
		->innerJoin('IATBundle:Deal','d', 'With', 'i.dealId=d.id')
		->where('i.id = :id')
		->setParameter(':id', $id);
	
		return $queryBuilder->getQuery()->getOneOrNullResult();
	}
	
	/**
	 * @author Ashish Shah (ashish414@gmail.com)
	 *
	 * Description : This function is used to delete inquiry
	 *
	 * @param 1 : $id
	 *
	 * @return : null
	 *
	 * @throws Exception: Null
	 *
	 **/
	public function deleteInquiryById($id){
		$inquiry = $this->getInquiryById($id);
		if(!empty($inquiry)){
			$this->_em->remove($inquiry);
			$this->_em->flush();
		
			$this->responseArray['status'] = 'success';
			$this->responseArray['message'] = "Enquiry has been deleted successfully.";
		}else{
			$this->responseArray['status'] = 'error';
			$this->responseArray['message'] = "Enquiry could not be deleted.";
		}
		return $this->responseArray;
	}
	
	/**
	 * @author Ashish Shah (ashish414@gmail.com)
	 *
	 * Description : This function is used to delete all inquiries when deal is deleted
	 *
	 * @param 1 : $dealId
	 *
	 * @return : null
	 *
	 * @throws Exception: Null
	 *
	 **/
	public function deleteAllInquiriesByDealId($dealId){
		$inquiries = $this->getInquiriesByDealId($dealId);
		if(!empty($inquiries) && count($inquiries) > 0){
			foreach ($inquiries as $inquiry){
				$this->_em->remove($inquiry);
			}
			$this->_em->flush();
			return true;
		}else{
			return false;
		}
	}
}