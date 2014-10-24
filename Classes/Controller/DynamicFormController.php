<?php

namespace MoveElevator\MeDynamicForm\Controller;

use \TYPO3\CMS\Core\Utility\GeneralUtility;
use \TYPO3\CMS\Core\Utility\HttpUtility;
use \TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use \TYPO3\CMS\Core\Messaging\FlashMessage;
use \MoveElevator\MeDynamicForm\Utility\SettingsUtility;

use \TYPO3\CMS\Extbase\Persistence\Generic\QueryResult;

/**
 * Class DynamicFormController
 *
 * @package MoveElevator\MeDynamicForm\Controller
 */
class DynamicFormController extends ActionController {

	private $formName = '';

	/**
	 * @return void
	 */
	public function initializeAction() {
		SettingsUtility::prepareSettings($this->settings);
	}



	public function formAction() {
	}

	/**
	 * @return void
	 */
	public function sendAction() {
	}

	/**
	 * @return void
	 */
	protected function validateSettings() {
		if ($this->settings['formName'] === '') {
			$this->addFlashMessage('formName is missing!', 'Error', FlashMessage::WARNING);
		}
	}
}