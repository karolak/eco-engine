<?php
namespace Karolak\EcoEngine\Domain\Common\ValueObject;

/**
 * Class HomeAddress
 * @package Karolak\EcoEngine\Domain\Common\ValueObject
 */
class HomeAddress implements AddressInterface
{
    /** @var Country */
    private $country;

    /** @var string */
    private $region;

    /** @var string */
    private $city;

    /** @var string */
    private $postCode;

    /** @var string */
    private $street;

    /** @var string */
    private $houseNumber;

    /** @var string */
    private $apartmentNumber;

    /**
     * Address constructor.
     * @param Country $country
     * @param string $region
     * @param string $city
     * @param string $postCode
     * @param string $street
     * @param string $houseNumber
     * @param string $apartmentNumber
     */
    public function __construct(
        Country $country,
        string $region,
        string $city,
        string $postCode,
        string $street,
        string $houseNumber,
        string $apartmentNumber
    )
    {
        $this->country = $country;
        $this->region = $region;
        $this->city = $city;
        $this->postCode = $postCode;
        $this->street = $street;
        $this->houseNumber = $houseNumber;
        $this->apartmentNumber = $apartmentNumber;
    }

    /**
     * @return Country
     */
    public function getCountry(): Country
    {
        return $this->country;
    }

    /**
     * @return string
     */
    public function getRegion(): string
    {
        return $this->region;
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @return string
     */
    public function getPostCode(): string
    {
        return $this->postCode;
    }

    /**
     * @return string
     */
    public function getStreet(): string
    {
        return $this->street;
    }

    /**
     * @return string
     */
    public function getHouseNumber(): string
    {
        return $this->houseNumber;
    }

    /**
     * @return string
     */
    public function getApartmentNumber(): string
    {
        return $this->apartmentNumber;
    }

    /**
     * @param ValueObjectInterface|HomeAddress $object
     * @return bool
     */
    public function equals(ValueObjectInterface $object): bool
    {
        return $object instanceof HomeAddress
            && $this->country->equals($object->getCountry())
            && $this->region === $object->getRegion()
            && $this->city === $object->getCity()
            && $this->postCode === $object->getPostCode()
            && $this->street === $object->getStreet()
            && $this->houseNumber === $object->getHouseNumber()
            && $this->apartmentNumber === $object->getApartmentNumber();
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function __toString(): string
    {
        return ((string) $this->country)
            . !empty($this->region) ? ' ' . $this->region : ''
            . !empty($this->postCode) ? ' ' . $this->postCode : ''
            . !empty($this->city) ? ' ' . $this->city : ''
            . !empty($this->street) ? ' ' . $this->street : ''
            . !empty($this->houseNumber) ? ' ' . $this->houseNumber : ''
            . !empty($this->apartmentNumber) ? '/' . $this->apartmentNumber : '';
    }
}