<?php

namespace App\Controller;

use App\Repository\QsoRepository;
use App\Enum\State;
use App\Service\PotaSpotService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DashboardController extends AbstractController
{
    #[Route('/', name: 'app_dashboard')]
    public function index(QsoRepository $qsoRepository, PotaSpotService $potaSpotService): Response
    {
        // Calculate which states have been worked already and which are still needed
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

        // Get current POTA spots
        $spots = $potaSpotService->getCurrentSpots();

        foreach ($spots as &$spot) {
            $spot['needed'] = !isset($workedMap[$spot['state']->value]);
        }
        unset($spot);

        return $this->render('dashboard/index.html.twig', [
            'workedStates' => $workedStates,
            'neededStates' => $neededStates,
            'spots' => $spots,
        ]);
    }
}
