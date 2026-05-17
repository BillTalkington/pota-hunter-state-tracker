<?php

namespace App\Controller;

use App\Repository\QsoRepository;
use App\Enum\State;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DashboardController extends AbstractController
{
    #[Route('/', name: 'app_dashboard')]
    public function index(QsoRepository $qsoRepository): Response
    {
        $workedCounts = $qsoRepository->getWorkedStateCounts();

        $workedMap = [];

        foreach ($workedCounts as $row) {
            $workedMap[$row['state']->value] = (int) $row['qsoCount'];
        }

        $workedStates = [];
        $neededStates = [];

        foreach (State::cases() as $state) {
            $count = $workedMap[$state->value] ?? 0;

            $stateData = [
                'state' => $state,
                'count' => $count,
            ];

            if ($count > 0) {
                $workedStates[] = $stateData;
            } else {
                $neededStates[] = $stateData;
            }
        }

        return $this->render('dashboard/index.html.twig', [
            'workedStates' => $workedStates,
            'neededStates' => $neededStates,
        ]);
    }
}
