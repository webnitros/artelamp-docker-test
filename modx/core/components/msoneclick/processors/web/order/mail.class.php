<?php
if (!class_exists('msOneClickFormDefaultProcessor')) {
    include_once dirname(dirname(__FILE__)) . '/default.php';
}
class msOneClickFormMailProcessor extends msOneClickFormDefaultProcessor
{
    protected $method = "MAIL";

    /**
     * @return array|string
     */
    public function process()
    {
        $tplMessage = $this->getProperty('tplMAILmessage', null);
        if (empty($tplMessage)) {
            return $this->failure($this->modx->lexicon('msoc_err_ms2_message_tpl'));
        }

        $email = $this->getProperty('email_method_mail', null);

        $this->setProperty('site_url', $this->modx->getOption('site_url'));

        if ($this->method == "MAIL") {
            $product = $this->product->toArray();
            unset($product['content']);
            $this->setProperty('product', $product);
        }

        $properties = $this->getProperties();
        $subject = $this->ms->pdoTools->getChunk($tplMessage, array_merge(array('subject' => 1, 'body' => 0), $properties));
        $body = $this->ms->pdoTools->getChunk($tplMessage, array_merge(array('subject' => 0, 'body' => 1), $properties));
        if (!$email) {
            $emailsender = $this->modx->getOption('emailsender');
            $ms2_email_manager = $this->modx->getOption('ms2_email_manager', null, $this->modx->getOption('emailsender'));
            if (!empty($ms2_email_manager)) {
                $email = $ms2_email_manager;
            } else if (!empty($emailsender)) {
                $email = $emailsender;
            } else {
                return $this->failure($this->modx->lexicon('msoc_err_ms2_email_manager'));
            }
        }

        $emails = array_map('trim', explode(',', $email));
        foreach ($emails as $email) {
            if (preg_match('/^[^@а-яА-Я]+@[^@а-яА-Я]+(?<!\.)\.[^\.а-яА-Я]{2,}$/m', $email)) {
                $this->ms2->sendEmail($email, $subject, $body);
            }
        }

        return $this->success($this->modx->lexicon('msoc_success_order_send'));
    }

}

return 'msOneClickFormMailProcessor';