<?php

namespace app\modules\partner\widgets;

use yii\base\Widget;

/**
 * Виджет «Статья» в кабинете партнёра.
 * Пока только рендерит свою вьюшку.
 *
 * Использование:
 * ```php
 * echo ArticleWidget::widget([]);
 * ```
 */
class ArticleWidget extends Widget
{
    public function run()
    {
        return $this->render('article');
    }
}
