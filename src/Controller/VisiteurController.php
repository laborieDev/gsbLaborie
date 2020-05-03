<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\FicheFType;

use App\Form\FicheHfType;
use App\Entity\FicheForfait;
use App\Entity\FicheHorsForfait;
use App\Repository\EtatRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use App\Repository\FicheForfaitTypeRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class VisiteurController extends AbstractController
{
    /**
     * @Route("/visiteur", name="visiteur")
     */
    public function index()
    {
        return $this->render('visiteur/index.html.twig');
    }

    /**
     * @Route("/visiteur/newFicheFrais", name="new_fiche_forfait")
     */
    public function gestionFicheForfait(Request $req, ManagerRegistry $mr, Security $sec)
    {
        $fiche = new FicheForfait();
        $gestion = "Ajouter";

        $form = $this->createForm(FicheFType::class, $fiche);
        
        $form->handleRequest($req);

        if($form->isSubmitted() && $form->isValid()){

            if(!$fiche->getId()){
                $fiche->setDate(new \DateTime())
                      ->setIdVisiteur($sec->getUser());
            }
            
            $manager = $mr->getManager();
            $manager->persist($fiche);
            $manager->flush();

            return $this->redirectToRoute('get_fiches',[
                "valid_message" => "Fiche forfait bien ajouté !"
            ]);
        }

        return $this->render('visiteur/gestFicheFrais.html.twig', [
            'formFiche' => $form->createView(),
            'gestion' => $gestion
        ]);
    }

    /**
     * @Route("/visiteur/newFicheHf", name="new_fiche_hf")
     * @Route("/visiteur/editFicheHf/{id}", name="edit_fiche_hf")
     */
    public function gestionFicheForfaitHf(FicheHorsForfait $fiche = null, Request $req, ManagerRegistry $mr, Security $sec)
    {
        $gestion = "Modifier";
        $gestionMessage = "modifiée";

        if(!$fiche){
            $fiche = new FicheHorsForfait();
            $gestion = "Ajouter";
            $gestionMessage = "ajoutée";
        }
        else{
            if( ($fiche->getIdVisiteur() !== $sec->getUser()) || ($fiche->getNbJustificatifs() != 0) || ($fiche->getIdEtat()->getIdEtat() != "CR") ){
                return $this->redirectToRoute('get_fiches',[
                    "error_message" => "Vous ne pouvez pas modifier cette fiche ! <br>
                                        Vérifiez que ce soit votre fiche ou qu'elle n'est pas de justificatif enregsitré. <br> 
                                        Si cette fiche n'est pas en état 'Fiche créée', vous ne pouvez pas la modifier !"
                ]);
            }
        }

        $form = $this->createForm(FicheHfType::class, $fiche);
        
        $form->handleRequest($req);

        if($form->isSubmitted() && $form->isValid()){
            
            $manager = $mr->getManager();

            if(!$fiche->getId()){
                $etatRepo = $manager->getRepository("App\Entity\Etat");

                $fiche->setDate(new \DateTime())
                      ->setIdVisiteur($sec->getUser())
                      ->setIdEtat($etatRepo->findOneBy(['idEtat' => 'CR']));
            }
            
            $manager->persist($fiche);
            $manager->flush();

            return $this->redirectToRoute('get_fiches',[
                "valid_message" => "Fiche bien $gestionMessage !"
            ]);
        }

        return $this->render('visiteur/gestFicheFraisHf.html.twig', [
            'formFiche' => $form->createView(),
            'gestion' => $gestion
        ]);
    }

    /**
     * @Route("/visiteur/clotureFicheHf/{id}", name="cloture_fiche_hf")
     */
    public function clotureFicheForfaitHf(FicheHorsForfait $fiche, ManagerRegistry $mr, EtatRepository $repo, Security $sec)
    {
        if( ($fiche->getIdVisiteur() !== $sec->getUser()) || ($fiche->getIdEtat()->getIdEtat() != "CR") ){
            return $this->redirectToRoute('get_fiches',[
                "error_message" => "Vous ne pouvez pas clôturer cette fiche ! <br>
                                    Vérifiez que ce soit votre fiche. <br> 
                                    Si cette fiche n'est pas en état 'Fiche créée', vous ne pouvez pas la clôturer !"
            ]);
        }

        $etat = $repo->findOneBy(['idEtat' => 'CL']);
        $fiche->setIdEtat($etat);

        $manager = $mr->getManager();
        $manager->persist($fiche);
        $manager->flush();

        return $this->redirectToRoute('get_fiches',[
            "valid_message" => "Fiche bien clôturée !"
        ]);

    }

    /**
     * @Route("/visiteur/deleteFicheHf/{id}", name="delete_fiche_hf")
     */
    public function deleteFicheHf(FicheHorsForfait $fiche, ManagerRegistry $mr, Security $sec){

        if( ($fiche->getIdVisiteur() === $sec->getUser()) && ($fiche->getNbJustificatifs() == 0) && ($fiche->getIdEtat()->getIdEtat() == "CR") ){
            $manager = $mr->getManager();
            $manager->remove($fiche);
            $manager->flush();
        }
        else{
            return $this->redirectToRoute('get_fiches',[
                "error_message" => 
                    "Vous ne pouvez pas supprimer une fiche qui n'ai pas à vous ou qui contient des justificatifs ! <br> 
                    Une fiche avec un autre état que 'Fiche créée' ne peut pas être supprimer !"
            ]);
        }

        return $this->redirectToRoute('get_fiches',[
            "valid_message" => "Fiche bien supprimée !"
        ]);
    }

    /**
     * @Route("/visiteur/mesFiches", name="get_fiches")
     */
    public function getFiches(Security $sec, FicheForfaitTypeRepository $repoType)
    { 
        $user = $sec->getUser();
        $forfaitsType = $repoType->findAll();
        
        $forfaitsValue = array();

        foreach($forfaitsType as $type){
            $forfaitsValue[$type->getIdForfaitType()]['libelle'] = $type->getLibelle();
            $forfaitsValue[$type->getIdForfaitType()]['nb'] = 0;
        }

        $fichesForfaits = $user->getFicheForfaits();

        foreach($fichesForfaits as $fiche){
            $type = $fiche->getIdType()->getIdForfaitType();
            $forfaitsValue[$type]['nb'] += $fiche->getQte();
        }

        if(isset($_GET['error_message']))
            return $this->render('visiteur/getFichesFrais.html.twig', [
                'user' => $user,
                'forfaitsType' => $forfaitsValue,
                'error_message' => $_GET['error_message']
            ]);

        if(isset($_GET['valid_message']))
            return $this->render('visiteur/getFichesFrais.html.twig', [
                'user' => $user,
                'forfaitsType' => $forfaitsValue,
                'valid_message' => $_GET['valid_message']
            ]);
        
        else
            return $this->render('visiteur/getFichesFrais.html.twig', [
                'user' => $user,
                'forfaitsType' => $forfaitsValue
            ]);
    }
}
