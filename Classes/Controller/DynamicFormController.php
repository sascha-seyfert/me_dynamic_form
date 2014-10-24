<?php

namespace MoveElevator\MeDynamicForm\Controller;

use \TYPO3\CMS\Core\Utility\GeneralUtility;
use \TYPO3\CMS\Core\Utility\HttpUtility;
use \TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use \TYPO3\CMS\Core\Messaging\FlashMessage;
use \TYPO3\CMS\Extbase\Persistence\Generic\QueryResult;
use \MoveElevator\MeDynamicForm\Utility\SettingsUtility;
use \MoveElevator\MeDynamicForm\Domain\Model\SendForm;

/**
 * Class DynamicFormController
 *
 * @package MoveElevator\MeDynamicForm\Controller
 */
class DynamicFormController extends ActionController {

	/**
	 * @var \MoveElevator\MeDynamicForm\Domain\Repository\SendFormRepository
	 * @inject
	 */
	protected $sendFormRepository;

	/**
	 * @return void
	 */
	public function initializeAction() {
		SettingsUtility::prepareSettings($this->settings);
	}



	public function formAction() {
	}

	/**
	 * @return void
	 */
	public function initializeSendAction() {
		if ($this->request->hasArgument('fields')) {
			/** @var \MoveElevator\MeDynamicForm\Domain\Model\SendForm' $sendForm */
			$sendForm = $this->objectManager->get('\MoveElevator\MeDynamicForm\Domain\Model\SendForm');
			$sendForm->setForm($this->settings['formName']);
			foreach($this->request->getArgument('fields') as $field => $value) {
				$sendForm->setValueByField($field, $value);
			}
			$this->request->setArgument('sendForm', $sendForm);
		}
	}

	/**
	 * @param \MoveElevator\MeDynamicForm\Domain\Model\SendForm $sendForm
	 * @return void
	 */
	public function sendAction(SendForm $sendForm) {

		$this->sendFormRepository->add($sendForm);
		\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($sendForm);
		\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($sendForm->getPrivacyPolicity());
	}

	/**
	 * @return void
	 */
	protected function validateSettings() {
		if ($this->settings['formName'] === '') {
			$this->addFlashMessage('formName is missing!', 'Error', FlashMessage::WARNING);
		}
	}
}