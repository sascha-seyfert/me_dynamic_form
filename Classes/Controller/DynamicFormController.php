<?php

namespace MoveElevator\MeDynamicForm\Controller;

use \TYPO3\CMS\Core\Utility\GeneralUtility;
use \TYPO3\CMS\Core\Utility\HttpUtility;
use \TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use \MoveElevator\MeShortlink\Utility\ShortlinkUtility;
use \MoveElevator\MeDynamicForm\Utility\SettingsUtility;

use \TYPO3\CMS\Extbase\Persistence\Generic\QueryResult;

/**
 * Class DynamicFormController
 *
 * @package MoveElevator\MeDynamicForm\Controller
 */
class DynamicFormController extends ActionController {
	/**
	 * @return void
	 */
	public function initializeAction() {
		SettingsUtility::prepareSettings($this->settings);
	}

	public function showAction(){

	}
}