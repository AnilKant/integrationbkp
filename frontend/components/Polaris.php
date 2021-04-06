<?php

namespace frontend\components;

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\Json;
use yii\base\Component;
use yii\helpers\ArrayHelper;
use Yii;

class Polaris extends Component
{
    public static $radio = 1;

    public static $checkbox = 1;

    public static $select = 1;

    public static $subSelect = 1;

    public static $textfield = 1;

    public static function Radio($name = '', $options = [])
    {
        $defaultOptions = [
            'id' => 'Radio' . static::$radio,
            'class' => '',
            'checked' => false,
            'label' => '',
            'value' => '',
            'help_text' => '',
            'help_text_id' => 'HelpText' . static::$radio++,
            'onclick' => '',
            'onchange' => '',
            'wrapper_id' => '',
            'wrapper_class' => '',
            'wrapper_option' => '',
            'field_option' => '',
        ];

        $options = ArrayHelper::merge(
            $defaultOptions,
            $options
        );

        $events = '';
        if (!empty($options['onclick'])) {
            $events .= ' onclick="' . $options['onclick'] . '"';
        }
        if (!empty($options['onchange'])) {
            $events .= ' onchange="' . $options['onchange'] . '"';
        }

        $checked = $options['checked'] ? 'checked="checked"' : '';
        $aria_checked = $options['checked'] ? 'true' : 'false';

        $html = '<div class="Polaris-Stack__Item ' . $options['wrapper_class'] . '" id="' . $options['wrapper_id'] . '" ' . $options['wrapper_option'] . '>';
        $html .= '<div>';
        $html .= '<label class="Polaris-Choice" for="' . $options['id'] . '">';
        $html .= '<span class="Polaris-Choice__Control">';
        $html .= '<span class="Polaris-RadioButton">';
        $html .= '<input id="' . $options['id'] . '" name="' . $name . '" class="Polaris-RadioButton__Input ' . $options['class'] . '" aria-describedby="' . $options['help_text_id'] . '" value="' . $options['value'] . '" type="radio" ' . $events . ' ' . $options['field_option'] . '>';
        $html .= '<span class="Polaris-RadioButton__Backdrop"></span>';
        $html .= '<span class="Polaris-RadioButton__Icon"></span>';
        $html .= '</span>';
        $html .= '</span>';

        if (!empty($options['label'])) {
            $html .= '<span class="Polaris-Choice__Label">' . Html::encode($options['label']) . '</span>';
        }

        $html .= '</label>';

        if (!empty($options['help_text'])) {
            $html .= '<div class="Polaris-Choice__Descriptions">';
            $html .= '<div class="Polaris-Choice__HelpText" id="' . $options['help_text_id'] . '">' . Html::encode($options['help_text']) . '</div>';
            $html .= '</div>';
        }

        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }

    public static function Checkbox($name = '', $options = [])
    {
        $defaultOptions = [
            'id' => 'Checkbox' . static::$checkbox++,
            'class' => '',
            'checked' => false,//checked
            'label' => '',
            'value' => '',
            'onclick' => '',
            'onchange' => '',
            'wrapper_id' => '',
            'wrapper_class' => '',
            'wrapper_option' => '',
            'field_option' => '',
            'yii_form_field' => false,
            'yii_error_div' => false,
        ];

        $options = ArrayHelper::merge(
            $defaultOptions,
            $options
        );

        $events = '';
        if (!empty($options['onclick'])) {
            $events .= ' onclick="' . $options['onclick'] . '"';
        }
        if (!empty($options['onchange'])) {
            $events .= ' onchange="' . $options['onchange'] . '"';
        }

        $checked = $options['checked'] ? 'checked="checked"' : '';
        $aria_checked = $options['checked'] ? 'true' : 'false';

        $html = '<label class="Polaris-Choice ' . $options['wrapper_class'] . '" id="' . $options['wrapper_id'] . '" for="' . $options['id'] . '" ' . $options['wrapper_option'] . '>';
        $html .= '<span class="Polaris-Choice__Control">';
        $divClass = '';
        if ($options['yii_form_field']) {
            $divClass = $options['id'] . '-container';
            $options['yii_error_div'] = true;
            //$html .= '<div class="'.$divClass.'">';
        }
        $html .= '<span class="Polaris-Checkbox ' . $divClass . '">';

        $html .= '<input id="' . $options['id'] . '" class="Polaris-Checkbox__Input ' . $options['class'] . '" aria-invalid="false" role="checkbox" ' . $checked . ' aria-checked="' . $aria_checked . '" value="' . $options['value'] . '" type="checkbox" name="' . $name . '" ' . $events . ' ' . $options['field_option'] . '>';
        if ($options['yii_form_field']) {
            $html .= '<div class="help-block"></div>';
            //$html .= '</div>';
        }
        $html .= '<span class="Polaris-Checkbox__Backdrop"></span>';
        $html .= '<span class="Polaris-Checkbox__Icon">';
        $html .= '<span class="Polaris-Icon">';
        $html .= '<svg class="Polaris-Icon__Svg checkedCheckbox" viewBox="0 0 20 20" focusable="false" aria-hidden="true">';
        $html .= '<path d="M8.315 13.859l-3.182-3.417a.506.506 0 0 1 0-.684l.643-.683a.437.437 0 0 1 .642 0l2.22 2.393 4.942-5.327a.437.437 0 0 1 .643 0l.643.684a.504.504 0 0 1 0 .683l-5.91 6.35a.437.437 0 0 1-.642 0"></path>';
        $html .= '</svg>';
        $html .= '<svg class="feather feather-minus indeterminateCheckbox" style="display: none" xmlns="http://www.w3.org/2000/svg" height="20" fill="none" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 15 24" width="15" stroke="#3f4eae"><line x1="5" y1="12" x2="19" y2="12"></line></svg>';
        $html .= '</span>';
        $html .= '</span>';
        $html .= '</span>';
        $html .= '</span>';

        if (!empty($options['label'])) {
            $html .= '<span class="Polaris-Choice__Label">' . Html::encode($options['label']) . '</span>';
        }

        $html .= '</label>';
        return $html;
    }

