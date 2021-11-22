<?php

namespace App\Controller;

use App\Entity\TodoTask;
use App\Entity\User;
use App\Repository\TodoTaskRepository;
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
                    return new JsonResponse(['success' => true,'id' => $usersRepo->getId(), 'username' => $usersRepo->getUsername(),'session_duration' => 600], Response::HTTP_OK);
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

    /**
     * @Route("/api/user/add", methods={"POST"} ,name="useradd")
     */
    public function userAdd(Request $request) : JsonResponse
    {
        try{
            $content = json_decode($request->getContent());
            if( isset($content->username) && isset($content->password) )
            {
                $username = $content->username;
                $password = $content->password;

                $usersRepo = $this->usersRepo->findOneBy(['username' => $username]);

                if(!$usersRepo)
                {
                    $entityManager = $this->getDoctrine()->getManager();

                    $user = new User();
                    $user->setUsername($username);
                    $user->setPassword(password_hash($password,PASSWORD_BCRYPT,['cost'=>13]));

                    $entityManager->persist($user);
                    $entityManager->flush();
                    return new JsonResponse(['success'], Response::HTTP_OK);
                }
                else{
                    return new JsonResponse("Already exist!", Response::HTTP_BAD_REQUEST);
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

    /**
     * @Route("/api/todo/task/add", methods={"POST"} ,name="taskadd")
     */
    public function taskAdd(Request $request) : JsonResponse
    {
        try {
            $content = json_decode($request->getContent());

            if( isset($content->task_subject) && isset($content->task_detail) && isset($content->user_id) )
            {
                if(!empty($content->task_subject) && !empty($content->task_detail))
                {

                    $entityManager = $this->getDoctrine()->getManager();

                    $userId = (int) $content->user_id;
                    $taskSubject = $content->task_subject;
                    $taskDetail = $content->task_detail;

                    $todoTask = new TodoTask();
                    $todoTask->setTaskSubject($taskSubject);
                    $todoTask->setTaskDetail($taskDetail);
                    $todoTask->setUserId($userId);
                    $todoTask->setCreatedAt(\DateTime::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s')));

                    $entityManager->persist($todoTask);
                    $entityManager->flush();

                    return new JsonResponse(['success'], Response::HTTP_OK);
                }
                else{
                    return new JsonResponse("Empty Field", Response::HTTP_BAD_REQUEST);
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

    /**
     * @Route("/api/todo/task", methods={"GET"} ,name="tasklist")
     */
    public function taskList(Request $request) : JsonResponse
    {
        try {
            if($request->query->has('user'))
            {
                $entityManager = $this->getDoctrine()->getManager();

                $userId = (int) $request->query->get('user');
                $todoTaskList = $entityManager->getRepository(TodoTask::class)->findBy(['userId' => $userId]);
                $data = [];
                foreach($todoTaskList as $key => $row)
                {
                    $data[] =[
                        'id'            => $row->getId(),
                        'user_id'       => $row->getUserId(),
                        'task_subject'  => $row->getTaskSubject(),
                        'task_detail'   => $row->getTaskDetail(),
                        'created_at'    => $row->getCreatedAt()
                    ];
                }
                return new JsonResponse($data, Response::HTTP_OK);
            }
            else{
                return new JsonResponse("error", Response::HTTP_BAD_REQUEST);
            }
        }
        catch (\Exception $e) {
            return new JsonResponse("error", Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @Route("/api/todo/task/{id}", methods={"DELETE"} ,name="taskdelete")
     */
    public function deleteTask(Request $request, TodoTask $todoTask) : JsonResponse
    {
        try {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($todoTask);
            $entityManager->flush();
            return new JsonResponse(['success'], Response::HTTP_OK);
        }
        catch (\Exception $e) {
            return new JsonResponse("error", Response::HTTP_BAD_REQUEST);
        }
    }

}