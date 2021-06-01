<?php

/*
 * @copyright  trilobit GmbH
 * @author     trilobit GmbH <https://github.com/trilobit-gmbh>
 * @license    LGPL-3.0-or-later
 * @link       http://github.com/trilobit-gmbh/contao-serverhint-bundle
 */

use Contao\Config;
use Contao\StringUtil;
use Trilobit\ServerhintBundle\ServerHintWizard;

/*
 * Back end form fields
 */
$GLOBALS['BE_FFL']['serverHintWizard'] = ServerHintWizard::class;

/*
 * Add css
 */
if (TL_MODE === 'BE') {
    $arrServerhint = StringUtil::deserialize(Config::get('serverhint'), true);

    $strStyle = '';

    if (Config::get('serverhint')
        && count($arrServerhint)
    ) {
        foreach ($arrServerhint as $value) {
            if ('' === $value['published']) {
                continue;
            }

            if (strtolower($_SERVER['HTTP_HOST']) === strtolower($value['value'])) {
                $value['label'] = str_replace('&#35;', '#', $value['label']);
                $value['label'] = str_replace('&#40;', '(', $value['label']);
                $value['label'] = str_replace('&#41;', ')', $value['label']);

                $strStyle .= '#header .inner { background-color: '.$value['label'].'; } '
                    .'#tl_navigation .menu_level_1 li.current { border-left-color: '.$value['label'].'; } '
                    .'#tmenu a:hover, '
                    .'#tmenu a.hover, '
                    .'#tmenu li:hover .h2, '
                    .'#tmenu .active .h2, '
                    .'#tmenu .burger button:hover '
                    .'{ background-color: '.$value['label'].'; } '
                ;

                if ('' !== $value['hint']) {
                    $strStyle .= '#home:after '
                        .'{ content: "'.$value['hint'].'"; display: inline-block; font-family: "Architects Daughter", cursive; font-size: 1rem; color: rgb(0,16,53); transform: rotate(-1deg); width: auto; padding-left: 15px; }';
                }
            }
        }

        $GLOBALS['TL_HEAD'][] = '<style>'.$strStyle.'</style>';
        $GLOBALS['TL_MOOTOOLS'][] = '<style>'.$strStyle.'</style>';
    }
}
