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
		if ($this->request->hasArgument('fields')) {
			/** @var \MoveElevator\MeDynamicForm\Domain\Model\SendForm' $sendForm */
			$sendForm = $this->objectManager->get('\MoveElevator\MeDynamicForm\Domain\Model\SendForm');
			$sendForm->setForm($this->settings['formName']);
			foreach ($this->request->getArgument('fields') as $field => $value) {
				$sendForm->setValueByField($field, $value);
			}
			$this->request->setArgument('sendForm', $sendForm);
		} else {
			$this->redirect('form');
		}
	}

	/**
	 * @param \MoveElevator\MeDynamicForm\Domain\Model\SendForm $sendForm
	 * @return void
	 */
	public function sendAction(SendForm $sendForm) {
		$this->view->assignMultiple(
			array(
				'formData' => $sendForm
			)
		);

		if ($this->settings['mailSettings']['adminMail']['receiver']) {

		}
		$this->sendFormRepository->add($sendForm);
		$this->sendEmails($sendForm);
	}

	/**
	 * @return void
	 */
	protected function validateSettings() {
		if ($this->settings['formName'] === '') {
			$this->addFlashMessage('formName is missing!', 'Error', FlashMessage::WARNING);
		}
	}

	/**
	 * @param \MoveElevator\MeDynamicForm\Domain\Model\SendForm $sendForm
	 */
	protected function sendEmails(SendForm $sendForm) {
		//@todo make emails better readable and format values
		if ($this->settings['mailSettings']['adminMail']['enabled'] === '1') {
			$view = $this->objectManager->get('TYPO3\CMS\Fluid\View\StandaloneView');

			$templateFile = $this->settings['mailSettings']['adminMail']['templateFile'];
			if (!$templateFile || !is_readable($templateFile)) {
				$view->setTemplatePathAndFilename(ExtensionManagementUtility::extPath('me_dynamic_form') . 'Resources/Private/Templates/Mails/Admin.txt');
			} else {
				$view->setTemplatePathAndFilename($templateFile);
			}
			$view->assign('settings', $this->settings);
			$view->assign('sendForm', $sendForm);

			$adminMailContent = $view->render();

			MailUtility::sendMail(
				$this->settings['mailSettings']['adminMail']['receiver'],
				$this->settings['mailSettings']['adminMail']['from'],
				$this->settings['mailSettings']['adminMail']['subject'],
				$adminMailContent
			);
		}

		if ($this->settings['mailSettings']['customerMail']['enabled'] === '1') {
			$view = $this->objectManager->get('TYPO3\CMS\Fluid\View\StandaloneView');

			$templateFile = $this->settings['mailSettings']['customerMail']['templateFile'];
			if (!$templateFile || !is_readable($templateFile)) {
				$view->setTemplatePathAndFilename(ExtensionManagementUtility::extPath('me_dynamic_form') . 'Resources/Private/Templates/Mails/Customer.txt');
			} else {
				$view->setTemplatePathAndFilename($templateFile);
			}

			$salutationFields = explode(',', $this->settings['mailSettings']['customerMail']['salutationFields']);
			$salutation = '';
			foreach($salutationFields as $salutationField){
				$salutation .= $sendForm->getValueByField($salutationField) . ' ';
			}

			$view->assign('settings', $this->settings);
			$view->assign('sendForm', $sendForm);
			$view->assign('salutation', trim($salutation));
			var_dump($this->settings['mailSettings']['customerMail']['receiverField']);
			$customerMailContent = $view->render();
			MailUtility::sendMail(
				$sendForm->getValueByField($this->settings['mailSettings']['customerMail']['receiverField']),
				$this->settings['mailSettings']['customerMail']['from'],
				$this->settings['mailSettings']['customerMail']['subject'],
				$customerMailContent
			);
		}
	}

	protected function getEmailBody($formData, $addressType) {
		if (isset($this->settings['mailSettings'][$addressType]['template'])) {
			/** @var \TYPO3\CMS\Fluid\View\StandaloneView $view */
			$view = $this->createView($addressType);
			$view->assign('products', $this->productsInBasket);
			$view->assign('settings', $this->settings);
			$view->assign('order', $order);

			return $view->render();
		}

		return NULL;
	}

	/**
	 * @param string $addressType
	 * @return \TYPO3\CMS\Fluid\View\StandaloneView
	 */
	protected function createView($addressType) {
		$templatePath = PATH_site . $this->settings['mailSettings'][$addressType]['template'];
		$partialsPath = PATH_site . $this->settings['order']['email']['templates']['partials'];
		/** @var \TYPO3\CMS\Fluid\View\StandaloneView $view */
		$view = $this->objectManager->get('TYPO3\CMS\Fluid\View\StandaloneView');
		$view->setTemplatePathAndFilename($templatePath);
		$view->setPartialRootPath($partialsPath);

		return $view;
	}
}