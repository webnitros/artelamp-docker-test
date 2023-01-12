<?php
/* include custom core config and define core path */
@include(dirname(__FILE__, 2) . '/config.core.php');
include_once dirname(MODX_CORE_PATH, 1).'/vendor/autoload.php';

class buildMapXPDO
{
    /* @var modY $modx*/
    public modY $modx;
    /** @var array $config */
    public $config = [];

    public function __construct($core_path, array $config = [])
    {
        $this->modx = modY::getInstance('modY');
        $this->modx->initialize('mgr');
        $this->modx->getService('error', 'error.modError');

        $root = dirname(dirname(__FILE__)) . '/';
        $core = $root . 'core/components/' . $config['name_lower'] . '/';

        $this->config = array_merge([
            'log_level' => modX::LOG_LEVEL_INFO,
            'log_target' => XPDO_CLI_MODE ? 'ECHO' : 'HTML',
            'root' => $root,
            'core' => $core,
        ], $config);

        $this->modx->setLogLevel($this->config['log_level']);
        $this->modx->setLogTarget($this->config['log_target']);
    }

    /**
     * Генерация полей в mysql через карту
     * @return bool
     */
    public function process()
    {
        if (empty($this->config['name_lower'])) {
            return 'Укажите наименование компонента';
        }

        $schema = $this->config['core'] . 'model/schema/' . $this->config['name_lower'] . '.mysql.schema.xml';
        

        if (!file_exists($schema)) {
            return 'Не удалось найти schema в директории :' . $schema;
        }

        /** @var xPDOCacheManager $cache */
        if ($cache = $this->modx->getCacheManager()) {
            $cache->deleteTree(
                $this->config['core'] . 'model/' . $this->config['name_lower'] . '/mysql',
                ['deleteTop' => true, 'skipDirs' => false, 'extensions' => []]
            );
        }

        /** @var xPDOManager $manager */
        $manager = $this->modx->getManager();
        /** @var xPDOGenerator $generator */
        $generator = $manager->getGenerator();
        $generator->parseSchema(
            $this->config['core'] . 'model/schema/' . $this->config['name_lower'] . '.mysql.schema.xml',
            $this->config['core'] . 'model/',
            true
        );

        $this->modx->log(modX::LOG_LEVEL_INFO, 'Model updated');
        $this->modx->getService($this->config['name_lower'], $this->config['name'], MODX_CORE_PATH . 'components/' . $this->config['name_lower'] . '/model/');
        $mapFile = $this->config['core'] . 'model/'.$this->config['name_lower'].'/metadata.mysql.php';
        if (file_exists($mapFile)) {
            $xpdo_meta_map = '';
            include $mapFile;
            if (!empty($xpdo_meta_map) and is_array($xpdo_meta_map)) {
                foreach ($xpdo_meta_map as $className => $extends) {
                    foreach ($extends as $extend) {
                        $this->modx->log(modX::LOG_LEVEL_INFO, 'Update table ' . $extend);
                        $manager->createObjectContainer($extend);
                    }
                }
            } else {
                return 'Error load load xpdo_meta_map' . $this->config['name'];
            }
        }
        return true;
    }

}

$package = $argv[1];
$install = new buildMapXPDO(MODX_CORE_PATH, [
    'name' => $package,
    'name_lower' => strtolower($package),
    'version' => 1,
    'release' => 1,
]);
$response = $install->process();
if ($response !== true) {
    $install->modx->log(modX::LOG_LEVEL_ERROR, $response);
}
