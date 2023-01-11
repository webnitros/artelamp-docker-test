<?php

/**
 * @deprecated
 */

require_once dirname(__FILE__).'/update.class.php';

class mspcResourceFromGridProcessor extends mspc2JoinUpdateProcessor
{
    public static function getInstance(modX &$modx, $className, $properties = array())
    {
        /** @var modProcessor $processor */
        $processor = new self($modx, $properties);

        return $processor;
    }

    public function initialize()
    {
        $data = $this->modx->fromJSON($this->getProperty('data'));
        if (empty($data)) {
            return $this->modx->lexicon('invalid_data');
        }
        $this->setProperties($data);
        $this->unsetProperty('data');

        return parent::initialize();
    }
}

return 'mspcResourceFromGridProcessor';
