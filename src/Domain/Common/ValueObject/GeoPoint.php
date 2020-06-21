<?php
namespace Karolak\EcoEngine\Domain\Common\ValueObject;

/**
 * Class GeoPoint
 * @package Karolak\EcoEngine\Domain\Common\ValueObject
 */
class GeoPoint implements ValueObjectInterface
{
    /** @var float */
    private $lat;

    /** @var float */
    private $lng;

    /**
     * GeoPoint constructor.
     * @param float $lat
     * @param float $lng
     */
    public function __construct(float $lat, float $lng)
    {
        $this->lat = $lat;
        $this->lng = $lng;
    }

    /**
     * @return float
     */
    public function getLat(): float
    {
        return $this->lat;
    }

    /**
     * @return float
     */
    public function getLng(): float
    {
        return $this->lng;
    }

    /**
     * @param ValueObjectInterface $object
     * @return bool
     */
    public function equals(ValueObjectInterface $object): bool
    {
        return $object instanceof GeoPoint
            && $this->lat === $object->getLat()
            && $this->lng === $object->getLng();
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function __toString(): string
    {
        return $this->lat . ' ' . $this->lng;
    }
}