<?php
	include_once __DIR__ . '/_default.php';

	/**
	 * Забор свойст из 1С и автоматическое создание поле из компонента msAddFiled
	 */
	class CrontabControllerSyncPropertyReference extends CrontabControllerSync
	{
		public function process()
		{

			// Забираем справочник полей
			$propertyReference = $this->propertyReference();

			// Сопоставление типо данных
			$arrayDiff = [
				'decimal' => 'decimal',
				'integer' => 'numberfield',
				'string'  => 'textfield',
				'boolean' => 'tinyint',
				'array'   => 'json',
				'text'    => 'textarea',
			];

			$xpdo_meta_map = [];
			include MODX_CORE_PATH . 'components/minishop2/model/minishop2/mysql/msproductdata.map.inc.php';
			$fields = $xpdo_meta_map['msProductData']['fields'];

			$fieldsProdcut = $this->modx->getFields('msProduct');


			$fieldsOffset = 'submit_to_artelamp_it,submit_to_site,submit_to_divinare_it,submit_to_technolight,errors,color,description,title';
			$fieldsOffset = array_merge($fields, array_flip(explode(',', $fieldsOffset)));
			$fieldsOffset = array_merge($fieldsProdcut, $fieldsOffset);
			foreach ($propertyReference as $item) {
				$type  = $item['type'];
				$field = $item['field'];

				// Все русские символы пропускаем
				if (preg_match("/[а-яё]/iu", $field)) {
					$this->modx->log(modX::LOG_LEVEL_ERROR, "Поле содержит русские буквы " . $field, '', __METHOD__, __FILE__, __LINE__);
					continue;
				}

				// Разрешено только латиница подчеркивание и цифры
				if (!preg_match("/[a-z0-9_]/i", $field)) {
					$this->modx->log(modX::LOG_LEVEL_ERROR, "Поле содержит русские буквы " . $field, '', __METHOD__, __FILE__, __LINE__);
					continue;
				}

				if (array_key_exists($field, $fieldsOffset)) {
					$Field = $this->modx->getObject('msafField', ['name' => $field]);
					if ($Field) {
						if (!$Field->hasField()) {
							$Field->addField();
							$Field->extension();
						}
					}
					continue;
				}
				if (array_key_exists($type, $arrayDiff)) {
					if (!$count = (boolean)$this->modx->getCount('msafField', ['name' => $field])) {
						/* @var msafField $Field */
						$Field = $this->modx->newObject('msafField');
						$Field->set('name', $field);
						$Field->set('title', $item['name']);
						$Field->set('type', $arrayDiff[$type]);
						$Field->set('indexes', FALSE);
						$Field->save();
						if (!$Field->hasField()) {
							$Field->addField();
							$Field->extension();
						}
					}
				}
			}

		}


	}