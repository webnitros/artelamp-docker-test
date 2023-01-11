<?php

/**
 *
 */
class mspc2MsopOnGetFullCost extends mspc2Plugin
{
    /**
     *
     */
    public function run()
    {
        $options = $this->sp['options'];
        $modifications = $this->sp['modifications'];
        if (empty($modifications)) {
            return;
        }
        $modification = $modifications[0];

        //
        if (!empty($modification['id'])) {
            $options['modification'] = $modification['id'];
        }

        $this->modx->event->returnedValues['options'] = $options;
    }
}