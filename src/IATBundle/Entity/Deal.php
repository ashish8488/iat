<?php

namespace IATBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Deal
 *
 * @ORM\Table(name="tbl_deal")
 * @ORM\Entity(repositoryClass="IATBundle\Entity\DealRepository")
 */
class Deal
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * 
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="userId", referencedColumnName="id")
     */
    private $userId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="startDate", type="datetime", nullable=false)
     */
    private $startDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="endDate", type="datetime", nullable=false)
     */
    private $endDate;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float", nullable=false)
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column(name="departureAirport", type="string", length=255, nullable=false)
     */
    private $departureAirport;
    
    /**
     * @var string
     *
     * @ORM\Column(name="destination", type="string", length=255, nullable=true)
     */
    private $destination;
    
    /**
     * @var string
     *
     * @ORM\Column(name="dealTitle", type="string", length=255, nullable=true)
     */
    private $dealTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="hotelName", type="string", length=255, nullable=true)
     */
    private $hotelName;

    /**
     * @var integer
     *
     * @ORM\Column(name="noOfNights", type="integer")
     */
    private $noOfNights;
    
    /**
     * @var string
     *
     * @ORM\Column(name="boardBasis", type="string", length=255, nullable=true)
     */
    private $boardBasis;
    
    /**
     * @var string
     *
     * @ORM\Column(name="extras", type="string", length=255, nullable=true)
     */
    private $extras;
    
    /**
     * @var string
     *
     * @ORM\Column(name="dealLink", type="string", length=255, nullable=false)
     */
    private $dealLink;

    /**
     * @var string
     *
     * @ORM\Column(name="departureMonths", type="string", length=255, nullable=false)
     */
    private $departureMonths;
    
    /**
     * @var string
     *
     * @ORM\Column(name="status", type="integer")
     */
    private $status;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime", nullable=false)
     */
    private $createdAt;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
    
    
    /**
     * Set startDate
     *
     * @param \DateTime $startDate
     *
     * @return Deal
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set endDate
     *
     * @param \DateTime $endDate
     *
     * @return Deal
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get endDate
     *
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set price
     *
     * @param float $price
     *
     * @return Deal
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set departureAirport
     *
     * @param string $departureAirport
     *
     * @return Deal
     */
    public function setDepartureAirport($departureAirport)
    {
        $this->departureAirport = $departureAirport;

        return $this;
    }

    /**
     * Get departureAirport
     *
     * @return string
     */
    public function getDepartureAirport()
    {
        return $this->departureAirport;
    }

    /**
     * Set destination
     *
     * @param string $destination
     *
     * @return Deal
     */
    public function setDestination($destination)
    {
        $this->destination = $destination;

        return $this;
    }

    /**
     * Get destination
     *
     * @return string
     */
    public function getDestination()
    {
        return $this->destination;
    }

    /**
     * Set noOfNights
     *
     * @param integer $noOfNights
     *
     * @return Deal
     */
    public function setNoOfNights($noOfNights)
    {
        $this->noOfNights = $noOfNights;

        return $this;
    }

    /**
     * Get noOfNights
     *
     * @return integer
     */
    public function getNoOfNights()
    {
        return $this->noOfNights;
    }

    /**
     * Set departureMonths
     *
     * @param string $departureMonths
     *
     * @return Deal
     */
    public function setDepartureMonths($departureMonths)
    {
        $this->departureMonths = $departureMonths;

        return $this;
    }

    /**
     * Get departureMonths
     *
     * @return string
     */
    public function getDepartureMonths()
    {
        return $this->departureMonths;
    }
    
    /**
     * Set status
     *
     * @param integer $status
     *
     * @return Inquiry
     */
    public function setStatus($status)
    {
    	$this->status = $status;
    
    	return $this;
    }
    
    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
    	return $this->status;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     *
     * @return Deal
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set hotelName
     *
     * @param string $hotelName
     *
     * @return Deal
     */
    public function setHotelName($hotelName)
    {
        $this->hotelName = $hotelName;

        return $this;
    }

    /**
     * Get hotelName
     *
     * @return string
     */
    public function getHotelName()
    {
        return $this->hotelName;
    }

    /**
     * Set boardBasis
     *
     * @param string $boardBasis
     *
     * @return Deal
     */
    public function setBoardBasis($boardBasis)
    {
        $this->boardBasis = $boardBasis;

        return $this;
    }

    /**
     * Get boardBasis
     *
     * @return string
     */
    public function getBoardBasis()
    {
        return $this->boardBasis;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Deal
     */
    public function setcreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getcreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set dealLink
     *
     * @param string $dealLink
     *
     * @return Deal
     */
    public function setDealLink($dealLink)
    {
        $this->dealLink = $dealLink;

        return $this;
    }

    /**
     * Get dealLink
     *
     * @return string
     */
    public function getDealLink()
    {
        return $this->dealLink;
    }

    /**
     * Set dealTitle
     *
     * @param string $dealTitle
     *
     * @return Deal
     */
    public function setDealTitle($dealTitle)
    {
        $this->dealTitle = $dealTitle;

        return $this;
    }

    /**
     * Get dealTitle
     *
     * @return string
     */
    public function getDealTitle()
    {
        return $this->dealTitle;
    }

    /**
     * Set extras
     *
     * @param string $extras
     *
     * @return Deal
     */
    public function setExtras($extras)
    {
        $this->extras = $extras;

        return $this;
    }

    /**
     * Get extras
     *
     * @return string
     */
    public function getExtras()
    {
        return $this->extras;
    }
}
