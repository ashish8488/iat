<?php

namespace IATBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Inquiry
 *
 * @ORM\Table(name="tbl_inquiry")
 * @ORM\Entity(repositoryClass="IATBundle\Entity\InquiryRepository")
 */
class Inquiry
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
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="Deal")
     * @ORM\JoinColumn(name="dealId", referencedColumnName="id")
     */
    private $dealId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="departureDate", type="datetime")
     */
    private $departureDate;

    /**
     * @var string
     *
     * @ORM\Column(name="cust1FName", type="string", length=255)
     */
    private $cust1FName;

    /**
     * @var string
     *
     * @ORM\Column(name="cust1LName", type="string", length=255)
     */
    private $cust1LName;

    /**
     * @var string
     *
     * @ORM\Column(name="cust1dob", type="datetime")
     */
    private $cust1dob;

    /**
     * @var string
     *
     * @ORM\Column(name="cust2FName", type="string", length=255)
     */
    private $cust2FName;

    /**
     * @var string
     *
     * @ORM\Column(name="cust2LName", type="string", length=255)
     */
    private $cust2LName;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="cust2dob", type="datetime")
     */
    private $cust2dob;

    /**
     * @var integer
     *
     * @ORM\Column(name="phone", type="integer")
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="prefferedCallTime", type="string", length=255)
     */
    private $prefferedCallTime;

    /**
     * @var string
     *
     * @ORM\Column(name="address1", type="string", length=255)
     */
    private $address1;

    /**
     * @var string
     *
     * @ORM\Column(name="address2", type="string", length=255)
     */
    private $address2;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="state", type="string", length=255)
     */
    private $state;

    /**
     * @var integer
     *
     * @ORM\Column(name="zipcode", type="integer")
     */
    private $zipcode;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=255)
     */
    private $country;
    
    /**
     * @var string
     *
     * @ORM\Column(name="status", type="integer")
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="cust1GroupOnCode", type="string", length=255)
     */
    private $cust1GroupOnCode;

    /**
     * @var string
     *
     * @ORM\Column(name="cust2GroupOnCode", type="string", length=255)
     */
    private $cust2GroupOnCode;
    
    /**
     * @var string
     *
     * @ORM\Column(name="cust1GroupOnCodeFile", type="string", nullable=true)
     */
    private $cust1GroupOnCodeFile;
    
    /**
     * @var string
     *
     * @ORM\Column(name="cust2GroupOnCodeFile", type="string", nullable=true)
     */
    private $cust2GroupOnCodeFile;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
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
     * Set departureDate
     *
     * @param \DateTime $departureDate
     *
     * @return Inquiry
     */
    public function setDepartureDate($departureDate)
    {
        $this->departureDate = $departureDate;

        return $this;
    }

    /**
     * Get departureDate
     *
     * @return \DateTime
     */
    public function getDepartureDate()
    {
        return $this->departureDate;
    }

    /**
     * Set cust1FName
     *
     * @param string $cust1FName
     *
     * @return Inquiry
     */
    public function setCust1FName($cust1FName)
    {
        $this->cust1FName = $cust1FName;

        return $this;
    }

    /**
     * Get cust1FName
     *
     * @return string
     */
    public function getCust1FName()
    {
        return $this->cust1FName;
    }

    /**
     * Set cust1LName
     *
     * @param string $cust1LName
     *
     * @return Inquiry
     */
    public function setCust1LName($cust1LName)
    {
        $this->cust1LName = $cust1LName;

        return $this;
    }

    /**
     * Get cust1LName
     *
     * @return string
     */
    public function getCust1LName()
    {
        return $this->cust1LName;
    }

    /**
     * Set cust1dob
     *
     * @param string $cust1dob
     *
     * @return Inquiry
     */
    public function setCust1dob($cust1dob)
    {
        $this->cust1dob = $cust1dob;

        return $this;
    }

    /**
     * Get cust1dob
     *
     * @return string
     */
    public function getCust1dob()
    {
        return $this->cust1dob;
    }

    /**
     * Set cust2FName
     *
     * @param string $cust2FName
     *
     * @return Inquiry
     */
    public function setCust2FName($cust2FName)
    {
        $this->cust2FName = $cust2FName;

        return $this;
    }

    /**
     * Get cust2FName
     *
     * @return string
     */
    public function getCust2FName()
    {
        return $this->cust2FName;
    }

    /**
     * Set cust2LName
     *
     * @param string $cust2LName
     *
     * @return Inquiry
     */
    public function setCust2LName($cust2LName)
    {
        $this->cust2LName = $cust2LName;

        return $this;
    }

    /**
     * Get cust2LName
     *
     * @return string
     */
    public function getCust2LName()
    {
        return $this->cust2LName;
    }

    /**
     * Set cust2dob
     *
     * @param \DateTime $cust2dob
     *
     * @return Inquiry
     */
    public function setCust2dob($cust2dob)
    {
        $this->cust2dob = $cust2dob;

        return $this;
    }

    /**
     * Get cust2dob
     *
     * @return \DateTime
     */
    public function getCust2dob()
    {
        return $this->cust2dob;
    }

    /**
     * Set phone
     *
     * @param integer $phone
     *
     * @return Inquiry
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return integer
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Inquiry
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set prefferedCallTime
     *
     * @param string $prefferedCallTime
     *
     * @return Inquiry
     */
    public function setPrefferedCallTime($prefferedCallTime)
    {
        $this->prefferedCallTime = $prefferedCallTime;

        return $this;
    }

    /**
     * Get prefferedCallTime
     *
     * @return string
     */
    public function getPrefferedCallTime()
    {
        return $this->prefferedCallTime;
    }

    /**
     * Set address1
     *
     * @param string $address1
     *
     * @return Inquiry
     */
    public function setAddress1($address1)
    {
        $this->address1 = $address1;

        return $this;
    }

    /**
     * Get address1
     *
     * @return string
     */
    public function getAddress1()
    {
        return $this->address1;
    }

    /**
     * Set address2
     *
     * @param string $address2
     *
     * @return Inquiry
     */
    public function setAddress2($address2)
    {
        $this->address2 = $address2;

        return $this;
    }

    /**
     * Get address2
     *
     * @return string
     */
    public function getAddress2()
    {
        return $this->address2;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return Inquiry
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set state
     *
     * @param string $state
     *
     * @return Inquiry
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set zipcode
     *
     * @param integer $zipcode
     *
     * @return Inquiry
     */
    public function setZipcode($zipcode)
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    /**
     * Get zipcode
     *
     * @return integer
     */
    public function getZipcode()
    {
        return $this->zipcode;
    }

    /**
     * Set country
     *
     * @param string $country
     *
     * @return Inquiry
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set cust1GroupOnCode
     *
     * @param string $cust1GroupOnCode
     *
     * @return Inquiry
     */
    public function setCust1GroupOnCode($cust1GroupOnCode)
    {
        $this->cust1GroupOnCode = $cust1GroupOnCode;

        return $this;
    }

    /**
     * Get cust1GroupOnCode
     *
     * @return string
     */
    public function getCust1GroupOnCode()
    {
        return $this->cust1GroupOnCode;
    }

    /**
     * Set cust2GroupOnCode
     *
     * @param string $cust2GroupOnCode
     *
     * @return Inquiry
     */
    public function setCust2GroupOnCode($cust2GroupOnCode)
    {
        $this->cust2GroupOnCode = $cust2GroupOnCode;

        return $this;
    }

    /**
     * Get cust2GroupOnCode
     *
     * @return string
     */
    public function getCust2GroupOnCode()
    {
        return $this->cust2GroupOnCode;
    }

    /**
     * Set dealId
     *
     * @param integer $dealId
     *
     * @return Inquiry
     */
    public function setDealId($dealId)
    {
        $this->dealId = $dealId;

        return $this;
    }

    /**
     * Get dealId
     *
     * @return integer
     */
    public function getDealId()
    {
        return $this->dealId;
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Inquiry
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set cust1GroupOnCodeFile
     *
     * @param string $cust1GroupOnCodeFile
     *
     * @return Inquiry
     */
    public function setCust1GroupOnCodeFile($cust1GroupOnCodeFile)
    {
        $this->cust1GroupOnCodeFile = $cust1GroupOnCodeFile;

        return $this;
    }

    /**
     * Get cust1GroupOnCodeFile
     *
     * @return string
     */
    public function getCust1GroupOnCodeFile()
    {
        return $this->cust1GroupOnCodeFile;
    }

    /**
     * Set cust2GroupOnCodeFile
     *
     * @param string $cust2GroupOnCodeFile
     *
     * @return Inquiry
     */
    public function setCust2GroupOnCodeFile($cust2GroupOnCodeFile)
    {
        $this->cust2GroupOnCodeFile = $cust2GroupOnCodeFile;

        return $this;
    }

    /**
     * Get cust2GroupOnCodeFile
     *
     * @return string
     */
    public function getCust2GroupOnCodeFile()
    {
        return $this->cust2GroupOnCodeFile;
    }
}
