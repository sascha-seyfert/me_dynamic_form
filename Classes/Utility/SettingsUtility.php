<?php

namespace MoveElevator\MeDynamicForm\Utility;

use \TYPO3\CMS\Core\Utility\GeneralUtility;
use \TYPO3\CMS\Extbase\Service\TypoScriptService;

/**
 * Class SettingsUtility
 *
 * @package MoveElevator\MeDynamicForm\Utility
 */
class SettingsUtility {
	/**
	 * @param array &$settings
	 * @return void
	 */
	static public function prepareSettings(array &$settings) {
		if (is_array($settings) && is_array(self::getCurrentFormSettings($settings))) {
			$preparedSettings = self::getCurrentFormSettings($settings);
			$preparedSettings['formName'] = $settings['currentForm'];
			foreach($settings as $settingKey => $setting) {
				if(!is_array($setting) || !isset($setting['formFields'])) {
					$preparedSettings[$settingKey] = $setting;
				}
			}
			$settings = $preparedSettings;
		}
	}

	/**
	 * @param array $settings
	 * @return array
	 */
	static private function getCurrentFormSettings(array $settings) {
		if (
			isset($settings['currentForm'])
			&& !empty($settings['currentForm'])
			&& is_array($settings[$settings['currentForm']])
		) {
			return $settings[$settings['currentForm']];
		}

		return FALSE;
	}

	/**
	 * Gets TS for a plugin
	 *
	 * @param string $pluginKey (e.q. tx_metimeline)
	 * @param string $typoscriptKey (e.q. settings)
	 *
	 * @return array|bool
	 * @throws \TYPO3\CMS\Extbase\Exception
	 */
	static public function getTypoScriptSetupByForm($form) {

		$settings = FALSE;

		/** @var \TYPO3\CMS\Extbase\Object\ObjectManager $objectManager */
		$objectManager = GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Object\ObjectManager');

		/** @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManager $configurationManager */
		$configurationManager = $objectManager->get('TYPO3\CMS\Extbase\Configuration\ConfigurationManager');
		$typoScript = $configurationManager->getConfiguration('FullTypoScript');

		if (!is_array($typoScript['plugin.']['tx_medynamicform.']['settings.'])) {
			throw new Exception('no typoscript setup for plugin.' . $pluginKey, 1352897029);
		}

		/** @var \TYPO3\CMS\Extbase\Service\TypoScriptService $typoScriptService */
		$typoScriptService = $objectManager->get('TYPO3\CMS\Extbase\Service\TypoScriptService');
		$settings = $typoScriptService->convertTypoScriptArrayToPlainArray($typoScript['plugin.']['tx_medynamicform.']['settings.']);
		$settings['currentForm'] = $form;

		return self::getCurrentFormSettings($settings);
	}
}