<?php
namespace Karolak\EcoEngine\Domain\Sale\ValueObject;

use Karolak\EcoEngine\Domain\Common\ValueObject\HomeAddress;
use Karolak\EcoEngine\Domain\Common\ValueObject\ValueObjectInterface;

/**
 * Class Invoice
 * @package Karolak\EcoEngine\Domain\Sale\ValueObject
 */
class Invoice implements ValueObjectInterface
{
    /** @var string */
    private $company;

    /** @var string */
    private $nip;

    /** @var HomeAddress */
    private $address;

    /**
     * Invoice constructor.
     * @param string $company
     * @param string $nip
     * @param HomeAddress $address
     */
    public function __construct(string $company, string $nip, HomeAddress $address)
    {
        $this->company = $company;
        $this->nip = $nip;
        $this->address = $address;
    }

    /**
     * @return string
     */
    public function getCompany(): string
    {
        return $this->company;
    }

    /**
     * @return string
     */
    public function getNip(): string
    {
        return $this->nip;
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
        return $object instanceof Invoice
            && $this->company === $object->getCompany()
            && $this->nip === $object->getNip()
            && $this->address->equals($object->getAddress());
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function __toString(): string
    {
        return $this->nip;
    }
}