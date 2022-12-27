<?php

declare(strict_types=1);

namespace CleverAge\SyliusDpdPlugin\Controller\Shop;

use CleverAge\SyliusDpdPlugin\Controller\ActionInterface;
use CleverAge\SyliusDpdPlugin\Entity\Traits\DpdOrderTrait;
use CleverAge\SyliusDpdPlugin\Service\GetPudoDetailsService;
use Doctrine\ORM\EntityManagerInterface;
use ReflectionClass;
use RuntimeException;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Order\Context\CartContextInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class SetDpdPickupPointIdToOrderAction implements ActionInterface
{
    private CartContextInterface $cartContext;
    private EntityManagerInterface $entityManager;
    private GetPudoDetailsService $pudoDetailsService;


    public function __construct(
        CartContextInterface   $cartContext,
        EntityManagerInterface $entityManager,
        GetPudoDetailsService  $pudoDetailsService
    )
    {
        $this->cartContext = $cartContext;
        $this->entityManager = $entityManager;
        $this->pudoDetailsService = $pudoDetailsService;
    }

    public function __invoke(Request $request): Response
    {
        /** @var OrderInterface $order */
        $order = $this->cartContext->getCart();
        if ($order->isEmpty()) {
            throw new NotFoundHttpException('Order was empty');
        }

        if (!$pickupPointId = $request->request->get('pickupPointId')) {
            throw new NotFoundHttpException('pickupPointId param not found.');
        }

        $pickupPointId = (string)$pickupPointId;

        $pickupPoint = $this->pudoDetailsService->byPudoId($pickupPointId);
        if (null === $pickupPoint) {
            throw new NotFoundHttpException("Pickup point not found for id '$pickupPointId'");
        }

        // Check if order implements DpdOrderTrait
        $orderReflection = new ReflectionClass($order);
        if (!in_array(DpdOrderTrait::class, $orderReflection->getTraitNames())) {
            throw new RuntimeException("Order does not implement DpdOrderTrait");
        }

        $order->setDpdPickupPointId($pickupPointId);
        $this->entityManager->flush();

        return new JsonResponse(json_encode($pickupPoint));
    }
}
