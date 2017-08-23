<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Article;
use AppBundle\Entity\Categorie;
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

        $Hopitales = $this->getDoctrine()
        ->getRepository('AppBundle:Hopitale')
        ->findAll();
        $session = new Session();
        $session->set('categorie', '0');
        $session->set('intervention', '0');

        if (!empty($_POST)) {
            //get the data
            $CategorieName= $_POST['categorie'];
            $intervention= $_POST['intervention'];
            $session->set('categorie', $CategorieName);
            $session->set('intervention', $intervention);


            if($CategorieName!='0'){
                $Categorie = $this->getDoctrine()
                ->getRepository('AppBundle:Categorie')
                ->findOneBy(
                    array('name' =>$CategorieName)
                );
                $ArticlesCategorie=$Categorie->getArticles();
                $Articles=$ArticlesCategorie;
            }
            $articlePreventive=array();
            $articlecurative=array();
            foreach($Articles as $key => $value) {
                $currentdate=new \DateTime('now');
                if($value->getDateService()->diff($currentdate)->y >=4 || $value->getEtat()=='En Panne'){
                    $articlecurative[]=$value;
                }else{
                    $articlePreventive[]=$value;
                }
            }
            if($intervention=='1'){
                $Articles=$articlePreventive;
            }
            if($intervention=='2'){
                $Articles=$articlecurative;
            }



            return $this->render('Maintenance/Maintenance.html.twig',array('articles' => $Articles,'categories'=>$Categories,'session'=>$session ));
        }


        return $this->render('Maintenance/Maintenance.html.twig',array('articles' => $Articles,'categories'=>$Categories,'session'=>$session));
    }


}
?>