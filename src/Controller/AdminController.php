<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Form\AddPatientType;



class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index( EntityManagerInterface $entityManager): Response
    {        
        return $this->render('admin/index.html.twig', [
            'user' => $this->getUser(),
        ]);
    }

    #[Route('/admin/add/patient', name: 'app_admin_add_patient')]
    public function addPatients(UserPasswordHasherInterface $userPasswordHasher, Security $security, Request $request, EntityManagerInterface $entityManager): Response
    {

        $patient = new User();       
        $orth = $this->getUser();
        $orth->addPatient($patient);
        $form = $this->createForm(AddPatientType::class, $patient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            try {
                $roles = $patient->getRoles(); 
                if (!in_array('ROLE_USER', $roles, true)) {
                    $roles[] = 'ROLE_USER'; 
                }
                $patient->setRoles($roles);

                $plainPassword = $form->get('plainPassword')->getData();
                $user = $form->getData();
                $user->setPassword($userPasswordHasher->hashPassword($patient, $plainPassword));  

                $entityManager->persist($user);
                $entityManager->flush();

                // ... perform some action, such as saving the task to the database

                return $this->redirectToRoute('app_admin');  
            
            } catch (\Exception $e) {

            $this->addFlash('error', 'An error occurred while adding the patient : ' . $e->getMessage());
        }

    }

        return $this->render('admin/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/admin/remove/{id}', name: 'app_admin_remove')]
    public function removePatient(User $patient, EntityManagerInterface $entityManager): Response
    {
        $orth = $this->getUser();

        $orth->removePatient($patient);

        $entityManager->remove($patient);
        $entityManager->flush();

        $this->addFlash('success', 'Patient removed successfully.');

        return $this->redirectToRoute('app_admin');

    }
}
