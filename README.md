# CleverAge/SyliusDpdPlugin

[![Latest Version][ico-version]](https://packagist.org/packages/cleverage/sylius-dpd-plugin)
[![Software License][ico-license]](LICENSE)

## Introduction

This sylius plugin allows you to manage parcel shipments with DPD.
You can define `DPD pickup points` delivery method.

For pickup point deliveries, an interactive map with the list of pickup points is generated according to the delivery
address entered the tunnel by the user.

## Usage

TODO add screenshots

## Installation

### Step 1: Install and enable plugin

Open a command console, enter your project directory and execute the following command to download the latest stable
version of this plugin:

```bash
$ composer require cleverage/sylius-dpd-plugin
```

This command requires you to have Composer installed globally, as explained in
the [installation chapter](https://getcomposer.org/doc/00-intro.md) of the Composer documentation.

Add bundle to your `config/bundles.php`:

```php
<?php
# config/bundles.php

return [
    // ...
    CleverAge\SyliusDpdPlugin\CleverAgeSyliusDpdPlugin::class => ['all' => true],
    // ...
];
```

### Step 2: Import routing and configs

#### Import routing

````yaml
# config/routes/clerverage_sylius_dpd.yaml
clever_age_sylius_dpd_shop:
  resource: "@CleverAgeSyliusDpdPlugin/Resources/config/shop_routing.yml"
````

#### Import application config

````yaml
# config/packages/_sylius.yaml
imports:
  - { resource: "@CleverAgeSyliusDpdPlugin/Resources/config/config.yaml" }
````

### Step 3: Update templates

#### Admin section

Add the following to the admin template `SyliusAdminBundle/Order/Show/_shipment.html.twig`
after shipment header:

```twig
{% include "CleverAgeSyliusDpdPlugin/Shipment/Label/pickupPoint.html.twig" %}
```

See an example [here](tests/Application/templates/bundles/SyliusAdminBundle/Order/Show/_shipment.html.twig).

#### Shop section

Add the following to the shop template `SyliusShopBundle/Checkout/SelectShipping/_choice.html.twig`

```twig
// ...
{% if method.isDpdPickup %}
    {% include '@CleverAgeSyliusDpdPlugin/Shipment/selectedPickupPoint.html.twig' %}
{% endif %}
// ...
{% if method.isDpdPickup %}
    {% include '@CleverAgeSyliusDpdPlugin/Shipment/map.html.twig' with {
        'pickupPoints': [myPickupPoints]
    } %}
{% endif %}
```

See an example [here](tests/Application/templates/bundles/SyliusShopBundle/Checkout/SelectShipping/_choice.html.twig).

Next add the following to the shop template `SyliusShopBundle/Common/Order/_shipments.html.twig`
after shipment method header:

```twig
{% include "@CleverAgeSyliusDpdPlugin/Shipment/Label/pickupPoint.html.twig" %}
```

See an example [here](tests/Application/templates/bundles/SyliusShopBundle/Common/Order/_shipments.html.twig).

## Step 4 : Update styles, scripts and install assets

Add the following to the shop template `SyliusShopBundle/_styles.html.twig`

```twig
{% include '@SyliusUi/_stylesheets.html.twig' with { 'path': 'bundles/cleveragesyliusdpdplugin/css/dpd-map.css' } %}
{% include '@SyliusUi/_stylesheets.html.twig' with { 'path': 'bundles/cleveragesyliusdpdplugin/css/dpd-popup.css' } %}

{# Important for the map ! #}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.8.0/dist/leaflet.css"
      integrity="sha512-hoalWLoI8r4UszCkZ5kL8vayOGVae1oxXe/2A4AO6J9+580uKHDO3JdHb7NzwwzK5xr/Fs0W40kiNHxM9vyTtQ=="
      crossorigin=""
/>
```

See an example [here](tests/Application/templates/bundles/SyliusShopBundle/_styles.html.twig).

Next the following to the shop template `SyliusShopBundle/_scripts.html.twig`

```twig
<script src="{{ asset('bundles/cleveragesyliusdpsplugin/js/dpd-map.js') }}" type="module"></script>
    {% include '@SyliusUi/_javascripts.html.twig' with { 'path': 'bundles/cleveragesyliusdpdplugin/js/dpd-select-shipping.js' } %}

    {% include '@SyliusUi/_javascripts.html.twig' with { 'path': 'bundles/cleveragesyliusdpdplugin/js/dpd-select-pickup-point.js' } %}
    {% include '@SyliusUi/_javascripts.html.twig' with { 'path': 'bundles/cleveragesyliusdpdplugin/js/dpd-change-pickup-point.js' } %}
```

See an example [here](tests/Application/templates/bundles/SyliusShopBundle/_scripts.html.twig).

### Install assets

```bash
bin/console assets:install --symlink
```

# Step 5 : Customize resources

**Shipping method resource**

If you haven't extended the shipping method resource yet, here is what it should look like :

```php
<?php
// src/Entity/ShippingMethod.php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use CleverAge\SyliusDpdPlugin\Contract\DpdShippingMethodInterface;
use CleverAge\SyliusDpdPlugin\Entity\Traits\DpdShippingMethodTrait;
use Sylius\Component\Core\Model\ShippingMethod as BaseShippingMethod;

/**
 * @ORM\Entity()
 * @ORM\Table(name="sylius_shipping_method")
 */
class ShippingMethod extends BaseShippingMethod implements DpdShippingMethodInterface
{
    use DpdShippingMethodTrait;
}

```

**Order resource**

If you haven't extended the order resource yet, here is what it should look like :

```php
<?php
// src/Entity/Order.php

declare(strict_types=1);

namespace App\Entity;

use CleverAge\SyliusDpdPlugin\Entity\Traits\DpdOrderTrait;
use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Core\Model\Order as BaseOrder;

/**
 * @ORM\Entity()
 * @ORM\Table(name="sylius_order")
 */
class Order extends BaseOrder
{
    use DpdOrderTrait;
}
```

You can read about extending resources [here](https://docs.sylius.com/en/latest/customization/model.html).

**Update shipping and order resources config**

Next you need to tell Sylius that you will use your own extended resources :

```yaml
# config/packages/_sylius.yaml

sylius_shipping:
  resources:
    shipping_method:
      classes:
        model: App\Entity\ShippingMethod

sylius_order:
  resources:
    order:
      classes:
        model: App\Entity\Order
```

# Step 6 : Update database schema

```bash
bin/console doctrine:migrations:diff
bin/console doctrine:migrations:migrate 
```

# Step 7 : Configure plugin

```yaml
// config/packages/cleverage_sylius_dpd.yaml

clever_age_sylius_dpd:
  securityKey: 'security key'
```

Enjoy now !

[ico-version]: https://poser.pugx.org/cleverage/sylius-dpd-plugin/v/stable

[ico-license]: https://poser.pugx.org/cleverage/sylius-dpd-plugin/license
