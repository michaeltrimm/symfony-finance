<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Portfolio
 *
 * @ORM\Table(name="portfolio")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PortfolioRepository")
 */
class Portfolio
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="user_id", type="integer", nullable=true)
     */
    private $userId;

    /**
     * @var string
     *
     * @ORM\Column(name="symbol", type="string", length=255)
     */
    private $symbol;

    /**
     * @var string
     *
     * @ORM\Column(name="price_paid", type="decimal", precision=10, scale=2)
     */
    private $pricePaid;

    /**
     * @var int
     *
     * @ORM\Column(name="quantity", type="integer")
     */
    private $quantity;

    /**
     * @var string
     *
     * @ORM\Column(name="last_price", type="decimal", precision=10, scale=2)
     */
    private $lastPrice;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_price_updated_on", type="datetimetz")
     */
    private $lastPriceUpdatedOn;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     *
     * @return Portfolio
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set symbol
     *
     * @param string $symbol
     *
     * @return Portfolio
     */
    public function setSymbol($symbol)
    {
        $this->symbol = $symbol;

        return $this;
    }

    /**
     * Get symbol
     *
     * @return string
     */
    public function getSymbol()
    {
        return $this->symbol;
    }

    /**
     * Set pricePaid
     *
     * @param string $pricePaid
     *
     * @return Portfolio
     */
    public function setPricePaid($pricePaid)
    {
        $this->pricePaid = $pricePaid;

        return $this;
    }

    /**
     * Get pricePaid
     *
     * @return string
     */
    public function getPricePaid()
    {
        return $this->pricePaid;
    }

    /**
     * Set quantity
     *
     * @param integer $quantity
     *
     * @return Portfolio
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set lastPrice
     *
     * @param string $lastPrice
     *
     * @return Portfolio
     */
    public function setLastPrice($lastPrice)
    {
        $this->lastPrice = $lastPrice;

        return $this;
    }

    /**
     * Get lastPrice
     *
     * @return string
     */
    public function getLastPrice()
    {
        return $this->lastPrice;
    }

    /**
     * Set lastPriceUpdatedOn
     *
     * @param \DateTime $lastPriceUpdatedOn
     *
     * @return Portfolio
     */
    public function setLastPriceUpdatedOn($lastPriceUpdatedOn)
    {
        $this->lastPriceUpdatedOn = $lastPriceUpdatedOn;

        return $this;
    }

    /**
     * Get lastPriceUpdatedOn
     *
     * @return \DateTime
     */
    public function getLastPriceUpdatedOn()
    {
        return $this->lastPriceUpdatedOn;
    }
}

