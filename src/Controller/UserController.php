<?php

namespace App\Controller;

use App\Entity\Etudiant;
use App\Entity\User;
use App\Form\EtudiantType;
use App\Repository\EtudiantRepository;
use App\Repository\GroupeRepository;
use App\Repository\UserRepository;
use App\Services\MailerService;
use App\Services\MessageService;
use DateInterval;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use PhpParser\Node\Expr\Cast\String_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\Mailer;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Constraints\Date;

class UserController extends AbstractController
{
    private $passwordHasher;

    public function __construct(UserPasswordEncoderInterface $passwordHasher)
    {
         $this->passwordHasher = $passwordHasher;
    }

    // Cette route permet de lister les users avec une certaine restriction des attributs
    /** 
     * @Route("admin/liste", name="admin_liste",methods={"GET"})
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
    */
    public function index(UserRepository $data,SerializerInterface $serializer)
    {
        $users = $data->findAll();
        //dd($this->getUser()->getId());
        return new JsonResponse($serializer->serialize($users,"json",
                                ["groups" => ["users"]]),JsonResponse::HTTP_OK,[],true);
        /*
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);
        $jsonContent = $serializer->serialize($users, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);
        $response = new Response($jsonContent);
        $response->headers->set('Content-Type', 'application/json');
        return $response;*/
    }

    //Cette route permet d'ajouter un administrateur
    /** 
     * @Route("admin/ajout", name="admin_ajout", methods={"POST","GET"})
    */
    public function addAdmin(Request $request,EntityManagerInterface $entityManager,SluggerInterface $slugger)
    { 

            if ($request->getMethod() == 'POST') 
            {
                    $user = new User();

                    $user->setNom($request->request->get('nom'));
                    $user->setPrenom($request->request->get('prenom'));
                    $user->setEmail($request->request->get('email'));
                    $user->setPays($request->request->get('pays'));
                    $start = new DateTimeImmutable($request->request->get('birthday'));
                    $finish = $start->add(new DateInterval('P3D'));
                    $start->format('Y-m-d');
                    $user->setBirthdayAt($start);
                    $user->setRoles(["ROLE_ADMIN"]);

                    $imageFile = $request->files->get('photo');
                    if ($imageFile) {
                        $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                        // this is needed to safely include the file name as part of the URL
                        $safeFilename = $slugger->slug($originalFilename);
                        $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();
        
                        // Move the file to the directory where brochures are stored
                        try {
                            $imageFile->move(
                                $this->getParameter('image_directory'),
                                $newFilename
                            );
                        } catch (FileException $e) {
                        }

                        $user->setPhoto($newFilename);
                    }
                $user->setPassword($this->passwordHasher->encodePassword($user, $request->request->get('password')));
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
                return $this->redirectToRoute('admin_connexion');
            }
        return $this->render('user/ajout.html.twig');
    }

    /** 
     * @Route("admin/modifier", name="admin_modifier", methods={"POST","GET"})
    */
    public function modifierAdmin(Request $request,EntityManagerInterface $entityManager, SluggerInterface $slugger)
    { 

            if ($request->getMethod() == 'POST') 
            {
                    $user = new User();

                    $user->setNom($request->request->get('nom'));
                    $user->setPrenom($request->request->get('prenom'));
                    $user->setEmail($request->request->get('email'));
                    $user->setPays($request->request->get('pays'));
                    $start = new DateTimeImmutable($request->request->get('birthday'));
                    $finish = $start->add(new DateInterval('P3D'));
                    $start->format('Y-m-d');
                    $user->setBirthdayAt($start);
                    $user->setRoles(["ROLE_ADMIN"]);
                    $imageFile = $request->files->get('photo');
                    if ($imageFile) {
                        $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                        // this is needed to safely include the file name as part of the URL
                        $safeFilename = $slugger->slug($originalFilename);
                        $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();
        
                        // Move the file to the directory where brochures are stored
                        try {
                            $imageFile->move(
                                $this->getParameter('image_directory'),
                                $newFilename
                            );
                        } catch (FileException $e) {
                        }

                        $user->setPhoto($newFilename);
                    }
                    $user->setPassword($this->passwordHasher->encodePassword($user, $request->request->get('password')));
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($user);
                    $entityManager->flush();
                return $this->redirectToRoute('admin_connexion');
            }
        return $this->render('user/ajout.html.twig');
    }

