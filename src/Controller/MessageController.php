<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MessageController extends AbstractController
{
    /**
     * @Route("/message/{id}", name="message")
     */
    public function index(UserRepository $data, int $id)
    {
        $user = $data->find($id);
        return $this->render('message/liste_messages.html.twig', ['user' => $user]);
    }
}
