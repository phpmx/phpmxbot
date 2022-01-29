<?php

namespace PhpMx\Http\Api\Controller;

use Symfony\Component\HttpFoundation\Request;

class LeaderBoardController
{
    public function __invoke(Request $request)
    {
        var_dump($request); exit();
    }
}
