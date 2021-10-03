<?php

namespace App\Controller;

use App\Entity\Groupe;
use App\Repository\GroupeRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Annotation\Route;

class GroupeController extends AbstractController
{
    /**
     * @Route("/groupe", name="groupe")
     */
    public function index(): Response
    {
        return $this->render('groupe/index.html.twig', [
            'controller_name' => 'GroupeController',
        ]);
    }

    /**
     * @Route("/groupe/ajout", name="groupe_ajout", methods={"POST","GET"})
    */
    public function ajoutGroupe(Request $request,UserRepository $data,EntityManagerInterface $entityManager)
    {
        $users = $data->findAll();
        $table = [];
        $pays = $request->request->get('pays');
        $groupe = new Groupe();
        foreach ($users as $e)
        {
            $table[] = $e;
        }
        if ($request->getMethod() == 'POST') 
        {
            foreach ($table as $t)
            {
                if ($t->getPays() == $pays)
                {
                    $groupe->setTitre($request->request->get('titre'));
                    $groupe->setDescription($request->request->get('description'));
                    $groupe->setType($request->request->get('type'));
                    $groupe->setPays($request->request->get('pays'));
                    $logo = $request->files->get('logo');
                    $logo_name = $logo->getClientOriginalName();
                    $logo->move($this->getParameter("image_directory"),$logo_name);
                    $photo = $request->files->get('photo');
                    $photo_name = $photo->getClientOriginalName();
                    $photo->move($this->getParameter("image_directory"),$photo_name);
                    $groupe->setLogo($logo_name);
                    $groupe->setPhoto($photo_name);
                    $t->setGroupe($groupe);
                    $entityManager->persist($groupe);
                    $entityManager->persist($t);
                }
            }
        }
        $entityManager->flush();

        return $this->render('groupe/ajout.html.twig');
    }
    /**
     * @Route("groupe/one/{id}", name="groupe_one", methods={"GET","POST"})
    */
    public function getOneEtudiant(Groupe $groupe,GroupeRepository $data, int $id)
    {
        $groupe = $data->find($id);
        return $this->render('groupe/affichage.html.twig', ['groupe' => $groupe]);
    }

    /**
     * @Route("groupe/etudiants", name="groupe_etudiants",methods={"GET"})
    */
    public function listeEtudiantsByGroupe(GroupeRepository $data,SerializerInterface $serializer)
    {
        $groupes = $data->findAll();
        return new JsonResponse($serializer->serialize($groupes,"json",
                                ["groups" => ["groupe","list"]]),JsonResponse::HTTP_OK,[],true);
       
    }
}
