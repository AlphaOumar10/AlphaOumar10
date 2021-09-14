<?php

namespace App\DataFixtures;

use App\Entity\Etudiant;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $passwordHasher;
      public function __construct(UserPasswordEncoderInterface $passwordHasher)
      {
           $this->passwordHasher = $passwordHasher;
      }
    public function load(ObjectManager $manager)
    {
        $users = [];
         for($i = 1; $i <= 10; $i++){
            $user = new User();
            $user->setNom("nom_user".$i);
            $user->setPrenom("prenom_user".$i);
            $user->setPays("pays_user".$i);
            $user->setEmail("email@gmail".$i);
            $password = $this->passwordHasher->encodePassword($user, 'pass123'.$i);
            $user->setPassword($password);
            $manager->persist($user);

            $users[] = $user;
         } 

         foreach($users as $user)
         {
             for($i = 1; $i <= 5; $i++)
             {
                $etudiant = new Etudiant();
                $etudiant->setCodeE("CodeE".$i);
                $etudiant->setNom("nom".$i);
                $etudiant->setPrenom("prenom".$i);
                $etudiant->setVille("ville".$i);
                $etudiant->setPays("pays".$i);
                $etudiant->setEmail("etudiant@gmail.com".$i);
                $password = $this->passwordHasher->encodePassword($etudiant, 'pass123'.$i);
                $etudiant->setPassword($password);
                $etudiant->setRoles(["ROLE_USER"]);
                $etudiant->setUser($user);
                $manager->persist($etudiant);
            }
         }

        $manager->flush();
    }
    
}
