<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\Serializer\SerializerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Request;
use App\Form\UserType;
use App\Security\Voter\CustomerVoter;
use JMS\Serializer\SerializationContext;
use Symfony\Component\Form\FormFactoryInterface;
use JMS\Serializer\Annotation as Serializer;

/**
 * Controller for all element about User entity
 * @author Tristan
 * @version 1
 */
class UserController extends AbstractController
{
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * List all user customer (related to authentified customer)
     * @return Response JsonResponse
     * @Rest\Get("/users", name="user_list")
     * @OA\Response(
     *     response=200,
     *     description="Return list of all users of connected customer",
     *     @Model(type=User::class, groups={"list"})
     * )
     * @OA\Tag(name="user")
     * @Security(name="Bearer")
     * @Serializer\Since("1.0")
     */
    public function listUsers(): Response
    {
        /** @var Customer $customer */
        $customer = $this->getUser();
        $data = $this->serializer->serialize(
            $customer->getUsers(),
            'json',
            SerializationContext::create()->setGroups(array('list'))
        );
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }


    /**
     * Display detail about user (related to authentified customer)
     * @param User $user
     * @return Response Json Response
     * @Rest\Get("/users/{id}", name="user_detail")
     * @OA\Response(
     *     response=200,
     *     description="Return detail of user",
     *     @Model(type=User::class, groups={"detail"})
     * )
     * @OA\Tag(name="user")
     * @Security(name="Bearer")
     * @Serializer\Since("1.0")
     */
    public function detailUser(User $user): Response
    {
        $this->denyAccessUnlessGranted(CustomerVoter::CUST_ACCESS, $user, 'customer not related to user');

        $data = $this->serializer->serialize(
            $user,
            'json',
            SerializationContext::create()->setGroups(array('detail'))
        );
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    
    /**
     * Create a new user (related to authentified customer)
     * @param Request $request
     * @param  FormFactoryInterface $factory
     * @return Response Json Response
     * @Rest\Post("/users", name="user_add")
     * @Rest\View(StatusCode = 201)
     * @OA\Response(
     *     response=201,
     *     description="Create a new user on customer concerned by jwt token",
     *     @Model(type=User::class, groups={"list"})
     * )
     *     @OA\RequestBody(
     *         description="Input data format",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="name",
     *                     description="The name of new user",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="address",
     *                     description="The address of new user",
     *                     type="string"
     *                 )
     *             )
     *         )
     *     )
     * )
     * @OA\Tag(name="user")
     * @Security(name="Bearer")
     * @Serializer\Since("1.0")
     */
    public function addUser(Request $request, FormFactoryInterface $factory): Response
    {
        /** @var Customer $customer */
        $customer = $this->getUser();
        $doctrine = $this->getDoctrine()->getManager();
        /** @var array $data */
        $data = $this->serializer->deserialize(
            $request->getContent(),
            'array',
            'json'
        );
        $user = new User();
        $form = $factory->create(UserType::class, $user, ['csrf_protection' => false]);
        $form->submit($data);
 
        if ($form->isValid()) {
            $user->setCustomer($customer);
            $doctrine->persist($user);
            $doctrine->flush();
            $result = $this->serializer->serialize($data, 'json');
            return new Response($result, Response::HTTP_CREATED);
        }
        $result = $this->serializer->serialize(
            (string) $form->getErrors(true, false),
            'json'
        );
        return new Response($result, Response::HTTP_BAD_REQUEST);
    }

    
    /**
     * delete one of user (related to authentified customer)
     * @param User $user concerned user
     * @return Response Json response
     * @Rest\Delete("/users/{id}", name="user_delete")
     * @OA\Response(
     *     response=200,
     *     description="remove an user"
     * )
     * @OA\Tag(name="user")
     * @Security(name="Bearer")
     * @Serializer\Since("1.0")
     */
    public function deleteUser(User $user): Response
    {
        $this->denyAccessUnlessGranted(CustomerVoter::CUST_ACCESS, $user, 'customer not related to user');
        $doctrine = $this->getDoctrine()->getManager();
        $doctrine->remove($user);
        $doctrine->flush();
        return new Response(null, Response::HTTP_OK);
    }
}
