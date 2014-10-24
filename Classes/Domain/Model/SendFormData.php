<?php

namespace MoveElevator\MeDynamicForm\Domain\Model;

/**
 * Class SendForm
 *
 * @package MoveElevator\MeDynamicForm\Domain\Model
 */
class SendFormData extends AbstractBaseModel {
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
	 * @return string
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
}