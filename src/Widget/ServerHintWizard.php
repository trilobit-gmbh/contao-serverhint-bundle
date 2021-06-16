<?php

/*
 * @copyright  trilobit GmbH
 * @author     trilobit GmbH <https://github.com/trilobit-gmbh>
 * @license    LGPL-3.0-or-later
 * @link       http://github.com/trilobit-gmbh/contao-serverhint-bundle
 */

namespace Trilobit\ServerhintBundle\Widget;

use Contao\Image;
use Contao\StringUtil;
use Contao\Widget;

/**
 * Class ServerHintWizard.
 */
class ServerHintWizard extends Widget
{
    /**
     * @var bool
     */
    protected $blnSubmitInput = true;

    /**
     * @var string
     */
    protected $strTemplate = 'be_widget';

    /**
     * @return bool|void
     */
    public function validate()
    {
        $mandatory = $this->mandatory;
        $options = $this->getPost($this->strName);

        // Check labels only (values can be empty)
        if (\is_array($options)) {
            foreach ($options as $key => $option) {
                // Unset empty rows
                if ('' === $option['label']) {
                    unset($options[$key]);
                    continue;
                }

                $options[$key]['label'] = trim($option['label']);
                $options[$key]['value'] = trim($option['value']);
                $options[$key]['hint'] = trim($option['hint']);

                if ('' !== $options[$key]['label']) {
                    $this->mandatory = false;
                }

                // Strip double quotes (see #6919)
                if ('' !== $options[$key]['label']) {
                    $options[$key]['label'] = str_replace('"', '', $options[$key]['label']);
                }

                if ('' !== $options[$key]['value']) {
                    $options[$key]['value'] = str_replace('"', '', $options[$key]['value']);
                }

                if ('' !== $options[$key]['hint']) {
                    $options[$key]['hint'] = str_replace('"', '', $options[$key]['hint']);
                }
            }
        }

        $options = array_values($options);
        $varInput = $this->validator($options);

        if (!$this->hasErrors()) {
            $this->varValue = $varInput;
        }

        // Reset the property
        if ($mandatory) {
            $this->mandatory = true;
        }
    }

    /**
     * @return string
     */
    public function generate()
    {
        $arrButtons = ['copy', 'delete', 'drag'];

        // Make sure there is at least an empty array
        if (!\is_array($this->varValue) || !$this->varValue[0]) {
            $this->varValue = [['']];
        }

        // Begin the table
        $return = '<table id="ctrl_'.$this->strId.'" class="tl_modulewizard">
  <thead>
    <tr>
      <th>'.$GLOBALS['TL_LANG']['tl_settings']['options']['server'].'</th>
      <th>'.$GLOBALS['TL_LANG']['tl_settings']['options']['color'].'</th>
      <th>'.$GLOBALS['TL_LANG']['tl_settings']['options']['hint'].'</th>
      <th></th>
      <th></th>
    </tr>
  </thead>
  <tbody class="sortable">';

        // Add fields
        foreach (range(0, \count($this->varValue)) as $i) {
            $return .= '
    <tr>
      <td><input type="text" name="'.$this->strId.'['.$i.'][value]" id="'.$this->strId.'_value_'.$i.'" class="tl_text" value="'.StringUtil::specialchars($this->varValue[$i]['value']).'"></td>
      <td><input type="text" name="'.$this->strId.'['.$i.'][label]" id="'.$this->strId.'_label_'.$i.'" class="tl_text" value="'.StringUtil::specialchars($this->varValue[$i]['label']).'"></td>
      <td><input type="text" name="'.$this->strId.'['.$i.'][hint]"  id="'.$this->strId.'_hint_'.$i.'"  class="tl_text" value="'.StringUtil::specialchars($this->varValue[$i]['hint']).'"></td>
      <td><input type="checkbox" name="'.$this->strId.'['.$i.'][published]" id="'.$this->strId.'_published_'.$i.'" class="fw_checkbox" value="1"'.($this->varValue[$i]['published'] ? ' checked="checked"' : '').'> <label for="'.$this->strId.'_published_'.$i.'">'.$GLOBALS['TL_LANG']['tl_settings']['options']['published'].'</label></td>
      ';

            // Add row buttons
            $return .= '
      <td>';

            foreach ($arrButtons as $button) {
                if ('drag' === $button) {
                    $return .= ' <button type="button" class="drag-handle" title="'.StringUtil::specialchars($GLOBALS['TL_LANG']['MSC']['move']).'">'.Image::getHtml('drag.svg').'</button>';
                } else {
                    $return .= ' <button type="button" data-command="'.$button.'" title="'.StringUtil::specialchars($GLOBALS['TL_LANG']['MSC']['ow_'.$button]).'">'.Image::getHtml($button.'.svg').'</button>';
                }
            }

            $return .= '</td>
    </tr>';
        }

        return $return.'
  </tbody>
  </table>
  <script>Backend.optionsWizard("ctrl_'.$this->strId.'")</script>';
    }
}
