<?php
namespace Karolak\EcoEngine\Domain\Sale\Order\ValueObject;

use Karolak\EcoEngine\Domain\Common\ValueObject\Email;
use Karolak\EcoEngine\Domain\Common\ValueObject\HomeAddress;
use Karolak\EcoEngine\Domain\Common\ValueObject\ValueObjectInterface;

/**
 * Class Customer
 * @package Karolak\EcoEngine\Domain\Sale\Order\ValueObject
 */
class Customer implements ValueObjectInterface
{
    /** @var string */
    private $firstName;

    /** @var string */
    private $lastName;

    /** @var string */
    private $phone;

    /** @var Email */
    private $email;

    /** @var HomeAddress */
    private $address;

    /**
     * Customer constructor.
     * @param string $firstName
     * @param string $lastName
     * @param string $phone
     * @param Email $email
     * @param HomeAddress $address
     */
    public function __construct(string $firstName, string $lastName, string $phone, Email $email, HomeAddress $address)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->phone = $phone;
        $this->email = $email;
        $this->address = $address;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * @return Email
     */
    public function getEmail(): Email
    {
        return $this->email;
    }

    /**
     * @return HomeAddress
     */
    public function getAddress(): HomeAddress
    {
        return $this->address;
    }

    /**
     * @param ValueObjectInterface $object
     * @return bool
     */
    public function equals(ValueObjectInterface $object): bool
    {
        return $object instanceof Customer
            && $this->firstName === $object->getFirstName()
            && $this->lastName === $object->getLastName()
            && $this->phone === $object->getPhone()
            && $this->email->equals($object->getEmail())
            && $this->address->equals($object->getAddress());
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function __toString(): string
    {
        return $this->firstName . ' ' . $this->lastName;
    }
}