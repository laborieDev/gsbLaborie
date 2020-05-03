<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\FicheForfait;
use App\Entity\SaveLoginUser;
use App\Entity\FicheHorsForfait;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }


    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        $faker = \Faker\Factory::create('fr_FR');

        $user = new User();
        $user->setUserName("Anthony LABORIE")
                 ->setLogin("agl")
                 ->setPassword("anna")
                 ->setAdresse("Le Dom 46130 CORNAC")
                 ->setDateEmbauche($faker->dateTime())
                 ->setIsComptable(false)
                 ->setroles(array("ROLE_ADMIN"));
        
        $hash = $this->encoder->encodePassword($user, $user->getPassword());
        $user->setPassword($hash);

        $manager->persist($user);

        $saveLogin = new SaveLoginUser();
        $saveLogin->setLogin("agl")
                  ->setPassword("anna");

        $manager->persist($saveLogin);

        $etatRepo = $manager->getRepository("App\Entity\Etat");
        $forfaitRepo = $manager->getRepository("App\Entity\FicheForfaitType");
        
        for($i = 1; $i <= 10; $i++){

            $user = new User();
            $lastname = $faker->lastName;
            $firstname = $faker->firstname();

            $mdp = $faker->password;

            $isComptable = $faker->boolean(50);
            if($isComptable)
                $roles = array("ROLE_COMPTABLE");
            else
                $roles = array("ROLE_VISITEUR");

            $user->setUserName($firstname." ".$lastname)
                 ->setLogin($lastname."-".$firstname)
                 ->setPassword($mdp)
                 ->setAdresse($faker->address)
                 ->setDateEmbauche($faker->dateTime())
                 ->setIsComptable($isComptable)
                 ->setroles($roles);

            
            $hash = $this->encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);

            $manager->persist($user);

            $saveLogin = new SaveLoginUser();
            $saveLogin->setLogin($lastname."-".$firstname)
                      ->setPassword($mdp);

            $manager->persist($saveLogin);

            if(!$user->getIsComptable()){

                for($i=1; $i<=2; $i++){
                    $fiche = new FicheHorsForfait();

                    $etat = $etatRepo->findOneBy(['idEtat' => 'CR']);
                
                    $fiche->setIdVisiteur($user)
                          ->setIdEtat($etat)
                          ->setLibelle($faker->realText(40))
                          ->setMontant($faker->randomFloat(2, 0, 120))
                          ->setDate(new \DateTime())
                          ->setNbJustificatifs(0);
                

                    $manager->persist($fiche);
                }
                for($i=1; $i<=3; $i++){
                    $fiche = new FicheForfait();

                    $forfait = $forfaitRepo->findAll();
                
                    $fiche->setIdVisiteur($user)
                          ->setIdType($forfait[rand(0,3)])
                          ->setQte(rand(1,5))
                          ->setDate(new \DateTime());

                    $manager->persist($fiche);
                }
            }
        }

        $manager->flush();
    }
}
