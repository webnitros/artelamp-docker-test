<?php

require_once MODX_CORE_PATH . 'model/modx/modprocessor.class.php';
if (file_exists(MODX_BASE_PATH . 'msPromoCode2/core/components/mspromocode2/processors/mgr/coupons/orders/doit.class.php')) {
    require_once MODX_BASE_PATH . 'msPromoCode2/core/components/mspromocode2/processors/mgr/coupons/orders/doit.class.php';
} else {
    require_once MODX_CORE_PATH . 'components/mspromocode2/processors/mgr/coupons/orders/doit.class.php';
}

return 'mspc2CouponOrderDoItProcessor';