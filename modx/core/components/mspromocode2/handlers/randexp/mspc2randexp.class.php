<?php

class mspc2Randexp
{
    /** @var modX $modx */
    protected $modx;
    /** @var msPromoCode2 $mspc2 */
    protected $mspc2;

    /**
     * @param msPromoCode2 $mspc2
     */
    public function __construct(msPromoCode2 &$mspc2)
    {
        $this->mspc2 = &$mspc2;
        $this->modx = &$mspc2->modx;
    }

    /**
     * @return bool
     */
    private function load()
    {
        if (!class_exists('RegRev\RegRev')) {
            require_once $this->mspc2->config['vendorPath'] . 'autoload.php';
        }

        return true;
    }

    /**
     * @param string $regexp
     *
     * @return null|RegRev\RegRev
     */
    public function get($regexp)
    {
        $output = '';
        $this->load();

        if (class_exists('RegRev\RegRev')) {
            $output = RegRev\RegRev::generate($regexp);
        }

        return $output;
    }
}