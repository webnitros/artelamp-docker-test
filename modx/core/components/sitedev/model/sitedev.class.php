<?php
include_once MODX_CORE_PATH . 'classes/YandexDisk.php';
require_once BASE_DIR . '/vendor/autoload.php';
\Traineratwot\config\Config::set('CACHE_PATH', MODX_CORE_PATH . 'cache/myCache/');

class siteDev
{
    /** @var modX $modx */
    public $modx;

    /** @var array $initialized */
    public $initialized = [];

    const assets_version = '1.1-dev';

    /** @var pdoFetch $pdoTools */
    public $pdoTools;


    /**
     * @param modX $modx
     * @param array $config
     */
    function __construct(modX &$modx, array $config = [])
    {
        $this->modx = $modx;
        $this->pdoTools = $modx->getService('pdoFetch');
        $corePath = MODX_CORE_PATH . 'components/sitedev/';
        $assetsUrl = MODX_ASSETS_URL . 'components/sitedev/';

        $this->config = array_merge([
            'corePath' => $corePath,
            'modelPath' => $corePath . 'model/',
            'processorsPath' => $corePath . 'processors/',

            'assetsUrl' => $assetsUrl,
            'cssUrl' => $assetsUrl . 'css/',
            'jsUrl' => $assetsUrl . 'js/',
        ], $config);

        $this->initialize();

    }

    /**
     * Initialize siteDev
     */
    public function initialize()
    {
        /* @var pdoFetch $pdoTools */
        $this->pdoTools = $this->modx->getService('pdoFetch');

        if (!isset($_SESSION['csrf-token'])) {
            $_SESSION['csrf-token'] = bin2hex(openssl_random_pseudo_bytes(16));
        }
        $csrf_token = $_SESSION['csrf-token'];
        $this->modx->regClientStartupHTMLBlock('<meta name="csrf-token" content="' . $csrf_token . '">' . "\n");

        $this->modx->addPackage('sitedev', $this->config['modelPath']);
        $this->mapExtends();
        include_once $this->config['corePath'] . 'functions/functions.php';
    }

    function mapExtends()
    {
        $this->modx->loadClass('msOrderAddress');
        $this->modx->map['msOrderAddress']['fields']['email'] = '';
        $this->modx->map['msOrderAddress']['fieldMeta']['email'] = [
            'dbtype' => 'varchar',
            'precision' => '100',
            'phptype' => 'string',
            'null' => TRUE,
        ];
        $this->modx->loadClass('msOrder');

        // order_in_1c
        $this->modx->map['msOrder']['fields']['order_in_1c'] = 0;
        $this->modx->map['msOrder']['fieldMeta']['order_in_1c'] = [
            'dbtype' => 'tinyint',
            'precision' => '1',
            'phptype' => 'boolean',
            'null' => TRUE,
        ];

        // is_send_admin
        $this->modx->map['msOrder']['fields']['is_send_admin'] = 0;
        $this->modx->map['msOrder']['fieldMeta']['is_send_admin'] = [
            'dbtype' => 'tinyint',
            'precision' => '1',
            'phptype' => 'boolean',
            'null' => TRUE,
        ];

        // rank
        $this->modx->loadClass('msProductLink');
        $this->modx->map['msProductLink']['fields']['rank'] = 0;
        $this->modx->map['msProductLink']['fieldMeta']['rank'] = [
            'dbtype' => 'float',
            'precision' => '12,6',
            'phptype' => 'float',
            'null' => FALSE,
        ];
    }

    /**
     * @param       $action
     * @param array $data
     *
     * @return array|bool|mixed
     */
    public function runProcessor($action, array $data = [])
    {
        $action = 'web/' . trim($action, '/');
        /** @var modProcessorResponse $response */
        $response = $this->modx->runProcessor($action, $data, ['processors_path' => $this->config['processorsPath']]);
        if ($response) {
            $data = $response->getResponse();
            if (is_string($data)) {
                $data = json_decode($data, TRUE);
            }

            return $data;
        }

        return FALSE;
    }


