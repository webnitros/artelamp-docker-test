<?php


	class CrontabControllerCollectionPageGenerator extends modCrontabController
	{

		public function run()
		{
			$parent          = 39640;
			$template        = 14;
			$addPage         = $this->modx->prepare(<<<SQL
INSERT INTO `ara3_site_content` (`type`, `contentType`, `pagetitle`, `longtitle`, `description`, 
`alias`, `alias_visible`, `link_attributes`, `published`, `pub_date`, `unpub_date`, `parent`, 
`isfolder`, `introtext`, `content`, `richtext`, `template`, `menuindex`, 
`searchable`, `cacheable`, `createdby`, `createdon`, `editedby`, `editedon`, `deleted`, `deletedon`, 
`deletedby`, `publishedon`, `publishedby`, `menutitle`, `donthit`, `privateweb`, `privatemgr`, `content_dispo`,
`hidemenu`, `class_key`, `context_key`, `content_type`, `uri`, `uri_override`, `hide_children_in_tree`, `show_in_tree`) 
VALUES ('document', 'text/html', :collection, :collection, '', :alias, 1, '', :published, 0, 0, 
        :parent, 1, :introtext, '', 0, :template, :menuindex, 1, 1, 11, :now, 11, :now, 0, 0, 0, 0, 0, '', 
0, 0, 0, 0, 0, 'msCategory', 'web', 1, :uri, 0, 0, 0);
SQL
			);
			$collections     = $this->modx->query("SELECT DISTINCT collection FROM ara3_ms2_products")->fetchAll(PDO::FETCH_COLUMN);
			$collectionPages = $this->modx->query("SELECT pagetitle,id FROM ara3_site_content WHERE `parent`=$parent AND `template`=$template")->fetchAll(PDO::FETCH_KEY_PAIR);
			usort($collections, function ($a, $b) {
				return $a <=> $b;
			});
			foreach ($collections as $i => $collection) {
				if (!$this->nameTest($collection)) {
					continue;
				}
				if (array_key_exists($collection, $collectionPages)) {
					$id = $collectionPages[$collection];
					$this->modx->query("update ara3_site_content set menuindex=$i WHERE `id`=$id");
					continue;
				}
				$alias = modResource::filterPathSegment($this->modx, $collection);
				$addPage->execute(
					[
						'collection' => $collection,
						'alias'      => $alias,
						'introtext'  => "?in_stock=1&collection=" . $collection,
						'published'  => 1,
						'menuindex'  => $i,
						'parent'     => $parent,
						'template'   => $template,
						'now'        => time(),
						'uri'        => "collection/" . $alias,
					]
				);
			}
		}

		public function nameTest(string $name)
		{
			$m = [];
			return (bool)preg_match("@[[:ascii:]]+@", $name, $m);
		}
	}