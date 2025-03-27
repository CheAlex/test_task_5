<?php

declare(strict_types=1);

namespace App\Exception\Wallet;

use Symfony\Component\HttpFoundation\Response;

class WalletAlreadyExists extends \DomainException
{
    public static function create(\Throwable $previous = null): self
    {
        return new self('Wallet already exists.', Response::HTTP_BAD_REQUEST, $previous);
    }
}