    /**
     * @param modSystemEvent $event
     * @param array $scriptProperties
     */
    public function handleEvent(modSystemEvent $event, array $scriptProperties)
    {
        extract($scriptProperties);
        switch ($event->name) {
            case 'pdoToolsOnFenomInit':

                $modx = $this->modx;
                $pdo = $this->pdoTools;

                /** @var Fenom|FenomX $fenom */
                $fenom->addAllowedFunctions([
                    'array_keys',
                    'array_values',
                ]);
                $fenom->addModifier('getCart', function ($input) use ($modx) {
                    if ($miniShop2 = $modx->getService('miniShop2')) {
                        $miniShop2->initialize($modx->context->key);
                        return $miniShop2->cart->status();
                    }
                    return FALSE;
                });
                $fenom->addAccessorSmart('SiteDev', 'SiteDev', Fenom::ACCESSOR_PROPERTY);
                $fenom->App = $this;

                #$fenom->addAccessorSmart('en', 'en', Fenom::ACCESSOR_PROPERTY);
                #$fenom->en = $this->modx->getOption('cultureKey') == 'en';

                $fenom->addAccessorSmart('assets_version', 'assets_version', Fenom::ACCESSOR_PROPERTY);
                $fenom->assets_version = $this::assets_version;


                // Регистрация версионированных скриптов в конце страницы
                $fenom->addModifier('script', function ($input) use ($modx) {
                    $cdn = $modx->getOption('site_cdn', NULL, '/inc/');
                    $path = $cdn . $assets_source . $input . "?v=" . $modx->getOption('assets_version');
                    $modx->regClientScript($path);

                });

                // Регистрация версионированных скриптов в конце страницы
                $fenom->addModifier('css', function ($input) use ($modx) {
                    $cdn = $modx->getOption('site_cdn', NULL, '/inc/');
                    $path = $cdn . $input . "?v=" . $modx->getOption('assets_version');
                    return '<link rel="stylesheet" href="' . $path . '">';

                });

                // Вырезает все кроме цифр
                $fenom->addModifier('sum_price', function ($product) use ($modx) {
                    $price = preg_replace('/[^0-9\.]/', '', $product['price']);
                    $count = $product['count'];
                    $total = $price * $count;
                    return formatPrice($total);
                });

                // Вырезает все кроме цифр
                $fenom->addModifier('number_price', function ($input) use ($modx) {
                    $input = preg_replace('/[^0-9\.]/', '', $input);
                    return $input;
                });

                // Вырезает все кроме цифр
                $fenom->addModifier('comparison_ids', function () use ($modx) {
                    if (!empty($_SESSION['Comparison']['web']['default']['ids'])) {
                        $list = array_keys(array_filter($_SESSION['Comparison']['web']['default']['ids']));
                        return implode(',', $list);
                    }
                    return NULL;
                });


                // Разбивает колонки на 3 части
                $fenom->addModifier('menu_column', function ($parent) use ($modx) {

                    $rows = [];
                    $q = $modx->newQuery('msCategory');
                    $q->select('id');
                    $q->where([
                        'parent' => $parent,
                        'published' => 1,
                        'deleted:!=' => 1,
                    ]);
                    $q->sortby('menuindex');
                    if ($q->prepare() && $q->stmt->execute()) {
                        while ($row = $q->stmt->fetch(PDO::FETCH_ASSOC)) {
                            $rows[] = $row['id'];
                        }
                    }

                    $len = count($rows);
                    $col = ceil($len / 3);
                    $array_chunk = array_chunk($rows, $col);
                    foreach ($array_chunk as $k => $re) {
                        $array_chunk[$k] = implode(',', $re);
                    }
                    return $array_chunk;
                });

                $fenom->addModifier('strtr', function ($str, $arr) {
                    return strtr($str, $arr);
                });

                $fenom->addModifier('cache', function ($name, $arr = []) use ($modx) {
                    $cacheSnippet = $arr['cacheSnippet'];
                    $cacheExpire = $arr['cacheExpire'];
                    return \Traineratwot\Cache\Cache::call($name, function () use ($cacheSnippet, $arr, $modx) {
                        return $modx->runSnippet($cacheSnippet, $arr);
                    }, $cacheExpire, 'fenom', FALSE);
                }
                );

                $fenom->addModifier('getIDByName', function ($name) {
                    $hash = hash('sha256', $name);
                    $id = preg_replace("@\D+@", "", $hash);
                    $id = (int)substr($id, 0, 6);
                    return $id;
                }
                );


                // Количество товаров в корзине
                $fenom->addModifier('count_cart', function ($id) use ($modx) {
                    /*   if (!empty($_SESSION['minishop2']['cart'])) {
                           foreach ($_SESSION['minishop2']['cart'] as $item) {
                               if ($id == $item['id']) {
                                   return $item['count'];
                               }
                           }
                       }*/
                    return 1;
                });
                $fenom->addModifier('true_price', function ($id) use ($modx) {
                    /** @var msProduct $product */
                    $product = $modx->getObject('msProduct', $id);
                    $price = $product->getPrice();
                    return $price;
                });
                $fenom->addModifier('float', function ($str) {
                    /** @var msProduct $product */
                    $str = strtr($str, [
                        " " => "",
                        "," => ".",
                    ]);
                    if (is_numeric($str)) {
                        return (float)$str;
                    }
                    return FALSE;
                });

                $fenom->addModifier('getCatalogsFromYandexDisk', function ($vendor) use ($modx) {
                    $a = (new YandexDisk($modx))->vendors($vendor);
                    $res = [];
                    foreach ($a as $i => $item) {
                        $res[$i]['ext'] = $item['ext'];
                        $res[$i]['size'] = $item['size'];
                        $res[$i]['name'] = $item['base_name'];
                        $res[$i]['yanedx_link'] = $item['url'];
                        $res[$i]['link'] = $item['save_url'];
                        $res[$i]['size'] = $item['size'];
                    }
                    return $res;
                });


                // Регистрация версионированных скриптов в конце страницы
                $fenom->addModifier('cdn', function ($input) {
                    if (empty($input)) {
                        return '';
                    }
                    return getenv('SITE_CDN') . ltrim($input, '/');
                });

                $this->modx->setPlaceholder('mode_dev', getenv('ENV') === 'dev');

                break;
            case 'OnHandleRequest':

                if (!empty($_REQUEST['q'])) {
                    $q = $_REQUEST['q'];
                    switch ($q) {
                        case 'ru':
                        case 'ru/':
                        case 'en':
                        case 'en/':
                        case 'it':
                        case 'it/':
                        case 'index.html':

                            //
                            $this->modx->sendRedirect(1, ['responseCode' => 'HTTP/1.1 301 Moved Permanently']);
                            break;
                        default:
                            break;
                    }

                    // Редирект с внутрених страниц
                    $first = substr($_SERVER['REQUEST_URI'], 0, 4);
                    if ($first === '/ru/' || $first === '/en/' || $first === '/it/') {
                        $new_url = substr($_SERVER['REQUEST_URI'], 3);
                        $site_url = rtrim($this->modx->getOption('site_url'), '/') . $new_url;
                        $this->modx->sendRedirect($site_url, ['responseCode' => 'HTTP/1.1 301 Moved Permanently']);
                    }

                }


                break;
            case 'msOnBeforeAddToCart':
            case 'msOnChangeInCart':


                $count = $scriptProperties['count'];
                $product = $scriptProperties['product'];
                if ($product instanceof msProduct) {
                    $product_id = $product->get('id');
                    $product_stock = $product->get('stock');
                    if (!empty($_SESSION['minishop2']['cart'])) {
                        foreach ($_SESSION['minishop2']['cart'] as $item) {
                            if ($product_id == $item['id']) {
                                $cart_count = (int)$item['count'];
                                $count = $count + $cart_count;
                            }
                        }
                    }
                    if ($product_stock < $count) {
                        $allowed = $product_stock - $product_stock;
                        $msg = 'Вы уже указали максимальное количество по наличию на складе';
                        if ($allowed > 0) {
                            $msg .= 'Доступное количество для добавления в корзину: ' . $allowed . ' шт.';
                        }
                        $this->modx->event->output($msg);
                    }
                }


                break;
            case 'msOnBeforeCreateOrder':
                $this->loadPluginsClass($event->name, $event, $scriptProperties);
                break;
            case 'OnLoadWebDocument':
                $this->loadFilterParams();
                if ($this->modx->resource->class_key === 'msProduct') {
                    if (!empty($this->modx->resource->get('lamp_socket'))) {
                        $lamp_socket = $this->modx->resource->get('lamp_socket');
                        $lam = is_array($lamp_socket) ? $lamp_socket : explode(',', $lamp_socket);
                        $this->modx->setPlaceholder('criteria_lamp_soket', $this->modx->toJSON([
                            'lamp_socket:IN' => $lam,
                        ]));
                    }
                }
                break;

        }
    }


