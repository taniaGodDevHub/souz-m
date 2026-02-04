<?php

namespace app\modules\stat\widgets;

use yii\base\Widget;

/**
 * Виджет статистики партнёра.
 *
 * Использование:
 * ```php
 * echo PartnerStatWidget::widget([]);
 * ```
 */
class PartnerStatWidget extends Widget
{
    public function run()
    {
        return $this->render('partner-stat');
    }
}
