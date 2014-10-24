<?php

if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPItoST43($_EXTKEY);


if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('extbase')) {
	\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin('MoveElevator.' . $_EXTKEY, 'DynamicForm', array(
			'DynamicForm' => 'form,send',
		),
		array(
			'DynamicForm' => 'form,send',
		)
	);
}