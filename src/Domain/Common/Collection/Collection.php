<?php
namespace Karolak\EcoEngine\Domain\Common\Collection;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

/**
 * Class Collection.
 * @package Karolak\EcoEngine\Domain\Common\Collection
 */
abstract class Collection implements IteratorAggregate, Countable
{
    /** @var array */
    protected $items;

    /**
     * Retrieve an external iterator
     * @link https://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     * @since 5.0.0
     */
    public function getIterator()
    {
        return new ArrayIterator($this->items);
    }

    /**
     * Count elements of an object
     * @link https://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     * @since 5.1.0
     */
    public function count()
    {
        return count($this->items);
    }

    /**
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->items);
    }

    /**
     * @return void
     */
    public function clear(): void
    {
        $this->items = [];
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->items;
    }
}