<?php

namespace Delivery\TestBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class DeliveryTestBundle extends Bundle {

    public function getParent() {
        return 'FOSUserBundle';
    }

}
