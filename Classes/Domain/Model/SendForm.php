<?php

namespace MoveElevator\MeDynamicForm\Domain\Model;

use \TYPO3\CMS\Extbase\Persistence\Generic\Exception\UnsupportedMethodException;
use \MoveElevator\MeCleverreach\Utility\SettingsUtility;

/**
 * Class SendForm
 *
 * @package MoveElevator\MeDynamicForm\Domain\Model
 */
class SendForm extends AbstractBaseModel {

	/**
	 * @var \TYPO3\CMS\Extbase\Object\ObjectManager
	 * @inject
	 */
	protected $objectManager;

	/**
	 * @var string
	 */
	protected $form;

	/**
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\MoveElevator\MeDynamicForm\Domain\Model\SendFormData>
	 */
	protected $fields;

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
	 * @param string $fieldName
	 * @return mixed
	 */
	public function getValueByField($fieldName) {
		$value = NULL;
		/**  @var \MoveElevator\MeDynamicForm\Domain\Model\SendFormData $field */
		foreach ($this->fields as $field) {
			if ($field->getField() === $fieldName) {
				$value = $field->getPreparedValue();
			}
		}

		return $value;
	}

	/**
	 * @param string $fieldName
	 * @param mixed $value
	 * @return void
	 */
	public function setValueByField($fieldName, $value) {

		$valueSaved = FALSE;

		if ($this->fields === NULL) {
			$this->fields = $this->objectManager->get('TYPO3\CMS\Extbase\Persistence\ObjectStorage');
		}

		/**  @var \MoveElevator\MeDynamicForm\Domain\Model\SendFormData $field */
		foreach ($this->fields as $field) {
			if ($field->getField() === $fieldName) {
				$value = $field->setValue($value);
				$valueSaved = TRUE;
			}
		}

		if ($valueSaved === FALSE) {
			/** @var \MoveElevator\MeDynamicForm\Domain\Model\SendFormData $sendFormData */
			$sendFormData = $this->objectManager->get('MoveElevator\MeDynamicForm\Domain\Model\SendFormData');
			$sendFormData->setSendForm($this);
			$sendFormData->setValue($value);
			$sendFormData->setField($fieldName);
			$this->fields->attach($sendFormData);
		}
	}

	/**
	 * @param string $form
	 */
	public function setForm($form) {
		$this->form = $form;
	}

	/**
	 * @return string
	 */
	public function getForm() {
		return $this->form;
	}

	/**
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
	 */
	public function getFields(){
		return $this->fields;
	}
}