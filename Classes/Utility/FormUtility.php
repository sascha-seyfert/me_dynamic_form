<?php

namespace MoveElevator\MeDynamicForm\Utility;

use \MoveElevator\MeLibrary\Utility\TyposcriptUtility;

/**
 * Class FormUtility
 *
 * @package MoveElevator\MeDynamicForm\Utility
 */
class FormUtility {
	/**
	 * @return array
	 */
	static function getAvailableForms() {
		$optionList = array();
		$settings = TyposcriptUtility::getTypoScriptSetup('tx_medynamicform', 'settings');

		if (is_array($settings)) {
			foreach ($settings as $formKey => $formSettings) {
				$optionList[substr($formKey,0,-1)] = $formSettings['formLabel'];
			}
		}

		return $optionList;
	}
}