    private $events = NULL;

    /**
     * @param                $load_event
     * @param modSystemEvent $event
     * @param array $scriptProperties
     */
    public function loadPluginsClass($load_event, modSystemEvent $event, $scriptProperties = [])
    {

        if (is_null($this->events)) {

            $this->config['eventsPath'] = $this->config['corePath'] . 'events/';
            if (!class_exists('fdkEventsHandler')) {
                include_once $this->config['corePath'] . 'events/fdkEventsHandler.php';
            }

            // Original plugins
            $events = scandir($this->config['eventsPath']);
            foreach ($events as $fileEvent) {
                if ($fileEvent == '.' || $fileEvent == '..' || $fileEvent == '_default.php') {
                    continue;
                }
                $file = $this->config['eventsPath'] . $fileEvent;
                if (file_exists($file)) {
                    /** @noinspection PhpIncludeInspection */
                    $include = include_once($file);
                    if (!empty($include['events']) and is_array($include['events'])) {
                        $class_include = $include['class'];
                        foreach ($include['events'] as $pluginEvent) {
                            $this->events[$pluginEvent][] = $class_include;
                        }
                    }
                }
            }
        }

        if (array_key_exists($load_event, $this->events)) {
            $classes = $this->events[$load_event];
            if (is_array($classes) and count($classes) > 0) {
                foreach ($classes as $class) {
                    if (class_exists($class)) {
                        $handlerClass = new $class($this->modx);
                        if (method_exists($handlerClass, $load_event)) {
                            $handlerClass->{$load_event}($event, $scriptProperties);
                        }
                    }
                }
            }
        }
    }


