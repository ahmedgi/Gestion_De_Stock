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
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\StreamedResponse;


class ArticleController extends Controller
{
    /**
     * @Route("/article", name="article_liste")
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
        $Categories = $this->getDoctrine()
        ->getRepository('AppBundle:Categorie')
        ->findAll();

        $Services = $this->getDoctrine()
        ->getRepository('AppBundle:Service')
        ->findAll();

        $Hopitales = $this->getDoctrine()
        ->getRepository('AppBundle:Hopitale')
        ->findAll();
        $form = $this->createFormBuilder($Article)
        ->add('Designation', TextType::class,array('attr' =>array('class'=>'form-control','style'=>'margin-bottom:15px')))
        ->add('Marque', TextType::class,array('attr' =>array('class'=>'form-control','style'=>'margin-bottom:15px')))
        ->add('Modele', TextType::class,array('attr' =>array('class'=>'form-control','style'=>'margin-bottom:15px')))
        ->add('societe', TextType::class,array('attr' =>array('class'=>'form-control','style'=>'margin-bottom:15px')))
        ->add('BonLivraison', FileType::class, array('label' => 'BonLivraison (PDF file)','attr'=>array('accept'=>'application/pdf')))
        ->add('DateService', DateType::class,array('attr' =>array('class'=>'formcontrol','style'=>'margin-bottom:15px')))
        ->add('Date_Acquisition', DateType::class,array('attr' =>array('class'=>'formcontrol','style'=>'margin-bottom:15px')))
        ->add('Num_Inventaire', TextType::class,array('attr' =>array('class'=>'form-control','style'=>'margin-bottom:15px')))
        ->add('NumSerie', TextType::class,array('attr' =>array('class'=>'form-control','style'=>'margin-bottom:15px')))
        ->add('Source_de_Financement', TextType::class,array('attr' =>array('class'=>'form-control','style'=>'margin-bottom:15px')))
        ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //die("the form is submitted");
            $Designation = $form['Designation']->getData();
            $Marque = $form['Marque']->getData();
            $Modele = $form['Modele']->getData();
            $societe = $form['societe']->getData();
            $DateService = $form['DateService']->getData();
            $CategorieName= $_POST['Categorie'];
            $ServiceName= $_POST['Service'];
            $LieuName= $_POST['Lieu'];
            $Etat = $_POST['etat'];
            $Date_Acquisition = $form['Date_Acquisition']->getData();
            $Num_Inventaire = $form['Num_Inventaire']->getData();
            $NumSerie = $form['NumSerie']->getData();
            $Source_de_Financement = $form['Source_de_Financement']->getData();
             // $file stores the uploaded PDF file
            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = $Article->getBonLivraison();

            // Generate a unique name for the file before saving it
            $fileName = md5(uniqid()).'.'.$file->guessExtension();

            // Move the file to the directory where brochures are stored
            $file->move(
                $this->getParameter('Articles_directory'),
                $fileName
            );
            // get the categorie
            $Categorie = $this->getDoctrine()
            ->getRepository('AppBundle:Categorie')
            ->findOneBy(
                array('name' =>$CategorieName)
            );
            $Service = $this->getDoctrine()
            ->getRepository('AppBundle:Service')
            ->findOneBy(
                array('nom' =>$ServiceName)
            );
            $Hopitale = $this->getDoctrine()
            ->getRepository('AppBundle:Hopitale')
            ->findOneBy(
                array('name' =>$LieuName)
            );

            $Article->setCategorie($Categorie);
            $Article->setService($Service);
            $Article->sethopitale($Hopitale);
            // Update the 'brochure' property to store the PDF file name
            // instead of its contents
            $Article->setBonLivraison($fileName);

            $Article->setDesignation($Designation);
            $Article->setMarque($Marque);
            $Article->setModele($Modele);
            $Article->setSociete($societe);
            $Article->setDateService($DateService);
            $Article->setNumSerie($NumSerie);  
            $Article->setEtat($Etat);
            $Article->setDateAcquisition($Date_Acquisition);
            $Article->setNumInventaire($Num_Inventaire);
            $Article->setSourceDeFinancement($Source_de_Financement);

            $em=$this->getDoctrine()->getManager();
            $em->persist($Article);
            $em->flush();
            $this->addFlash(
                'notice',
                "l'article est bien ajouter");
            return $this->redirectToRoute('article_liste');
        }

        return $this->render('article/create.html.twig',array('form'=>$form->createView(),"Categories"=>$Categories,
            "Services"=>$Services,"Hopitales"=>$Hopitales));
    }
    /**
     * @Route("/article/edit/{id}", name="article_edit")
     */
    public function editAction($id,Request $request)
    {
        $Article = $this->getDoctrine()
        ->getRepository('AppBundle:Article')
        ->find($id);
        $Categories = $this->getDoctrine()
        ->getRepository('AppBundle:Categorie')
        ->findAll();

        $Services = $this->getDoctrine()
        ->getRepository('AppBundle:Service')
        ->findAll();

        $Hopitales = $this->getDoctrine()
        ->getRepository('AppBundle:Hopitale')
        ->findAll();
        $filename=new File($this->getParameter('Articles_directory').'/'.$Article->getBonLivraison());
        $Article->setBonLivraison(
            new File($this->getParameter('Articles_directory').'/'.$Article->getBonLivraison())
        );
        $form = $this->createFormBuilder($Article)
        ->add('Designation', TextType::class,array('attr' =>array('class'=>'form-control','style'=>'margin-bottom:15px')))
        ->add('Marque', TextType::class,array('attr' =>array('class'=>'form-control','style'=>'margin-bottom:15px')))
        ->add('Modele', TextType::class,array('attr' =>array('class'=>'form-control','style'=>'margin-bottom:15px')))
        ->add('societe', TextType::class,array('attr' =>array('class'=>'form-control','style'=>'margin-bottom:15px')))
        ->add('DateService', DateType::class,array('attr' =>array('class'=>'formcontrol','style'=>'margin-bottom:15px')))
        ->add('Num_Inventaire', TextType::class,array('attr' =>array('class'=>'form-control','style'=>'margin-bottom:15px')))
        ->add('Date_Acquisition', DateType::class,array('attr' =>array('class'=>'formcontrol','style'=>'margin-bottom:15px')))
        ->add('Source_de_Financement', TextType::class,array('attr' =>array('class'=>'form-control','style'=>'margin-bottom:15px')))
         ->add('NumSerie', TextType::class,array('attr' =>array('class'=>'form-control','style'=>'margin-bottom:15px')))
        ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //get the data
            $Designation = $form['Designation']->getData();
            $Marque = $form['Marque']->getData();
            $Modele = $form['Modele']->getData();
            $societe = $form['societe']->getData();
            $DateService = $form['DateService']->getData();
            $CategorieName= $_POST['Categorie'];
            $ServiceName= $_POST['Service'];
            $LieuName= $_POST['Lieu'];
            $Etat = $_POST['etat'];
            $Date_Acquisition = $form['Date_Acquisition']->getData();
            $Num_Inventaire = $form['Num_Inventaire']->getData();
            $NumSerie = $form['NumSerie']->getData();
            $Source_de_Financement = $form['Source_de_Financement']->getData();

            // get the categorie
            $Categorie = $this->getDoctrine()
            ->getRepository('AppBundle:Categorie')
            ->findOneBy(
                array('name' =>$CategorieName)
            );
            $Service = $this->getDoctrine()
            ->getRepository('AppBundle:Service')
            ->findOneBy(
                array('nom' =>$ServiceName)
            );
            $Hopitale = $this->getDoctrine()
            ->getRepository('AppBundle:Hopitale')
            ->findOneBy(
                array('name' =>$LieuName)
            );


            $em=$this->getDoctrine()->getManager();
            $Article=$em->getRepository('AppBundle:Article')->find($id);

            $Article->setCategorie($Categorie);
            $Article->setService($Service);
            $Article->sethopitale($Hopitale);
            $Article->setBonLivraison(basename($filename));

            $Article->setDesignation($Designation);
            $Article->setMarque($Marque);
            $Article->setModele($Modele);
            $Article->setSociete($societe);
            $Article->setDateService($DateService);
            $Article->setNumSerie($NumSerie);  
            $Article->setEtat($Etat);
            $Article->setDateAcquisition($Date_Acquisition);
            $Article->setNumInventaire($Num_Inventaire);
            $Article->setSourceDeFinancement($Source_de_Financement); 

            $em->flush();
            $this->addFlash(
                'notice',
                "l'article est bien modifier");

            return $this->redirectToRoute('article_liste');
        }

