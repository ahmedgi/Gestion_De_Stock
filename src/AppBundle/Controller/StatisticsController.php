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
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\Session\Session;


class StatisticsController extends Controller
{
    /**
     * @Route("/Statistique", name="Statistique")
     */
    public function statisticAction()
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

            return $this->render('statistique/statistique.html.twig',array('articles' => $Articles,'services'=>$Services,'categories'=>$Categories,'session'=>$session ));
        }


        return $this->render('statistique/statistique.html.twig',array('articles' => $Articles,'services'=>$Services,'categories'=>$Categories,'session'=>$session));
    }


}
?>