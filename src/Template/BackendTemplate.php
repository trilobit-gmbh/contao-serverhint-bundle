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
        $hint = StringUtil::deserialize(Config::get('serverhint'), true);

        $style = '';

        if (!empty($hint) && \count($hint)) {
            foreach ($hint as $value) {
                if ('' === $value['published']) {
                    continue;
                }

                if (strtolower($_SERVER['HTTP_HOST']) === strtolower($value['value'])) {
                    $value['label'] = str_replace('&#35;', '#', $value['label']);
                    $value['label'] = str_replace('&#40;', '(', $value['label']);
                    $value['label'] = str_replace('&#41;', ')', $value['label']);

                    $style .= '#header .inner { background-color: '.$value['label'].'; } '
                        .'#tl_navigation .menu_level_1 li.current { border-left-color: '.$value['label'].'; } '
                        .'#tmenu a:hover, '
                        .'#tmenu a.hover, '
                        .'#tmenu li:hover .h2, '
                        .'#tmenu .active .h2, '
                        .'#tmenu .burger button:hover '
                        .'{ background-color: '.$value['label'].'; } '
                    ;

                    if ('' !== $value['hint']) {
                        $style .= '#home:after '
                            .'{ content: "'.$value['hint'].'"; display: inline-block; font-family: "Architects Daughter", cursive; font-size: 1rem; color: rgb(0,16,53); transform: rotate(-1deg); width: auto; padding-left: 15px; }';
                    }
                }
            }
            if (!empty($style)) {
                $style = '<style>'.$style.'</style>';
                $buffer = \Safe\preg_replace('/<body/', $style.'<body', $buffer);
            }
        }

        return $buffer;
    }
}
