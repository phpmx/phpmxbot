<?php

namespace PhpMx\Http\Controller\Api;

use PhpMx\Services\Leaderboard;
use Symfony\Component\HttpFoundation\JsonResponse;

class LeaderBoardController
{
    public function __invoke(Leaderboard $leaderboard): JsonResponse
    {
        return new JsonResponse($leaderboard->getLeaderboard());
    }
}
