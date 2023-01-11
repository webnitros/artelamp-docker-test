<?php

/**
 *
 */
class mspc2MsOnManagerCustomCssJs extends mspc2Plugin
{
    public function run()
    {
        /** @var modManagerController $controller */
        $controller = &$this->sp['controller'];
        $page = $this->sp['page'];

        if ($page == 'orders') {
            $this->mspc2->loadManagerScripts($controller, [
                'css/main',
                'js/vendor',
                'js/misc',
                'js/minishop2',
            ]);
        }
    }
}