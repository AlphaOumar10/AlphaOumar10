<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\Groupe;
use App\Entity\Publication;
use App\Entity\Reponse;
use App\Entity\User;
use App\Repository\CommentaireRepository;
use App\Repository\EtudiantRepository;
use App\Repository\GroupeRepository;
use App\Repository\PublicationRepository;
use App\Repository\UserRepository;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
   

    /**
     * @Route("/index/{id}", name="index")
    */
    public function acceuil(User $user,UserRepository $data, int $id)
    {
        $user = $data->find($id);
        $publications = $user->getPublications();
        //dd($publications);
        return $this->render('base.html.twig', ['user' => $user,'publications' => $publications]);
    }

    /**
     * @Route("/publication/{id}/{id1}", name="publication_one")
    */
    public function afficherPublication(User $user,UserRepository $data
                    ,PublicationRepository $data1,int $id,int $id1)
    {
        $user = $data->find($id);
        $publication = $data1->find($id1);
        //$commentaire = $publication->getCommentaires();
        //$commentaire = $data2->find($id2);
        return $this->render('accueil/publication_one.html.twig', ['user' => $user, 'publication' => $publication]);
    }

    /**
     * @Route("accueil/publication/ajout/{id}", name="publication_ajout", methods={"GET","POST"})
    */
    public function ajoutPublication(Request $request,UserRepository $data,GroupeRepository $data1,EntityManagerInterface $entityManager,int $id)
    {
        $user = $data->find($id);
        $groupe = $data1->find(4);
        $publication = new Publication();
        if ($request->getMethod() == 'POST') 
        {

                    $publication->setTitre($request->request->get('titre'));
                    $publication->setContenu($request->request->get('contenu'));
                    $publication->setPays($user->getPays());
                    $publication->setCreateAt(new DateTimeImmutable('now'));
                    $photo = $request->files->get('photo');
                    $photo_name = $photo->getClientOriginalName();
                    $photo->move($this->getParameter("image_directory"),$photo_name);
                    $publication->setPhoto($photo_name);
                    $publication->setCommunaute($groupe);
                    $user->addPublication($publication);
                    $entityManager->persist($publication);
                    $entityManager->persist($user);
        }
        $entityManager->flush();
        $publications = $user->getPublications();

        return $this->render('base.html.twig', ['user' => $user,'publications' => $publications]);
    }

    /**
     * @Route("accueil/publication/groupe/{id}/{id1}", name="publication_groupe", methods={"GET","POST"})
    */
    public function ajoutPublication2(Request $request,UserRepository $data,GroupeRepository $data1,EntityManagerInterface $entityManager,int $id,int $id1)
    {
        $user = $data->find($id);
        $groupe = $data1->find($id1);
        $publication = new Publication();
        if ($request->getMethod() == 'POST') 
        {

                    $publication->setTitre($request->request->get('titre'));
                    $publication->setContenu($request->request->get('contenu'));
                    $publication->setPays($user->getPays());
                    $publication->setCreateAt(new DateTimeImmutable('now'));
                    $photo = $request->files->get('photo');
                    $photo_name = $photo->getClientOriginalName();
                    $photo->move($this->getParameter("image_directory"),$photo_name);
                    $publication->setPhoto($photo_name);
                    $groupe->addPubly($publication);
                    $user->addGroupe($groupe);
                    $publication->setUser($user);

                    $entityManager->persist($publication);
                    $entityManager->persist($groupe);
                    $entityManager->persist($user);
        }
        $entityManager->flush();
        $publications = $groupe->getPublies();

        return $this->render('groupe/liste_groupe.html.twig', ['user' => $user,'groupe' => $groupe,'publications' => $publications]);
    }


    /**
     * @Route("accueil/commentaire/ajout/{id}/{id1}", name="commentaire_ajout", methods={"GET","POST"})
    */
    public function ajoutCommentaire(Request $request,PublicationRepository $data,UserRepository $data1,EntityManagerInterface $entityManager,int $id,int $id1)
    {
        $user = $data1->find($id);
        $publication = $data->find($id1);
        $commentaire = new Commentaire;
        if ($request->getMethod() == 'POST') 
        {
            $commentaire->setContenu($request->request->get('contenu'));
            $commentaire->setCreateAt(new DateTimeImmutable('now'));
            $commentaire->setPublication($publication);
            $commentaire->setUserss($user);
            

            $entityManager->persist($commentaire);
        }
        $entityManager->flush();

        return $this->render('accueil/commenter_publication.html.twig',['publication' => $publication,'user' => $user]);
    }

    /**
     * @Route("accueil/publication/liste/{id}", name="publication_liste", methods={"GET","POST"})
    */
    public function listePublication(Request $request,int $id,UserRepository $data1)
    {
        $user = $data1->find($id);
        $publications = $user->getPublications();
        return $this->render('accueil/liste_publication.html.twig', ['publications' => $publications,'user' => $user]);
    }
    
    /**
     * @Route("accueil/publication/commentaire/{id}/{id1}/{id2}", name="publication_reponse", methods={"GET","POST"})
    */
    public function repondrePublication(PublicationRepository $data,UserRepository $data1,EntityManagerInterface $entityManager,
                                        Request $request,CommentaireRepository $data2,int $id,int $id1,int $id2)
    {
        $publication = $data->find($id1);
        $user = $data1->find($id);
        $commentaire = $data2->find($id2);;
        $reponse = new Reponse();
        if ($request->getMethod() == 'POST') 
        {
            $reponse->setContenu($request->request->get('reponse'));
            $reponse->setCreateAt(new DateTimeImmutable('now'));
            $reponse->setReponseU($user);
            $commentaire->addReponse($reponse);

            $entityManager->persist($reponse);
            $entityManager->persist($commentaire);
        }
        $entityManager->flush();

        return $this->render('accueil/repondre_publication.html.twig',['publication' => $publication,'user' => $user,'commentaire' => $commentaire]);
    }


   
}
