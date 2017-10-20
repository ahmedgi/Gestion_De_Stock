<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Article;
use AppBundle\Entity\Categorie;
use AppBundle\Entity\Intervention;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\Session\Session;


class MaintenanceController extends Controller
{
    /**
     * @Route("/Maintenance", name="Maintenance")
     */
    public function MaintenanceAction()
    {
        $Articles = $this->getDoctrine()
        ->getRepository('AppBundle:Article')
        ->findAll();
        $Categories = $this->getDoctrine()
        ->getRepository('AppBundle:Categorie')
        ->findAll();

        $Services = $this->getDoctrine()
        ->getRepository('AppBundle:Service')
        ->findAll();

        $Hopitales = $this->getDoctrine()
        ->getRepository('AppBundle:Hopitale')
        ->findAll();
        $session = new Session();
        $session->set('categorie', '0');
        $session->set('service', '0');

        if (!empty($_POST)) {

            //get the data
            $CategorieName= $_POST['categorie'];
            $ServiceName= $_POST['service'];
            $session->set('categorie', $CategorieName);
            $session->set('service', $ServiceName);


            if($CategorieName!='0'){
                $Categorie = $this->getDoctrine()
                ->getRepository('AppBundle:Categorie')
                ->findOneBy(
                    array('name' =>$CategorieName)
                );
                $ArticlesCategorie=$Categorie->getArticles();
                $Articles=$ArticlesCategorie;
            }

            if($ServiceName!='0'){
                $Service = $this->getDoctrine()
                ->getRepository('AppBundle:Service')
                ->findOneBy(
                    array('nom' =>$ServiceName)
                );
                $ArticlesService=$Service->getArticles();
                $Articles=$ArticlesService;
            }

            if($CategorieName!='0' && $ServiceName!='0'){
                $articles=array();
                foreach ($ArticlesCategorie as $key => $value) {
                    if($ArticlesService->contains($value)){
                        $articles[]=$value;
                    }
                };
                $Articles=$articles;
            }

            return $this->render('Maintenance/Maintenance.html.twig',array('articles' => $Articles,'services'=>$Services,'categories'=>$Categories,'session'=>$session ));
        }


        return $this->render('Maintenance/Maintenance.html.twig',array('articles' => $Articles,'services'=>$Services,'categories'=>$Categories,'session'=>$session));
    }

    /**
     * @Route("/Maintenance/intervention/{id}", name="intervention")
    */
    public function InterventionAction($id,Request $request){

        $Article = $this->getDoctrine()
        ->getRepository('AppBundle:Article')
        ->find($id);

        $form = $this->createFormBuilder()
        ->add('rapport', TextareaType::class,array('attr' =>array('class'=>'form-control','style'=>'margin-bottom:15px')))
        ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //die("the form is submitted");
            $Intervention=new Intervention;
            $Name = $_POST['name'];
            $Rapport = $form['rapport']->getData();

            $Intervention->setName($Name);
            $Intervention->setRapport($Rapport);
            $Intervention->setArticle($Article);
            $Article->setIntervention($Intervention);

  

            $em=$this->getDoctrine()->getManager();
            $em->persist($Intervention);
            $em->flush();
            $this->addFlash(
                'notice',
                "l'Intervention est bien générer");
            return $this->redirectToRoute('Maintenance');
        }
        return $this->render('Maintenance/intervention.html.twig',array('article'=>$Article,'form' => $form->createView()));
    }

    /**
     * @Route("/Maintenance/delete/intervention/{id}", name="Intervention_delete")
     */
    public function deleteIntervention($id)
    {
        $em = $this->getDoctrine()->getManager();
        $Article= $this->getDoctrine()
        ->getRepository('AppBundle:Article')
        ->find($id);
        $Intervention= $this->getDoctrine()
        ->getRepository('AppBundle:Intervention')
        ->find($Article->getIntervention()->getId());
        $Article->setIntervention(null);

        $em->remove($Intervention);
        $em->flush();
        $this->addFlash(
            'notice',
            "l'Intervention est bien supprimer");
        return $this->redirectToRoute('Maintenance');

    }
   /**
     * @Route("/Maintenance/intervention/details/{id}", name="Intervention_details")
     */
    public function detailsIntervention($id,Request $request){

        $Intervention= $this->getDoctrine()
        ->getRepository('AppBundle:Intervention')
        ->find($id);
        $Article=$this->getDoctrine()
        ->getRepository('AppBundle:Article')
        ->find($Intervention->getArticle()->getId());

        $form = $this->createFormBuilder($Intervention)
        ->add('rapport', TextareaType::class,array('attr' =>array('class'=>'form-control','style'=>'margin-bottom:15px')))
        ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //die("the form is submitted");
            $Name = $_POST['name'];
            $Rapport = $form['rapport']->getData();

            $Intervention->setName($Name);
            $Intervention->setRapport($Rapport);
            $Intervention->setArticle($Article);

  

            $em=$this->getDoctrine()->getManager();
            $em->persist($Intervention);
            $em->flush();
            $this->addFlash(
                'notice',
                "l'Intervention est bien générer");
            return $this->redirectToRoute('Maintenance');
        }
        return $this->render('Maintenance/intervention.html.twig',array('article'=>$Article,'form' => $form->createView()));

    }


}
?>