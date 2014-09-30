<?php

if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

$extensionName = \TYPO3\CMS\Core\Utility\GeneralUtility::underscoredToUpperCamelCase($_EXTKEY);
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin($_EXTKEY, 'DynamicForm', 'm:e DynamicForm-Label');
$pluginSignature = strtolower($extensionName) . '_dynamic_form';
$TCA['tt_content']['types']['list']['subtypes_excludelist'][$pluginSignature] = 'layout,select_key,pages,recursive';
