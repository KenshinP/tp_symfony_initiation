<?php

namespace App\Controller;

use App\Entity\Employes;
use App\Form\EmployesFormType;
use App\Repository\EmployesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EmployesController extends AbstractController
{
    /**
     * @Route("/employes", name="app_employes")
     */
    public function index(): Response
    {
        return $this->render('employes/index.html.twig', [
            'controller_name' => 'EmployesController',
        ]);
    }

    /**
     * @Route("/employeslist", name="employes_list")
     */
    public function employesList(EmployesRepository $employesRepository){
        $employes = $employesRepository->findAll();

        return $this->render("employes/employes_list.html.twig", ['employes' => $employes]);

    }

    /**
     * @Route("/update/employe/{id}", name="update_employe")
     */
    public function updateEmploye(EmployesRepository $employesRepository,EntityManagerInterface $entityManagerInterface, $id, Request $request){
        $employe = $employesRepository->find($id);

        $employeForm = $this->createForm(EmployesFormType::class,$employe);

        $employeForm->handleRequest($request);

        if($employeForm->isSubmitted() && $employeForm->isValid()){
            $entityManagerInterface->persist($employe);
            $entityManagerInterface->flush();

            return $this->redirectToRoute('employes_list');
        }

        return $this->render("employes/employes_form.html.twig", ['employeForm' =>$employeForm->createView()] );

    }

    /**
     * @Route("/create/employe", name="create_employe")
     */
    public function createEmploye(EntityManagerInterface $entityManagerInterface,Request $request){
        $employe = new Employes();

        $employeForm = $this->createForm(EmployesFormType::class,$employe);
        $employeForm->handleRequest($request);
        if($employeForm->isSubmitted() && $employeForm->isValid()){
            $entityManagerInterface->persist($employe);
            $entityManagerInterface->flush();

            return $this->redirectToRoute('employes_list');
        }
        return $this->render('employes/employes_form.html.twig',['employeForm' =>$employeForm->createView()]);
    }

    /**
     * @Route("delete/employe/{id}", name="delete_employe")
     */
    public function deleteEmploye($id,EntityManagerInterface $entityManagerInterface, EmployesRepository $employesRepository){
        $employe = $employesRepository->find($id);

        $entityManagerInterface->remove($employe);
        $entityManagerInterface->flush();

        return $this->redirectToRoute('employes_list');
    }

    
}
