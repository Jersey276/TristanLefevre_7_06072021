<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use JMS\Serializer\Annotation as Serializer;

/**
 * Controller for all element for display Product entity
 * @author Tristan
 * @version 1
 */
class ProductController extends AbstractController
{
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * List all products
     * @param ProductRepository $productRepo
     * @return Response Json response
     * @Rest\Get("/products", name="product_list")
     * @OA\Response(
     *     response=200,
     *     description="Return list of all products",
     *     @Model(type=Product::class, groups={"list"})
     * )
     * @Serializer\Since("1.0")
     * @OA\Tag(name="products")
     * @Security(name="Bearer")
     */
    public function listProducts(ProductRepository $productRepo): Response
    {
        $products = $productRepo->findAll();
        $data = $this->serializer->serialize($products, 'json', SerializationContext::create()->setGroups(array('list')));
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * Display detail about product
     * @param Product $product
     * @return Response Json response
     * @Rest\Get("/products/{id}", name="product_detail")
     * @OA\Response(
     *     response=200,
     *     description="Return detail for a product",
     *     @Model(type=Product::class, groups={"detail"})
     * )
     * @Serializer\Since("1.0")
     * @OA\Tag(name="products")
     * @Security(name="Bearer")
     */
    public function productDetail(Product $product) : Response
    {
        $data = $this->serializer->serialize($product, 'json', SerializationContext::create()->setGroups(array('detail')));
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}
