<?php

namespace PhpMx\Application;

use PhpMx\Http\Kernel;
use Symfony\Component\HttpFoundation\Request;

class ApiApplication implements ApplicationInterface
{
    private Kernel $kernel;

    public function __construct(Kernel $kernel)
    {
        $this->kernel = $kernel;
    }

    public function execute()
    {
        $request = Request::createFromGlobals();
        $response = $this->kernel->handle($request);
        $response->send();
        $this->kernel->terminate($request, $response);
    }
}