    public function searchParams()
    {
        $fields = [
            # 'sub_category' => 'ms|sub_category',
            'price' => 'ms|price:number',


            'new' => 'ms|new:boolean',
            'sale' => 'ms|sale:boolean',
            'under_order' => 'ms|under_order:boolean',
            'in_stock' => 'ms|in_stock:boolean',
            'file_is_3d_model' => 'ms|file_is_3d_model:boolean',
            'lamp_type' => 'msoption|lamp_type',
            'lamp_style' => 'msoption|lamp_style',
            'mesto_prim' => 'msoption|mesto_prim',
            'ploshad_osvesheniya' => 'ms|ploshad_osvesheniya:number',
            'lamp_socket' => 'msoption|lamp_socket',
            'light_temperatures' => 'msoption|light_temperatures',

            'ip_class' => 'ms|ip_class',

            'armature_color' => 'msoption|armature_color',
            'collection' => 'ms|collection',
            'armature_material' => 'msoption|armature_material',
            'plafond_material' => 'msoption|plafond_material',

            'length' => 'ms|length:number',
            'height' => 'ms|height:number',
            'width' => 'ms|width:number',
            //					'weight' => 'ms|weight:number',
        ];

        foreach ($fields as $field => $filter) {
            $name = explode(':', $filter);
            $alias = $name[0];
            $filters[] = $filter;
            $aliases[] = $alias . '==' . $field;
            $tmp = explode('|', $alias);
            $table = $tmp[0];
            $title = $field;
            $help = '';
            /* @var modResource $object */
            if ($Field = $this->modx->getObject('msafField', ['name' => $field])) {
                $title = $Field->get('title');
                $help = $Field->get('help');
            }

            if ($field === 'ploshad_osvesheniya') {
                $title = 'пл. освещения, м2';
            }
            if ($field === 'lamp_socket') {
                $title = 'Тип цоколя';
            }
            $title = trim(str_ireplace('_web', '', $title));
            $this->modx->lexicon->set('mse2_filter_' . $table . '_' . $field, $title);
            $this->modx->lexicon->set('mse2_filter_' . $table . '_' . $field . '_hint', $help);
        }
        $this->modx->lexicon->set('mse2_filter_new_value', 'Новый');
        $this->modx->lexicon->set('mse2_filter_sale_value', 'Распродажа');
        $this->modx->lexicon->set('mse2_filter_in_stock_value', 'В наличии');
        $this->modx->lexicon->set('mse2_filter_under_order_value', 'Под заказ');


        #$this->modx->setPlaceholder('fdk_msearch_filters', implode(',', $filters));
        #$this->modx->setPlaceholder('fdk_msearch_aliases', implode(',', $aliases));


        $msearch = [
            'tpl' => '@FILE chunks/catalog/product/row.tpl',
            'tplOuter' => '@FILE chunks/catalog/wrapper.tpl',
            'frontend_css' => '',
            'limit' => 24,
            'element' => 'msProducts',
            'setMeta' => '1',
            'filters' => implode(',', $filters),
            'aliases' => implode(',', $aliases),
            'class' => 'msProduct',
            'innerJoin' => ['msCategory' => ['class' => 'msCategory', 'alias' => 'msCategory', 'on' => 'msProduct.parent = msCategory.id']],

            'tplFilter.outer.ploshad_osvesheniya' => '@FILE chunks/catalog/filters/default/outer.slider.tpl',
            'tplFilter.row.ploshad_osvesheniya' => '@FILE chunks/catalog/filters/default/row.number.tpl',

            'tplFilter.outer.price' => '@FILE chunks/catalog/filters/price/outer.tpl',
            'tplFilter.row.price' => '@FILE chunks/catalog/filters/price/row.tpl',

            'tplFilter.outer.length' => '@FILE chunks/catalog/filters/slider/outer.tpl',
            'tplFilter.row.length' => '@FILE chunks/catalog/filters/slider/row.tpl',
            'tplFilter.outer.height' => '@FILE chunks/catalog/filters/slider/outer.tpl',
            'tplFilter.row.height' => '@FILE chunks/catalog/filters/slider/row.tpl',
            'tplFilter.outer.width' => '@FILE chunks/catalog/filters/slider/outer.tpl',
            'tplFilter.row.width' => '@FILE chunks/catalog/filters/slider/row.tpl',

            'tplFilter.outer.new' => '@FILE chunks/catalog/filters/boolean/outer.tpl',
            'tplFilter.row.new' => '@FILE chunks/catalog/filters/boolean/row.tpl',
            'tplFilter.outer.sale' => '@FILE chunks/catalog/filters/boolean/outer.tpl',
            'tplFilter.row.sale' => '@FILE chunks/catalog/filters/boolean/row.tpl',
            'tplFilter.outer.in_stock' => '@FILE chunks/catalog/filters/boolean/outer.tpl',
            'tplFilter.row.in_stock' => '@FILE chunks/catalog/filters/boolean/row.tpl',
            'tplFilter.outer.under_order' => '@FILE chunks/catalog/filters/boolean/outer.tpl',
            'tplFilter.row.under_order' => '@FILE chunks/catalog/filters/boolean/row.tpl',

            'tplFilter.row.sub_category' => '@FILE chunks/catalog/filters/category/row.tpl',
            'tplFilter.outer.sub_category' => '@FILE chunks/catalog/filters/category/outer.tpl',

            'tplFilter.outer.default' => '@FILE chunks/catalog/filters/default/outer.tpl',
            'tplFilter.row.default' => '@FILE chunks/catalog/filters/default/row.tpl',

            'suggestionsMaxResults' => 500000,
            'suggestionsMaxFilters' => 500000,
            'suggestionsRadio' => '',
            'showEmptyFilters' => false,
            'suggestions' => 1,
            'suggestionsSliders' => 0,
            'showLog' => 0,
            'pageLimit' => 7,
            'tplPageWrapper' => '@INLINE <ul class="pagination">{$first}{$prev}{$pages}{$next}{$last}</ul>',
            'tplPageFirst' => '@INLINE <li class="page-item page-item-first"><a href="[[+href]]"></a></li>',
            'tplPageLast' => '@INLINE <li class="page-item page-item-last"><a href="[[+href]]"></a></li>',
            'tplPagePrev' => '@INLINE <li class="page-item page-item-prev"><a href="[[+href]]"></a></li>',
            'tplPageNext' => '@INLINE <li class="page-item page-item-next"><a href="[[+href]]"></a></li>',
            'tplPageSkip' => '@INLINE <li class="page-item disabled"><span>...</span></li>',


            'tplPageLastEmpty' => '@INLINE ',
            'tplPageFirstEmpty' => '@INLINE ',
            'tplPagePrevEmpty' => '@INLINE ',
            'tplPageNextEmpty' => '@INLINE ',
            'showZeroPrice' => 0,
        ];
        return $msearch;
    }

