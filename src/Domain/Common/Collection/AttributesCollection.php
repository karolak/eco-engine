<?php
namespace Karolak\EcoEngine\Domain\Common\Collection;

use Karolak\EcoEngine\Domain\Common\ValueObject\AttributeInterface;

/**
 * Class AttributesCollection
 * @package Karolak\EcoEngine\Domain\Common\Collection
 */
class AttributesCollection extends Collection
{
    /**
     * AttributesCollection constructor.
     * @param AttributeInterface ...$items
     */
    public function __construct(AttributeInterface ...$items)
    {
        $this->items = $items;
    }

    /**
     * @param AttributeInterface $item
     */
    public function add(AttributeInterface $item): void
    {
        $this->items[] = $item;
    }

    /**
     * @param string $name
     * @return AttributeInterface|null
     */
    public function getByName(string $name): ?AttributeInterface
    {
        $result = null;
        foreach ($this->items as $item) {
            if ($item->getName() === $name) {
                $result = $item;
                break;
            }
        }

        return $result;
    }
}