<?php

namespace MoveElevator\MeDynamicForm\Controller;

use \TYPO3\CMS\Core\Utility\GeneralUtility,
	\TYPO3\CMS\Core\Utility\HttpUtility;
use \TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use \MoveElevator\MeShortlink\Utility\ShortlinkUtility;


use \TYPO3\CMS\Extbase\Persistence\Generic\QueryResult;

/**
 * Class DynamicFormController
 *
 * @package MoveElevator\MeDynamicForm\Controller
 */
class DynamicFormController extends ActionController {

	public function showAction(){
		var_dump($this->settings);
	}
}