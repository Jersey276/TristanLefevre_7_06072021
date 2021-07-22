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

class ProductController extends AbstractController
{
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @Rest\Get("/products", name="product_list")
     * @OA\Response(
     *     response=200,
     *     description="Return list of all products",
     *     @Model(type=Product::class, groups={"list"})
     * )
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
     * @Rest\Get("/products/{id}", name="product_detail")
     *      * @OA\Response(
     *     response=200,
     *     description="Return detail for a product",
     *     @Model(type=Product::class, groups={"detail"})
     * )
     * @OA\Tag(name="products")
     * @Security(name="Bearer")
     */
    public function productDetail(Product $product) : Response
    {
        $data = $this->serializer->serialize($product, 'json',SerializationContext::create()->setGroups(array('detail')));
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}
