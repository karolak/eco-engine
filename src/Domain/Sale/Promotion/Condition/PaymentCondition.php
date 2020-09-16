<?php
namespace Karolak\EcoEngine\Domain\Sale\Promotion\Condition;

use Karolak\EcoEngine\Domain\Sale\Order\Entity\Order;

/**
 * Class PaymentCondition
 * @package Karolak\EcoEngine\Domain\Sale\Promotion\Condition
 */
class PaymentCondition implements ConditionInterface
{
    /** @var string */
    private $code;

    /**
     * PaymentCondition constructor.
     * @param string $code
     */
    public function __construct(string $code)
    {
        $this->code = $code;
    }

    /**
     * @param Order $order
     * @return bool
     */
    public function isSatisfiedBy(Order $order): bool
    {
        $payment = $order->getPayment();
        if (null === $payment) {
            return false;
        }

        return $payment->getCode() === $this->code;
    }
}