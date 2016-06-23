<?php
return array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:pits_site_statistics/Resources/Private/Language/locallang_db.xlf:tx_pitssitestatistics_domain_model_statistics',
		'label' => 'service_account_email',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,
		'versioningWS' => 2,
		'versioning_followPages' => TRUE,

		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),
		'searchFields' => 'service_account_email,key_file_location',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('pits_site_statistics') . 'Resources/Public/Icons/tx_pitssitestatistics_domain_model_statistics.gif'
	),
	'interface' => array(
		'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden,service_account_email, key_file_location,web_title,property_trackingid,default_account',
	),
	'types' => array(
		'1' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden;;1, service_account_email, key_file_location,web_title,property_trackingid,default_account --div--;LLL:EXT:cms/locallang_ttc.xlf:tabs.access, starttime, endtime'),
	),
	'palettes' => array(
		'1' => array('showitem' => ''),
	),
	'columns' => array(
	
		'sys_language_uid' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.language',
			'config' => array(
				'type' => 'select',
				'renderType' => 'selectSingle',
				'foreign_table' => 'sys_language',
				'foreign_table_where' => 'ORDER BY sys_language.title',
				'items' => array(
					array('LLL:EXT:lang/locallang_general.xlf:LGL.allLanguages', -1),
					array('LLL:EXT:lang/locallang_general.xlf:LGL.default_value', 0)
				),
			),
		),
		'l10n_parent' => array(
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.l18n_parent',
			'config' => array(
				'type' => 'select',
				'renderType' => 'selectSingle',
				'items' => array(
					array('', 0),
				),
				'foreign_table' => 'tx_pitssitestatistics_domain_model_statistics',
				'foreign_table_where' => 'AND tx_pitssitestatistics_domain_model_statistics.pid=###CURRENT_PID### AND tx_pitssitestatistics_domain_model_statistics.sys_language_uid IN (-1,0)',
			),
		),
		'l10n_diffsource' => array(
			'config' => array(
				'type' => 'passthrough',
			),
		),

		't3ver_label' => array(
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.versionLabel',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'max' => 255,
			)
		),
	
		'hidden' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.hidden',
			'config' => array(
				'type' => 'check',
			),
		),
		'starttime' => array(
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.starttime',
			'config' => array(
				'type' => 'input',
				'size' => 13,
				'max' => 20,
				'eval' => 'datetime',
				'checkbox' => 0,
				'default' => 0,
				'range' => array(
					'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
				),
			),
		),
		'endtime' => array(
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.endtime',
			'config' => array(
				'type' => 'input',
				'size' => 13,
				'max' => 20,
				'eval' => 'datetime',
				'checkbox' => 0,
				'default' => 0,
				'range' => array(
					'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
				),
			),
		),

		'service_account_email' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:pits_site_statistics/Resources/Private/Language/locallang_db.xlf:tx_pitssitestatistics_domain_model_statistics.service_account_email',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,required,email'
			),
		),
		'key_file_location' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:pits_site_statistics/Resources/Private/Language/locallang_db.xlf:tx_pitssitestatistics_domain_model_statistics.key_file_location',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,required'
			)
		),
		'web_title' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:pits_site_statistics/Resources/Private/Language/locallang_db.xlf:tx_pitssitestatistics_domain_model_statistics.web_title',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,required'
			)
		),
		'default_account' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:pits_site_statistics/Resources/Private/Language/locallang_db.xlf:tx_pitssitestatistics_domain_model_statistics.default_account',
			'config' => array(
				'type' => 'check',
				'default' => '0',
				'eval' => 'trim'
			)
		),
		'property_trackingid' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:pits_site_statistics/Resources/Private/Language/locallang_db.xlf:tx_pitssitestatistics_domain_model_statistics.property_trackingid',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,required'
			)
		),
		
	),
);