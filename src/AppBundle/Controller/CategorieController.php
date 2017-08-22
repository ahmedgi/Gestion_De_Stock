<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Article;
use AppBundle\Entity\Categorie;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CategorieController extends Controller
{
       /**
     * @Route("/categorie/create", name="categorie_create")
     */
    public function createAction(Request $request)
    {
        $Categorie=new Categorie;
        $form = $this->createFormBuilder($Categorie)
        ->add('Name', TextType::class,array('attr' =>array('class'=>'form-control','style'=>'margin-bottom:15px')))
        ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //die("the form is submitted");
            $Name = $form['Name']->getData();
            $date=new \dateTime('now');

            $Categorie->setName($Name);
            $Categorie->setCreationDate($date);

  

            $em=$this->getDoctrine()->getManager();
            $em->persist($Categorie);
            $em->flush();
            $this->addFlash(
                'notice',
                "la categorie est bien ajouter");
            return $this->redirectToRoute('categorie_liste');
        }

        return $this->render('Categorie/add.html.twig',array('form'=>$form->createView()));
    }
    /**
     * @Route("/categorie/list", name="categorie_liste")
     */
    public function listAction()
    {
        $Categorie = $this->getDoctrine()
        ->getRepository('AppBundle:Categorie')
        ->findAll();

        return $this->render('Categorie/list.html.twig',array('categories' => $Categorie));
    }

     /**
     * @Route("categorie/edit/{id}", name="Categorie_edit")
     */
    public function editAction($id,Request $request)
    {
        $Categorie = $this->getDoctrine()
        ->getRepository('AppBundle:Categorie')
        ->find($id);
     
        $form = $this->createFormBuilder($Categorie)
        ->add('Name', TextType::class,array('attr' =>array('class'=>'form-control','style'=>'margin-bottom:15px')))


        ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //get the data
            $Name = $form['Name']->getData();


            $em=$this->getDoctrine()->getManager();
            $Categorie=$em->getRepository('AppBundle:Categorie')->find($id);

            $Categorie->setName($Name); 

            $em->flush();
            $this->addFlash(
                'notice',
                "la Categorie est bien modifier");
            return $this->redirectToRoute('categorie_liste');
        }

        return $this->render('Categorie/edit.html.twig',
            array('form' =>$form->createView()));
    }


    /**
     * @Route("/categorie/delete/{id}", name="categorie_delete")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $Categorie=$em->getRepository('AppBundle:Categorie')->find($id);

        $em->remove($Categorie);
        $em->flush();
        $em->flush();
        $this->addFlash(
            'notice',
            "la categorie est bien supprimer");
        return $this->redirectToRoute('categorie_liste');

    }

    /**
     * @Route("categorie/import", name="Categorie_import")
     */
    public function importAction(Request $request)
    {
        $form = $this->createFormBuilder()
        ->add('submitFile', FileType::class, array('label' => 'Upload file','attr'=>array('class'=>'form-control','style'=>'margin-bottom:15px')))
        ->getForm();

        // Check if we are posting stuff
       $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

                 // Get file
                $file = $form->get('submitFile');

                 // Your csv file here when you hit submit button
                $filename=$file->getData();
                $utilisateurs = array(); // Tableau qui va contenir les éléments extraits du fichier CSV
                $row = 0; // Représente la ligne
                // Import du fichier CSV 
                if (($handle = fopen($filename, "r")) !== FALSE) { // Lecture du fichier, à adapter
                    while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) { // Eléments séparés par un point-virgule, à modifier si necessaire
                        $num = count($data); // Nombre d'éléments sur la ligne traitée
                        $row++;
                        for ($c = 0; $c < $num; $c++) {
                            $utilisateurs[$row] = array(
                                    "Name" => $data[0],
                            );
                        }
                    }
                    fclose($handle); 
                    
                }

                $em = $this->getDoctrine()->getManager(); // EntityManager pour la base de données
                
                // Lecture du tableau contenant les utilisateurs et ajout dans la base de données
                foreach ($utilisateurs as $utilisateur) {
                    
                    // On crée un objet utilisateur
                    $Categorie = new Categorie();        
                    // Hydrate l'objet avec les informations provenants du fichier CSV
                    $Categorie->setName($utilisateur["Name"]);
                    $date=new \dateTime('now');
                    $Categorie->setCreationDate($date);
                    // Enregistrement de l'objet en vu de son écriture dans la base de données
                    $em->persist($Categorie);
                    
                }
                
                // Ecriture dans la base de données
                $em->flush();
                $this->addFlash(
                'notice',
                "les Categories sont bien ajouter");
                return $this->redirectToRoute('categorie_liste');

         }

        return $this->render('/Categorie/import.html.twig',
            array('form' => $form->createView(),)
        );
    }
    /**
     * @Route("/categorie/generate/csv", name="csv_generate")
     */
    public function generatecsvcategorieAction()
    {

        $response = new StreamedResponse();
        $response->setCallback(function() {
            $handle = fopen('php://output', 'w+');
            fputcsv($handle, array('NameCategorie', 'code'),';');
            fclose($handle);
        });

        $response->setStatusCode(200);
        $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment; filename=categories.csv');

        return $response;

    }


}
?>