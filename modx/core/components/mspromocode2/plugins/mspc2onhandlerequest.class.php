<?php

/**
 *
 */
class mspc2OnHandleRequest extends mspc2Plugin
{
    public function run()
    {
        if (@$_GET['mspc2_load']) {
            @session_abort();
        }
    }
}