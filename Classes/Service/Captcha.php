<?php

namespace MoveElevator\MeDynamicForm\Service;


use TYPO3\CMS\Core\Utility\MathUtility;

class Captcha {

	const MODE_CALCULATOR = 1;
	const MODE_IMAGE = 2;
	const MODE_QUESTION = 3;

	protected $captchaMode = self::MODE_CALCULATOR;

	protected $captchaVars = array();

	public function __construct($captchaSettings) {
		switch ($captchaSettings['type']) {
			case 'CALCULATOR':
				$this->captchaMode = self::MODE_CALCULATOR;
				break;
			case 'IMAGE':
				$this->captchaMode = self::MODE_IMAGE;
				break;
			case 'QUESTION':
				$this->captchaMode = self::MODE_QUESTION;
				break;
			default:
				$this->captchaMode = self::MODE_CALCULATOR;
		}
	}

	/**
	 * @return array|boolean
	 */
	public function getCaptcha() {
		switch ($this->captchaMode) {
			case self::MODE_CALCULATOR:
				$this->initializeCalculator();
				break;
			case self::MODE_IMAGE:
				$this->initializeImage();
				break;
			case self::MODE_QUESTION:
				$this->initializeQuestion();
				break;
			default:
				return FALSE;
		}
		$this->writeCaptchaVarsToSession();

		return $this->captchaVars;
	}

	/**
	 * @return void
	 */
	protected function initializeCalculator() {
		$operandOne = (string)rand(5, 10);
		$operandTwo = (string)rand(1, 5);
		$operatorNumber = rand(0, 2);

		switch ($operatorNumber) {
			case 1:
				$operator = ' - ';
				break;
			case 2:
				$operator = ' * ';
				break;
			default:
				$operator = ' + ';
				break;
		}
		$this->captchaVars = array(
			'captchaResult' => MathUtility::calculateWithParentheses($operandOne . $operator . $operandTwo),
			'operandOne' => $operandOne,
			'operandTwo' => $operandTwo,
			'operator' => $operator,
		);
	}

	/**
	 * @todo must be implemented
	 * @return array
	 */
	protected function initializeImage() {

	}

	/**
	 * @todo must be implemented
	 * @return array
	 */
	protected function initializeQuestion() {

	}

	/**
	 * @return void
	 */
	protected function writeCaptchaVarsToSession() {
		$GLOBALS['TSFE']->fe_user->setKey('ses', 'me_dynamic_form_captcha', serialize($this->captchaVars));
		$GLOBALS['TSFE']->fe_user->storeSessionData();
	}

	/**
	 * @return string
	 */
	public function getCaptchaResult() {
		$captchaSessionData = $GLOBALS['TSFE']->fe_user->getKey('ses', 'me_dynamic_form_captcha');
		$captchaData = unserialize($captchaSessionData);
		if (isset($captchaData['captchaResult'])) {
			$captchaResult = $captchaData['captchaResult'];
		} else {
			$captchaResult = FALSE;
		}
		return $captchaResult;

	}
}