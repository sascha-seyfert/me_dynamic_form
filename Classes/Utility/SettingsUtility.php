<?php

namespace MoveElevator\MeDynamicForm\Utility;

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
}