    //Cette route permet à l'administrateur de se connecter
    /** 
     * @Route("admin/login", name="admin_connexion", methods={"POST","GET"})
    */
    public function loginAdmin(Request $request,UserRepository $data,EntityManagerInterface $entityManager)
    {
        $users = $data->findAll();
        $table = [];
        $email = $request->request->get('email');
        $password = $request->request->get('password');
        foreach ($users as $u)
        {
            $table[] = $u;
        }
        if ($request->getMethod() == 'POST') 
        {
            foreach ($table as $t)
            {
                if($t->getEmail() == $email && $t->getPassword())
                {
                    return $this->redirectToRoute('admin_liste');
                }
            }
        }
        return $this->render('user/connexion.html.twig');
    }

    /**
     * @Route("admin/one/{id}", name="admin_one", methods={"GET","POST"})
    */
    public function getOneUser(User $user,UserRepository $data, int $id,Request $request,SluggerInterface $slugger)
    {
        $user = $data->find($id);
        if ($request->getMethod() == 'POST') 
        {
            $user->setNom($request->request->get('nom'));
            $user->setPrenom($request->request->get('prenom'));
            $user->setEmail($request->request->get('email'));
            $user->setPays($request->request->get('pays'));
            $user->setBirthdayAt(new DateTimeImmutable($request->request->get('birthday')));
            $imageFile = $request->files->get('photo');
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $imageFile->move(
                        $this->getParameter('image_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                }

                $user->setPhoto($newFilename);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
        }
        return $this->render('user/affichage.html.twig', ['user' => $user]);
    }


    // Cette route permet à l'administrateur d'inscrire(inserer) un étudiant du meme pays que lui
    /**
     * @Route("admin/insertion", name="admin_inserer", methods={"POST"})
     * @Security("is_granted('ROLE_ADMIN')")
    */
    public function insererEtudiant(Request $request,UserRepository $data1,
                                EntityManagerInterface $entityManager,\Swift_Mailer $mailer)
    {
        $mail = "rse@gmail.com";
        $code = "CodeE7899";

        $donnees = json_decode($request->getContent());

        $etudiant = new Etudiant();

        $etudiant->setNom($donnees->nom);
        $etudiant->setPrenom($donnees->prenom);
        $etudiant->setCodeE($donnees->codeE);
        $etudiant->setVille($donnees->ville);
        $etudiant->setPays($donnees->pays);
        $etudiant->setEmail($donnees->email);
        $etudiant->setPassword($this->passwordHasher->encodePassword($etudiant, "pass123"));

        $etudiant->setRoles(["ROLE_ETUDIANT"]);

        $users = $data1->findAll();
        $table1 = [];
        foreach ($users as $u)
        {
            $table1[] = $u;
        }
        foreach ($table1 as $t)
        {
             if ($t->getPays() == $donnees->pays)
            {
                $t->addEtudiant($etudiant);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($t);
                $entityManager->persist($etudiant);
                $message = (new \Swift_Message('Bienvenue'))
                        ->setFrom($t->getEmail())
                        ->setTo($donnees->email)
                        ->setBody(
                            $this->renderView(
                                'email/registration.html.twig',
                                ['mail' => $donnees->email,'code' =>$donnees->codeE]
                            ),
                            'text/html'
                );

                $mailer->send($message);
            }    
        }
        $entityManager->flush();

        return new Response('ok', 201);
    }

    // Cette route affiche la liste des etudiants par administrateurs
    /**
     * @Route("admin/etudiants", name="admin_etudiants",methods={"GET"})
     * @Security("is_granted('ROLE_ADMIN')")
    */
    public function listeEtudiantsByUser(UserRepository $data,SerializerInterface $serializer)
    {
        $users = $data->findAll();
        return new JsonResponse($serializer->serialize($users,"json",
                                ["groups" => ["list","get","id"]]),JsonResponse::HTTP_OK,[],true);
       
    }

    // Cette route permet de réinitialiser le mot de passe d'un étudiant inscrit
    /**
     * @Route("/etudiant/password", name="etudiant_password", methods={"POST","GET"})
    */
    public function resetPassword(Request $request,EtudiantRepository $data,EntityManagerInterface $entityManager)
    {
        
        $etudiants = $data->findAll();
        $table = [];
        $code = $request->request->get('code');
        foreach ($etudiants as $e)
        {
            $table[] = $e;
        }
        foreach ($table as $t)
        {
            if ($t->getCodeE() == $code)
            {
                $t->setPassword($this->passwordHasher->encodePassword($t, $request->request->get('password')));
                $entityManager->persist($t);
            }
        }
        $entityManager->flush();

        return $this->render('email/reset_password.html.twig');
    }

    // Cette route permet d'envoyer un email à l'étudiant afin d'accéder au lien pour s'inscrire
    /**
     * @Route("admin/email", name="admin_email", methods={"POST"})
     * @Security("is_granted('ROLE_ADMIN')")
    */
    public function emailEtudiant(Request $request,\Swift_Mailer $mailer)
    {

        $donnees = json_decode($request->getContent());
     
        $message = (new \Swift_Message('Bienvenue'))
                        ->setFrom($donnees->user)
                    ->setTo($donnees->email)
                        ->setBody(
                            $this->renderView(
                                'email/mailer.html.twig',
                                ['code' =>$donnees->codeE]
                            ),
                            'text/html'
        );

        $mailer->send($message);

        return new Response('ok', 201);
    }

    // Cette route permet à l'étudiant de s'inscrire(formulaire) à l'aide d'un lien qu'il a recu par son administrateur
    /**
     * @Route("/etudiant/insertion", name="admin_inserer2", methods={"POST","GET"})
    */
    public function insererEtudiantCode(Request $request,UserRepository $data,
                                        GroupeRepository $data1,EntityManagerInterface $entityManager)
    {
        $groupes = $data1->findAll();
        $table1 = [];
        $users = $data->findAll();
        $table = [];
        $pays = $request->request->get('pays');
        $etudiant = new Etudiant();
        foreach ($users as $e)
        {
            $table[] = $e;
        }
        foreach ($groupes as $g)
        {
            $table1[] = $g;
        }
        if ($request->getMethod() == 'POST') 
        {
            foreach ($table1 as $t1)
            {
                foreach ($table as $t)
                {
                        if ($t1->getPays() == $pays && $t->getPays() == $pays)
                        {
                            $etudiant->setNom($request->request->get('nom'));
                            $etudiant->setPrenom($request->request->get('prenom'));
                            $etudiant->setCodeE($request->request->get('code'));
                            $etudiant->setEmail($request->request->get('email'));
                            $etudiant->setPays($request->request->get('pays'));
                            $etudiant->setVille($request->request->get('ville'));
                            $etudiant->setBirthdayAt(new DateTimeImmutable($request->request->get('birthday')));
                            $etudiant->setPassword($this->passwordHasher->encodePassword($t, $request->request->get('password')));
                            $photo = $request->files->get('photo');
                            $photo_name = $photo->getClientOriginalName();
                            $photo->move($this->getParameter("image_directory"),$photo_name);
                            $etudiant->setPhoto($photo);
                            $t1->addEtudiant($etudiant);
                            $t->addEtudiant($etudiant);
                            $entityManager->persist($etudiant);
                            $entityManager->persist($t);
                            $entityManager->persist($t1);
                        }
                    
                }
            
            }
        }
            $entityManager->flush();
            //return $this->redirectToRoute('etudiant_login');

        return $this->render('email/inscription.html.twig');
    }

    /**
     * @Route("etudiant/one/{id}", name="etudiant_one", methods={"GET","POST"})
    */
    public function getOneEtudiant(Etudiant $etudiant,EtudiantRepository $data, int $id)
    {
        $etudiant = $data->find($id);
        return $this->render('email/affichage.html.twig', ['etudiant' => $etudiant]);
    }

     /** 
     * @Route("etudiant/login", name="etudiant_login", methods={"POST","GET"})
    */
    public function loginEtudiant(Request $request,EtudiantRepository $data,EntityManagerInterface $entityManager)
    {
        $etudiants = $data->findAll();
        $table = [];
        $email = $request->request->get('email');
        $password = $request->request->get('password');
        foreach ($etudiants as $e)
        {
            $table[] = $e;
        }
        if ($request->getMethod() == 'POST') 
        {
            foreach ($table as $t)
            {
                if($t->getEmail() == $email && $t->getPassword())
                {
                    return $this->redirectToRoute('etudiant_one',['id' => $t->getId()]);
                }
            }
        }
        return $this->render('email/connexion.html.twig');
    }

    
}
