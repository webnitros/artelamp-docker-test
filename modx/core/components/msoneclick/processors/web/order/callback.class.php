<?php
if (!class_exists('msOneClickFormMailProcessor')) {
    include_once dirname(__FILE__) . '/mail.class.php';
}
class msOneClickFormCallBackProcessor extends msOneClickFormMailProcessor
{
    protected $method = "CALLBACK";
    /**
     * @param string $ctx
     * @return bool
     */
    public function actionCheckMinishop2($ctx = 'web')
    {
        return true;
    }
}

return 'msOneClickFormCallBackProcessor';