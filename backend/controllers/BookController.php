<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\Book;
use common\models\Team;

/**
 * Site controller
 */
class BookController extends Controller
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                    'create' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $user_id = Yii::$app->user->id;
        $books = Book::find()->alias('b')
            ->joinWith('members m')
            ->where(['b.team_id' => null, 'b.type' => 'book'])
            ->andWhere(['OR', ['b.user_id' => $user_id], ['m.user_id' => $user_id]])
            ->all();
        $books[] = false;
        $teams = [
            [
                'id' => null,
                'name' => 'Personal Book',
                'books' => $books
            ]
        ];
        $ts = Team::find()->alias('t')
            ->select(['t.*', 'is_admin' => "([[t.user_id]]='$user_id' OR [[m.is_admin]]=1)"])
            ->joinWith('members m')
            ->with('books')
            ->where(['t.team_id' => null, 't.type' => 'team'])
            ->andWhere(['OR', ['t.user_id' => $user_id], ['m.user_id' => $user_id]])
            ->all();
        /* @var $team Team */
        $itemTeams = [];
        foreach ($ts as $team) {
            $books = $team->books;
            if ($team->is_admin) {
                $books[] = false;
                $itemTeams[$team->id] = $team->name;
            }
            
            $teams[] = [
                'id' => $team->id,
                'name' => $team->name,
                'books' => $books,
            ];
        }
        return $this->render('index', [
                'teams' => $teams,
                'itemTeams' => $itemTeams,
        ]);
    }

    public function actionCreate()
    {
        Yii::$app->getResponse()->format = 'json';
        $model = new Book([
            'user_id' => Yii::$app->user->id,
        ]);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view','id'=>$model->id]);
        }
        return $model->errors;
    }

    public function actionView($id)
    {
        $model = Book::findOne($id);

        return $this->render('view', [
            'model'=>$model,
        ]);
    }
}
