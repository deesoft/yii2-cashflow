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
            ->joinWith('members u')
            ->where(['b.team_id' => null, 'b.type' => 'book'])
            ->andWhere(['OR', ['b.user_id' => $user_id], ['u.id' => $user_id]])
            ->all();
        $books[] = [
            'name' => 'Create new book',
            'description' => ''
        ];
        $teams = [
            [
                'id' => null,
                'name' => 'Personal Book',
                'books' => $books
            ]
        ];
        $ts = Team::find()->alias('t')
            ->joinWith('members u')
            ->with('books')
            ->where(['t.team_id' => null, 't.type' => 'team'])
            ->andWhere(['OR', ['t.user_id' => $user_id], ['u.id' => $user_id]])
            ->all();
        foreach ($ts as $team) {
            $books = $team->books;
            $books[] = [
                'name' => 'Create new book',
                'description' => ''
            ];
            $teams[] = [
                'id' => $team->id,
                'name' => $team->name,
                'books' => $books,
            ];
        }
        return $this->render('index', [
                'teams' => $teams
        ]);
    }

    public function actionCreate($team_id=null)
    {
        $model = new Book([
            'team_id' => $team_id,
        ]);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }
}
