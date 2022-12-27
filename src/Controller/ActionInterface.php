<?php

declare(strict_types=1);

namespace CleverAge\SyliusDpdPlugin\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface ActionInterface
{
    public function __invoke(Request $request): Response;
}
