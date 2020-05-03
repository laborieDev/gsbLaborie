<?php

namespace App\DataFixtures;

use App\Entity\FicheForfaitType;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ForfaitType extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $forfait = new FicheForfaitType();
        $forfait->setLibelle("Forfait Etape")
                ->setMontant(110)
                ->setIdForfaitType('ETP');

        $manager->persist($forfait);
        
        $forfait = new FicheForfaitType();
        $forfait->setLibelle("Frais Kilométrique")
                ->setMontant(0.62)
                ->setIdForfaitType('KM');

        $manager->persist($forfait);
        
        $forfait = new FicheForfaitType();
        $forfait->setLibelle("Nuitée Hôtel")
                ->setMontant(80)
                ->setIdForfaitType('NUI');

        $manager->persist($forfait);
        
        $forfait = new FicheForfaitType();
        $forfait->setLibelle("Repas Restaurant")
                ->setMontant(25)
                ->setIdForfaitType('REP');

        $manager->persist($forfait);

        $manager->flush();
    }
}