    public static function Select($name = '', $select_options = [], $options = [])
    {
        $defaultOptions = [
            'id' => 'Select' . static::$select,
            'label_id' => 'Select' . static::$select++ . 'Label',
            'class' => '',
            'selected' => '',
            'label' => '',
            'onclick' => '',
            'onchange' => '',
            'wrapper_id' => '',
            'wrapper_class' => '',
            'wrapper_option' => '',
            'field_option' => '',
            'yii_form_field' => false,
            'yii_error_div' => false,
            'help_block' => '',
            'select2_class' => '',
            'required' => false,
            'label_action' => false,
            'label_action_option' => []
        ];

        $options = ArrayHelper::merge(
            $defaultOptions,
            $options
        );
        $required_class = '';
        if ($options['required']) {
            $required_class = 'required_field';
        }
        $events = '';
        if (!empty($options['onclick'])) {
            $events .= ' onclick="' . $options['onclick'] . '"';
        }
        if (!empty($options['onchange'])) {
            $events .= ' onchange="' . $options['onchange'] . '"';
        }

        $html = '<div class="Polaris-FormLayout__Item ' . $options['wrapper_class'] . '" id="' . $options['wrapper_id'] . '" ' . $options['wrapper_option'] . '>';
        $html .= '<div class="">';

        if (!empty($options['label'])) {
            $html .= '<div class="Polaris-Labelled__LabelWrapper ' . $required_class . '" >';
            $html .= '<div class="Polaris-Label">';
            $html .= '<label id="' . $options['label_id'] . '" for="' . $options['id'] . '" class="Polaris-Label__Text">' . Html::encode($options['label']) . '</label>';
            $html .= '</div>';
            if ($options['label_action']) {
                $labelActionOption = $options['label_action_option'];
                $labelActionName = isset($labelActionOption['label_action_name']) ? $labelActionOption['label_action_name'] : 'Default Text';
                $labelTitle = isset($labelActionOption['label_title']) ? $labelActionOption['label_title'] : 'Title';
                $html .= '<div class="Polaris-Labelled__Action">';
                $html .= '<button type="button" class="Polaris-Button Polaris-Button--plain" title="' . $labelTitle . '" data-toggle="tooltip">';
                $html .= '<span class="Polaris-Button__Content">';
                $html .= '<span class="Polaris-Button__Text">' . $labelActionName . '</span>';
                $html .= '</span>';
                $html .= '</button>';
                $html .= '</div>';
            }
            $html .= '</div>';
        } else {
            if ($options['label_action']) {
                $html .= '<div class="Polaris-Labelled__LabelWrapper>';
                $html .= '<div class="Polaris-Label">';
                $html .= '</div>';
                $labelActionOption = $options['label_action_option'];
                $labelActionName = isset($labelActionOption['label_action_name']) ? $labelActionOption['label_action_name'] : 'Default Text';
                $labelTitle = isset($labelActionOption['label_title']) ? $labelActionOption['label_title'] : 'Title';
                $html .= '<div class="Polaris-Labelled__Action">';
                $html .= '<button type="button" class="Polaris-Button Polaris-Button--plain" title="' . $labelTitle . '" data-toggle="tooltip">';
                $html .= '<span class="Polaris-Button__Content">';
                $html .= '<span class="Polaris-Button__Text">' . $labelActionName . '</span>';
                $html .= '</span>';
                $html .= '</button>';
                $html .= '</div>';
                $html .= '</div>';
            }
        }

        $divClass = '';
        if ($options['yii_form_field']) {
            $divClass = $options['id'] . '-container';
            $options['yii_error_div'] = true;
        }

        if ($options['select2_class'] != '') {
            $html .= '<div class="Polaris-Select custom-polaris-select ' . $options['select2_class'] . ' ' . $divClass . '">';
            $html .= '<select id="' . $options['id'] . '" name="' . $name . '" class="Polaris-Select__Input ' . $options['class'] . '" aria-invalid="false" ' . $events . ' ' . $options['field_option'] . ' style="width:100%">';
        } else {
            $html .= '<div class="Polaris-Select custom-polaris-select ' . $divClass . '">';
            $html .= '<select id="' . $options['id'] . '" name="' . $name . '" class="Polaris-Select__Input ' . $options['class'] . '" aria-invalid="false" ' . $events . ' ' . $options['field_option'] . '>';
        }

        //$html .= '<div class="Polaris-Select">';
        // $html .= '<select id="' . $options['id'] . '" name="' . $name . '" class="Polaris-Select__Input ' . $options['class'] . '" aria-invalid="false" ' . $events . ' ' . $options['field_option'] . '>';
        // echo "<hr><pre>";
        // print_r($select_options);
        // die("<hr>te55st");
        foreach ($select_options as $value => $label) {
            if (!is_array($options['selected'])) {

                if ($value == 0 && $options['selected'] === '') {
                    $html .= '<option value="' . $value . '" >' . Html::encode($label) . '</option>';
                } elseif ($value == $options['selected']) {
                    $html .= '<option value="' . $value . '" selected="selected">' . Html::encode($label) . '</option>';
                } else {
                    $html .= '<option value="' . $value . '">' . Html::encode($label) . '</option>';
                }

            } else {
                if (in_array($value, $options['selected'])) {
                    $html .= '<option value="' . $value . '" selected="selected">' . Html::encode($label) . '</option>';
                } else {
                    $html .= '<option value="' . $value . '">' . Html::encode($label) . '</option>';
                }
            }
        }
        $html .= '</select>';
        if ($options['yii_error_div']) {
            $html .= '<div class="help-block"></div>';
        }
        $html .= '<div class="Polaris-Select__Icon">';
        $html .= '<span class="Polaris-Icon">';
        $html .= '<svg class="Polaris-Icon__Svg" viewBox="0 0 20 20" focusable="false" aria-hidden="true">';
        $html .= '<path d="M13 8l-3-3-3 3h6zm-.1 4L10 14.9 7.1 12h5.8z" fill-rule="evenodd"></path>';
        $html .= '</svg>';
        $html .= '</span>';
        $html .= '</div>';
        //$html .= '<div class="Polaris-Select__Backdrop"></div>';
        $html .= '</div>';
        if ($options['help_block'] != '') {
            $html .= '<div class="Polaris-Labelled__HelpText">' . $options['help_block'] . '</div>';
        }
        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }

    public static function TextField($name = '', $options = [])
    {
        $defaultOptions = [
            'id' => 'TextField' . static::$textfield,
            'label_id' => 'TextField' . static::$textfield++ . 'Label',
            'class' => '',
            'label' => '',
            'value' => '',
            'type' => 'text',
            'onclick' => '',
            'onchange' => '',
            'wrapper_id' => '',
            'wrapper_class' => '',
            'wrapper_option' => '',
            'field_option' => '',
            'yii_form_field' => false,
            'yii_error_div' => false,
            'prefix' => '',
            'suffix' => '',
            'help_block' => '',
            'required' => false,
            'display' => true,
            'label_action' => false,
            'label_action_option' => []
        ];

        $options = ArrayHelper::merge(
            $defaultOptions,
            $options
        );

        $events = '';
        if (!empty($options['onclick'])) {
            $events .= ' onclick="' . $options['onclick'] . '"';
        }
        if (!empty($options['onchange'])) {
            $events .= ' onchange="' . $options['onchange'] . '"';
        }
        if (!$options['display']) {
            $html = '<div class="Polaris-FormLayout__Item ' . $options['wrapper_class'] . '" id="' . $options['wrapper_id'] . '" ' . $options['wrapper_option'] . ' style="display:none">';

        } else {
            $html = '<div class="Polaris-FormLayout__Item ' . $options['wrapper_class'] . '" id="' . $options['wrapper_id'] . '" ' . $options['wrapper_option'] . '>';
        }

        $html .= '<div class="">';
        $required_class = '';
        if ($options['required']) {
            $required_class = 'required_field';
        }
        if (!empty($options['label'])) {
            $html .= '<div class="Polaris-Labelled__LabelWrapper ' . $required_class . ' ">';
            $html .= '<div class="Polaris-Label">';
            $html .= '<label id="' . $options['label_id'] . '" for="' . $options['id'] . '" class="Polaris-Label__Text">' . Html::encode($options['label']) . '</label>';
            $html .= '</div>';
            if ($options['label_action']) {
                $labelActionOption = $options['label_action_option'];
                $labelActionName = isset($labelActionOption['label_action_name']) ? $labelActionOption['label_action_name'] : 'Default Text';
                $labelTitle = isset($labelActionOption['label_title']) ? $labelActionOption['label_title'] : 'Title';
                $html .= '<div class="Polaris-Labelled__Action">';
                $html .= '<button type="button" class="Polaris-Button Polaris-Button--plain" title="' . $labelTitle . '" data-toggle="tooltip">';
                $html .= '<span class="Polaris-Button__Content">';
                $html .= '<span class="Polaris-Button__Text">' . $labelActionName . '</span>';
                $html .= '</span>';
                $html .= '</button>';
                $html .= '</div>';
            }
            $html .= '</div>';
        }
        $divClass = '';
        if ($options['yii_form_field']) {
            $divClass = $options['id'] . '-container';
            $options['yii_error_div'] = true;
        }

        $html .= '<div class="Polaris-TextField ' . $divClass . '" >';
        if (isset($options['prefix']) && $options['prefix'] != '') {
            $html .= '<div class="Polaris-TextField__Prefix">' . $options['prefix'] . '</div>';
        }
        $html .= '<input id="' . $options['id'] . '" class="Polaris-TextField__Input input-field ' . $options['class'] . '" aria-labelledby="' . $options['label_id'] . '" aria-invalid="false" name="' . $name . '" value="' . $options['value'] . '" type="' . $options['type'] . '" ' . $events . ' ' . $options['field_option'] . '>';

        if (isset($options['suffix']) && $options['suffix'] != '') {
            $html .= '<div class="Polaris-TextField__Suffix">' . $options['suffix'] . '</div>';
        }
        if ($options['yii_error_div']) {
            $html .= '<div class="help-block"></div>';
        }
        $html .= '<div class="Polaris-TextField__Backdrop"></div>';
        $html .= '</div>';
        if ($options['help_block'] != '') {
            $html .= '<div class="Polaris-Labelled__HelpText">' . $options['help_block'] . '</div>';
        }
        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }

