<?php

namespace MoveElevator\MeDynamicForm\Validation\Validator;

use \TYPO3\CMS\Core\Utility\GeneralUtility;
use \TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use \TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator;
use \TYPO3\CMS\Extbase\Validation\Validator\ValidatorInterface;
use \MoveElevator\MeDynamicForm\Utility\SettingsUtility;

/**
 * Class FormValidator
 *
 * @package MoveElevator\MeDynamicForm\Validation\Validator
 */
class SendFormValidator extends AbstractValidator implements ValidatorInterface {
	/**
	 * @var string
	 */
	protected $currentForm;

	/**
	 * @param \MoveElevator\MeDynamicForm\Domain\Model\SendForm|NULL $sendForm
	 * @return boolean
	 */
	public function isValid($sendForm) {
		$valid = TRUE;
		$this->currentForm = $sendForm['currentForm'];
		$settings = SettingsUtility::getTypoScriptSetupByForm($sendForm['currentForm']);

		foreach($settings['formFields'] as $property => $fieldConfiguration) {
			if (is_array($fieldConfiguration['rules'])) {
				foreach($fieldConfiguration['rules'] as $rule => $ruleValue) {

					if (!$this->validateRule($sendForm[$property], $rule, $ruleValue)) {
						$valid = FALSE;
						$this->addError(
							LocalizationUtility::translate(
								$this->getMessageKey($property, $rule),
								'me_dynamicform'
							),
							time(),
							array(
								'property' => $property,
								'rule' => $rule
							)
						);
					}
				}
			}
		}

		return $valid;
	}

	/**
	 * @param mixed $value
	 * @param array $rule
	 * @param mixed $ruleValue
	 * @return bool
	 */
	protected function validateRule($value, $rule, $ruleValue) {
		$valid = FALSE;

		$methodName = 'validate' . ucfirst($rule);
		if(method_exists($this, $methodName)) {
			$valid = $this->$methodName($value, $ruleValue);
		}

		return $valid;
	}

	/**
	 * @param string $property
	 * @param rules $rule
	 * @return string
	 */
	protected function getMessageKey($property, $rule) {
		return $this->currentForm . '.' . $property . '.' . $rule;
	}

	/**
	 * @param mixed $value
	 * @return bool
	 */
	protected function validateRequired($value) {
		if(empty($value)) {
			return FALSE;
		}

		return TRUE;
	}

	/**
	 * @param mixed $value
	 * @param int $ruleValue
	 * @return bool
	 */
	protected function validateMinLength($value, $ruleValue) {
		if(strlen($value) < intval($ruleValue)) {
			return FALSE;
		}

		return TRUE;
	}

	/**
	 * @param mixed $value
	 * @param int $ruleValue
	 * @return bool
	 */
	protected function validateMaxLength($value, $ruleValue) {
		if(strlen($value) > intval($ruleValue)) {
			return FALSE;
		}

		return TRUE;
	}

	/**
	 * @param string $value
	 * @return bool
	 */
	protected function validateEmail($value) {
		return GeneralUtility::validEmail($value);
	}
}