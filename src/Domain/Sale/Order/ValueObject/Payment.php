<?php
namespace Karolak\EcoEngine\Domain\Sale\Order\ValueObject;

use Karolak\EcoEngine\Domain\Common\ValueObject\ValueObjectInterface;

/**
 * Class Payment
 * @package Karolak\EcoEngine\Domain\Sale\Order\ValueObject
 */
class Payment implements ValueObjectInterface
{
    /** @var string */
    private $code;

    /**
     * Payment constructor.
     * @param string $code
     */
    public function __construct(string $code)
    {
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param ValueObjectInterface $object
     * @return bool
     */
    public function equals(ValueObjectInterface $object): bool
    {
        return $object instanceof Payment
            && $this->code === $object->getCode();
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