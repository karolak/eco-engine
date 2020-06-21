<?php
namespace Karolak\EcoEngine\Domain\Common\ValueObject;

/**
 * Class PickupPointAddress
 * @package Karolak\EcoEngine\Domain\Common\ValueObject
 */
class PickupPointAddress implements AddressInterface
{
    /** @var string */
    private $code;

    /** @var string */
    private $description;

    /** @var GeoPoint|null */
    private $geoPoint;

    /**
     * PickupPointAddress constructor.
     * @param string $code
     * @param string $description
     * @param GeoPoint|null $geoPoint
     */
    public function __construct(string $code, string $description = '', GeoPoint $geoPoint = null)
    {
        $this->code = $code;
        $this->description = $description;
        $this->geoPoint = $geoPoint;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return GeoPoint|null
     */
    public function getGeoPoint(): ?GeoPoint
    {
        return $this->geoPoint;
    }

    /**
     * @param ValueObjectInterface $object
     * @return bool
     */
    public function equals(ValueObjectInterface $object): bool
    {
        return $object instanceof PickupPointAddress
            && $this->code === $object->getCode()
            && $this->description === $object->getDescription()
            && (
                $this->geoPoint === null && $object->getGeoPoint() === null
                || $this->geoPoint !== null && $object->getGeoPoint() !== null && $this->geoPoint->equals($object->getGeoPoint())
            );
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