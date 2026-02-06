<?php

namespace app\modules\partner\controllers;

use app\controllers\AccessController;
use app\models\Lead;
use app\models\LeadForm;
use app\models\LeadStatus;
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

    /**
     * Страница финансов партнёра.
     */
    public function actionBilling()
    {
        return $this->render('billing');
    }

    public function actionLeads()
    {
        $leadForm = new LeadForm();
        $partnerId = Yii::$app->user->isGuest ? null : (int) Yii::$app->user->id;
        $leads = [];
        if ($partnerId) {
            $leads = Lead::find()
                ->where(['partner_id' => $partnerId])
                ->with(['client.profile', 'status', 'carMark', 'carModel', 'leadStatusHistories'])
                ->orderBy(['date_add' => SORT_DESC])
                ->all();
        }
        $timelineStatuses = LeadStatus::find()
            ->where(['not in', 'id', [10, 80, 90]])
            ->orderBy(['id' => SORT_ASC])
            ->all();
        return $this->render('leads', [
            'leadForm' => $leadForm,
            'leads' => $leads,
            'timelineStatuses' => $timelineStatuses,
        ]);
    }

    public function actionCreateLead()
    {
        $leadForm = new LeadForm();
        $post = Yii::$app->request->post();
        if ($leadForm->load($post)) {
            $leadForm->photos = is_array($post['photos'] ?? null) ? $post['photos'] : [];
            $leadForm->pdf = is_array($post['pdf'] ?? null) ? $post['pdf'] : [];
            if ($leadForm->createLead()) {
                Yii::$app->session->setFlash('success', 'Лид успешно создан.');
                return $this->redirect(['leads']);
            }
            $errors = $leadForm->getFirstErrors();
            $msg = empty($errors) ? 'Не удалось создать лид. Проверьте данные.' : implode(' ', $errors);
            Yii::$app->session->setFlash('error', $msg);
            Yii::warning([
                'LeadForm errors' => $leadForm->getErrors(),
                'attributes' => $leadForm->getAttributes(),
            ], __METHOD__);
        }
        return $this->render('leads', [
            'leadForm' => $leadForm,
            'openLeadModal' => true,
        ]);
    }

    /**
     * Просмотр одной заявки (только свои по partner_id).
     */
    public function actionLead($id)
    {
        $lead = Lead::find()
            ->where(['id' => (int) $id, 'partner_id' => (int) Yii::$app->user->id])
            ->with(['client.profile', 'status', 'carMark', 'carModel', 'leadStatusHistories.status', 'leadFiles', 'insuranceCompany'])
            ->one();
        if (!$lead) {
            throw new \yii\web\NotFoundHttpException('Заявка не найдена.');
        }
        return $this->render('lead', ['lead' => $lead]);
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

        $where = ['or',
            ['like', 'user_profile.f', $q],
            ['like', 'user_profile.i', $q],
            ['like', 'user_profile.o', $q],
        ];


        if(!empty($telDigits)){
            $where[] = ['like', 'user.tel', $telDigits];
        }

        $query = UserProfile::find()
            ->joinWith('user')
            ->where($where)
            ->limit(15);
        $sql = $query->createCommand()->rawSql;

        $profiles = $query->all();

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
