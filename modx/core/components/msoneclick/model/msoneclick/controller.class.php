<?php
abstract class msoneclickDefaultController {
	/* @var modX $modx */
	public $modx;
	/* @var msOneClick $ms */
	public $ms;
	public $config;


	public function __construct(msOneClick $msoneclick, array $config = array()) {
		$this->modx = & $msoneclick->modx;
		$this->ms = & $msoneclick;
		$this->setDefault($config);
		$topics = $this->getLanguageTopics();
		foreach ($topics as $topic) {
			$this->modx->lexicon->load($topic);
		}
	}

	public function setDefault($config = array()) {$this->config = $config;}

	public function initialize($ctx = 'web') {return true;}

	public function getLanguageTopics() {return array('msoneclick:form');}

	public function getDefaultAction() {return 'defaultAction';}

	public function defaultAction() {return 'Default action of default controller';}

	/* This method returns an error response
	 *
	 * @param string $message A lexicon key for error message
	 * @param array $data Additional data, for example cart status
	 * @param array $placeholders Array with placeholders for lexicon entry
	 *
	 * @return array|string $response
	 * */
	public function error($message = '', $data = array(), $placeholders = array()) {
		$response = array(
			'success' => false
		,'message' => $this->modx->lexicon($message, $placeholders)
		,'data' => $data
		);

		return $this->config['json_response'] ? $this->modx->toJSON($response) : $response;
	}

	/* This method returns an success response
	 *
	 * @param string $message A lexicon key for success message
	 * @param array $data Additional data, for example cart status
	 * @param array $placeholders Array with placeholders for lexicon entry
	 *
	 * @return array|string $response
	 * */
	public function success($message = '', $data = array(), $placeholders = array()) {
		$response = array(
			'success' => true
		,'message' => $this->modx->lexicon($message, $placeholders)
		,'data' => $data
		);

		return $this->config['json_response'] ? $this->modx->toJSON($response) : $response;
	}

}