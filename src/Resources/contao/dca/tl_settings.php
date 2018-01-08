<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2017 Leo Feyer
 *
 * @license LGPL-3.0+
 */

// Load language file(s)
System::loadLanguageFile('tl_serverhint');


/**
 * System configuration
 */
$GLOBALS['TL_DCA']['tl_settings']['palettes']['default'] = str_replace
(
    ';{proxy_legend',
    ';{serverhint_legend:hide},serverhint;{proxy_legend',
    $GLOBALS['TL_DCA']['tl_settings']['palettes']['default']
);

// Fields
$GLOBALS['TL_DCA']['tl_settings']['fields']['serverhint'] = array
(
    'label'     => &$GLOBALS['TL_LANG']['tl_settings']['serverhint'],
    'inputType' => 'serverHintWizard',
    'eval'      => array('tl_class'=>'clr')
);
