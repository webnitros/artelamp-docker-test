<?php

/**
 *
 */
class mspc2MsOnChangeInCart extends mspc2Plugin
{
    /**
     * @var mspc2Manager $manager
     */
    protected $manager;

    /**
     * @param msPromoCode2 $mspc2
     * @param array        $sp
     */
    public function __construct(msPromoCode2 &$mspc2, array &$sp)
    {
        parent::__construct($mspc2, $sp);

        $this->manager = $this->mspc2->getManager();
    }

    /**
     *
     */
    public function run()
    {
        $coupon = $this->manager->getCurrentCoupon();
        // $this->manager->setMessageSession($coupon);

        $coupon = is_array($coupon) ? $coupon : null;
        if (!empty($coupon)) {
            $this->manager->setCoupon((int)$coupon['id']);
        }
    }
}