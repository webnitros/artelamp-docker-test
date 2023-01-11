<?php


	class MyControllerConfigIsUserSession extends modRestController
	{
		public $protected = TRUE;

		public function get()
		{
			return $this->post();
		}

		public function post()
		{
//			$this->properties = json_decode(file_get_contents('php://input'), 1);
//			$sessionId        = $this->getProperty('sessionId');
//			if ($sessionId) {
//				/** @var smartSessionHandler $session */
//				$session = $this->modx->getService($this->modx->getOption('session_handler_class'));
//				$db      = $session->_getSession($sessionId, FALSE);
//				if ($db && (int)$db->get('user_id') > 0) {
//					$this->success('yes', TRUE);
//					return;
//				}
//				$this->success('no', FALSE);
//				return;
//			}
			$this->failure('empty request');
			session_write_close();
		}
	}