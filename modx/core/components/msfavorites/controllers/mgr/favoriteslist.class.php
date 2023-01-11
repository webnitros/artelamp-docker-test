<?php
require_once dirname(dirname(dirname(__FILE__))) . '/index.class.php';

class ControllersFavoritesListManagerController extends msFavoritesMainController {

    public static function getDefaultController() {
        return 'favoriteslist';
    }

}

class msFavoritesFavoritesListManagerController extends msFavoritesMainController {

    public function getPageTitle() {
        return $this->modx->lexicon('msfavorites') . ' :: ' . $this->modx->lexicon('msfavorites_favoriteslist');
    }

    public function getLanguageTopics() {
        return array('msfavorites:default');
    }

    public function loadCustomCssJs() {
        $this->addJavascript(MODX_MANAGER_URL . 'assets/modext/util/datetime.js');
        $this->addJavascript($this->msFavorites->config['jsUrl'] . 'mgr/favoriteslist/userslist.grid.js');
        $this->addJavascript($this->msFavorites->config['jsUrl'] . 'mgr/favoriteslist/favoriteslist.grid.js');
        $this->addJavascript($this->msFavorites->config['jsUrl'] . 'mgr/favoriteslist/favoriteslist.panel.js');


        $grid_fields = array_map('trim', explode(',', $this->modx->getOption('msfavorites_favoriteslist_grid_fields', null, 'id,name,total,favorites', true)));

        $this->addHtml(str_replace('			', '', '
			<script type="text/javascript">

				msFavorites.config.favoriteslist_grid_fields = ' . $this->modx->toJSON($grid_fields) . ';


				Ext.onReady(function() {
					MODx.load({ xtype: "msfavorites-page-favoriteslist"});
				});
			</script>'
        ));
    }

    public function getTemplateFile() {
        return $this->msFavorites->config['templatesPath'] . 'mgr/favoriteslist.tpl';
    }
 
}

// MODX 2.3
class ControllersMgrFavoritesListManagerController extends ControllersFavoritesListManagerController {

    public static function getDefaultController() {
        return 'mgr/favoriteslist';
    }

}

class msFavoritesMgrFavoritesListManagerController extends msFavoritesFavoritesListManagerController {

}