<?php
/**
 * The base class for msFavorites.
 */

class msFavorites {
	/* @var modX $modx */
	public $modx;
	public $namespace = 'msfavorites';
	public $config = array();
	public $initialized = array();
	public $mode = 0;
	public $count = 1;
	/**
	 * @param modX $modx
	 * @param array $config
	 */
	function __construct(modX &$modx, array $config = array()) {
		$this->modx =& $modx;

		$this->namespace = $this->getOption('msfavorites', $config, 'msfavorites');
		$corePath = $this->modx->getOption('msfavorites_core_path', $config, $this->modx->getOption('core_path') . 'components/msfavorites/');
		$assetsUrl = $this->modx->getOption('msfavorites_assets_url', $config, $this->modx->getOption('assets_url') . 'components/msfavorites/');
		$connectorUrl = $assetsUrl . 'connector.php';

		$this->config = array_merge(array(
			'assetsUrl' => $assetsUrl,
			'cssUrl' => $assetsUrl . 'css/',
			'jsUrl' => $assetsUrl . 'js/',
			'imagesUrl' => $assetsUrl . 'images/',
			'connectorUrl' => $connectorUrl,

			'corePath' => $corePath,
			'modelPath' => $corePath . 'model/',
			'chunksPath' => $corePath . 'elements/chunks/',
			'templatesPath' => $corePath . 'elements/templates/',
			'chunkSuffix' => '.chunk.tpl',
			'snippetsPath' => $corePath . 'elements/snippets/',
			'processorsPath' => $corePath . 'processors/',
			'frontend_css' => $this->modx->getOption('msfavorites_ms_front_css', null, '[[+assetsUrl]]css/web/default.css'),
			'frontend_js' => $this->modx->getOption('msfavorites_ms_front_js', null, '[[+assetsUrl]]js/web/default.js'),
		), $config);

		$this->modx->addPackage('msfavorites', $this->config['modelPath']);
		$this->modx->lexicon->load('msfavorites:default');
		$this->mode = $this->modx->getOption('msfavorites_mode', $config, 0);
		$this->count = $this->modx->getOption('msfavorites_move_count', $config, 100);
	}

	public function getOption($key, $config = array(), $default = null) {
		$option = $default;
		if (!empty($key) && is_string($key)) {
			if ($config != null && array_key_exists($key, $config)) {
				$option = $config[$key];
			} elseif (array_key_exists($key, $this->config)) {
				$option = $this->config[$key];
			} elseif (array_key_exists("{$this->namespace}.{$key}", $this->modx->config)) {
				$option = $this->modx->getOption("{$this->namespace}.{$key}");
			}
		}
		return $option;
	}

	public function initialize($ctx = 'web', $scriptProperties = array()) {
		$this->config = array_merge($this->config, $scriptProperties);
		$this->config['ctx'] = $ctx;
		if (!empty($this->initialized[$ctx])) {
			return true;
		}
		switch ($ctx) {
			case 'mgr': break;
			default:
				if (!defined('MODX_API_MODE') || !MODX_API_MODE) {
					if ($css = trim($this->config['frontend_css'])) {
						if (preg_match('/\.css/i', $css)) {
							$this->modx->regClientCSS(str_replace('[[+assetsUrl]]', $this->config['assetsUrl'], $css));
						}
					}
					if ($js = trim($this->config['frontend_js'])) {
						if (preg_match('/\.js/i', $js)) {
							$this->modx->regClientScript(str_replace('[[+assetsUrl]]', $this->config['assetsUrl'], $js));
						}
					}
				}
				$this->initialized[$ctx] = true;
				break;
		}
		return true;
	}

