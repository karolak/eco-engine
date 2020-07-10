<?php
namespace Karolak\EcoEngine\Domain\Sale\Promotion\Collection;

use Karolak\EcoEngine\Domain\Common\Collection\Collection;
use Karolak\EcoEngine\Domain\Sale\Promotion\Action\ActionInterface;

/**
 * Class ActionsCollection
 * @package Karolak\EcoEngine\Domain\Sale\Promotion\Collection
 */
class ActionsCollection extends Collection
{
    /**
     * ActionsCollection constructor.
     * @param ActionInterface ...$items
     */
    public function __construct(ActionInterface ...$items)
    {
        $this->items = $items;
    }

    /**
     * @param ActionInterface $item
     */
    public function add(ActionInterface $item): void
    {
        $this->items[] = $item;
    }
}