        return $this->render('article/edit.html.twig',
            array('form' =>$form->createView(),"article"=>$Article,"Categories"=>$Categories,"Services"=>$Services,"Hopitales"=>$Hopitales));
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
        $this->addFlash(
            'notice',
            "l'article est bien supprimer");
        return $this->redirectToRoute('article_liste');

    }
    /**
     * @Route("/article/generate/{id}", name="article_generate")
     */
    public function generateAction($id)
    {
        // get the service
        $TBS = $this->get('opentbs');
        // load your template
        $TBS->LoadTemplate($directoryPath = $this->container->getParameter('kernel.root_dir') . '/../web/Templates/BonDecharge.docx');
        // replace variables
        $TBS->MergeField('client', array('name' => 'Belhadj Ahmed'));
        // send the file
        $TBS->Show(OPENTBS_DOWNLOAD, 'bson_décharge.docx');
        $this->addFlash(
            'notice',
            "l'article est bien generer");
        return $this->redirectToRoute('article_details');

    }
    /**
     * @Route("/article/import", name="artricle_import")
     */
    public function importAction(Request $request)
    {
        $form = $this->createFormBuilder()
        ->add('submitFile', FileType::class, array('label' => 'submitFile (PDF file)','attr'=>array('class'=>'form-control','style'=>'margin-bottom:15px')))
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
                                    "Lieu" => $data[0],
                                    "Designation" => $data[1],
                                    "Marque" => $data[2],
                                    "Modele" => $data[3],
                                    "societe" => $data[4],
                                    "NumSerie" => $data[5],
                            );
                        }
                    }
                    fclose($handle); 
                    
                }

                $em = $this->getDoctrine()->getManager(); // EntityManager pour la base de données
                
                // Lecture du tableau contenant les utilisateurs et ajout dans la base de données
                foreach ($utilisateurs as $utilisateur) {
                    
                    // On crée un objet utilisateur
                    $Article = new Article();        
                    // Hydrate l'objet avec les informations provenants du fichier CSV
                    $Article->setLieu($utilisateur["Lieu"]);
                    $Article->setDesignation($utilisateur["Designation"]);
                    $Article->setMarque($utilisateur["Marque"]);
                    $Article->setModele($utilisateur["Modele"]);
                    $Article->setSociete($utilisateur["societe"]);
                    $Article->setNumSerie($utilisateur["NumSerie"]);
                    $date=new \dateTime('now');
                    $Article->setDateService($date);
                    // Enregistrement de l'objet en vu de son écriture dans la base de données
                    $em->persist($Article);
                    
                }
                
                // Ecriture dans la base de données
                $em->flush();
                $this->addFlash(
                'notice',
                "les Articles sont bien ajouter");
                return $this->redirectToRoute('article_liste');

         }

        return $this->render('/article/import.html.twig',
            array('form' => $form->createView(),)
        );
    }
    /**
     * @Route("/article/generatetemplate/csv", name="csvarticle_generate")
     */
    public function generatetarticlecsvAction()
    {

        $response = new StreamedResponse();
        $response->setCallback(function() {
            $handle = fopen('php://output', 'r+');
            fputcsv($handle, array('Lieu', 'Designation','Marque','Modele'),';');

            fclose($handle);
        });

        $response->setStatusCode(200);
        $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="Articles.csv"');

        return $response;

    }

}
?>