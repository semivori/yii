<?php


namespace semivori\yii\helpers;


class Html extends \yii\helpers\Html
{
    /**
     * @param  array  $attributes
     * @return string
     */
    public static function renderTagAttributes($attributes)
    {
        self::boolOptions($attributes);

        $display = Arr::extract($attributes, 'display', true);

        if (!$display) {
            self::addCssStyle($attributes, ['display' => 'none']);
        }

        return parent::renderTagAttributes($attributes);
    }

    /**
     * @inheritdoc
     */
    public static function addCssClass(&$options, $class)
    {
        $class = self::boolClass($class);

        parent::addCssClass($options, $class);
    }

    /**
     * @param  string|array  $class
     * @return array
     */
    public static function boolClass($class)
    {
        $initialClass = $class;

        if (is_array($initialClass)) {
            $class = [];

            foreach ($initialClass as $name => $value) {
                if (is_bool($value)) {
                    if ($value) {
                        $class[] = $name;
                    }
                } else {
                    $class[] = $value;
                }
            }
        }

        return $class;
    }

    /**
     * @param $options
     */
    public static function boolOptions(&$options)
    {
        if (isset($options['class'])) {
            $options['class'] = self::boolClass($options['class']);
        }
    }
    /**
     * @param  array  $options
     * @return string
     */
    public static function beginDiv($options = [])
    {
        return self::beginTag('div', $options);
    }

    /**
     * @return string
     */
    public static function endDiv()
    {
        return self::endTag('div');
    }

    /**
     * @param  string  $html
     * @return string|string[]|null
     */
    public static function urlToLink($html)
    {
        return preg_replace_callback(
            '/((([A-Za-z]{3,9}:(?:\/\/)?)(?:[-;:&=\+\$,\w]+@)?[A-Za-z0-9.-]+|(?:www.|[-;:&=\+\$,\w]+@)[A-Za-z0-9.-]+)(:[0-9]{0,5})?(#[\w]*)?((?:\/[\+~%\/.\w\-_]*)?\??(?:[-\+=&;%@.\w_]*)#?(?:[.\!\/\\w]*))?)/',
            function ($matches) {
                $match = $matches[0];
                $url = $match;

                if (preg_match('/^www\./', $url)) {
                    $url = "https://{$url}";
                }

                return Html::a($match, $url, ['target' => '_blank']);
            },
            $html
        );
    }
    /**
     * @param  array  $data
     * @return string
     */
    public static function table($data)
    {
        // start table
        $html = '<table>';

        if ($data) {
            // header row
            $html .= '<tr>';
            foreach ($data[0] as $key => $value) {
                $html .= '<th>'.htmlspecialchars($key).'</th>';
            }
            $html .= '</tr>';

            // data rows
            foreach ($data as $key => $value) {
                $html .= '<tr>';
                foreach ($value as $key2 => $value2) {
                    $value2 = is_array($value2) || is_object($value2) ? json_encode($value2) : $value2;

                    $html .= '<td style="padding: 3px; border: 1px solid #000000;">'.htmlspecialchars($value2).'</td>';
                }
                $html .= '</tr>';
            }
            // finish table and return it
        }

        $html .= '</table>';

        return $html;
    }

    /**
     * @param  string  $content
     * @param  array  $options
     * @return string
     */
    public static function div($content, $options = [])
    {
        return self::tag('div', $content, $options);
    }

    /**
     * @param  string  $content
     * @param  array  $options
     * @return string
     */
    public static function span($content, $options = [])
    {
        return self::tag('span', $content, $options);
    }

    /**
     * @param  array  $options
     * @param  string  $key
     * @return bool
     */
    public static function hasDataAttribute($options, $key)
    {
        return isset($options["data-{$key}"]) || isset($options['data'][$key]);
    }
}
