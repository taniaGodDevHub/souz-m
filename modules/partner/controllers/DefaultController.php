<?php

namespace app\modules\partner\controllers;

use app\controllers\AccessController;
use app\models\LeadForm;
use app\models\User;
use app\models\UserProfile;
use app\modules\cars\models\CarModel;
use Yii;
use yii\web\Response;

class DefaultController extends AccessController
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionLeads()
    {
        $leadForm = new LeadForm();
        return $this->render('leads', [
            'leadForm' => $leadForm,
        ]);
    }

    public function actionCreateLead()
    {
        $leadForm = new LeadForm();
        if ($leadForm->load(Yii::$app->request->post()) && $leadForm->createLead()) {
            Yii::$app->session->setFlash('success', 'Лид успешно создан.');
            return $this->redirect(['leads']);
        }
        Yii::$app->session->setFlash('error', 'Не удалось создать лид. Проверьте данные.');
        return $this->render('leads', [
            'leadForm' => $leadForm,
            'openLeadModal' => true,
        ]);
    }

    /**
     * Возвращает список моделей авто по mark_id (для каскадного выпадающего списка).
     */
    public function actionCarModels($mark_id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return CarModel::getListByMarkId($mark_id);
    }

    /**
     * Поиск клиентов по фамилии, имени или телефону для автодополнения.
     * Возвращает JSON: [{id, label, f, i, o, tel}, ...], label = "Ф И О\n+7 (XXX) XXX-XX-XX".
     */
    public function actionSearchClient($q)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $q = trim((string) $q);
        if (mb_strlen($q) < 2) {
            return [];
        }
        $telDigits = preg_replace('/\D+/', '', $q);

        $profiles = UserProfile::find()
            ->joinWith('user')
            ->where(['or',
                    ['like', 'user.tel', $telDigits],
                    ['like', 'user_profile.f', $q],
                    ['like', 'user_profile.i', $q],
                    ['like', 'user_profile.o', $q],
                ])
            ->limit(15)
            ->all();

        $out = [];
        foreach ($profiles as $p) {

            $f = $p->f;
            $i = $p->i;
            $o = $p->o;
            $tel = !empty($p->user) ? $p->user->tel : '';
            $id = $p->user_id;
            $label = trim($f . ' ' . $i . ' ' . $o) . "\n" . self::formatTelForDisplay($tel);
            $out[] = [
                'id' => $id,
                'label' => $label,
                'f' => $f,
                'i' => $i,
                'o' => $o,
                'tel' => $tel,
            ];
        }
        return $out;
    }

    private static function formatTelForDisplay(string $tel): string
    {
        $tel = preg_replace('/\D+/', '', $tel);
        if (strlen($tel) === 11 && ($tel[0] === '7' || $tel[0] === '8')) {
            if ($tel[0] === '8') {
                $tel = '7' . substr($tel, 1);
            }
            return '+7 (' . substr($tel, 1, 3) . ') ' . substr($tel, 4, 3) . '-' . substr($tel, 7, 2) . '-' . substr($tel, 9, 2);
        }
        return $tel ?: '';
    }
}
