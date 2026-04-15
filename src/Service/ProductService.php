<?php

namespace App\Service;

use App\DTO\CreateProductInput;
use App\Entity\Product;
use App\Entity\User;
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

    public function createProduct(CreateProductInput $input, User $owner): Product
    {
        $product = new Product();
        $product->setName($input->name);
        $product->setPrice((string) $input->price);
        $product->setStock($input->stock);
        $product->setOwner($owner);

        $this->productRepository->save($product);

        return $product;
    }

    public function deleteProduct(int $id): bool
    {
        $product = $this->productRepository->find($id);
        if (!$product) {
            return false;
        }

        $this->productRepository->remove($product);

        return true;
    }
}
