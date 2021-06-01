<?php

/*
 * @copyright  trilobit GmbH
 * @author     trilobit GmbH <https://github.com/trilobit-gmbh>
 * @license    LGPL-3.0-or-later
 * @link       http://github.com/trilobit-gmbh/contao-serverhint-bundle
 */

use Contao\CoreBundle\DataContainer\PaletteManipulator;

// Load language file(s)
System::loadLanguageFile('tl_serverhint');

// Fields
$GLOBALS['TL_DCA']['tl_settings']['fields']['serverhint'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_settings']['serverhint'],
    'inputType' => 'serverHintWizard',
    'eval' => ['tl_class' => 'clr'],
];

PaletteManipulator::create()
    ->addLegend('serverhint_legend', 'frontend_legend', PaletteManipulator::POSITION_BEFORE)
    ->addField(['serverhint'], 'serverhint_legend', PaletteManipulator::POSITION_APPEND)
    ->applyToPalette('default', 'tl_settings')
;
