<?php

namespace App\Controller;

use App\Entity\Qso;
use App\Form\QsoType;
use App\Repository\QsoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/qso')]
final class QsoController extends AbstractController
{
    #[Route(name: 'app_qso_index', methods: ['GET'])]
    public function index(QsoRepository $qsoRepository): Response
    {
        return $this->render('qso/index.html.twig', [
            'qsos' => $qsoRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_qso_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $qso = new Qso();
        $form = $this->createForm(QsoType::class, $qso);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($qso);
            $entityManager->flush();

            return $this->redirectToRoute('app_qso_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('qso/new.html.twig', [
            'qso' => $qso,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_qso_show', methods: ['GET'])]
    public function show(Qso $qso): Response
    {
        return $this->render('qso/show.html.twig', [
            'qso' => $qso,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_qso_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Qso $qso, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(QsoType::class, $qso);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_qso_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('qso/edit.html.twig', [
            'qso' => $qso,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_qso_delete', methods: ['POST'])]
    public function delete(Request $request, Qso $qso, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$qso->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($qso);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_qso_index', [], Response::HTTP_SEE_OTHER);
    }
}
