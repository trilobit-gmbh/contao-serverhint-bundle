<?php

/*
 * @copyright  trilobit GmbH
 * @author     trilobit GmbH <https://github.com/trilobit-gmbh>
 * @license    LGPL-3.0-or-later
 * @link       http://github.com/trilobit-gmbh/contao-serverhint-bundle
 */

use Trilobit\ServerhintBundle\Widget\ServerHintWizard;
use Trilobit\ServerhintBundle\Template\BackendTemplate;

$GLOBALS['BE_FFL']['serverHintWizard'] = ServerHintWizard::class;

$GLOBALS['TL_HOOKS']['outputBackendTemplate'][] = [BackendTemplate::class, '__invoke'];