    public function loadFilterParams()
    {

        if ($this->modx->context->key != 'mgr' && $this->modx->resource->class_key === 'msCategory') {


            $this->modx->setPlaceholder('fdk_msearch', $this->searchParams());


            return TRUE;
//
//
//				$aliases = [];
//				$filters = [];
//
//				echo '<pre>';
//				print_r($filters);
//				die;
//
//				$tplsDefault = [
//					'slider' => [
//						'outer' => '@FILE chunks/catalog/filters/slider/outer.tpl',
//						'row' => '@FILE chunks/catalog/filters/slider/row.tpl',
//					],
//					'default' => [
//						'outer' => '@FILE chunks/catalog/filters/default/outer.tpl',
//						'row' => '@FILE chunks/catalog/filters/default/row.tpl',
//					],
//					'boolean' => [
//						'outer' => '@FILE chunks/catalog/filters/boolean/outer.tpl',
//						'row' => '@FILE chunks/catalog/filters/boolean/row.tpl',
//					],
//				];
//
//
//				$fieldsboolean = [];
//				$fieldsMetas = $this->modx->getFieldMeta('msProductData');
//				foreach ($fieldsMetas as $field => $meta) {
//					if ($meta['phptype'] == 'boolean') {
//						$fieldsboolean[] = $field;
//					}
//				}
//				$fieldsboolean = array_flip($fieldsboolean);
//
//				$filters = [];
//				$aliases = [];
//				$tpls = [];
//
//				$q = $this->modx->newQuery('sfField');
//				$q->select('alias,slider,mfilter_filter,mfilter_outer,mfilter_row,mfilter_name,mfilter_hint,class');
//				$q->where([
//					'mfilter' => 1,
//				]);
//				$q->sortby('rank', 'ASC');
//				if ($q->prepare() && $q->stmt->execute()) {
//					while ($row = $q->stmt->fetch(PDO::FETCH_ASSOC)) {
//
//						$class = $row['class'];
//						$slider = $row['slider'];
//						$alias = $row['alias'];
//						$filter = $row['mfilter_filter'];
//
//						$filters[] = $filter;
//
//						$prefix = 'msoption';
//						if (strripos($filter, 'ms|') !== FALSE) {
//							$prefix = 'ms';
//						}
//						$tmp = explode(':', $filter);
//						$aliases[] = $tmp[0] . '==' . $alias;
//
//						$this->modx->lexicon->set('mse2_filter_' . $prefix . '_' . $alias, $row['mfilter_name']);
//						$this->modx->lexicon->set('mse2_filter_' . $prefix . '_' . $alias . '_hint', $row['mfilter_hint']);
//						$mfilter_outer = trim($row['mfilter_outer']);
//
//
//						$tplKey = 'default';
//						if ($slider) {
//							$tplKey = 'slider';
//						} elseif (array_key_exists($alias, $fieldsboolean)) {
//							$tplKey = 'boolean';
//						}
//						$tpls['tplFilter.outer.' . $alias] = $tplsDefault[$tplKey]['outer'];
//						$tpls['tplFilter.row.' . $alias] = $tplsDefault[$tplKey]['row'];
//					}
//				}
//
//
//				$filtersRanks = [];
//				foreach ($filters as $filter) {
//					if (strripos($filter, ":") !== FALSE) {
//						$str = strpos($filter, ":");
//						$filter = substr($filter, 0, $str);
//					}
//					$filtersRanks[] = $filter;
//				}
//
//				$this->modx->setPlaceholder('seo_placeholder_ranks', $filtersRanks);
//
//
//				$filters = implode(',', $filters);
//				$aliases = implode(',', $aliases);
//
//
//				//&aliases=`test1|value==test1`
//
////&pageLinkScheme=`/[[+pageVarKey]]-[[+page]]`
//				$seoFilter = array_merge([
//					'tpl' => '@FILE chunks/catalog/product.row.tpl',
//					'frontend_css' => '',
//					'limit' => 36,
//					#'pageLinkScheme' => '[[+pageVarKey]]-[[+page]]/',
//					#'parents' => $_modx->resource.id,
//					'element' => 'msProducts',
//					'setMeta' => '1',
//					'filters' => $filters,
//					'aliases' => $aliases,
//					'class' => 'msProduct',
//					'innerJoin' => ['msCategory' => ['class' => 'msCategory', 'alias' => 'msCategory', 'on' => 'msProduct.parent = msCategory.id']],
//					'sortby' => 'msCategory.menuindex ASC,msProduct.menuindex',
//					'tplFilter.outer.default' => '@FILE chunks/catalog/filters/default/outer.tpl',
//					'tplFilter.row.default' => '@FILE chunks/catalog/filters/default/row.tpl',
//
//					'toSeparatePlaceholders' => 'collary.',
//					'ajaxMode' => 'default',
//					'tplPageLastEmpty' => '@INLINE ',
//					'tplPageFirstEmpty' => '@INLINE ',
//					'tplPagePrevEmpty' => '@INLINE ',
//					'tplPageNextEmpty' => '@INLINE ',
//					'suggestionsMaxResults' => 500000,
//					'suggestionsMaxFilters' => 500000,
//					'suggestionsRadio' => '',
//					'showEmptyFilters' => FALSE,
//
//					'includeThumbs' => 'small,medium',
//					'suggestions' => 1,
//					'suggestionsSliders' => 1,
//					'showLog' => 0,
//					'noJsInitialize' => 1,
//					'tplPageWrapper' => '@INLINE <ul class="pagination">{$first}{$prev}{$pages}{$next}{$last}</ul><div class="push40"></div>',
//				], $tpls);
//
//
//				$domain_id = $this->modx->getPlaceholder('sd.id');
//				// Сортировка по умолчанию индивидуально для каждого домена
//				$seoFilter = array_merge($seoFilter, [
//					'leftJoin' => [
//						'Domains' => [
//							'class' => 'SeodomainsProduct',
//							'on' => "Domains.domain_id = {$domain_id} AND Domains.product_id = Data.id",
//						],
//					],
//					'sortAliases' => [
//						'domain_sort' => 'Domains',
//					],
//					'sort' => 'domain_sort:asc',
//				]);
//
//				$seoFilter['aliases'] .= ',domain_sort|rank==domain_sort';
//				$this->modx->setPlaceholder('seo_placeholder', $seoFilter);
        }
    }
}
