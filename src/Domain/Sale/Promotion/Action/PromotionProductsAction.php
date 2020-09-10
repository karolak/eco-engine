<?php
namespace Karolak\EcoEngine\Domain\Sale\Promotion\Action;

use Karolak\EcoEngine\Domain\Sale\Promotion\Collection\PromotionProductsCollection;
use Karolak\EcoEngine\Domain\Sale\Promotion\Exception\InvalidLimitException;
use Karolak\EcoEngine\Domain\Sale\Promotion\Exception\PromotionProductAlreadyAddedException;
use Karolak\EcoEngine\Domain\Sale\Promotion\ValueObject\PromotionProduct;

/**
 * Class PromotionProductsAction
 * @package Karolak\EcoEngine\Domain\Sale\Promotion\Action
 */
class PromotionProductsAction implements ActionInterface
{
    /** @var PromotionProductsCollection */
    private $products;

    /** @var int */
    private $limit;

    /**
     * PromotionProductsAction constructor.
     * @param array|PromotionProduct[] $products
     * @param int $limit
     * @throws InvalidLimitException
     * @throws PromotionProductAlreadyAddedException
     */
    public function __construct(array $products, int $limit = 1)
    {
        $this->products = new PromotionProductsCollection(...$products);
        if ($limit < 0) {
            throw new InvalidLimitException();
        }
        $this->limit = $limit;
    }

    /**
     * @return array|PromotionProduct[]
     */
    public function getProducts(): array
    {
        return $this->products->toArray();
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }
}