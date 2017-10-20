<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Article;
use AppBundle\Entity\Categorie;
use AppBundle\Entity\Service;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ServiceController extends Controller
{
	   /**
     * @Route("/Service/create", name="Service_create")
     */
    public function createAction(Request $request)
    {
        $Service=new Service;
        $form = $this->createFormBuilder($Service)
        ->add('nom', TextType::class,array('attr' =>array('class'=>'form-control','style'=>'margin-bottom:15px')))
        ->add('num', TextType::class,array('attr' =>array('class'=>'form-control','style'=>'margin-bottom:15px')))
        ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //die("the form is submitted");
            $Name = $form['nom']->getData();
            $num = $form['num']->getData();

            $Service->setNom($Name);
            $Service->setNum($num);

  

            $em=$this->getDoctrine()->getManager();
            $em->persist($Service);
            $em->flush();
            $this->addFlash(
                'notice',
                "l'Service est bien ajouter");
            return $this->redirectToRoute('Service_liste');
        }

        return $this->render('service/add.html.twig',array('form'=>$form->createView()));
    }
    /**
     * @Route("/Service/list", name="Service_liste")
     */
    public function listAction()
    {
        $Service = $this->getDoctrine()
        ->getRepository('AppBundle:Service')
        ->findAll();

        return $this->render('service/list.html.twig',array('Services' => $Service));
    }

    /**
     * @Route("Service/edit/{id}", name="Service_edit")
     */
    public function editAction($id,Request $request)
    {
        $Service = $this->getDoctrine()
        ->getRepository('AppBundle:Service')
        ->find($id);
     
        $form = $this->createFormBuilder($Service)
        ->add('nom', TextType::class,array('attr' =>array('class'=>'form-control','style'=>'margin-bottom:15px')))
        ->add('num', TextType::class,array('attr' =>array('class'=>'form-control','style'=>'margin-bottom:15px')))


        ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //get the data
            $nom = $form['nom']->getData();
            $num = $form['num']->getData();


            $em=$this->getDoctrine()->getManager();
            $Service=$em->getRepository('AppBundle:Service')->find($id);

            $Service->setNom($nom);
            $Service->setNum($num); 

            $em->flush();
            $this->addFlash(
                'notice',
                "le Service est bien modifier");
            return $this->redirectToRoute('Service_liste');
        }

        return $this->render('service/edit.html.twig',
            array('form' =>$form->createView()));
    }

    /**
     * @Route("/Service/delete/{id}", name="Service_delete")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $Service=$em->getRepository('AppBundle:Service')->find($id);

        if(count($Service->getArticles())==0){
            $em->remove($Service);
            $em->flush();
            $this->addFlash(
                'notice',
                "la categorie est bien supprimer");
            return $this->redirectToRoute('categorie_liste');
        }else{
                $this->addFlash(
                'erreur',
                "le service selectionner contient des articles");
            return $this->redirectToRoute('Service_liste');

        }

    }

   
}
?>