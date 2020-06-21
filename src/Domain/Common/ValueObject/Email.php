<?php
namespace Karolak\EcoEngine\Domain\Common\ValueObject;

use Karolak\EcoEngine\Domain\Common\Exception\InvalidEmailException;

/**
 * Class Email
 * @package Karolak\EcoEngine\Domain\Common\ValueObject
 */
class Email implements ValueObjectInterface
{
    /** @var string */
    private $email;

    /**
     * Email constructor.
     * @param string $email
     * @throws InvalidEmailException
     */
    public function __construct(string $email)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            throw new InvalidEmailException();
        }

        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param ValueObjectInterface|Email $object
     * @return bool
     */
    public function equals(ValueObjectInterface $object): bool
    {
        return $object instanceof Email
            && $this->email === $object->getEmail();
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function __toString(): string
    {
        return $this->email;
    }
}