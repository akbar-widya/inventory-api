<?php

namespace App\Controller;

use App\DTO\CreateProductInput;
use App\Service\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProductController extends AbstractController
{
    public function __construct(
        private ProductService $productService,
        private ValidatorInterface $validator
    ) {}

    #[Route('/products', name: 'products_index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $products = $this->productService->findAll();

        $data = array_map(fn($p) => [
            'id'        => $p->getId(),
            'name'      => $p->getName(),
            'price'     => $p->getPrice(),
            'stock'     => $p->getStock(),
            'createdAt' => $p->getCreatedAt()->format('Y-m-d H:i:s'),
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

    #[Route('/products', name: 'products_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $body = json_decode($request->getContent(), true);

        $input = new CreateProductInput();
        $input->name  = $body['name'] ?? '';
        $input->price = $body['price'] ?? 0.0;
        $input->stock = $body['stock'] ?? 0;

        $errors = $this->validator->validate($input);
        if (count($errors) > 0) {
            $messages = [];
            foreach ($errors as $error) {
                $messages[$error->getPropertyPath()] = $error->getMessage();
            }
            return $this->json(['errors' => $messages], 422);
        }

        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        $product = $this->productService->createProduct($input, $user);

        return $this->json([
            'id'        => $product->getId(),
            'name'      => $product->getName(),
            'price'     => $product->getPrice(),
            'stock'     => $product->getStock(),
            'createdAt' => $product->getCreatedAt()->format('Y-m-d H:i:s'),
        ], 201);
    }

    #[Route('/products/{id}', name: 'products_delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $deleted = $this->productService->deleteProduct($id);

        if (!$deleted) {
            return $this->json(['error' => 'Product not found'], 404);
        }

        return $this->json(['success' => true]);
    }
}
