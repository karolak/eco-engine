<?php
namespace Karolak\EcoEngine\Domain\Sale\Order\ValueObject;

use Karolak\EcoEngine\Domain\Common\ValueObject\AddressInterface;
use Karolak\EcoEngine\Domain\Common\ValueObject\ValueObjectInterface;
use Karolak\EcoEngine\Domain\Sale\Order\Exception\InvalidPriceValueException;

/**
 * Class Shipment
 * @package Karolak\EcoEngine\Domain\Sale\Order\ValueObject
 */
class Shipment implements ValueObjectInterface
{
    /** @var string */
    private $code;

    /** @var int */
    private $price;

    /** @var AddressInterface */
    private $address;

    /**
     * Shipment constructor.
     * @param string $code
     * @param int $price
     * @param AddressInterface $address
     * @throws InvalidPriceValueException
     */
    public function __construct(string $code, int $price, AddressInterface $address)
    {
        if ($price < 0) {
            throw new InvalidPriceValueException();
        }
        $this->code = $code;
        $this->price = $price;
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
     * @return int
     */
    public function getPrice(): int
    {
        return $this->price;
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
            && $this->price === $object->getPrice()
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