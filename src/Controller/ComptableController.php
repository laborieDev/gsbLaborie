<?php

namespace App\Controller;

use App\Entity\FicheHorsForfait;
use App\Repository\EtatRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\FicheHorsForfaitRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ComptableController extends AbstractController
{
    /**
     * @Route("/comptable", name="comptable")
     */
    public function index()
    {
        return $this->render('comptable/index.html.twig');
    }

    /**
     * @Route("/comptable/validation_fiches/add_justificatifs/{id}", name="add_justificatifs")
     */
    public function addJustificatifs(FicheHorsForfait $fiche, ManagerRegistry $mr)
    {
        if( (!(isset($_POST['nbJustificatif'])))
            || ($fiche->getIdEtat()->getIdEtat() != "CL") )
            
            return $this->redirectToRoute('valid_fiches',[
                "error_message" => "Cette action n'est pas possible !"
            ]);
        
        $nbJustificatifs = $fiche->getnbJustificatifs() + $_POST['nbJustificatif'];
        $fiche->setnbJustificatifs($nbJustificatifs);

        $manager = $mr->getManager();
        $manager->persist($fiche);
        $manager->flush();

        return $this->redirectToRoute('valid_fiches',[
            "valid_message" => "Action bien prise en compte !"
        ]);
    }


    /**
     * @Route("/comptable/validation_fiches/DELETE/{id}", name="c_delete_fiche")
     */
    public function deleteFicheComptable(FicheHorsForfait $fiche, ManagerRegistry $mr)
    {
        $manager = $mr->getManager();
        $manager->remove($fiche);
        $manager->flush();

        return $this->redirectToRoute('valid_fiches',[
            "valid_message" => "Fiche bien supprimée !"
        ]);
    }

    /**
     * @Route("/comptable/validation_fiches/{etat}/{id}", name="valid_fiche")
     */
    public function validationFiche(FicheHorsForfait $fiche, EtatRepository $repo, ManagerRegistry $mr, string $etat)
    {
        $etat = $repo->findOneBy(['idEtat' => $etat]);

        if($etat == "")
            return $this->redirectToRoute('valid_fiches',[
                "error_message" => "Cette action n'est pas possible !"
            ]);

        $fiche->setIdEtat($etat);

        $manager = $mr->getManager();
        $manager->persist($fiche);
        $manager->flush();

        return $this->redirectToRoute('valid_fiches',[
            "valid_message" => "Action bien prise en compte !"
        ]);
    }

    /**
     * @Route("/comptable/validation_fiches", name="valid_fiches")
     */
    public function validation(ManagerRegistry $mr)
    {
        $conn = $mr->getManager()->getConnection();

        $sql = "
            SELECT f.id, f.libelle, f.montant, f.date, f.nb_justificatifs, u.username
            FROM fiche_hors_forfait f, etat e, user u
            WHERE e.id_etat = 'CL'
            AND e.id = f.id_etat_id
            AND u.id = f.id_visiteur_id
            ORDER BY f.id DESC
            ";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $fiches = $stmt->fetchAll();

        if(isset($_GET['valid_message']))
            return $this->render('comptable/validation.html.twig',[
                'fiches' => $fiches,
                'valid_message' => $_GET['valid_message']
            ]);

        if(isset($_GET['error_message']))
            return $this->render('comptable/validation.html.twig',[
                'fiches' => $fiches,
                'error_message' => $_GET['error_message']
            ]);

        return $this->render('comptable/validation.html.twig',[
            'fiches' => $fiches
        ]);
    }

    /**
     * @Route("/comptable/remboursement_fiches", name="rembourser_fiches")
     */
    public function remboursement(ManagerRegistry $mr)
    {
        $conn = $mr->getManager()->getConnection();

        $sql = "
            SELECT f.id, f.libelle, f.montant, f.date, f.nb_justificatifs, u.username
            FROM fiche_hors_forfait f, etat e, user u
            WHERE e.id_etat = 'VA'
            AND e.id = f.id_etat_id
            AND u.id = f.id_visiteur_id
            ORDER BY f.id DESC
        ";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $fiches = $stmt->fetchAll();

        return $this->render('comptable/remboursement.html.twig',[
            'fiches' => $fiches
        ]);
    }
}
