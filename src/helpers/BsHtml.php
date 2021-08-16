<?php


namespace semivori\yii\helpers;


use yii\helpers\ArrayHelper;

trait BsHtml
{

    /**
     * @param  string  $left
     * @param  string  $right
     * @param  array  $options
     * @return string
     */
    public static function row(string $left, string $right, $options = [])
    {
        Html::addCssClass($options, 'row');

        return Html::tag(
            'div',
            $left.$right,
            $options
        );
    }

    /**
     * @param  string  $left
     * @param  string  $right
     * @param  array  $options
     * @return string
     */
    public static function twoCols($left, $right, $options = [])
    {
        Html::addCssClass($options, 'row');
        $sizes = Arr::extract($options, 'sizes', [6, 6]);
        $left = self::column($left, $sizes[0]);
        $right = self::column($right, $sizes[1]);

        return Html::tag(
            'div',
            $left.$right,
            $options
        );
    }

    /**
     * @param  string  $content
     * @param  string  $size
     * @param  array  $options
     * @return string
     */
    public static function column(string $content, string $size, $options = [])
    {
        $options = array_merge(
            [
                'breakpoint' => null,
                'tag' => 'div',
            ],
            $options
        );

        $options['breakpoint'] = isset($options['bp']) ? $options['bp'] : $options['breakpoint'];

        if ($options['breakpoint']) {
            $options['breakpoint'] = "-{$options['breakpoint']}";
        }

        Html::addCssClass($options, ["col{$options['breakpoint']}-{$size}"]);

        return Html::tag(Arr::extract($options, 'tag'), $content, $options);
    }

    /**
     * @param  array  $items
     * @param  array  $options
     * @return string
     */
    public static function justifyBtw(array $items, array $options = [])
    {
        Html::addCssClass($options, 'd-flex justify-content-between align-items-center');

        return Html::tag('div', implode('', $items), $options);
    }

    /**
     * @param  array  $items
     * @param  array  $options
     * @return string
     */
    public static function justifyCenter(array $items, array $options = [])
    {
        Html::addCssClass($options, 'd-flex justify-content-center align-items-center');

        return Html::tag('div', implode('', $items), $options);
    }

    /**
     * @param  string  $content
     * @param  array  $options
     * @param  string  $tag
     * @return string
     */
    public static function dFlex($content, $options = [], $tag = 'div')
    {
        Html::addCssClass($options, 'd-flex');

        return Html::tag($tag, $content, $options);
    }

    /**
     * @param  string  $content
     * @param  array  $options
     * @return string
     */
    public static function inputGroup(string $content, array $options = [])
    {
        Html::addCssClass($options, 'input-group');

        return Html::tag('div', $content, $options);
    }

    /**
     * @param  string  $text
     * @param  string  $target
     * @param  array  $options
     * @return string
     */
    public static function modalA(string $text, string $target, array $options = [])
    {
        $options['data-toggle'] = 'modal';
        $options['data-target'] = $target;

        return Html::a($text, "#", $options);
    }

    /**
     * @param  array  $options
     * @return string
     */
    public static function beginRow(array $options = [])
    {
        Html::addCssClass($options, 'row');

        return Html::beginTag('div', $options);
    }

    /**
     * @return string
     */
    public static function endRow()
    {
        return Html::endDiv();
    }

    /**
     * @param  int  $size
     * @param  string  $content
     * @param  array  $options
     * @return string
     */
    public static function h(int $size, string $content = '', array $options = [])
    {
        if (Arr::extract($options, 'bold', false)) {
            Html::addCssClass($options, 'font-weight-bold');
        }

        return Html::tag("h$size", $content, $options);
    }

    /**
     * @param  string  $label
     * @param  array  $options
     * @return string
     */
    public static function submitButtonRow(string $label = 'Save', array $options = [])
    {
        Html::addCssClass($options, 'btn btn-primary');

        return Html::div(Html::submitButton($label, $options), ['class' => 'form-group text-right']);
    }

    /**
     * @param $title
     * @param  string|null  $subTitle
     * @param  array  $options
     * @return string
     */
    public static function modalHeader(string $title, $subTitle = null, array $options = [])
    {
        if ($subTitle) {
            $title .= '<br>';
            $title .= Html::tag('b', $subTitle);
        }

        $content = Html::tag(Arr::extract($options, 'header.tag', 'h5'), $title, ['class' => 'modal-title']);
        $content .= '<div><button type="button" class="close pt-1" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';

        return Html::tag('div', $content, ['class' => 'modal-header d-flex justify-content-between']);
    }

    /**
     * @param  array  $options
     * @return string
     */
    public static function beginModalBody($options = [])
    {
        Html::addCssClass($options, 'modal-body py-2 px-3');

        return Html::beginDiv($options);
    }

    /**
     * @param $id
     * @param $size
     * @param  array  $options
     * @return string
     */
    public static function beginModalContent($id, $size, $options = [])
    {
        Html::addCssClass($options, 'modal fade');

        $options = ArrayHelper::merge(
            $options,
            [
                'id' => $id,
                'tabindex' => "-1",
                'role' => "dialog",
                'aria-hidden' => "true",
            ]
        );

        $content = Html::beginDiv($options);
        $content .= Html::beginDiv(['class' => "modal-dialog modal-{$size}", 'role' => 'document']);
        $content .= Html::beginDiv(['class' => "modal-content"]);

        return $content;
    }

    /**
     * @return string
     */
    public static function endModalContent()
    {
        return Html::endDiv().Html::endDiv().Html::endDiv();
    }

    /**
     * @param  array  $options
     * @return string
     */
    public static function beginCard($options = [])
    {
        Html::addCssClass($options, 'card');

        return Html::beginTag('div', $options);
    }

    /**
     * @param  array  $options
     * @return string
     */
    public static function beginCardBody($options = [])
    {
        Html::addCssClass($options, 'card-body');

        return Html::beginTag('div', $options);
    }

    /**
     * @param  array  $options
     * @return string
     */
    public static function cardHeader($content, $options = [])
    {
        Html::addCssClass($options, 'text-white mb-0');

        return Html::div(Html::tag('h3', $content, $options), ['class' => 'card-header']);
    }

    /**
     * @param  bool  $responsive
     * @param  array  $options
     * @return string
     */
    public static function beginTable($responsive = true, $options = [])
    {
        $content = '';

        if ($responsive) {
            $content .= Html::beginDiv(['class' => 'table-responsive']);
        }

        Html::addCssClass($options, 'table table-sm table-striped');
        $content .= Html::beginTag('table', $options);

        return $content;
    }

    /**
     * @param  bool  $responsive
     * @return string
     */
    public static function endTable($responsive = true)
    {
        $content = '';

        $content .= Html::endTag('table');

        if ($responsive) {
            $content .= Html::endDiv();
        }

        return $content;
    }

    /**
     * @param  string  $text
     * @param  null  $url
     * @param  array  $options
     * @return mixed
     */
    public static function aBlock(string $text, $url = null, $options = [])
    {
        Html::addCssClass($options, ['btn-block']);

        return Html::a($text, $url, $options);
    }

    /**
     * @param  string  $content
     * @param  array  $options
     * @return mixed
     */
    public static function buttonBlock($content = 'Button', $options = [])
    {
        Html::addCssClass($options, ['btn-block']);

        return Html::button($content, $options);
    }
}
