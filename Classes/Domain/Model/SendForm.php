<?php

namespace MoveElevator\MeDynamicForm\Domain\Model;

use \TYPO3\CMS\Extbase\Persistence\Generic\Exception\UnsupportedMethodException;
use \TYPO3\CMS\Extbase\DomainObject\AbstractValueObject;
use \MoveElevator\MeCleverreach\Utility\SettingsUtility;

/**
 * Class SendForm
 *
 * @package MoveElevator\MeDynamicForm\Domain\Model
 */
class SendForm extends AbstractBaseModel {

	/**
	 * @var array
	 */
	protected $fields = array();

	/**
	 * @param string $methodName
	 * @param array $arguments
	 * @return mixed
	 * @throws UnsupportedMethodException
	 */
	public function __call($methodName, $arguments) {
		$methodType = substr($methodName, 0, 3);
		$field = lcfirst(substr($methodName, 3));

		switch ($methodType) {
			case 'get':
					return $this->getValueByField($field);
				break;
			case 'set':
					$this->setValueByField($field, $arguments['value']);
				break;
			default:
				throw new UnsupportedMethodException('The method "' . $methodName . '" is not supported by the model.', 1233180480);
		}
	}

	/**
	 * @param string $field
	 * @return mixed
	 */
	protected function getValueByField($field) {
		$value = NULL;

		if (is_array($this->fields) && isset($this->fields[$field])) {
			$value = $this->fields[$field];
		}

		return $value;
	}

	/**
	 * @param string $field
	 * @param mixed $value
	 * @return void
	 */
	public function setValueByField($field, $value) {
		if (is_array($this->fields)) {
			$this->fields[$field] = $value;
		}
	}
}


//	/**  @var \MoveElevator\MeDynamicForm\Domain\Model\SendFormData $field */
//foreach($this->fields as $field) {
//if ($field->getField() === $field) {
//$value = $field->getValue();
//}
//}