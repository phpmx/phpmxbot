<?php

namespace PhpMx\Application;

use PhpMx\Http\Kernel;
use Symfony\Component\HttpFoundation\Request;

class ApiApplication implements ApplicationInterface
{
    public function execute()
    {
        $kernel = new Kernel($_ENV['ENVIRONMENT'], $_ENV['DEBUG']);
        $request = Request::createFromGlobals();
        $response = $kernel->handle($request);
        $response->send();
        $kernel->terminate($request, $response);
    }
}
