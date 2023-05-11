<?php

namespace Sixgweb\Forms\Classes;

class Helper
{
    public static function entryFieldValuesToHTML($model, $options = [])
    {
        $fields = $model->getFieldableFields();
        $values = $options['field_values'] ?? $model->field_values;

        $c = $options['container'] ?? 'ul';
        $w = $options['wrapper'] ?? 'li';
        $l = $options['label'] ?? 'strong';
        $ls = $options['labelSeparator'] ?? ': ';

        $html = '<' . $c . '>';

        foreach ($fields as $field) {
            $value = '';
            switch ($field->type) {
                case 'fileupload':
                    $relation = 'field_values_' . $field->code;
                    if ($value = $model->{$relation}) {
                        if ($value instanceof \October\Rain\Database\Collection) {
                            $files = [];
                            foreach ($value as $file) {
                                $files[] = $file->getPath();
                            }
                            $value = $files;
                        } else {
                            $value = $value->getPath();
                        }
                    }
                    break;
                case 'repeater':
                    if (isset($values[$field->code])) {
                        if (is_array($values[$field->code])) {
                            foreach ($values[$field->code] as $repeater) {
                                $options['field_values'] = $repeater;
                                $value .= self::entryFieldValuesToHTML($field, $options);
                            }
                        } else {
                            $options['field_values'] = $values[$field->code];
                            $value = self::entryFieldValuesToHTML($field, $options);
                        }
                    }
                    break;
                default:
                    $value = $values[$field->code] ?? null;
                    break;
            }

            $html .= '<' . $w . '>';
            $html .= '<' . $l . '>' . $field->name  . $ls . '</' . $l . '>';
            if (is_array($value)) {
                $html .= '<' . $c . '><' . $w . '>';
                $html .= implode('</' . $w . '>' . '<' . $w . '>', $value);
                $html .= '</' . $w . '>' . '</' . $c . '>';
            } else {
                $html .= $value;
            }
            $html .= '</' . $w . '>';
        }
        $html .= '</' . $c . '>';
        return $html;
    }
}
