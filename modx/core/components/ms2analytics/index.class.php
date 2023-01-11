<?php
	ini_set('display_errors', 1);
	ini_set('display_errors', 1);
	if (file_exists(MODX_CORE_PATH . 'components/extraext/model/extraext.include.php')) {
		include_once MODX_CORE_PATH . 'components/extraext/model/extraext.include.php';
	}
	if (class_exists('extraExtManagerController')) {
		//Основной контроллер
		class ms2analyticsIndexManagerController extends extraExtManagerController
		{

			public $componentName = 'ms2analytics'; // название компонента так как называется его папка в assets/components/: по умолчанию равно namespace
			public $devMode = TRUE;

			public function getLanguageTopics()
			{
				return [
					'ms2analytics:default',
				];
			}

			public function getPageTitle()
			{
				return $this->modx->lexicon('ms2analytics');
			}

			public function loadCustomCssJs()
			{
				$this->addJavascript('ajax/libs/jquery/3.5.1/jquery.min.js', 'https://ajax.googleapis.com/', TRUE);
				$this->addJavascript('js/mgr/main.js', $this->componentUrl);
			}
		}
	} else {
		//Запасной контроллер
		class ms2analyticsIndexManagerController extends modExtraManagerController
		{
			public function getPageTitle()
			{
				return 'install Error';
			}

			public function loadCustomCssJs()
			{
				$this->addHtml("
					<H1 class='error'>INSTALL ERROR</H1>
					<p>Pleas install <strong>extraExt</strong> for correct work</p>
				");
			}
		}
	}
