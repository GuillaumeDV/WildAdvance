<?php

namespace App\Controller;

use App\Entity\Argonaute;
use App\Form\ArgonauteType;
use App\Repository\ArgonauteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(EntityManagerInterface $em, Request $request): Response
    {
        //appel de la bdd pour la vue via une variable
        $argonautes = $this->getDoctrine()
            ->getRepository(Argonaute::class)
            ->findAll();

        //mise en place du form et sauvegarde de données
        $argonaute = new Argonaute();
        $form = $this->createForm(ArgonauteType::class, $argonaute);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($argonaute);
            $em->flush();
            return $this->redirectToRoute('home');
        }
        

        return $this->render('home/index.html.twig', [
            'form' => $form->createView(),
            'controller_name' => 'HomeController',
            'argonautes' => $argonautes,
        ]);
    }
}
