<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;



class SecurityController extends Controller
{
    /**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request)
    {
       $helper = $this->get('security.authentication_utils');

       return $this->render(
           'auth/login.html.twig',
           array(
               'last_username' => $helper->getLastUsername(),
               'error'         => $helper->getLastAuthenticationError(),
           )
       );
    }

    /**
     * @Route("/login_check", name="security_login_check")
     */
    public function loginCheckAction()
    {

    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction()
    {

    }

    /**
     * @Route("/profile", name="profile")
     */
    public function profileAction(Request $request,UserPasswordEncoderInterface $encoder)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $user = $this->get('security.token_storage')->getToken()->getUser();
        $entity = $em->getRepository('AppBundle:User')->find($user->getId());
        // $user === $entity => true
 


        if ($request->getMethod() === 'POST')
        {
            if(isset($_POST['updateprofile'])){
                $firstname= $_POST['first_name'];
                $lastname= $_POST['last_name'];
                $tel= $_POST['tel'];
                $entity->setNom($lastname);
                $entity->setPrenom($firstname);
                $entity->setTel($tel);
            }
            if(isset($_POST['updatepassword'])){
                $plainPassword= $_POST['old_password'];
                $Newpassword= $_POST['Npassword'];
                $Confirmpassword= $_POST['Ncpassword'];
                if($encoder->isPasswordValid($entity, $plainPassword)){
                    if($Newpassword==$Confirmpassword){
                        $encoded = $encoder->encodePassword($entity, $Newpassword);
                        $entity->setPassword($encoded);
                    }else{
                        $this->addFlash(
                        'erreur',
                        "passwords do not match");
                    }
                }else{
                    $this->addFlash(
                        'erreur',
                        'the old password is incorrect');
                }
            }

                $em->persist($entity);
                $em->flush();

                return $this->redirect($this->generateUrl('profile'));
        }

        return $this->render('auth/profil.html.twig', array(
            'entity'      => $entity,
            //'form'   => $form->createView(),
        ));
    }
}