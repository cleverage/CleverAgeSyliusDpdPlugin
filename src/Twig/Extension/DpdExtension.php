<?php

declare(strict_types=1);

namespace CleverAge\SyliusDpdPlugin\Twig\Extension;

use CleverAge\SyliusDpdPlugin\Model\PudoItemModel;
use CleverAge\SyliusDpdPlugin\Service\GetPudoDetailsService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class DpdExtension extends AbstractExtension
{
    private GetPudoDetailsService $getPudoDetailsService;

    public function __construct(
        GetPudoDetailsService $getPudoDetailsService
    )
    {
        $this->getPudoDetailsService = $getPudoDetailsService;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('cleverage_dpd_get_pudo_by_id', [$this, 'getPudoById']),
        ];
    }

    public function getPudoById(string $pudoId): ?PudoItemModel
    {
        return $this->getPudoDetailsService->byPudoId($pudoId);
    }
}
