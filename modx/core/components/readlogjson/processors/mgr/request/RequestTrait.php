<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 26.09.2022
 * Time: 10:43
 */

trait RequestTrait
{
    /* @var ReadLogJsonRequest $object */
    public $object;

    public function checkValidate()
    {
        $url = trim($this->getProperty('url'));


        if (empty($url)) {
            $this->modx->error->addField('url', $this->modx->lexicon('readlogjson_request_err_url'));
        } else {
            $this->setProperty('url', trim($url));
        }

        $method = mb_strtolower($this->getProperty('method_name'));
        switch ($method) {
            case 'post':
            case 'delete':
            case 'get':
            case 'patch':
                $this->setProperty('method', $method);
                break;
            default:
                $this->modx->error->addField('method_name', $this->modx->lexicon('readlogjson_request_err_method'));
                break;
        }

        $this->validateJsonParams();


    }

    private function validateJsonParams()
    {
        $fields = $this->object->fieldsJson();
        foreach ($fields as $field) {
            $error = null;
            $values = [];
            if (array_key_exists($field, $this->properties)) {
                $json = $this->getProperty($field);
                if ($json === substr($json, 0, 1)) {
                    $values = $this->modx->fromJSON($json);
                    if (!is_array($values)) {
                        $error = 'Значение не является массивом';
                    }
                }
            }

            if ($error) {
                $this->modx->error->addField($field, $error);
            } else {
               # $this->setProperty($field, $values);
            }
        }
    }
}
