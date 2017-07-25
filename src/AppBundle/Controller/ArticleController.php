<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Article;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ArticleController extends Controller
{
    /**
     * @Route("/", name="article_liste")
     */
    public function listAction()
    {
        $Articles = $this->getDoctrine()
        ->getRepository('AppBundle:Article')
        ->findAll();

        return $this->render('article/index.html.twig',array('articles' => $Articles));
    }
        /**
     * @Route("/article/create", name="article_create")
     */
    public function createAction(Request $request)
    {
        $Article=new Article;
        $form = $this->createFormBuilder($Article)
        ->add('Lieu', TextType::class,array('attr' =>array('class'=>'form-control','style'=>'margin-bottom:15px')))
        ->add('Designation', TextareaType::class,array('attr' =>array('class'=>'form-control','style'=>'margin-bottom:15px')))
        ->add('Marque', TextType::class,array('attr' =>array('class'=>'form-control','style'=>'margin-bottom:15px')))
        ->add('Modele', TextType::class,array('attr' =>array('class'=>'form-control','style'=>'margin-bottom:15px')))
        ->add('societe', TextType::class,array('attr' =>array('class'=>'form-control','style'=>'margin-bottom:15px')))
        ->add('DateService', DateType::class,array('attr' =>array('class'=>'formcontrol','style'=>'margin-bottom:15px')))

        ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //die("the form is submitted");
            $Lieu = $form['Lieu']->getData();
            $Designation = $form['Designation']->getData();
            $Marque = $form['Marque']->getData();
            $Modele = $form['Modele']->getData();
            $societe = $form['societe']->getData();
            $DateService = $form['DateService']->getData();

            $Article->setLieu($Lieu);
            $Article->setDesignation($Designation);
            $Article->setMarque($Marque);
            $Article->setModele($Modele);
            $Article->setSociete($societe);
            $Article->setDateService($DateService);
            $Article->setNumSerie('2656565');  

            $em=$this->getDoctrine()->getManager();
            $em->persist($Article);
            $em->flush();
            $this->addFlash(
                'notice',
                "l'article est bien ajouter");
            return $this->redirectToRoute('article_liste');
        }

        return $this->render('article/create.html.twig',array('form'=>$form->createView()));
    }
        /**
     * @Route("/article/edit/{id}", name="article_edit")
     */
    public function editAction($id,Request $request)
    {
        $Article = $this->getDoctrine()
        ->getRepository('AppBundle:Article')
        ->find($id);

        $form = $this->createFormBuilder($Article)
        ->add('Lieu', TextType::class,array('attr' =>array('class'=>'form-control','style'=>'margin-bottom:15px')))
        ->add('Designation', TextareaType::class,array('attr' =>array('class'=>'form-control','style'=>'margin-bottom:15px')))
        ->add('Marque', TextType::class,array('attr' =>array('class'=>'form-control','style'=>'margin-bottom:15px')))
        ->add('Modele', TextType::class,array('attr' =>array('class'=>'form-control','style'=>'margin-bottom:15px')))
        ->add('societe', TextType::class,array('attr' =>array('class'=>'form-control','style'=>'margin-bottom:15px')))
        ->add('DateService', DateType::class,array('attr' =>array('class'=>'formcontrol','style'=>'margin-bottom:15px')))

        ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //get the data
            $Lieu = $form['Lieu']->getData();
            $Designation = $form['Designation']->getData();
            $Marque = $form['Marque']->getData();
            $Modele = $form['Modele']->getData();
            $societe = $form['societe']->getData();
            $DateService = $form['DateService']->getData();

            $em=$this->getDoctrine()->getManager();
            $Article=$em->getRepository('AppBundle:Article')->find($id);

            $Article->setLieu($Lieu);
            $Article->setDesignation($Designation);
            $Article->setMarque($Marque);
            $Article->setModele($Modele);
            $Article->setSociete($societe);
            $Article->setDateService($DateService);
            $Article->setNumSerie('2656565');  

            $em->flush();
            $this->addFlash(
                'notice',
                "l'article est bien modifier");
            return $this->redirectToRoute('article_liste');
        }

        return $this->render('article/edit.html.twig',
            array('form' =>$form->createView()));
    }
        /**
     * @Route("/article/details/{id}", name="article_details")
     */
    public function detailsAction($id)
    {
        $Article = $this->getDoctrine()
        ->getRepository('AppBundle:Article')
        ->find($id);

        return $this->render('article/details.html.twig',array('article' => $Article));
    }

            /**
     * @Route("/article/delete/{id}", name="article_delete")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $Article=$em->getRepository('AppBundle:Article')->find($id);

        $em->remove($Article);
        $em->flush();
        $em->flush();
        $this->addFlash(
            'notice',
            "l'article est bien supprimer");
        return $this->redirectToRoute('article_liste');

    }
}
?>