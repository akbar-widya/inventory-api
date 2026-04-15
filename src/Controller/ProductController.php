<?php

namespace App\Controller;

use App\Service\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class ProductController extends AbstractController
{
    public function __construct(private ProductService $productService) {}

    #[Route('/products', name: 'products_index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $products = $this->productService->findAll();

        $data = array_map(fn($p) => [
            'id'         => $p->getId(),
            'name'       => $p->getName(),
            'price'      => $p->getPrice(),
            'stock'      => $p->getStock(),
            'createdAt'  => $p->getCreatedAt()->format('Y-m-d H:i:s'),
        ], $products);

        return $this->json(['products' => $data]);
    }

    #[Route('/products/{id}', name: 'products_show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        $product = $this->productService->findById($id);

        if (!$product) {
            return $this->json(['error' => 'Product not found'], 404);
        }

        return $this->json([
            'id'        => $product->getId(),
            'name'      => $product->getName(),
            'price'     => $product->getPrice(),
            'stock'     => $product->getStock(),
            'createdAt' => $product->getCreatedAt()->format('Y-m-d H:i:s'),
        ]);
    }
}
