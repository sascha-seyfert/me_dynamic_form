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
		if (isset($this->settings['formConfiguration']['spamProtection']['enabled']) && $this->settings['formConfiguration']['spamProtection']['enabled'] === '1') {
			/** @var \MoveElevator\MeDynamicForm\Service\Captcha $captchaService */
			$captchaService = $this->objectManager->get('MoveElevator\MeDynamicForm\Service\Captcha', $this->settings['formConfiguration']['spamProtection']);
			$this->view->assign('captchaVars', $captchaService->getCaptcha());
		}
	}

	/**
	 * @return void
	 */
	public function initializeSendAction() {
		if ($this->request->hasArgument('fields') === FALSE || !is_array($this->request->getArgument('fields'))) {
			$this->redirect('form');
		}

		$this->generateSendFormModel();
	}

	/**
	 * @param array $fields
	 * @param \MoveElevator\MeDynamicForm\Domain\Model\SendForm $sendForm
	 * @validate $fields MoveElevator.MeDynamicForm:SendFormValidator
	 * @return void
	 */
	public function sendAction(array $fields = array(), SendForm $sendForm) {

		$this->view->assignMultiple(
			array(
				'formData' => $sendForm
			)
		);

		$this->sendFormRepository->add($sendForm);
	}

	/**
	 * @return void
	 */
	protected function validateSettings() {
		if ($this->settings['formName'] === '') {
			$this->addFlashMessage('formName is missing!', 'Error', FlashMessage::WARNING);
		}
	}

	protected function generateSendFormModel() {
		if ($this->request->hasArgument('fields')) {
			$fields = $this->request->getArgument('fields');
			/** @var \MoveElevator\MeDynamicForm\Domain\Model\SendForm' $sendForm */
			$sendForm = $this->objectManager->get('\MoveElevator\MeDynamicForm\Domain\Model\SendForm');
			$sendForm->setForm($this->settings['formName']);
			$fields['currentForm'] = $this->settings['formName'];
			foreach ($fields as $field => $value) {
				$sendForm->setValueByField($field, $value);
			}
			$this->request->setArguments(
				array(
					'sendForm' => $sendForm,
					'fields' => $fields
				)
			);
		}
	}
}