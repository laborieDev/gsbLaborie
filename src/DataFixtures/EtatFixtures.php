<?php

namespace App\DataFixtures;

use App\Entity\Etat;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class EtatFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();

        $etat = new Etat();
        $etat->setIdEtat("CR")
             ->setLibelle("Fiche créée");

        $manager->persist($etat);
        
        $etat = new Etat();
        $etat->setIdEtat("CL")
             ->setLibelle("Fiche clôturée");

        $manager->persist($etat);
        
        $etat = new Etat();
        $etat->setIdEtat("VA")
             ->setLibelle("Validée et mise en paiement");

        $manager->persist($etat);
        
        $etat = new Etat();
        $etat->setIdEtat("RB")
             ->setLibelle("Remboursée");

        $manager->persist($etat);

        $manager->flush();
    }
}
