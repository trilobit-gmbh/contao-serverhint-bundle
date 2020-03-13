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

use Contao\CoreBundle\DataContainer\PaletteManipulator;

/**
 * System configuration
 */
PaletteManipulator::create()
    ->addLegend('serverhint_legend:hide', 'frontend_legend', PaletteManipulator::POSITION_AFTER)
    ->addField('serverhint', 'serverhint_legend:hide', PaletteManipulator::POSITION_APPEND)
    ->applyToPalette('default', 'tl_settings')
;

// Fields
$GLOBALS['TL_DCA']['tl_settings']['fields']['serverhint'] = array
(
    'label'     => &$GLOBALS['TL_LANG']['tl_settings']['serverhint'],
    'inputType' => 'serverHintWizard',
    'eval'      => array('tl_class'=>'clr')
);