    public static function TextArea($name = '', $options = [])
    {
        $defaultOptions = [
            'id' => 'TextArea' . static::$textfield,
            'label_id' => 'TextArea' . static::$textfield++ . 'Label',
            'class' => '',
            'label' => '',
            'value' => '',
            'placeholder' => 'text',
            'onclick' => '',
            'onchange' => '',
            'wrapper_id' => '',
            'wrapper_class' => '',
            'yii_form_field' => false,
            'yii_error_div' => false,
            'wrapper_option' => '',
            'field_option' => '',
        ];

        $options = ArrayHelper::merge(
            $defaultOptions,
            $options
        );

        $events = '';
        if (!empty($options['onclick'])) {
            $events .= ' onclick="' . $options['onclick'] . '"';
        }
        if (!empty($options['onchange'])) {
            $events .= ' onchange="' . $options['onchange'] . '"';
        }

        $html = '<div class="Polaris-FormLayout__Item ' . $options['wrapper_class'] . '" id="' . $options['wrapper_id'] . '" ' . $options['wrapper_option'] . '>';
        $html .= '<div class="">';

        if (!empty($options['label'])) {
            $html .= '<div class="Polaris-Labelled__LabelWrapper">';
            $html .= '<div class="Polaris-Label">';
            $html .= '<label id="' . $options['label_id'] . '" for="' . $options['id'] . '" class="Polaris-Label__Text">' . Html::encode($options['label']) . '</label>';
            $html .= '</div>';
            $html .= '</div>';
        }
        $divClass = '';
        if ($options['yii_form_field']) {

            $divClass = $options['id'] . '-container';
            $options['yii_error_div'] = true;
        }
        $html .= '<div class="Polaris-TextField Polaris-TextField--multiline ' . $divClass . '">';
        $html .= '<textarea id="' . $options['id'] . '" placeholder="' . $options['placeholder'] . '" class="Polaris-TextField__Input ' . $options['class'] . '" name="' . $name . '" aria-labelledby="' . $options['label_id'] . '" aria-invalid="false" ' . $events . ' ' . $options['field_option'] . '>' . $options['value'] . '</textarea>';
        if ($options['yii_error_div']) {
            $html .= '<div class="help-block"></div>';
        }
        $html .= '<div class="Polaris-TextField__Backdrop"></div>';
        $html .= '<div aria-hidden="true" class="Polaris-TextField__Resizer">';
        $html .= '<div class="Polaris-TextField__DummyInput">' . $options['placeholder'] . '</div>';
        $html .= '<div class="Polaris-TextField__DummyInput"></div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }

    /*
     * $type : error , warning and success
     * $options : ['heading'=>'my first heading','message'=>'my error','button'=>['link'=>'https://apps.cedcommerce.com','label'=>'test','field_option'=>'target="_blank"']]
     */
    public static function Notification($type = '', $options = [])
    {
        $defaultOptions = [
            'wrapper_id' => 'polaris-' . $type,
            'wrapper_class' => 'mb-30',
            'field_option' => '',
            'close_button' => false
        ];

        $options = ArrayHelper::merge(
            $defaultOptions,
            $options
        );

        switch ($type) {
            case 'error' :
                $class = 'Polaris-Banner--statusCritical';
                $iconClass = 'Polaris-Icon--colorRedDark';
                break;
            case 'warning' :
                $class = 'Polaris-Banner--statusWarning';
                $iconClass = 'Polaris-Icon--colorYellowDark';
                break;
            case 'success';
                $class = 'Polaris-Banner--statusSuccess';
                $iconClass = 'Polaris-Icon--colorGreenDark';
                break;
            case 'info';
                $class = 'Polaris-Banner--statusInfo';
                $iconClass = 'Polaris-Icon--colorTealDark';
                break;
            case 'info_message';
                $class = 'Polaris-Banner--statusMessage';
                $iconClass = 'Polaris-Icon--colorGrey';
                break;
            default :
                $class = 'Polaris-Banner--statusWarning';
                $iconClass = 'Polaris-Icon--colorYellowDark';
                break;
        }
        $html = '<div class="custom-message-wrap ' . $options['wrapper_class'] . '" id="' . $options['wrapper_id'] . '">';
        $html .= '<div aria-describedby="Banner3Content" aria-labelledby="Banner3Heading" aria-live="polite" role="alert" tabindex="0" class="Polaris-Banner ' . $class . ' Polaris-Banner--hasDismiss Polaris-Banner--withinPage p-10">';

        if ($options['close_button']) {

            $html .= '<div class="Polaris-Banner__Dismiss">';
            $html .= '<button aria-label="Dismiss notification" class="Polaris-Button Polaris-Button--plain Polaris-Button--iconOnly" onclick=removeError("' . $type . '") type="button">';
            $html .= '<span class="Polaris-Button__Content" >';
            $html .= '<span class="Polaris-Button__Icon" >';
            $html .= '<span class="Polaris-Icon" >';
            $html .= '<svg aria - hidden = "true" focusable = "false" viewBox = "0 0 20 20" class="Polaris-Icon__Svg" >';
            $html .= '<path fill - rule = "evenodd" d = "M11.414 10l4.293-4.293a.999.999 0 1 0-1.414-1.414L10 8.586 5.707 4.293a.999.999 0 1 0-1.414 1.414L8.586 10l-4.293 4.293a.999.999 0 1 0 1.414 1.414L10 11.414l4.293 4.293a.997.997 0 0 0 1.414 0 .999.999 0 0 0 0-1.414L11.414 10z" />';
            $html .= '</svg >';
            $html .= '</span >';
            $html .= '</span >';
            $html .= '</span >';
            $html .= '</button >';
            $html .= '</div >';
        }
        $html .= '<div class="Polaris-Banner__Ribbon" >';
        $html .= '<span class="Polaris-Icon ' . $iconClass . ' Polaris-Icon--isColored Polaris-Icon--hasBackdrop" >';
        $html .= '<svg aria - hidden = "true" focusable = "false" viewBox = "0 0 20 20" class="Polaris-Icon__Svg" >';
        $html .= '<g fill - rule = "evenodd" ><circle r = "9" cy = "10" cx = "10" fill = "currentColor" /><path d = "M10 0C4.486 0 0 4.486 0 10s4.486 10 10 10 10-4.486 10-10S15.514 0 10 0m0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8m0-13a1 1 0 0 0-1 1v4a1 1 0 1 0 2 0V6a1 1 0 0 0-1-1m0 8a1 1 0 1 0 0 2 1 1 0 0 0 0-2" /> </g >';
        $html .= '</svg >';
//        $html .= self::Svg($type);
        $html .= '</span >';
        $html .= '</div >';
        $html .= '<div > ';

        if (isset($options['heading'])) {
            $html .= '<div id = "Banner3Heading" class="Polaris-Banner__Heading" >';
            $html .= '<p class="Polaris-Heading" > ' . $options['heading'] . ' </p >';
            $html .= '</div > ';
        }
        if (isset($options['message'])) {
            $html .= '<div id = "Banner3Content" class="Polaris-Banner__Content" >';
            if ($options['message'] != strip_tags($options['message'])) {
                $html .= '<div class="message"> ' . $options['message'] . ' </div > ';
            } else {
                $html .= '<p > ' . $options['message'] . ' </p > ';
            }
        }
        if (isset($options['button'])) {
            if (!isset($options['button']['field_option'])) {
                $options['button']['field_option'] = '';
            }
            $html .= '<div class="Polaris-Banner__Actions" >';
            $html .= '<div class="Polaris-ButtonGroup" >';
            $html .= '<div class="Polaris-ButtonGroup__Item" >';
            $html .= '<a href = "' . $options['button']['link'] . '" ' . $options['button']['field_option'] . 'class="Polaris-Button Polaris-Button--outline" type = "button" >';
            $html .= '<span class="Polaris-Button__Content" >';
            $html .= '<span > ' . $options['button']['label'] . ' </span >';
            $html .= '</span >';
            $html .= '</a >';
            $html .= '</div >';
            $html .= '</div >';
            $html .= '</div > ';
        }

        if (isset($options['message'])) {
            $html .= '</div >';
        }
        $html .= '</div >';
        $html .= '</div > ';
        $html .= '</div > ';


        return $html;
    }

    public static function ConnectedSelect($name = '', $select_options = [], $options = [], $sub_name = '', $sub_select_options = [], $sub_options = [])
    {
        $defaultOptions = [
            'id' => 'Select' . static::$select,
            'label_id' => 'Select' . static::$select++ . 'Label',
            'class' => '',
            'selected' => '',
            'label' => '',
            'onclick' => '',
            'onchange' => '',
            'wrapper_id' => '',
            'wrapper_class' => '',
            'wrapper_option' => '',
            'field_option' => '',
            'yii_form_field' => false,
            'yii_error_div' => false,
            'required' => false
        ];


        $options = ArrayHelper::merge(
            $defaultOptions,
            $options
        );

        $events = '';
        if (!empty($options['onclick'])) {
            $events .= ' onclick="' . $options['onclick'] . '"';
        }
        if (!empty($options['onchange'])) {
            $events .= ' onchange="' . $options['onchange'] . '"';
        }


        $defaultSubOptions = [
            'id' => 'Select' . static::$subSelect,
            'label_id' => 'Select' . static::$subSelect++ . 'Label',
            'class' => '',
            'selected' => '',
            'label' => '',
            'onclick' => '',
            'onchange' => '',
            'wrapper_id' => '',
            'wrapper_class' => '',
            'wrapper_option' => '',
            'field_option' => '',
        ];
        $suboptions = ArrayHelper::merge(
            $defaultSubOptions,
            $sub_options
        );

        $subevents = '';
        if (!empty($sub_options['onclick'])) {
            $subevents .= ' onclick="' . $sub_options['onclick'] . '"';
        }
        if (!empty($options['onchange'])) {
            $subevents .= ' onchange="' . $sub_options['onchange'] . '"';
        }

        $html = '';
        $html .= '<div class="Polaris-FormLayout__Item custom-polaris-select-combined">';
        $required_class = '';
        if ($options['required']) {
            $required_class = 'required_field';
        }
        if (!empty($options['label'])) {

            $html .= '<div class="Polaris-Labelled__LabelWrapper ' . $required_class . '">';
            $html .= '<div class="Polaris-Label">';
            $html .= '<label id="' . $options['label_id'] . '" for="' . $options['id'] . '" class="Polaris-Label__Text">' . Html::encode($options['label']) . '</label>';
            $html .= '</div>';
            $html .= '</div>';
        }
        $html .= '<div class="Polaris-Connected">';
        $html .= '<div class="Polaris-Connected__Item Polaris-Connected__Item--primary">';

        $divClass = '';
        if ($options['yii_form_field']) {
            $divClass = $options['id'] . '-container';
            $options['yii_error_div'] = true;

        }
        $html .= '<div class="Polaris-Select custom-polaris-select ' . $divClass . '">';

        $html .= '<select id="' . $options['id'] . '" name="' . $name . '" class="Polaris-Select__Input ' . $options['class'] . '" aria-invalid="false" ' . $events . ' ' . $options['field_option'] . '>';
        foreach ($select_options as $value => $label) {
            if ($value == $options['selected'])
                $html .= '<option value="' . $value . '" selected="selected">' . Html::encode($label) . '</option>';
            else
                $html .= '<option value="' . $value . '">' . Html::encode($label) . '</option>';
        }
        $html .= '</select>';
        if ($options['yii_error_div']) {
            $html .= '<div class="help-block"></div>';
        }
        $html .= '<div class="Polaris-Select__Icon">';
        $html .= '<span class="Polaris-Icon">';
        $html .= '<svg class="Polaris-Icon__Svg" viewBox="0 0 20 20" focusable="false" aria-hidden="true"><path d="M13 8l-3-3-3 3h6zm-.1 4L10 14.9 7.1 12h5.8z" fill-rule="evenodd"></path></svg>';
        $html .= '</span>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<div class="Polaris-Connected__Item Polaris-Connected__Item--connection">';
        $html .= '<div class="Polaris-Labelled--hidden">';

        if (!empty($suboptions['label'])) {

            $html .= '<div class="Polaris-Labelled__LabelWrapper">';
            $html .= '<div class="Polaris-Label">';
            $html .= '<label id="' . $suboptions['label_id'] . '" for="' . $suboptions['id'] . '" class="Polaris-Label__Text">' . Html::encode($suboptions['label']) . '</label>';

            $html .= '</div>';
            $html .= '</div>';
        }

        $subdivClass = '';
        if ($suboptions['yii_form_field']) {
            $subdivClass = $suboptions['id'] . '-container';
            $suboptions['yii_error_div'] = true;

        }
        $html .= '<div class="Polaris-Select custom-polaris-select ' . $subdivClass . '">';

        $html .= '<select id="' . $suboptions['id'] . '" name="' . $sub_name . '" class="Polaris-Select__Input ' . $suboptions['class'] . '" aria-invalid="false" ' . $subevents . ' ' . $suboptions['field_option'] . '>';
        foreach ($sub_select_options as $value => $label) {
            if ($value == $suboptions['selected'])
                $html .= '<option value="' . $value . '" selected="selected">' . Html::encode($label) . '</option>';
            else
                $html .= '<option value="' . $value . '">' . Html::encode($label) . '</option>';
        }
        $html .= '</select>';
        if ($suboptions['yii_error_div']) {
            $html .= '<div class="help-block"></div>';
        }
        $html .= '<span class="Polaris-Select__Icon">';
        $html .= '<span class="Polaris-Icon">';
        $html .= '<svg class="Polaris-Icon__Svg" viewBox="0 0 20 20" focusable="false" aria-hidden="true">';
        $html .= '<path d="M13 8l-3-3-3 3h6zm-.1 4L10 14.9 7.1 12h5.8z" fill-rule="evenodd"></path>';
        $html .= '</svg>';
        $html .= '</span>';
        $html .= '</span>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }

    public static function confirmPopup($header_message = 'Confirm box', $message = 'Are you sure you want to delete?')
    {
        $html = '<div class="Custom-Polaris-modal" style="display:none">
            <div class="Polaris-Modal-Dialog__Container" data-polaris-layer="true" data-polaris-overlay="true">
                <div>
                    <div class="Polaris-Modal-Dialog__Modal" role="dialog" aria-labelledby="modal-header1" tabindex="-1">
                        <div class="Polaris-Modal-Header">
                            <div id="modal-header1" class="Polaris-Modal-Header__Title">
                                <h2 class="Polaris-DisplayText Polaris-DisplayText--sizeSmall">' . $header_message . '</h2>
                            </div>
                            <button class="Polaris-Modal-CloseButton" onclick="polaris_modal_close(this)"><span class="Polaris-Icon Polaris-Icon--colorInkLighter Polaris-Icon--isColored"><svg class="Polaris-Icon__Svg" viewBox="0 0 20 20" focusable="false" aria-hidden="true">
                            <path d="M11.414 10l6.293-6.293a.999.999 0 1 0-1.414-1.414L10 8.586 3.707 2.293a.999.999 0 1 0-1.414 1.414L8.586 10l-6.293 6.293a.999.999 0 1 0 1.414 1.414L10 11.414l6.293 6.293a.997.997 0 0 0 1.414 0 .999.999 0 0 0 0-1.414L11.414 10z" fill-rule="evenodd"></path>
                            </svg></span>
                            </button>
                        </div>
                        <div class="Polaris-Modal__BodyWrapper">
                            <div class="Polaris-Modal__Body Polaris-Scrollable Polaris-Scrollable--vertical" data-polaris-scrollable="true" polaris="[object Object]">
                                <section class="Polaris-Modal-Section">
                                    <div class="Polaris-TextContainer">
                                        <div class="custom-normal-table">
                                            <span>' . $message . '</span>
                                        </div>
                                    </div>
                                </section>
                            </div>
                        </div>
                        <div class="Polaris-Modal-Footer">
                            <div class="Polaris-Modal-Footer__FooterContent">
                                <div class="Polaris-Stack Polaris-Stack--alignmentCenter">
                                    <div class="Polaris-Stack__Item Polaris-Stack__Item--fill"></div>
                                    <div class="Polaris-Stack__Item">
                                        <div class="Polaris-ButtonGroup">
                                            <div class="Polaris-ButtonGroup__Item"><button type="button"  class="Polaris-Button" onclick="polaris_modal_close()"><span class="Polaris-Button__Content"><span>Cancel</span></span></button></div>
                                            <div class="Polaris-ButtonGroup__Item"><button type="button" onclick="polarisModalConfirm()" class="Polaris-Button Polaris-Button--primary"><span class="Polaris-Button__Content"><span>Confirm</span></span></button></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="Polaris-Backdrop">
            </div>
        </div>';

        return $html;

    }

    public static function Svg($sygtype, $icon_type = "major")
    {
        $svg = '';
        switch ($sygtype) {
            case 'edit' :
                $svg .= '<svg width="20" height="20" viewBox="0 0 25 23" fill="none" stroke="#637381" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2"><polygon points="16 3 21 8 8 21 3 21 3 16 16 3"></polygon></svg>';
                break;
            case 'view' :
                $svg .= '<svg width="20" height="20" viewBox="0 0 25 23" fill="none" stroke="#637381" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>';
                break;
            case 'error' :
                $svg .= '<svg fill="none" stroke="#637381" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" class="feather feather-info" width="20" height="20" viewBox="0 0 25 23"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12" y2="8"></line></svg>';
                break;
            case 'ajax_error' :
                $svg .= '<svg class="message-status" version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 130.2 130.2"><circle class="path circle" fill="none" stroke="#D06079" stroke-width="6" stroke-miterlimit="10" cx="65.1" cy="65.1" r="62.1"/><line class="path line" fill="none" stroke="#D06079" stroke-width="6" stroke-linecap="round" stroke-miterlimit="10" x1="34.4" y1="37.9" x2="95.8" y2="92.3"/><line class="path line" fill="none" stroke="#D06079" stroke-width="6" stroke-linecap="round" stroke-miterlimit="10" x1="95.8" y1="38" x2="34.4" y2="92.2"/></svg>';
                break;
            case 'delete' :
                $svg .= '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#637381" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>';
                break;
            case 'plus' :
                $svg .= '<svg class="feather feather-plus" stroke-linejoin="round" stroke-linecap="round" stroke-width="1.7" stroke="#637381" fill="none" viewBox="0 0 25 23" height="20" width="20"><line y2="19" x2="12" y1="5" x1="12"/><line y2="12" x2="19" y1="12" x1="5"/></svg>';
                break;
            case 'minus' :
                $svg .= '<svg class="feather feather-minus" stroke-linejoin="round" stroke-linecap="round" stroke-width="1.7" stroke="#637381" fill="none" viewBox="0 0 25 23" height="20" width="20"><line y2="12" x2="19" y1="12" x1="5"/></svg>';
                break;
            case 'next' :
                $svg .= '<svg class="Polaris-Icon__Svg" viewBox="0 0 20 20" focusable="false" aria-hidden="true"><path d="M17.707 9.293l-5-5a.999.999 0 1 0-1.414 1.414L14.586 9H3a1 1 0 1 0 0 2h11.586l-3.293 3.293a.999.999 0 1 0 1.414 1.414l5-5a.999.999 0 0 0 0-1.414" fill-rule="evenodd"></path></svg>';
                break;
            case 'previous' :
                $svg .= '<svg class="Polaris-Icon__Svg" viewBox="0 0 20 20" focusable="false" aria-hidden="true"><path d="M17.707 9.293l-5-5a.999.999 0 1 0-1.414 1.414L14.586 9H3a1 1 0 1 0 0 2h11.586l-3.293 3.293a.999.999 0 1 0 1.414 1.414l5-5a.999.999 0 0 0 0-1.414" fill-rule="evenodd"></path></svg>';
                break;
            case 'more_action' :
                $svg .= '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg>';
                break;
            case 'success' :
                $svg .= '<svg class="message-status" version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 130.2 130.2"><circle class="path circle" fill="none" stroke="#73AF55" stroke-width="7" stroke-miterlimit="10" cx="65.1" cy="65.1" r="62.1"/><polyline class="path check" fill="none" stroke="#73AF55" stroke-width="7" stroke-linecap="round" stroke-miterlimit="10" points="100.2,40.2 51.5,88.8 29.8,67.5 "/></svg>';
                break;
            case 'cancel' :
                $svg .= '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#637381" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>';
                break;
            case 'truck' :
                $svg .= '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#637381" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>';
                break;
            case 'warning' :
                $svg .= '<svg class="message-status" version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 130.2 130.2"><circle class="path circle" fill="none" stroke="#eec200" stroke-width="6" stroke-miterlimit="10" cx="65.1" cy="65.1" r="62.1"></circle><line id="svg_2" x1="65" x2="64.4" y1="41" stroke-miterlimit="10" y2="92.2" stroke-linecap="round" stroke-width="6" stroke="#eec200" fill="none" class="path line"/><ellipse class="ex-mark" ry="2" rx="2" id="svg_3" cy="28.60001" cx="64.59998" stroke-opacity="null" stroke-width="6" stroke="#eec200" fill="#eec200" stroke-dasharray= 20px stroke-miterlimit=0/></svg>';
                break;
            case 'refund' :
                $svg .= '<svg width="20" height="20" version="1.2" baseProfile="tiny" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 20 20" xml:space="preserve"><g><g><path stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" d="M3.18,10c0-4.46,3.61-8.07,8.07-8.07c4.46,0,8.07,3.61,8.07,8.07s-3.61,8.07-8.07,8.07c-2.05,0-3.92-0.76-5.34-2.02" fill="none" stroke="#637381" stroke-width="1.5"></path><path d="M3.38,9L5.6,9c0.38,0,0.62,0.41,0.43,0.74l-1.11,1.92l-1.11,1.92c-0.19,0.33-0.66,0.33-0.85,0l-1.11-1.92 L0.74,9.74C0.55,9.41,0.79,9,1.17,9L3.38,9z" fill="#637381"></path></g><g><path d="M12.04,12.02c0-0.34-0.09-0.6-0.28-0.8s-0.5-0.38-0.95-0.55c-0.45-0.17-0.83-0.33-1.16-0.5C9.33,10,9.05,9.81,8.82,9.6 C8.59,9.38,8.41,9.13,8.28,8.84C8.15,8.55,8.08,8.2,8.08,7.8c0-0.69,0.22-1.26,0.66-1.7s1.03-0.7,1.77-0.78V4h0.98v1.33 c0.73,0.1,1.29,0.41,1.7,0.91c0.41,0.5,0.62,1.15,0.62,1.95h-1.78c0-0.49-0.1-0.86-0.3-1.1c-0.2-0.24-0.47-0.37-0.82-0.37 c-0.34,0-0.6,0.1-0.78,0.29C9.95,7.2,9.86,7.47,9.86,7.8c0,0.31,0.09,0.56,0.27,0.75s0.52,0.38,1.01,0.58s0.89,0.38,1.21,0.56 c0.32,0.17,0.58,0.37,0.8,0.59c0.22,0.22,0.38,0.47,0.5,0.75c0.12,0.28,0.17,0.61,0.17,0.98c0,0.7-0.22,1.26-0.65,1.7 c-0.43,0.44-1.03,0.69-1.8,0.77v1.22h-0.98v-1.22c-0.84-0.09-1.49-0.39-1.95-0.9c-0.46-0.51-0.69-1.18-0.69-2.02h1.78 c0,0.49,0.12,0.86,0.35,1.12c0.23,0.26,0.57,0.39,1,0.39c0.36,0,0.65-0.1,0.86-0.29C11.93,12.6,12.04,12.35,12.04,12.02z" fill="#637381" stroke="none" stroke-width="0.4"></path></g></g><g></g><g></g><g></g><g></g><g></g><g></g></svg>';
                break;
            case 'calender' :
                $svg .= '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>';
                break;
            case 'file' :
                $svg .= '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#637381" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path><polyline points="13 2 13 9 20 9"></polyline></svg>';
                break;
            case 'inventory' :
                $svg .= '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#5C6AC4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="Polaris-Avatar__Image"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>';
                break;
            case 'price' :
                $svg .= '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#5C6AC4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="Polaris-Avatar__Image"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>';
                break;
            case 'product':
                $svg .= '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#5C6AC4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="Polaris-Avatar__Image"><polyline points="1 4 1 10 7 10"></polyline><polyline points="23 20 23 14 17 14"></polyline><path d="M20.49 9A9 9 0 0 0 5.64 5.64L1 10m22 4l-4.64 4.36A9 9 0 0 1 3.51 15"></path></svg>';
                break;
            case 'product_delete':
                $svg .= '<svg class="Polaris-Avatar__Image" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#5C6AC4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>';
                break;
            case 'sku':
                $svg .= '<svg class="Polaris-Avatar__Image" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24" height="24" viewBox="0 0 24 24"><defs><path id="a" d="M0 0h24v24H0V0z"></path></defs><clipPath id="b"><use xlink:href="#a" overflow="visible"></use></clipPath><path fill="#5c6ac4" clip-path="url(#b)" d="M21 10.12h-6.78l2.74-2.82c-2.73-2.7-7.15-2.8-9.88-.1-2.73 2.71-2.73 7.08 0 9.79 2.73 2.71 7.15 2.71 9.88 0C18.32 15.65 19 14.08 19 12.1h2c0 1.98-.88 4.55-2.64 6.29-3.51 3.48-9.21 3.48-12.72 0-3.5-3.47-3.53-9.11-.02-12.58 3.51-3.47 9.14-3.47 12.65 0L21 3v7.12zM12.5 8v4.25l3.5 2.08-.72 1.21L11 13V8h1.5z"></path></svg>';
                break;
            case 'ship':
                $svg .= '<svg class="Polaris-Avatar__Image" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke="#5C6AC4" stroke-width="2"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>';
                break;
            case 'on_site':
                $svg .= '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" class="feather feather-external-link" stroke="#637381"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>';
                break;
            case 'product_preview':
                $svg .= '<svg width="20" height="20" viewBox="0 0 20 22" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M9.75 1.5H4.5C4.10218 1.5 3.72064 1.65804 3.43934 1.93934C3.15804 2.22064 3 2.60218 3 3V15C3 15.3978 3.15804 15.7794 3.43934 16.0607C3.72064 16.342 4.10218 16.5 4.5 16.5H13.5C13.8978 16.5 14.2794 16.342 14.5607 16.0607C14.842 15.7794 15 15.3978 15 15V6.75L9.75 1.5Z" stroke="#637381" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M9.75 1.5V6.75H15" stroke="#637381" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M13.5 18.5C15.7091 18.5 17.5 16.7091 17.5 14.5C17.5 12.2909 15.7091 10.5 13.5 10.5C11.2909 10.5 9.5 12.2909 9.5 14.5C9.5 16.7091 11.2909 18.5 13.5 18.5Z" fill="white" stroke="#637381" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M18.5 19.5L16.325 17.325" stroke="#637381" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
</svg>';
                break;
            case 'share':
                $svg .= '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#637381" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" class="feather feather-share-2"><circle cx="18" cy="5" r="3"></circle><circle cx="6" cy="12" r="3"></circle><circle cx="18" cy="19" r="3"></circle><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line></svg>';
                break;
            case 'question':
                if ($icon_type == 'minor') {
                    $svg .= '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"><path fill="#212B36" fill-rule="evenodd" d="M11 11H9v-.148c0-.876.306-1.499 1-1.852.385-.195 1-.568 1-1a1.001 1.001 0 0 0-2 0H7c0-1.654 1.346-3 3-3s3 1 3 3-2 2.165-2 3zm-2 4h2v-2H9v2zm1-13a8 8 0 1 0 0 16 8 8 0 0 0 0-16z"/></svg>';
                } else {
                    $svg .= '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"><path fill="#637381" d="M10 0C4.486 0 0 4.486 0 10s4.486 10 10 10 10-4.486 10-10S15.514 0 10 0m0 18c-4.411 0-8-3.589-8-8s3.589-8 8-8 8 3.589 8 8-3.589 8-8 8m0-4.1a1.1 1.1 0 1 0 .001 2.201A1.1 1.1 0 0 0 10 13.9M10 4C8.625 4 7.425 5.161 7.293 5.293A1.001 1.001 0 0 0 8.704 6.71C8.995 6.424 9.608 6 10 6a1.001 1.001 0 0 1 .591 1.808C9.58 8.548 9 9.616 9 10.737V11a1 1 0 1 0 2 0v-.263c0-.653.484-1.105.773-1.317A3.013 3.013 0 0 0 13 7c0-1.654-1.346-3-3-3"/></svg>';
                }
                break;
            default :
                $svg = '';
                break;
        }
        return $svg;
    }

    public static function Icon($tag = 'button', $sygtype = '', $options = [])
    {
        $defaultOptions = [
            'id' => '',
            'class' => 'Polaris-Button Polaris-Button--iconOnly',
        ];

        $options = ArrayHelper::merge(
            $defaultOptions,
            $options
        );

        $svg = self::Svg($sygtype);

        $html = '<span class="Polaris-Button__Content">';
        $html .= '<span class="Polaris-Button__Icon">';
        $html .= '<span class="Polaris-Icon">';
        $html .= $svg;
        $html .= '</span>';
        $html .= '</span>';
        $html .= '</span>';
        $buttonhtml = '<' . $tag . ' ' . static::renderTagAttributes($options) . ">";
        //return isset(static::$voidElements[strtolower($tag)]) ? $buttonhtml : "$buttonhtml$html</$tag>";
        return "$buttonhtml$html</$tag>";
    }

    public static $voidElements = [
        'area' => 1,
        'base' => 1,
        'br' => 1,
        'col' => 1,
        'command' => 1,
        'embed' => 1,
        'hr' => 1,
        'img' => 1,
        'input' => 1,
        'keygen' => 1,
        'link' => 1,
        'meta' => 1,
        'param' => 1,
        'source' => 1,
        'track' => 1,
        'wbr' => 1,
    ];
    public static $attributeOrder = [
        'type',
        'id',
        'class',
        'name',
        'value',

        'href',
        'src',
        'action',
        'method',

        'selected',
        'checked',
        'readonly',
        'disabled',
        'multiple',

        'size',
        'maxlength',
        'width',
        'height',
        'rows',
        'cols',

        'alt',
        'title',
        'rel',
        'media',
    ];
    public static $dataAttributes = ['data', 'data-ng', 'ng'];

    public static function renderTagAttributes($attributes)
    {
        if (count($attributes) > 1) {
            $sorted = [];
            foreach (static::$attributeOrder as $name) {
                if (isset($attributes[$name])) {
                    $sorted[$name] = $attributes[$name];
                }
            }
            $attributes = array_merge($sorted, $attributes);
        }

        $html = '';
        foreach ($attributes as $name => $value) {
            if (is_bool($value)) {
                if ($value) {
                    $html .= " $name";
                }
            } elseif (is_array($value)) {
                if (in_array($name, static::$dataAttributes)) {
                    foreach ($value as $n => $v) {
                        if (is_array($v)) {
                            $html .= " $name-$n='" . Json::htmlEncode($v) . "'";
                        } else {
                            $html .= " $name-$n=\"" . static::encode($v) . '"';
                        }
                    }
                } else {
                    $html .= " $name='" . Json::htmlEncode($value) . "'";
                }
            } elseif ($value !== null) {
                $html .= " $name=\"" . static::encode($value) . '"';
            }
        }

        return $html;
    }

    public static function encode($content, $doubleEncode = true)
    {
        return htmlspecialchars($content, ENT_QUOTES | ENT_SUBSTITUTE, Yii::$app ? Yii::$app->charset : 'UTF-8', $doubleEncode);
    }

    public static function decode($content)
    {
        return htmlspecialchars_decode($content, ENT_QUOTES);
    }

    public static function Header($options = [])
    {
        $defaultOptions = [
            'heading' => '',
            'breadcrumb' => [],
            'primary_actions' => [],
            'secondary_actions' => [],
            'pagination_actions' => []
        ];

        $options = ArrayHelper::merge(
            $defaultOptions,
            $options
        );
        if ($options == $defaultOptions) {
            return '';
        }
        $headerClass = 'Polaris-Page-Header';

        $primaryButtonHtml = '';
        $secondaryActionHtml = '';
        $breadcrumbHtml = '';
        $rollButtonHtml = '';
        $paginationHtml = '';
        if (!empty($options['primary_actions'])) {
            $primaryButtonHtml .= '<div class="Polaris-Page-Header__PrimaryAction">';
            $primaryButtonHtml .= '<div class="Polaris-ButtonGroup">';

            foreach ($options['primary_actions'] as $actions) {
                $primaryButtonHtml .= '<div class="Polaris-ButtonGroup__Item">';
                $buttonClass = isset($actions['options']['class']) ? $actions['options']['class'] : '';
                if (isset($actions['as_primary']) && $actions['as_primary'] === false) {
                    $actions['options']['class'] = 'Polaris-Button ' . $buttonClass;
                } else {
                    $actions['options']['class'] = 'Polaris-Button Polaris-Button--primary ' . $buttonClass;
                }
                $buttonAttr = (isset($actions['options']) && !empty($actions['options'])) ? static::renderTagAttributes($actions['options']) : '';
                $primaryButtonHtml .= '<' . $actions['tag'] . ' ' . $buttonAttr . ">";
                $primaryButtonHtml .= '<span class="Polaris-Button__Content">';
                $primaryButtonHtml .= '<span class="Polaris-Button__Text">' . $actions['label'] . '</span>';
                $primaryButtonHtml .= '</span>';
                $primaryButtonHtml .= '</' . $actions['tag'] . '>';
                $primaryButtonHtml .= '</div>';
            }
            $primaryButtonHtml .= '</div>';
            $primaryButtonHtml .= '</div>';
        }
        if (!empty($options['secondary_actions'])) {
            $headerClass = $headerClass . ' Polaris-Page-Header__Header--hasSecondaryActions';

            $secondaryActionHtml .= '<div class="Polaris-Page-Header__SecondaryActions">';
            $secondaryActionHtml .= '<div class="Polaris-Page-Header__IndividualActions">';
            $popover = '';

            foreach ($options['secondary_actions'] as $actions) {
                $secondaryActionHtml .= '<div class="Polaris-Page-Header__IndividualAction">';
                $buttonClass = isset($actions['options']['class']) ? $actions['options']['class'] : '';
                if (isset($actions['as_secondary']) && $actions['as_secondary'] === false) {
                    $actions['options']['class'] = 'Polaris-Button ' . $buttonClass;
                } else {
                    $actions['options']['class'] = 'Polaris-Header-Action ' . $buttonClass;
                }
                $buttonAttr = (isset($actions['options']) && !empty($actions['options'])) ? static::renderTagAttributes($actions['options']) : '';
                $secondaryActionHtml .= '<' . $actions['tag'] . ' ' . $buttonAttr . ">";
                if (isset($actions['svg'])) {
                    $secondaryActionHtml .= $actions['svg'];
                }
                $secondaryActionHtml .= '<span class="Polaris-Button__Content">' . $actions['label'] . '</span>';
//                $secondaryActionHtml .= '<span class="Polaris-Button__Text">' . $actions['label'] . '</span>';
                $secondaryActionHtml .= '</span>';
                $secondaryActionHtml .= '</' . $actions['tag'] . '>';
                $secondaryActionHtml .= '</div>';

                $popover .= '<li>';
                $popover .= '<' . $actions['tag'] . ' ' . $buttonAttr . ">";
                if (isset($actions['svg'])) {
                    $popover .= $actions['svg'];
                }
                $popover .= '<div class="Polaris-ActionList__Content">' . $actions['label'] . '</div>';
                $popover .= '</' . $actions['tag'] . '>';
                $popover .= '</li>';
            }
            $secondaryActionHtml .= '</div>';
            $secondaryActionHtml .= '</div>';
            $headerClass = $headerClass . ' Polaris-Page-Header__Header--hasRollup';

            $rollButtonHtml .= '<div class="Polaris-Page-Header__Rollup">';
            $rollButtonHtml .= '<div class="Custom_polaris_dropdown">';
            $rollButtonHtml .= '<button type="button" class="Polaris-Button Polaris-Button--plain Polaris-Button--iconOnly" tabindex="0" aria-controls="Popover1" aria-owns="Popover1" aria-haspopup="true" aria-expanded="false">';
            $rollButtonHtml .= '<span class="Polaris-Button__Content">';
            $rollButtonHtml .= '<span class="Polaris-Button__Icon">';
            $rollButtonHtml .= '<span class="Polaris-Icon">';
            $rollButtonHtml .= '<svg class="Polaris-Icon__Svg" viewBox="0 0 20 20" focusable="false" aria-hidden="true"><path d="M6 10a2 2 0 1 1-4.001-.001A2 2 0 0 1 6 10zm6 0a2 2 0 1 1-4.001-.001A2 2 0 0 1 12 10zm6 0a2 2 0 1 1-4.001-.001A2 2 0 0 1 18 10z" fill-rule="evenodd"></path></svg>';
            $rollButtonHtml .= '</span>';
            $rollButtonHtml .= '</span>';
            $rollButtonHtml .= '</span>';
            $rollButtonHtml .= '</button>';

            $rollButtonHtml .= '<div class="Polaris-Popover" data-polaris-overlay="true">';
            $rollButtonHtml .= '<div class="Polaris-Popover__Wrapper">';
            $rollButtonHtml .= '<div id="Popover1" class="Polaris-Popover__Content">';
            $rollButtonHtml .= '<div class="Polaris-Popover__Pane Polaris-Scrollable Polaris-Scrollable--vertical Polaris-Scrollable--hasTopShadow">';
            $rollButtonHtml .= '<div class="Polaris-ActionList">';
            $rollButtonHtml .= '<div class="Polaris-ActionList__Section--withoutTitle">';
            $rollButtonHtml .= '<ul class="Polaris-ActionList__Actions">';
            $rollButtonHtml .= $popover;
            $rollButtonHtml .= '</ul>';
            $rollButtonHtml .= '</div>';
            $rollButtonHtml .= '</div>';
            $rollButtonHtml .= '</div>';
            $rollButtonHtml .= '</div>';
            $rollButtonHtml .= '</div>';
            $rollButtonHtml .= '</div>';

            $rollButtonHtml .= '</div>';
            $rollButtonHtml .= '</div>';
        }

        if (!empty($options['breadcrumb'])) {
            $headerClass = $headerClass . ' Polaris-Page-Header__Header--hasBreadcrumbs';

            $breadcrumbHtml .= '<nav role="navigation">';
            $breadcrumbHtml .= '<a class="Polaris-Breadcrumbs__Breadcrumb" href="' . $options['breadcrumb']['link'] . '" data-polaris-unstyled="true">';
            $breadcrumbHtml .= '<span class="Polaris-Breadcrumbs__Icon">';
            $breadcrumbHtml .= '<span class="Polarisc-Icon">';
            $breadcrumbHtml .= '<svg class="Polaris-Icon__Svg" viewBox="0 0 20 20" focusable="false" aria-hidden="true"><path d="M12 16a.997.997 0 0 1-.707-.293l-5-5a.999.999 0 0 1 0-1.414l5-5a.999.999 0 1 1 1.414 1.414L8.414 10l4.293 4.293A.999.999 0 0 1 12 16" fill-rule="evenodd"></path></svg>';
            $breadcrumbHtml .= '</span>';
            $breadcrumbHtml .= '</span>';
            $breadcrumbHtml .= '<span class="Polaris-Breadcrumbs__Content">' . $options['breadcrumb']['label'] . '</span>';
            $breadcrumbHtml .= '</a>';
            $breadcrumbHtml .= '</nav>';

        }

        if (!empty($options['pagination_options'])) {
            $headerClass = $headerClass . ' Polaris-Page-Header__Header--hasPagination';

            $paginationHtml .= '<div class="Polaris-Page-Header__Pagination">';
            $paginationHtml .= '<nav class="Polaris-Pagination Polaris-Pagination--plain" aria-label="Pagination">';
            foreach ($options['pagination_options'] as $paginations) {
                $paginationHtml .= self::Icon('button', 'next', ['class' => 'Polaris-Pagination__Button', 'href' => 'tesxt']);
            }
            $paginationHtml .= '</nav>';
            $paginationHtml .= '</div>';
        }
        $html = '';
        $html .= '<div class="' . $headerClass . '">';
        if ($breadcrumbHtml != '' || $paginationHtml != '' /*|| $rollButtonHtml != ''*/) {
            $html .= '<div class="Polaris-Page-Header__Navigation">';
            if ($breadcrumbHtml != '') {
                $html .= $breadcrumbHtml;
            }
            if ($paginationHtml != '') {
                $html .= $paginationHtml;
            }
            /*if ($rollButtonHtml != '') {
                $html .= $rollButtonHtml;
            }*/
            $html .= '</div>';
        }
        $html .= '<div class="Polaris-Page-Header__MainContent">';
        $html .= '<div class="Polaris-Page-Header__TitleAndActions">';
        $html .= '<div class="Polaris-Page-Header__TitleAndRollup">';
        $html .= '<div class="Polaris-Page-Header__Title">';
        $html .= '<div>';
        $html .= '<h1 class="Polaris-DisplayText Polaris-DisplayText--sizeLarge">' . $options['heading'] . '</h1>';
        $html .= '</div>';
        $html .= '<div>';
        $html .= '</div>';
        $html .= '</div>';
        if ($rollButtonHtml) {
            if ($rollButtonHtml != '') {
                $html .= $rollButtonHtml;
            }
        }
        $html .= '</div>';
        $html .= '<div class="Polaris-Page-Header__Actions">';
        $html .= $secondaryActionHtml;
        $html .= $primaryButtonHtml;
        $html .= '</div>';
        $html .= '</div>';
        $html .= $primaryButtonHtml;
        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }

    public static function learnMore($label, $url)
    {
        $html = '<div class="Polaris-FooterHelp"><div class="Polaris-FooterHelp__Content"><div class="Polaris-FooterHelp__Icon">';
        $html .= '<span class="Polaris-Icon Polaris-Icon--colorTeal Polaris-Icon--isColored Polaris-Icon--hasBackdrop"><svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true"><circle cx="10" cy="10" r="9" fill="currentColor"></circle>
                         <path d="M10 0C4.486 0 0 4.486 0 10s4.486 10 10 10 10-4.486 10-10S15.514 0 10 0m0 18c-4.411 0-8-3.589-8-8s3.589-8 8-8 8 3.589 8 8-3.589 8-8 8m0-4a1 1 0 1 0 0 2 1 1 0 1 0 0-2m0-10C8.346 4 7 5.346 7 7a1 1 0 1 0 2 0 1.001 1.001 0 1 1 1.591.808C9.58 8.548 9 9.616 9 10.737V11a1 1 0 1 0 2 0v-.263c0-.653.484-1.105.773-1.317A3.013 3.013 0 0 0 13 7c0-1.654-1.346-3-3-3"></path></svg></span></div>';
        $html .= '<div class="Polaris-FooterHelp__Text"><a class="Polaris-Link" target="_blank" href=' . $url . '>' . $label . '</a></div></div></div>';
        return $html;
    }
}