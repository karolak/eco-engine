<?php
namespace Karolak\EcoEngine\Domain\Sale\ValueObject;

use Karolak\EcoEngine\Domain\Common\ValueObject\ValueObjectInterface;

/**
 * Class PromotionAction
 * @package Karolak\EcoEngine\Domain\Sale\ValueObject
 */
class PromotionAction implements ValueObjectInterface
{
    /** @var string */
    private $code;

    /** @var array */
    private $params;

    /**
     * PromotionAction constructor.
     * @param string $code
     * @param array $params
     */
    public function __construct(string $code, array $params = [])
    {
        $this->code = $code;
        $this->params = $params;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * @param ValueObjectInterface $object
     * @return bool
     */
    public function equals(ValueObjectInterface $object): bool
    {
        return $object instanceof PromotionAction
            && $this->code === $object->getCode()
            && $this->params === $object->getParams();
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