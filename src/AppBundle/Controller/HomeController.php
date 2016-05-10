<?php
/**
 * Created by PhpStorm.
 * User: Geert
 * Date: 2/05/2016
 * Time: 13:46
 */

namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        return $this->render('homepage/home.html.twig', array(
            'user' => $this->getUser()
        ));
    }

    /**
     * @return Response
     * @Route("/admin", name="admin")
     */
    public function adminAction()
    {
        //$roles = $this->get('security.role_hierarchy');
        $user = $this->getUser();

        // yay! Use this to see if the user is logged in
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            //throw $this->createAccessDeniedException();
            return $this->redirectToRoute('login', array('user' => $user));
        }


        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('login', array('user' => $user));
        }
        
        return $this->render('admin/admin.html.twig', array(
            'user' => $user,
        ));
    }


}