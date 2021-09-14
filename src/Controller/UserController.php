<?php

namespace App\Controller;

use App\Entity\Etudiant;
use App\Entity\User;
use App\Form\EtudiantType;
use App\Repository\EtudiantRepository;
use App\Repository\UserRepository;
use App\Services\MailerService;
use App\Services\MessageService;
use Doctrine\ORM\EntityManagerInterface;
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

class UserController extends AbstractController
{
    private $passwordHasher;
    public function __construct(UserPasswordEncoderInterface $passwordHasher)
    {
         $this->passwordHasher = $passwordHasher;
    }
    /**
     * @Route("admin/liste", name="admin_liste",methods={"GET"})
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
    */
    public function index(UserRepository $data,SerializerInterface $serializer)
    {
        $users = $data->findAll();
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


    /**
     * @Route("admin/ajout", name="user_ajout", methods={"POST"})
    */
    public function addUser(Request $request)
    {
            $user = new User();

            $donnees = json_decode($request->getContent());

            $user->setEmail($donnees->email);
            $user->setPassword($this->passwordHasher->encodePassword($user, $donnees->password));
            $user->setRoles($donnees->roles);
            $user->setNom($donnees->nom);
            $user->setPrenom($donnees->prenom);
            $user->setPays($donnees->pays);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return new Response('ok', 201);
    }

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

    /**
     * @Route("/etudiant/insertion", name="admin_inserer2", methods={"POST","GET"})
    */
    public function insererEtudiantCode(Request $request,UserRepository $data,EntityManagerInterface $entityManager)
    {
        $users = $data->findAll();
        $table = [];
        $pays = $request->request->get('pays');
        $etudiant = new Etudiant();
        foreach ($users as $e)
        {
            $table[] = $e;
        }
        foreach ($table as $t)
        {
            if ($t->getPays() == $pays)
            {
                $etudiant->setNom($request->request->get('nom'));
                $etudiant->setPrenom($request->request->get('prenom'));
                $etudiant->setCodeE($request->request->get('code'));
                $etudiant->setEmail($request->request->get('email'));
                $etudiant->setPays($request->request->get('pays'));
                $etudiant->setVille($request->request->get('ville'));
                $etudiant->setPassword($this->passwordHasher->encodePassword($t, $request->request->get('password')));
                $t->addEtudiant($etudiant);
                $entityManager->persist($etudiant);
                $entityManager->persist($t);
            }
        }
        $entityManager->flush();

        return $this->render('email/inscription.html.twig');
    }

    
}
