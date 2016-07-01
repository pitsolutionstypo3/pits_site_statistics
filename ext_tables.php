<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

if (TYPO3_MODE === 'BE') {

	/**
	 * Registers a Backend Module
	 */
	\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
		'PITS.' . $_EXTKEY,
		'system',	 // Make module a submodule of 'system'
		'PitsSiteStatistics',	// Submodule key
		'',						// Position
		array(
			'Statistics' => 'index, filter, settings,addForm,addSave,list,editForm,updateSave,delete',
			
		),
		array(
			'access' => 'user,group',
			'icon'   => 'EXT:' . $_EXTKEY . '/ext_icon.gif',
			'labels' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_pitssitestatistics.xlf',
		)
	);

}

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'Site Statistics');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_pitssitestatistics_domain_model_statistics', 'EXT:pits_site_statistics/Resources/Private/Language/locallang_csh_tx_pitssitestatistics_domain_model_statistics.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_pitssitestatistics_domain_model_statistics');
