<?php

/**
 * Class msFavoritesMainController
 */
abstract class msFavoritesMainController extends modExtraManagerController {
	/** @var msFavorites $msFavorites */
	public $msFavorites;


	/**
	 * @return void
	 */
	public function initialize() {
		$corePath = $this->modx->getOption('msfavorites_core_path', null, $this->modx->getOption('core_path') . 'components/msfavorites/');
		require_once $corePath . 'model/msfavorites/msfavorites.class.php';

		$this->msFavorites = new msFavorites($this->modx);

		$this->addJavascript($this->msFavorites->config['jsUrl'] . 'mgr/msfavorites.js');
		$this->addHtml('<script type="text/javascript">
		Ext.onReady(function() {
			msFavorites.config = ' . $this->modx->toJSON($this->msFavorites->config) . ';
			msFavorites.config.connector_url = "' . $this->msFavorites->config['connectorUrl'] . '";
		});
		</script>');

		parent::initialize();
	}


	/**
	 * @return array
	 */
	public function getLanguageTopics() {
		return array('msfavorites:default');
	}


	/**
	 * @return bool
	 */
	public function checkPermissions() {
		return true;
	}
}


/**
 * Class IndexManagerController
 */
class IndexManagerController extends msFavoritesMainController {

	/**
	 * @return string
	 */
	public static function getDefaultController() {
		return 'mgr/favoriteslist';
	}
}