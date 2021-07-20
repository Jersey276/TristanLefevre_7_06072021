<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Form\UserType;
use Doctrine\DBAL\ForwardCompatibility\Result;
use JMS\Serializer\SerializationContext;
use Symfony\Component\Form\FormFactoryInterface;

class UserController extends AbstractController
{

    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @Rest\Get("/users", name="user_list")
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
     * @Rest\Get("/users/{id}", name="user_detail")
     */
    public function detailUser(User $user): Response
    {
        $data = $this->serializer->serialize($user, 'json', SerializationContext::create()->setGroups(array('detail')));
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');
        return $response;    
    }

    
    /**
     * @Rest\Post("/users", name="user_add")
     * @Rest\View(StatusCode = 201)
     */
    public function addUser(Request $request, FormFactoryInterface $factory): Response
    {
        /** @var Customer $customer */
        $customer = $this->getUser();
        $doctrine = $this->getDoctrine()->getManager();
        $data = $this->serializer->deserialize($request->getContent(),'array', 'json');
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
        $result = $this->serializer->serialize((string) $form->getErrors(true,false), 'json');
        return new Response($result, Response::HTTP_BAD_REQUEST);
    }

    
    /**
     * @Rest\Delete("/users/{id}", name="user_delete")
     */
    public function deleteUser(User $user): Response
    {
        $doctrine = $this->getDoctrine()->getManager();
        $doctrine->remove($user);
        $doctrine->flush();
        return new Response(null, Response::HTTP_OK);
    }
}
