<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $conn = $manager->getConnection();

        $sql = "
            CREATE EVENT cloture_event
            ON SCHEDULE
            EVERY 1 MONTH
            STARTS ('2020-04-01 01:00:00')
            DO
              UPDATE fichefrais
              SET `idEtat` = (
                  CASE 
                  WHEN `idEtat` LIKE 'CR'
                  AND SUBSTRING(mois, 5,2) = MONTH(DATE_SUB(DATE(NOW()), INTERVAL 1 MONTH)) THEN 'CL'
                  ELSE  idEtat
                  END )
            ";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
    }
}
