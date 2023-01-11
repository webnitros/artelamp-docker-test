<?php

/**
 *
 */
class mspc2MsOnBeforeAddToCart extends mspc2Plugin
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
        // Set from key to msPromoCode placeholder
        $this->modx->setPlaceholder('_call_from', 'cart');

        //
        $this->manager->refreshCartProductKeys();
    }
}