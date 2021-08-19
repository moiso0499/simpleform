<?php

namespace App\Controller;

use App\Entity\Cliente;
use App\Form\ClienteType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ClientController extends AbstractController
{
    /**
     * @Route("/", name="client")
     */
    public function index(Request $request, ValidatorInterface $validator): Response
    {
        $cliente = new Cliente();
        $form = $this->createForm(ClienteType::class, $cliente);
        $form->handleRequest($request);
        if($form->isSubmitted()){   

            $errors = $validator->validate($cliente);
            if (count($errors) > 0) {
                $errorsString = (string) $errors;
                $this->addFlash('danger', $errorsString);
            } else {
                $em = $this->getDoctrine()->getManager();
                $em->persist($cliente);
                $em->flush();
                $this->addFlash('success', 'El cliente fue creado'.$cliente->toString());
            }
        }
        return $this->render('client/create.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
