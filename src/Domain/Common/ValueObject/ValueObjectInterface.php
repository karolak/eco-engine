<?php
namespace Karolak\EcoEngine\Domain\Common\ValueObject;

/**
 * Interface ValueObjectInterface
 * @package Karolak\EcoEngine\Domain\Common\ValueObject
 */
interface ValueObjectInterface
{
    /**
     * @param ValueObjectInterface $object
     * @return bool
     */
    public function equals(ValueObjectInterface $object): bool;

    /**
     * @return string
     */
    public function __toString(): string;
}