<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    private $usersRepo;
    private $passwordEncoder;

    public function __construct(UserRepository $usersRepo, UserPasswordHasherInterface $passwordEncoder)
    {
        $this->usersRepo = $usersRepo;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @Route("/api", name="api")
     */
    public function index(): Response
    {
        return $this->render('api/index.html.twig', [
            'controller_name' => 'ApiController',
        ]);
    }

    /**
     * @Route("/api/login", methods={"POST"} ,name="loginapi")
     */

    public function login(Request $request) : JsonResponse
    {
        try {
            $content = json_decode($request->getContent());

            if( isset($content->username) && isset($content->password) )
            {

                $username = $content->username;
                $password = $content->password;
                $usersRepo = $this->usersRepo->findOneBy(['username' => $username]);

                $passwordHash = $usersRepo->getPassword();

                if( password_verify($password,$passwordHash) )
                {
                    return new JsonResponse(['id' => $usersRepo->getId(), 'username' => $usersRepo->getUsername(),'session_duration' => 600], Response::HTTP_OK);
                }
                else{
                    return new JsonResponse("error", Response::HTTP_BAD_REQUEST);
                }
            }
            else{
                return new JsonResponse("error", Response::HTTP_BAD_REQUEST);
            }
        }
        catch (\Exception $e) {
            return new JsonResponse("error", Response::HTTP_BAD_REQUEST);
        }
    }
}