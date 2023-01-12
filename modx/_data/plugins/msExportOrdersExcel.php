id: 23
source: 1
name: msExportOrdersExcel
category: msExportOrdersExcel
properties: null
static_file: core/components/msexportordersexcel/elements/plugins/msexportordersexcel.php

-----

/** @var modX $modx */
/* @var msExportOrdersExcel $msExportOrdersExcel */
switch ($modx->event->name) {
    case 'OnManagerPageBeforeRender':

        $namespace = $controller->config['namespace'];
        $cnr = $controller->config['controller'];

        // minishop2 - экспорт заказов
        if ($namespace == "minishop2") {

            $area = null;
            switch ($cnr) {
                case "controllers/mgr/orders":
                    $area = "minishop2-grid-orders";
                    break;
                case "mgr/orders":
                    $area = "minishop2-form-orders";
                    break;
                default:
                    break;
            }

            if ($area) {
                $msExportOrdersExcel = $modx->getService('msExportOrdersExcel', 'msExportOrdersExcel', MODX_CORE_PATH . 'components/msexportordersexcel/model/');
                $msExportOrdersExcel->ButtonRegistration($area, $namespace, $cnr);
            }

        }

        break;
    case 'OnMODXInit':
        $modx->lexicon->load('msexportordersexcel:export');
        break;
    case 'OnHandleRequest':
        if ($modx->context->key != 'mgr') {
            $msExportOrdersExcel = $modx->getService('msExportOrdersExcel', 'msExportOrdersExcel', MODX_CORE_PATH . 'components/msexportordersexcel/model/');
            $msExportOrdersExcel->frontendDownload();
        }
        break;
}