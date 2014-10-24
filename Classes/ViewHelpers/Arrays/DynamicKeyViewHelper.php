<?php

namespace MoveElevator\MeDynamicForm\ViewHelpers\Arrays;

use \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Class DynamicKeyViewHelper
 *
 * @package MoveElevator\MeDynamicForm\ViewHelpers\Arrays
 */
class DynamicKeyViewHelper extends AbstractViewHelper {

	/**
	 * @param array|object $list
	 * @param string $key
	 * @return mixed
	 */
	public function render($list, $key) {
		if (is_array($list) && isset($list[$key])) {
			return $list[$key];
		}

		$getMethod = 'get' . ucfirst($key);

		try {
			return $list->$getMethod();
		} catch (Exception $e) {
			echo $e;
		}
	}
}
