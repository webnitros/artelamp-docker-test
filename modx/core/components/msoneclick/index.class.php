<?php

/**
 * Class msOneClickMainController
 */
abstract class msOneClickMainController extends modExtraManagerController {
	/** @var msOneClick $msOneClick */
	public $msOneClick;


	/**
	 * @return void
	 */
	public function initialize() {
		$corePath = $this->modx->getOption('msoneclick_core_path', null, $this->modx->getOption('core_path') . 'components/msoneclick/');
		require_once $corePath . 'model/msoneclick/msoneclick.class.php';

		$this->msOneClick = new msOneClick($this->modx);
		//$this->addCss($this->msOneClick->config['cssUrl'] . 'mgr/main.css');
		$this->addJavascript($this->msOneClick->config['jsUrl'] . 'mgr/msoneclick.js');
		$this->addHtml('
		<script type="text/javascript">
			msOneClick.config = ' . $this->modx->toJSON($this->msOneClick->config) . ';
			msOneClick.config.connector_url = "' . $this->msOneClick->config['connectorUrl'] . '";
		</script>
		');

		parent::initialize();
	}


	/**
	 * @return array
	 */
	public function getLanguageTopics() {
		return array('msoneclick:default');
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
class IndexManagerController extends msOneClickMainController {

	/**
	 * @return string
	 */
	public static function getDefaultController() {
		return 'home';
	}
}