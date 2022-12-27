<?php

declare(strict_types=1);

namespace CleverAge\SyliusDpdPlugin\Controller\Shop;

use CleverAge\SyliusDpdPlugin\Controller\ActionInterface;
use CleverAge\SyliusDpdPlugin\Entity\Traits\DpdOrderTrait;
use Doctrine\ORM\EntityManagerInterface;
use ReflectionClass;
use RuntimeException;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Order\Context\CartContextInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class RemoveDpdPickupPointIdToOrderAction implements ActionInterface
{
    private CartContextInterface $cartContext;
    private EntityManagerInterface $entityManager;

    public function __construct(
        CartContextInterface   $cartContext,
        EntityManagerInterface $entityManager
    )
    {
        $this->cartContext = $cartContext;
        $this->entityManager = $entityManager;
    }

    public function __invoke(Request $request): Response
    {
        if (!$pickupPointId = $request->get('pickupPointId')) {
            throw new NotFoundHttpException('pickupPointId param not found.');
        }

        /** @var OrderInterface $order */
        $order = $this->cartContext->getCart();

        // Check if order implements DpdOrderTrait
        $orderReflection = new ReflectionClass($order);
        if (!in_array(DpdOrderTrait::class, $orderReflection->getTraitNames())) {
            throw new RuntimeException("Order does not implement DpdOrderTrait");
        }

        $storedPickupPointId = $order->getDpdPickupPointId();

        if ($storedPickupPointId === $pickupPointId) {
            $order->setDpdPickupPointId(null);
            $this->entityManager->flush();
        }

        return new JsonResponse(['success' => true]);
    }
}
