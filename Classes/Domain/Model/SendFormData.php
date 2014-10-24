<?php

namespace MoveElevator\MeDynamicForm\Domain\Model;

use \MoveElevator\MeDynamicForm\Utility\SettingsUtility;

/**
 * Class SendForm
 *
 * @package MoveElevator\MeDynamicForm\Domain\Model
 */
class SendFormData extends AbstractBaseModel {

	/**
	 * @var \MoveElevator\MeDynamicForm\Domain\Model\SendForm
	 */
	protected $sendForm;

	/**
	 * @var string
	 */
	protected $field;

	/**
	 * @var string
	 */
	protected $value;

	/**
	 * @param string $field
	 */
	public function setField($field) {
		$this->field = $field;
	}

	/**
	 * @return string
	 */
	public function getField() {
		return $this->field;
	}

	/**
	 * @param string $value
	 */
	public function setValue($value) {
		$this->value = $value;
	}

	/**
	 * @return mixed
	 */
	public function getValue() {
		return $this->value;
	}

	/**
	 * @return array
	 */
	public function toArray() {
		return array(
			$this->getField() => $this->getValue()
		);
	}

	/**
	 * @return mixed
	 */
	public function getPreparedValue() {
		$value = $this->getValue();
		$settings = SettingsUtility::getTypoScriptSetupByForm($this->sendForm->getForm());

		if (isset($settings['formFields'][$this->field]['options'])) {
			$value = $settings['formFields'][$this->field]['options'][$value];
		}

		return $value;
	}

	/**
	 * @param \MoveElevator\MeDynamicForm\Domain\Model\SendForm $sendForm
	 * @return void
	 */
	public function setSendForm($sendForm) {
		$this->sendForm = $sendForm;
	}

	/**
	 * @return \MoveElevator\MeDynamicForm\Domain\Model\SendForm
	 */
	public function getSendForm() {
		return $this->sendForm;
	}
}