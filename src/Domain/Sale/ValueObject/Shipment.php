<?php
namespace Karolak\EcoEngine\Domain\Sale\ValueObject;

use Karolak\EcoEngine\Domain\Common\ValueObject\AddressInterface;
use Karolak\EcoEngine\Domain\Common\ValueObject\ValueObjectInterface;

/**
 * Class Shipment
 * @package Karolak\EcoEngine\Domain\Sale\ValueObject
 */
class Shipment implements ValueObjectInterface
{
    /** @var string */
    private $code;

    /** @var AddressInterface */
    private $address;

    /**
     * Shipment constructor.
     * @param $code
     * @param AddressInterface $address
     */
    public function __construct($code, AddressInterface $address)
    {
        $this->code = $code;
        $this->address = $address;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return AddressInterface
     */
    public function getAddress(): AddressInterface
    {
        return $this->address;
    }

    /**
     * @param ValueObjectInterface $object
     * @return bool
     */
    public function equals(ValueObjectInterface $object): bool
    {
        return $object instanceof Shipment
            && $this->code === $object->getCode()
            && $this->address->equals($object->getAddress());
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function __toString(): string
    {
        return $this->code;
    }
}