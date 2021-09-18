<?php

namespace App\Controller;

use App\Entity\Etudiant;
use App\Repository\EtudiantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


class EtudiantController extends AbstractController
{

    private $passwordHasher;
    public function __construct(UserPasswordEncoderInterface $passwordHasher)
    {
         $this->passwordHasher = $passwordHasher;
    }

    // Cette route affiche la liste des etudiants avec une certaine restriction des attributs 
    /**
     * @Route("etudiant/liste", name="etudiant_liste",methods={"GET"})
     * @Security("is_granted('ROLE_ADMIN')")
    */
    public function index(EtudiantRepository $data,SerializerInterface $serializer)
    {
        $etudiants = $data->findAll();
        return new JsonResponse($serializer->serialize($etudiants,"json",
                                ["groups" => ["list"]]),JsonResponse::HTTP_OK,[],true);

    }

    // Cette route permet à l'etudiant de s'inscire une fois sur l'application par lui meme
    /**
     * @Route("etudiant/inscription", name="etudiant_inscription", methods={"POST"})
    */
    public function inscription(Request $request,EtudiantRepository $data)
    {

            $etudiants = $data->findAll();
            $table = [];
            foreach($etudiants as $e)
            {
                $table[] = $e;
            }
            
            $donnees = json_decode($request->getContent());

            $etudiant = new Etudiant();

            $etudiant->setNom($donnees->nom);
            $etudiant->setPrenom($donnees->prenom);
            $etudiant->setCodeE($donnees->codeE);
            $etudiant->setVille($donnees->ville);
            $etudiant->setPays($donnees->pays);
            $etudiant->setPassword($this->passwordHasher->encodePassword($etudiant, $donnees->password));
            $etudiant->setEmail($donnees->email);

            $etudiant->setRoles(["ROLE_ETUDIANT"]);

            foreach($table as $t)
            {
                if ($t->getCodeE() == $donnees->codeE)
                {
                    return new Response('Ce code existe', 201);
                }
                else
                {
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($etudiant);
                }
            }
                    
 

            $entityManager->flush();
            

            return new Response('ok', 201);
    }

    // Cette route permet à l'etudiant de se connecter
    /**
     * @Route("etudiant/login", name="etudiant_login", methods={"POST"})
    */
    public function login(Request $request,EtudiantRepository $data)
    {
            $etudiants = $data->findAll();
            $table = [];
            foreach ($etudiants as $t)
            {
                $table[] = $t;
            }
            $donnees = json_decode($request->getContent());
           // dd($donnees);
           // dd($table);
            foreach($table as $e);
            {
                if ($e->getEmail() == $donnees->email && $e->getPassword() == $donnees->password)
                {
                    return new Response('ok', 201);
                }
                else{
                    return new Response('Error',404);
                }
            }
            return new Response('ok', 201);
    }
 

}
