<?php

namespace app\modules\partner\controllers;

use app\controllers\AccessController;
use app\models\UserProfile;
use app\models\UserProfileForm;
use Yii;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * Профиль пользователя в кабинете партнёра.
 */
class UserProfileController extends AccessController
{
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Просмотр профиля (редирект после сохранения).
     */
    public function actionView($id)
    {
        $profile = $this->findModel($id);
        $this->checkProfileAccess($profile);
        return $this->render('view', [
            'profile' => $profile,
        ]);
    }

    /**
     * Редактирование профиля текущего партнёра.
     */
    public function actionUpdate()
    {
        $profile = UserProfile::find()
            ->where(['user_id' => Yii::$app->user->identity->id])
            ->with('user')
            ->one();

        if (empty($profile)) {
            $profile = new UserProfile();
            $profile->user_id = Yii::$app->user->identity->id;
            $profile->save(false);
        }

        $model = new UserProfileForm();
        $model->loadFromProfile($profile);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->saveToProfile($profile)) {
            return $this->redirect(['update']);
        }

        return $this->render('update', [
            'model' => $model,
            'profile' => $profile,
        ]);
    }

    protected function findModel($id)
    {
        $model = UserProfile::find()
            ->where(['id' => $id])
            ->with('user')
            ->one();
        if ($model !== null) {
            return $model;
        }
        throw new NotFoundHttpException('Страница не найдена.');
    }

    protected function checkProfileAccess(UserProfile $profile)
    {
        if ($profile->user_id != Yii::$app->user->id) {
            throw new NotFoundHttpException('Доступ запрещён.');
        }
    }
}