	public function OnWebPageInit($sc) {

		if (empty($_REQUEST['msf_action']) || empty($_SERVER['HTTP_X_REQUESTED_WITH']) || $_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') {
			return;
		}
		$ac = trim(strtolower($_REQUEST['msf_action']));
		$mode = $this->mode;
		$ac = $ac . '_' . $mode;
		// 0 - авторизованние
		// 1 - анонимы
		// 2 - все вместе с переносом избранного при авторизации
		$user_id = $this->modx->user->id;
		$msf_id = $_REQUEST['resource'];
		$list = !empty($_REQUEST['list'])
			? (string) $_REQUEST['list']
			: 'default';
		$list_id = !empty($_REQUEST['list_id'])
			? (int) $_REQUEST['list_id']
			: 1;
		$prop = !empty($_REQUEST['properties'])
			? $this->modx->toJSON($_REQUEST['properties'])
			: '[]';
		$response = array('success' => true, 'message' => '', 'data' => array());
		switch ($ac) {
			case 'add_0':
			case 'remove_0':
			{
				if ($user_id == 0) {
					$response['success'] = false;
					$response['message'] = $this->modx->lexicon('msfavorites_err_no_user');
				}
				elseif(!$this->WorkOrNot($msf_id)) {
					$response['success'] = false;
					$response['message'] = $this->modx->lexicon('msfavorites_err_add_resource');
				}
				else {
					$arr = array('user_id' => $user_id, 'msf_id' => $msf_id, 'list' => $list);
					if ($ac == 'add_0') {
						$this->modx->getObject('msFavoritesList', array_merge($arr, array('properties' => $prop)));
					}
					else {
						$msf = $this->modx->getObject('msFavoritesList', $arr);
						$msf->remove();
					}
					$count = $this->modx->getCount('msFavoritesList', array('user_id' => $user_id, 'list' => $list, 'msf_id:!=' => 0));
					$response['data'] = array(
						'total' => $count,
					);
					$response['data']['link'] = ($count > 0)
						? urldecode($this->modx->context->makeUrl($list_id, '', $this->modx->getOption('link_tag_scheme')))
						: '#';
				}
				break;

			}
			case 'add_1':
			case 'remove_1':
			{
				$session = & $_SESSION['msfavorites'][$list];
				$session['work'] = '1';
				$session['flag'] = '1';
				if (!isset($session) || !isset($session['work']) ) {
					$response['success'] = false;
					$response['message'] = $this->modx->lexicon('msfavorites_err_ses');
				}
				elseif(!$this->WorkOrNot($msf_id)) {
					$response['success'] = false;
					$response['message'] = $this->modx->lexicon('msfavorites_err_add_resource');
				}
				else {
					if ($ac == 'add_1') {
						$session['ids'][$msf_id] = 'added';
						$session['properties'][$msf_id] = $prop;
					}
					else unset($session['ids'][$msf_id], $session['properties'][$msf_id]);
					$response['data'] = array(
						'total' => count($session['ids']),
					);
					$response['data']['link'] = !empty($session['ids'])
						? urldecode($this->modx->context->makeUrl($list_id, '', $this->modx->getOption('link_tag_scheme')))
						: '#';
				}
				break;
			}
			case 'add_2':
			case 'remove_2':
			{
				if(!$this->WorkOrNot($msf_id)) {
					$response['success'] = false;
					$response['message'] = $this->modx->lexicon('msfavorites_err_add_resource');
				}
				elseif ($user_id == 0) {
					$response['info'] = $this->modx->lexicon('msfavorites_err_no_user_pre');
					$session = & $_SESSION['msfavorites'][$list];
					$session['work'] = '1';
					$session['flag'] = '1';
					if (!isset($session) || !isset($session['work']) ) {
						$response['success'] = false;
						$response['message'] = $this->modx->lexicon('msfavorites_err_ses');
					}
					elseif (empty($msf_id) || !$this->modx->getCount('modResource', array('id' => $msf_id, 'published' => 1, 'deleted' => 0))) {
						$response['success'] = false;
						$response['message'] = $this->modx->lexicon('msfavorites_err_add_resource');
					}
					else {
						if ($ac == 'add_2') {
							$session['ids'][$msf_id] = 'added';
							$session['properties'][$msf_id] = $prop;
						}
						else unset($session['ids'][$msf_id], $session['properties'][$msf_id]);
						$response['data'] = array(
							'total' => count($session['ids']),
						);
					}
					$response['data']['link'] = !empty($session['ids'])
						? urldecode($this->modx->context->makeUrl($list_id, '', $this->modx->getOption('link_tag_scheme')))
						: '#';
				}
				else {
					$this->move2base($list, $user_id);
					$arr = array('user_id' => $user_id, 'msf_id' => $msf_id, 'list' => $list);
					if ($ac == 'add_2') {
						$this->modx->getObject('msFavoritesList', array_merge($arr, array('properties' => $prop)));
					}
					else {
						$msf = $this->modx->getObject('msFavoritesList', $arr);
						$msf->remove();
					}
					$count = $this->modx->getCount('msFavoritesList', array('user_id' => $user_id, 'list' => $list, 'msf_id:!=' => 0));
					$response['data'] = array(
						'total' => $count,
					);
					$response['data']['link'] = ($count > 0)
						? urldecode($this->modx->context->makeUrl($list_id, '', $this->modx->getOption('link_tag_scheme')))
						: '#';
				}
				break;
			}
				break;
		}

		echo $this->modx->toJSON($response);
		@session_write_close();
		exit;

	}

