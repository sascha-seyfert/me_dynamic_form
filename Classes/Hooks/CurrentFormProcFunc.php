<?php

namespace MoveElevator\MeDynamicForm\Hooks;

use \MoveElevator\MeDynamicForm\Utility\FormUtility;

/**
 * Class CurrentFormProcFunc
 *
 * @package MoveElevator\MeDynamicForm\Hooks
 */
class CurrentFormProcFunc {
	/**
	 * @param array $config
	 * @return array
	 */
	public function getAvailableForms(array $config) {
		$availableForms = FormUtility::getAvailableForms();
		$optionList = array();
		if (is_array($availableForms)) {
			foreach ($availableForms as $key => $form) {
				$optionList[$key] = array($form, $key);
			}
		}
		$config['items'] = array_merge(
			$config['items'],
			$optionList,
			array()
		);

		return $config;
	}
}