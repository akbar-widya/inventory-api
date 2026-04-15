<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class CreateProductInput
{
    #[Assert\NotBlank]
    public string $name = '';

    #[Assert\NotBlank]
    #[Assert\Positive]
    public float $price = 0.0;

    #[Assert\NotBlank]
    #[Assert\PositiveOrZero]
    public int $stock = 0;
}
