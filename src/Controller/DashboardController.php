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
    public function index(
        QsoRepository $qsoRepository,
        PotaSpotService $potaSpotService
    ): Response {
        // NOTE: Consider moving state + spot logic into a "DashboardDataBuilder service or similar.

        // --------------------
        // 1. DB: WORKED STATES
        // --------------------
        $workedCounts = $qsoRepository->getWorkedStateCounts();

        $workedMap = [];

        foreach ($workedCounts as $row) {
            $workedMap[$row['state']->value] = (int) $row['qsoCount'];
        }

        // --------------------
        // 2. BUILD WORKED / NEEDED STATES
        // --------------------
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

        // --------------------
        // 3. API: POTA SPOTS
        // --------------------
        $spots = $potaSpotService->getCurrentSpots();

        foreach ($spots as &$spot) {
            $spot['needed'] = !isset($workedMap[$spot['state']->value]);
        }
        unset($spot);

        // --------------------
        // 4. GROUP SPOTS BY STATE
        // --------------------
        $spotsByState = [];

        foreach ($spots as $spot) {
            $stateValue = $spot['state']->value;
            $spotsByState[$stateValue][] = $spot;
        }

        // --------------------
        // 5. ATTACH SPOTS TO NEEDED STATES
        // --------------------
        foreach ($neededStates as &$state) {
            $stateValue = $state['state']->value;

            $state['spots'] = $spotsByState[$stateValue] ?? [];
        }
        unset($state);

        // Prioritize states with active spots and
        // descend sort by total # of active state spots
        usort($neededStates, function ($a, $b) {
            return count($b['spots']) <=> count($a['spots']);
        });

        // --------------------
        // 6. RENDER
        // --------------------
        return $this->render('dashboard/index.html.twig', [
            'workedStates' => $workedStates,
            'neededStates' => $neededStates,
        ]);
    }
}
