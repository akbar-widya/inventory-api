<?php

namespace App\Service;

use App\Repository\ProductRepository;

class ProductService
{
    public function __construct(private ProductRepository $productRepository) {}

    public function findAll(): array
    {
        return $this->productRepository->findAll();
    }

    public function findById(int $id): ?object
    {
        return $this->productRepository->find($id);
    }
}