	public function OnSiteSettingsRender($sc) {

		$this->modx->controller->addLexiconTopic('msfavorites:default');
		$this->modx->controller->addHtml('<script type="text/javascript">
                // This a demo combo, instead use your own with your processor to load your "options"
                var msfavoritesXtype = function(config) {
                    Ext.apply(config, {
                        store: new Ext.data.SimpleStore({
                            fields: ["data","value"]
                            ,data: [
                                [_(\'msfavorites_opt_0\'), "0"]
                                ,[_(\'msfavorites_opt_1\'), "1"]
                                ,[_(\'msfavorites_opt_2\'), "2"]
                            ]
                        })
                        ,displayField: "data"
                        ,valueField: "value"
                        ,mode: "local"
                    });
                    msfavoritesXtype.superclass.constructor.call(this, config);
                };
                Ext.extend(msfavoritesXtype, MODx.combo.ComboBox);
                Ext.reg("msfavorites-combo-opt", msfavoritesXtype);

                Ext.onReady(function() {
                    Ext.override(MODx.combo.xType, {
                        listeners: {
                            afterRender: {
                                fn: function(elem) {
                                    var store = elem.getStore();
                                    // Add your custom xtype(s)
                                    var newXtypes = [
                                        new Ext.data.Record({
                                            d: "msfavorites opt"
                                            ,v: "msfavorites-combo-opt"
                                        })
                                    ];
                                    store.add(newXtypes);
                                }
                                ,scope: this
                            }
                        }
                    });
            });
            </script>');

		return '';

	}

	public function OnBeforeEmptyTrash($sc) {
		$deletedids = $this->modx->event->params['ids'];
		if(!empty($deletedids)) {
			$msf = $this->modx->getIterator('msFavoritesList', array('msf_id:IN' => $deletedids));
			foreach ($msf as $m) {$m->remove();}
		}
	}

	public function move2base($list, $user_id) {
		$session = & $_SESSION['msfavorites'][$list];
		if (isset($session['flag']) ) {
			$ids = !empty($session)
				? $session['ids']
				: array();
			if((!empty($ids)) && ($this->count > count($ids) )){
				foreach(array_keys($ids) as $msf_id) {
					if($this->WorkOrNot($msf_id)) {
						$this->modx->getObject('msFavoritesList', array(
							'user_id' => $user_id,
							'msf_id' => $msf_id,
							'list' => $list,
							'properties' => !empty($session['properties'][$msf_id]) ? $session['properties'][$msf_id] : '[]'
						));
					}
				}
			}
			else {$this->modx->log(1, print_r('[error:msFavorites] превышение кол-ва ресурсов при перемещении', 1));}
			unset($session['flag']);
		}
	}

	public function WorkOrNot($msf_id) {
		if (empty($msf_id) || !$this->modx->getCount('modResource', array('id' => $msf_id, 'published' => 1, 'deleted' => 0))) {return false;}
		return true;
	}

}