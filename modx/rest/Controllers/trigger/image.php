<?php

	class MyControllerTriggerImage extends ApiInterface
	{
		public function post()
		{
			$input = json_encode($this->properties, JSON_PRETTY_PRINT | 256);
			file_put_contents(__DIR__ . '/test.json', $input);
			$article  = $this->getProperty('article');
			$force    = (boolean)$this->getProperty('force');
			$triggers = $this->getProperty('triggers');
			if (!empty($triggers)) {
				$errorTriggers = $triggers;
				$ids           = [];
				$q             = $this->modx->newQuery('msProductData');
				$q->select('id,article');
				$q->where([
							  'article:IN' => array_keys($triggers),
						  ]);
				if ($q->prepare() && $q->stmt->execute()) {
					while ($row = $q->stmt->fetch(PDO::FETCH_ASSOC)) {
						$article = $row['article'];
						if (array_key_exists($article, $errorTriggers)) {
							unset($errorTriggers[$article]);
						}
						$ids[] = $row['id'];
					}
				}

				$count = 0;
				if (!empty($ids)) {
					$ids   = implode(',', $ids);
					$table = $this->modx->getTableName('msProductData');
					$count = $this->modx->exec("UPDATE {$table} SET update_images = '1'  WHERE id IN ({$ids})");
				}
				$this->success('', [
					'error_404'    => array_values($errorTriggers),
					'generate'     => 'completed',
					'total_update' => $count,
				]);
			} else {

				if (empty($article)) {
					$this->failure('Укажите артикул', [], 500);
				} else {
					/* @var msProductData $object */
					if ($object = $this->modx->getObject('msProductData', ['article' => $article])) {
						if (!$force) {
							$object->set('update_images', TRUE);
							if ($object->save()) {
								$this->success('', [
									'article'    => $article,
									'product_id' => $object->get('id'),
									'generate'   => 'completed',
								]);
							}
						} else {
							$data = [
								'product_id' => $object->get('id'),
							];
                            $status_code = 1000;
                            try {
                                include_once MODX_CORE_PATH . 'classes/fdkNewDownloadImages.php';
                                $this->fdkNewDownloadImages = new fdkNewDownloadImages($this->modx);
                                $status_code = $this->fdkNewDownloadImages->getImages($object, $article);
                                $message = $this->fdkNewDownloadImages->errorMsg;
                            } catch (Exception $e) {
                                $this->failure($e->getMessage(), [], 500);
                            }
                            if ($status_code === 200) {
                                $this->success('', [
                                    'article' => $article,
                                    'product_id' => $object->get('id'),
                                    'generate' => 'completed'
                                ]);
                            } else {
                                $this->failure($message, $data, $status_code);
                            }
						}
					} else {
						$this->failure('Товар не найден', [
							'article' => $article,
						],             404);
					}
				}
			}
		}

	}
