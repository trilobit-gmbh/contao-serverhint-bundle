<?php

/*
 * @copyright  trilobit GmbH
 * @author     trilobit GmbH <https://github.com/trilobit-gmbh>
 * @license    LGPL-3.0-or-later
 * @link       http://github.com/trilobit-gmbh/contao-serverhint-bundle
 */

namespace Trilobit\ServerhintBundle\Template;

use Contao\Config;
use Contao\StringUtil;

class BackendTemplate
{
    public function __invoke(string $buffer): string
    {
        $adjustPercent = .15;

        $hint = StringUtil::deserialize(Config::get('serverhint'), true);

        $style = '';

        if (!empty($hint) && \count($hint)) {
            foreach ($hint as $value) {
                if (empty($value['published'])) {
                    continue;
                }

                if (strtolower($_SERVER['HTTP_HOST']) === strtolower($value['value'])) {
                    $value['label'] = str_replace('&#35;', '#', $value['label']);
                    $value['label'] = str_replace('&#40;', '(', $value['label']);
                    $value['label'] = str_replace('&#41;', ')', $value['label']);

                    $hoverColor = $this->adjustBrightness($value['label'], $adjustPercent);

                    $style .= '#header .inner '
                        .'{ background-color: '.$value['label'].'; } '
                        .'#tl_navigation .tl_level_2 li.active '
                        .'{ border-left-color: '.$value['label'].'; } '
                        .'#tl_navigation .menu_level_1 li.current '
                        .'{ border-left-color: '.$value['label'].'; } '
                        .'#tmenu a:hover, #tmenu a.hover, #tmenu li:hover .h2, #tmenu .active .h2, #tmenu .burger button:hover { background-color: '.$hoverColor.'; } '
                        .'#tmenu a:hover, #tmenu li:hover h2, #tmenu .burger button:hover { background-color: '.$hoverColor.'; } '
                    ;

                    if (!empty($value['hint'])) {
                        $style .= '#home:after '
                            .'{ content: "'.$value['hint'].'"; display: inline-block; font-family: "Architects Daughter", cursive; font-size: 1rem; color: rgb(0,16,53); transform: rotate(-1deg); width: auto; padding-left: 15px; }';
                    }
                }
            }
            if (!empty($style)) {
                $style = '<style>'.$style.'</style>';
                $buffer = preg_replace('/<body/', $style.'<body', $buffer);
            }
        }

        return $buffer;
    }

    public function adjustBrightness($hexCode, $adjustPercent)
    {
        $hexCode = ltrim($hexCode, '#');

        if (3 === \strlen($hexCode)) {
            $hexCode = $hexCode[0].$hexCode[0].$hexCode[1].$hexCode[1].$hexCode[2].$hexCode[2];
        }

        $hexCode = array_map('hexdec', str_split($hexCode, 2));

        foreach ($hexCode as &$color) {
            $adjustableLimit = $adjustPercent < 0 ? $color : 255 - $color;
            $adjustAmount = ceil($adjustableLimit * $adjustPercent);

            $color = str_pad(dechex($color + $adjustAmount), 2, '0', \STR_PAD_LEFT);
        }

        return '#'.implode('', $hexCode);
    }
}
