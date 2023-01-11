<?php

/**
 *
 */
class mspc2MsOnSubmitOrder extends mspc2Plugin
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
        $this->manager->refreshCartDiscount();
    }
}