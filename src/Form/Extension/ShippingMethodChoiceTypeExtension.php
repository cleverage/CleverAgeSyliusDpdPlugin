<?php

declare(strict_types=1);

namespace CleverAge\SyliusDpdPlugin\Form\Extension;

use CleverAge\SyliusDpdPlugin\Contract\DpdShippingMethodInterface;
use Sylius\Bundle\ShippingBundle\Form\Type\ShippingMethodChoiceType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class ShippingMethodChoiceTypeExtension extends AbstractTypeExtension
{
    public function configureOptions(OptionsResolver $resolver): void
    {

        $resolver->setDefault('choice_attr', function (DpdShippingMethodInterface $choiceValue): array {
            $defaultAttr = [
                'class' => 'input-shipping-method',
            ];

            if (!$choiceValue->isDpdPickup()) {
                return $defaultAttr;
            }

            return [
                    'data-dpd-pickup' => true,
                ] + $defaultAttr;
        });
    }

    public static function getExtendedTypes(): iterable
    {
        return [ShippingMethodChoiceType::class];
    }
}
