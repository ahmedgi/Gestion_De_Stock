<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Article;
use AppBundle\Entity\Hopitale;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\StreamedResponse;

class HopitaleController extends Controller
{
	   /**
     * @Route("/Hopitale/create", name="Hopitale_create")
     */
    public function createAction(Request $request)
    {
        $Hopitale=new Hopitale;
        $form = $this->createFormBuilder($Hopitale)
        ->add('name', TextType::class,array('attr' =>array('class'=>'form-control','style'=>'margin-bottom:15px')))
        ->add('adresse', TextType::class,array('attr' =>array('class'=>'form-control','style'=>'margin-bottom:15px')))
        ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //die("the form is submitted");
            $Name = $form['name']->getData();
            $addresse = $form['adresse']->getData();

            $Hopitale->setName($Name);
            $Hopitale->setAdresse($addresse);

  

            $em=$this->getDoctrine()->getManager();
            $em->persist($Hopitale);
            $em->flush();
            $this->addFlash(
                'notice',
                "l'hopitale est bien ajouter");
            return $this->redirectToRoute('Hopitale_liste');
        }

        return $this->render('hopitale/add.html.twig',array('form'=>$form->createView()));
    }
    /**
     * @Route("/Hopitale/list", name="Hopitale_liste")
     */
    public function listAction()
    {
        $Hopitale = $this->getDoctrine()
        ->getRepository('AppBundle:Hopitale')
        ->findAll();

        return $this->render('hopitale/list.html.twig',array('hopitales' => $Hopitale));
    }

     /**
     * @Route("Hopitale/edit/{id}", name="Hopitale_edit")
     */
    public function editHopitaleAction($id,Request $request)
    {
        $Hopitale = $this->getDoctrine()
        ->getRepository('AppBundle:Hopitale')
        ->find($id);
     
        $form = $this->createFormBuilder($Hopitale)
        ->add('Name', TextType::class,array('attr' =>array('class'=>'form-control','style'=>'margin-bottom:15px')))
        ->add('adresse', TextType::class,array('attr' =>array('class'=>'form-control','style'=>'margin-bottom:15px')))


        ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //get the data
            $Name = $form['Name']->getData();
            $addresse = $form['adresse']->getData();


            $em=$this->getDoctrine()->getManager();
            $Hopitale=$em->getRepository('AppBundle:Hopitale')->find($id);

            $Hopitale->setName($Name);
            $Hopitale->setAdresse($addresse); 

            $em->flush();
            $this->addFlash(
                'notice',
                "l' Hopitale est bien modifier");
            return $this->redirectToRoute('Hopitale_liste');
        }

        return $this->render('hopitale/edit.html.twig',
            array('form' =>$form->createView()));
    }

    /**
     * @Route("/Hopitale/delete/{id}", name="Hopitale_delete")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $Hopitale=$em->getRepository('AppBundle:Hopitale')->find($id);

        if(count($Hopitale->getArticles())==0 && count($Hopitale->getServices())==0){
            $em->remove($Hopitale);
            $em->flush();
            $this->addFlash(
                'notice',
                "l'Hopitale est bien supprimer");
            return $this->redirectToRoute('Hopitale_liste');
        }else{
                $this->addFlash(
                'erreur',
                "l'Hopitale selectionner contient des Service ou bien est associer a une article");
            return $this->redirectToRoute('Hopitale_liste');

        }

    }

   
}
?>