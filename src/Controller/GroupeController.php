<?php

namespace App\Controller;

use App\Entity\Groupe;
use App\Repository\GroupeRepository;
use App\Repository\PublicationRepository;
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
     * @Route("/groupe/ajout/{id}", name="groupe_ajout", methods={"POST","GET"})
    */
    public function ajoutGroupe(Request $request,UserRepository $data,EntityManagerInterface $entityManager,int $id)
    {
        $user = $data->find($id);
        $groupe = new Groupe();
        
        if ($request->getMethod() == 'POST') 
        {
 
                $groupe->setTitre($request->request->get('titre'));
                $groupe->setDescription($request->request->get('description'));
                $groupe->setType("privée");
                $groupe->setPays($user->getPays());
                $logo = $request->files->get('logo');
                $logo_name = $logo->getClientOriginalName();
                $logo->move($this->getParameter("image_directory"),$logo_name);
                $photo = $request->files->get('photo');
                $photo_name = $photo->getClientOriginalName();
                $photo->move($this->getParameter("image_directory"),$photo_name);
                $groupe->setLogo($logo_name);
                $groupe->setPhoto($photo_name);
                $user->addGroupe($groupe);
                $entityManager->persist($groupe);
                $entityManager->persist($user);
        }
        $entityManager->flush();

        return $this->render('groupe/ajout_groupe.html.twig', ['user' => $user]);
    }
    /**
     * @Route("groupe/one/{id}/{id1}", name="groupe_one", methods={"GET","POST"})
    */
    public function getOneGroupe(UserRepository $data1,GroupeRepository $data, int $id, int $id1)
    {
        $user = $data1->find($id);
        $groupe = $data->find($id1);
        $publications = $groupe->getPublies();
        //dd($publications);
       // $groupe = $user->getGroupe();

        return $this->render('groupe/liste_groupe.html.twig', ['groupe' => $groupe, 'user' => $user,'publications' => $publications]);
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
