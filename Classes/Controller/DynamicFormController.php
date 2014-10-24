<?php

namespace MoveElevator\MeDynamicForm\Controller;

use MoveElevator\MeDynamicForm\Utility\MailUtility;
use \TYPO3\CMS\Core\Utility\GeneralUtility;
use \TYPO3\CMS\Core\Utility\HttpUtility;
use \TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use \TYPO3\CMS\Core\Messaging\FlashMessage;
use \TYPO3\CMS\Extbase\Persistence\Generic\QueryResult;
use \TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
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
		$this->sendEmails($sendForm);
	}

	/**
	 * @param \MoveElevator\MeDynamicForm\Domain\Model\SendForm $sendForm
	 */
	protected function sendEmails(SendForm $sendForm) {
		//@todo make emails better readable and format values
		if ($this->settings['mailSettings']['adminMail']['enabled'] === '1') {
			$this->sendAdminMail($sendForm);
		}

		if ($this->settings['mailSettings']['customerMail']['enabled'] === '1') {
			$this->sendCustomerMail($sendForm);
		}
	}

	/**
	 * @param \MoveElevator\MeDynamicForm\Domain\Model\SendForm $sendForm
	 * @return bool
	 */
	protected function sendAdminMail(SendForm $sendForm) {
		$view = $this->objectManager->get('TYPO3\CMS\Fluid\View\StandaloneView');
			foreach ($fields as $field => $value) {
		$templateFile = $this->settings['mailSettings']['adminMail']['templateFile'];
		if (!$templateFile || !is_readable($templateFile)) {
			$view->setTemplatePathAndFilename(ExtensionManagementUtility::extPath('me_dynamic_form') . 'Resources/Private/Templates/Mails/Admin.txt');
		} else {
			$view->setTemplatePathAndFilename($templateFile);
			}
		$view->assign('settings', $this->settings);
		$view->assign('sendForm', $sendForm);
					'sendForm' => $sendForm,
		$adminMailContent = $view->render();

		return MailUtility::sendMail(
			$this->settings['mailSettings']['adminMail']['receiver'],
			$this->settings['mailSettings']['adminMail']['from'],
			$this->settings['mailSettings']['adminMail']['subject'],
			$adminMailContent
		);
		}
	 * @param SendForm $sendForm
	 * @return bool
	protected function sendCustomerMail(SendForm $sendForm) {
		$templateFile = $this->settings['mailSettings']['customerMail']['templateFile'];
		if (!$templateFile || !is_readable($templateFile)) {
			$view->setTemplatePathAndFilename(ExtensionManagementUtility::extPath('me_dynamic_form') . 'Resources/Private/Templates/Mails/Customer.txt');
		} else {
			$view->setTemplatePathAndFilename($templateFile);
		}

		$salutationFields = explode(',', $this->settings['mailSettings']['customerMail']['salutationFields']);
		$salutation = '';
		foreach ($salutationFields as $salutationField) {
			$salutation .= $sendForm->getValueByField($salutationField) . ' ';
		}

		$view->assign('settings', $this->settings);
		$view->assign('sendForm', $sendForm);
		$view->assign('salutation', trim($salutation));
		$customerMailContent = $view->render();
		return MailUtility::sendMail(
			$sendForm->getValueByField($this->settings['mailSettings']['customerMail']['receiverField']),
			$this->settings['mailSettings']['customerMail']['from'],
			$this->settings['mailSettings']['customerMail']['subject'],
			$customerMailContent
		);
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