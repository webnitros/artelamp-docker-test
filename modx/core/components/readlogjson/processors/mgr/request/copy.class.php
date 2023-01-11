<?php

/**
 * Disable an Mailing
 */
class ReadLogJsonRequestCopyProcessor extends modProcessor
{
    public $objectType = 'ReadLogJsonRequest';
    public $classKey = 'ReadLogJsonRequest';
    public $languageTopics = array('ReadLogJsonRequest');


    /** {inheritDoc} */
    public function process()
    {
        $id = $this->getProperty('id');

        /* @var modResource $object */
        if ($request = $this->modx->getObject($this->classKey, $id)) {
            /** @var ReadLogJsonRequest $newRquest */
            $newRquest = $this->modx->newObject($this->classKey);
            $newRquest->fromArray($request->get(
                array(
                    'url',
                    'method',
                    'event',
                    'params',
                )
            ));
            $newRquest->save();
        } else {
            return $this->failure('Не удалось получить запрос');
        }

        return $this->success();
    }

}

return 'ReadLogJsonRequestCopyProcessor';
