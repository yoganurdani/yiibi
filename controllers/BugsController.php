<?php

namespace app\controllers;

use Yii;
use app\models\Bugs;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BugsController implements the CRUD actions for Bugs model.
 */
class BugsController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Bugs models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Bugs::find(),
        ]);

        $model = array();
        $data = Bugs::find()->all();
        $tipe = array(array('Fitur Pencarian Tiket Promo',
                'Fitur Pemesanan Tiket',
                'Fitur Pencarian Tiket',
                'Fitur Tutorial',
                'Fitur Login',
                'Fitur Registrasi',
                'Lain-lain'), array(0,0,0,0,0,0,0));
        $selector = "All";
        if (Yii::$app->request->isPost) {
            $selector = Yii::$app->request->post('id');
        }
        
        if ($selector == "All")
        {
            foreach ($data as $key => $value){
                    
                    if(count($tipe) == 0)
                    {
                        array_push($tipe[0], $value->tipeBugs);
                        array_push($tipe[1], $value->jumlahBugs);
                    }
                   
                    for ($i = 0; $i<count($tipe[0]); $i++)
                    {
                        if ($value->tipeBugs == $tipe[0][$i])
                        {
                            $tipe[1][$i] += $value->jumlahBugs;
                        //    break;
                        }

                        //if ($i == count($tipe)-1)
                        //{
                        //    array_push($tipe[0], $value->tipeBugs);
                        //    array_push($tipe[1], $value->jumlahBugs);
                        //}
                    }
            }
        }
        else {
            foreach ($data as $key => $value){
                $tanggal = date("F",strtotime($value->tanggal));
                $tanggal = (string) $tanggal;
                if ( $selector == $tanggal )
                {
                    if(count($tipe) == 0)
                    {
                        array_push($tipe[0], $value->tipeBugs);
                        array_push($tipe[1], $value->jumlahBugs);
                    }
                    else
                    {
                        for ($i = 0; $i<count($tipe[0]); $i++)
                        {
                            if ($value->tipeBugs == $tipe[0][$i])
                            {
                                $tipe[1][$i] += $value->jumlahBugs;
                            //    break;
                            }

                            //if ($i == count($tipe)-1)
                            //{
                            //    array_push($tipe[0], $value->tipeBugs);
                            //    array_push($tipe[1], $value->jumlahBugs);
                            //}

                        }
                    }
                }
            }
        }
        
        return $this->render('index', [
            'tipe' => $tipe,
            'selector' => $selector
        ]);
    }

    /**
     * Displays a single Bugs model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Bugs model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Bugs();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Bugs model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Bugs model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Bugs model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Bugs the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Bugs::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
