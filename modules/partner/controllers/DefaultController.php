<?php

namespace app\modules\partner\controllers;

use app\controllers\AccessController;
use app\models\LeadForm;
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
}
