<?php

namespace MoveElevator\MeDynamicForm\Utility;

use \TYPO3\CMS\Core\Utility\MailUtility as Typo3MailUtility;
use \TYPO3\CMS\Core\Utility\GeneralUtility;
use \TYPO3\CMS\Core\SingletonInterface;

/**
 * Class FormUtility
 *
 * @package MoveElevator\MeDynamicForm\Utility
 */
class MailUtility implements SingletonInterface {
	/**
	 * Sends the Email
	 *
	 * @param string $recipient
	 * @param string $from
	 * @param string $subject
	 * @param string $message
	 * @param string $contentType
	 * @return boolean
	 */
	public static function sendMail($recipient, $from = '', $subject, $message, $contentType = 'text/plain') {
		if (trim($from) == '') {
			$from = Typo3MailUtility::getSystemFrom();
		}
		/* @var $mailer \TYPO3\CMS\Core\Mail\MailMessage */
		$mailer = GeneralUtility::makeInstance('TYPO3\CMS\Core\Mail\MailMessage');
		$mailer->setCharset('UTF-8');
		$mailer->setFrom($from);

		$mailer->setTo($recipient);
		$mailer->setSubject($subject);
		$mailer->setBody($message, $contentType);
		$mailer->send();

		return $mailer->isSent();
	}
}