<?php
namespace Karolak\EcoEngine\Domain\Common\ValueObject;

/**
 * Interface AttributeInterface
 * @package Karolak\EcoEngine\Domain\Common\ValueObject
 */
interface AttributeInterface extends ValueObjectInterface
{
    /**
     * @return string
     */
    public function getName(): string;
}