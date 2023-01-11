<?php

abstract class mspc2Plugin
{
    /** @var modX $modx */
    protected $modx;
    /** @var msPromoCode2 $mspc2 */
    protected $mspc2;
    /** @var array $sp */
    protected $sp;

    /**
     * @param msPromoCode2 $mspc2
     * @param array          $sp
     */
    public function __construct(msPromoCode2 &$mspc2, array &$sp)
    {
        $this->mspc2 = &$mspc2;
        $this->modx = &$this->mspc2->modx;
        $this->sp = &$sp;
        $this->mspc2->initialize($this->modx->context->key);
    }

    abstract public function run();
}