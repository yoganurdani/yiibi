<?php

namespace app\controllers;

use Yii;
use app\models\Complain;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * KomplainController implements the CRUD actions for Complain model.
 */
class KomplainController extends Controller
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
     * Lists all Complain models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Complain::find(),
        ]);
        
        if (Yii::$app->user->isGuest) {
            $this->redirect(Yii::$app->urlManager->createAbsoluteUrl('site/login'));
        }
        $model = array();
        $data = Complain::find()->all();
        
        //$sql = 'SELECT AVG(jumlahKomplain) AS avg FROM complain';
        //$avg = Complain::findBySql($sql)->all(); 
       // $avg = $sql->queryScalar();
        
        $command = Yii::$app->db->createCommand("SELECT AVG(jumlahKomplain) FROM complain");
        $avg = $command->queryScalar();
        
        $command2 = Yii::$app->db->createCommand("SELECT AVG(responKomplain) FROM complain");
        $avgRespon = $command2->queryScalar();
        
        $command3 = Yii::$app->db->createCommand("SELECT round(AVG(responKomplain*100/jumlahKomplain), 2) FROM complain");
        $avgPerRespon = $command3->queryScalar();
        
        $avgNoRespon = 100-$avgPerRespon;
        
        $command4 = Yii::$app->db->createCommand("SELECT MAX(jumlahKomplain) FROM complain");
        $maxKom = $command4->queryScalar();
        
        $command5 = Yii::$app->db->createCommand("SELECT MIN(jumlahKomplain) FROM complain");
        $minKom = $command5->queryScalar();
        
        $command6 = Yii::$app->db->createCommand("SELECT round(MAX(jumlahKomplain*jumlahCS/responKomplain)) FROM complain");
        $maxCS = $command6->queryScalar();
        
        $command7 = Yii::$app->db->createCommand("SELECT CEILING(AVG(jumlahCS)) FROM complain");
        $avgCS = $command7->queryScalar();
       
        
        return $this->render('index', ['data'=> $data, 'model' => $model, 'avg' => $avg, 'avgRespon' => $avgRespon, 'avgPerRespon' => $avgPerRespon, 'avgNoRespon' => $avgNoRespon, 'maxKom' => $maxKom, 'minKom' => $minKom, 'maxCS' => $maxCS , 'avgCS' => $avgCS]);
    
    }
    
    public function actionJanuari()
    {
        $sql = 'SELECT * FROM complain WHERE SUBSTRING(tanggal,6,2) = 01';
        $data = Complain::findBySql($sql)->all(); 

        $command1 = Yii::$app->db->createCommand("SELECT AVG(jumlahKomplain) FROM complain WHERE SUBSTRING(tanggal,6,2) = 01");
        $avgJanuari = $command1->queryScalar();
        
        $command2 = Yii::$app->db->createCommand("SELECT AVG(responKomplain) FROM complain WHERE SUBSTRING(tanggal,6,2) = 01");
        $avgResponJan = $command2->queryScalar();
        
        $command3 = Yii::$app->db->createCommand("SELECT round(AVG(responKomplain*100/jumlahKomplain), 2) FROM complain WHERE SUBSTRING(tanggal,6,2) = 01");
        $avgPerResponJan = $command3->queryScalar();
        
        $avgNoResponJan = 100-$avgPerResponJan;
        
        $command4 = Yii::$app->db->createCommand("SELECT MAX(jumlahKomplain) FROM complain WHERE SUBSTRING(tanggal,6,2) = 01");
        $maxKomJan = $command4->queryScalar();
        
        $command5 = Yii::$app->db->createCommand("SELECT MIN(jumlahKomplain) FROM complain WHERE SUBSTRING(tanggal,6,2) = 01");
        $minKomJan = $command5->queryScalar();
        
         $command6 = Yii::$app->db->createCommand("SELECT round(MAX(jumlahKomplain*jumlahCS/responKomplain)) FROM complain WHERE SUBSTRING(tanggal,6,2) = 01");
        $maxCSJan = $command6->queryScalar();
       
        
       return $this->render('januari', ['data' => $data, 'avgJanuari' => $avgJanuari,'avgResponJan' => $avgResponJan, 'avgPerResponJan' => $avgPerResponJan, 'avgNoResponJan' => $avgNoResponJan, 'maxKomJan' => $maxKomJan, 'minKomJan' => $minKomJan, 'maxCSJan' =>  $maxCSJan  ]);
    }
    
     public function actionFebruari()
    {
        $sql = 'SELECT * FROM complain WHERE SUBSTRING(tanggal,6,2) = 02';
        $data = Complain::findBySql($sql)->all(); 
           
        $command1 = Yii::$app->db->createCommand("SELECT AVG(jumlahKomplain) FROM complain WHERE SUBSTRING(tanggal,6,2) = 02");
        $avgFebruari = $command1->queryScalar();
        
        $command2 = Yii::$app->db->createCommand("SELECT AVG(responKomplain) FROM complain WHERE SUBSTRING(tanggal,6,2) = 02");
        $avgResponFeb = $command2->queryScalar();
        
        $command3 = Yii::$app->db->createCommand("SELECT round(AVG(responKomplain*100/jumlahKomplain), 2) FROM complain WHERE           SUBSTRING(tanggal,6,2) = 02");
        $avgPerResponFeb = $command3->queryScalar();
        
        $avgNoResponFeb = 100-$avgPerResponFeb;
        
        $command4 = Yii::$app->db->createCommand("SELECT MAX(jumlahKomplain) FROM complain WHERE SUBSTRING(tanggal,6,2) = 02");
        $maxKomFeb = $command4->queryScalar();
        
        $command5 = Yii::$app->db->createCommand("SELECT MIN(jumlahKomplain) FROM complain WHERE SUBSTRING(tanggal,6,2) = 02");
        $minKomFeb = $command5->queryScalar();
        
         $command6 = Yii::$app->db->createCommand("SELECT round(MAX(jumlahKomplain*jumlahCS/responKomplain)) FROM complain WHERE SUBSTRING(tanggal,6,2) = 02");
        $maxCSFeb = $command6->queryScalar();
       
        
       return $this->render('februari', ['data' => $data, 'avgFebruari' => $avgFebruari,'avgResponFeb' => $avgResponFeb, 'avgPerResponFeb' => $avgPerResponFeb, 'avgNoResponFeb' => $avgNoResponFeb, 'maxKomFeb' => $maxKomFeb, 'minKomFeb' => $minKomFeb, 'maxCSFeb' =>  $maxCSFeb  ]);
   
    }
    
           public function actionMaret()
    {
        $sql = 'SELECT * FROM complain WHERE SUBSTRING(tanggal,6,2) = 03';
        $data = Complain::findBySql($sql)->all(); 
               
        $sql = 'SELECT * FROM complain WHERE SUBSTRING(tanggal,6,2) = 03';
        $data = Complain::findBySql($sql)->all(); 
           
        $command1 = Yii::$app->db->createCommand("SELECT AVG(jumlahKomplain) FROM complain WHERE SUBSTRING(tanggal,6,2) = 03");
        $avgMaret = $command1->queryScalar();
        
        $command2 = Yii::$app->db->createCommand("SELECT AVG(responKomplain) FROM complain WHERE SUBSTRING(tanggal,6,2) = 03");
        $avgResponMar = $command2->queryScalar();
        
        $command3 = Yii::$app->db->createCommand("SELECT round(AVG(responKomplain*100/jumlahKomplain), 2) FROM complain WHERE           SUBSTRING(tanggal,6,2) = 03");
        $avgPerResponMar = $command3->queryScalar();
        
        $avgNoResponMar = 100-$avgPerResponMar;
        
        $command4 = Yii::$app->db->createCommand("SELECT MAX(jumlahKomplain) FROM complain WHERE SUBSTRING(tanggal,6,2) = 03");
        $maxKomMar = $command4->queryScalar();
        
        $command5 = Yii::$app->db->createCommand("SELECT MIN(jumlahKomplain) FROM complain WHERE SUBSTRING(tanggal,6,2) = 03");
        $minKomMar = $command5->queryScalar();
        
         $command6 = Yii::$app->db->createCommand("SELECT round(MAX(jumlahKomplain*jumlahCS/responKomplain)) FROM complain WHERE SUBSTRING(tanggal,6,2) = 03");
        $maxCSMar = $command6->queryScalar();
       
        
       return $this->render('maret', ['data' => $data, 'avgMaret' => $avgMaret,'avgResponMar' => $avgResponMar, 'avgPerResponMar' => $avgPerResponMar, 'avgNoResponMar' => $avgNoResponMar, 'maxKomMar' => $maxKomMar, 'minKomMar' => $minKomMar, 'maxCSMar' =>  $maxCSMar  ]);
    }
    
           public function actionApril()
    {
        $sql = 'SELECT * FROM complain WHERE SUBSTRING(tanggal,6,2) = 04';
        $data = Complain::findBySql($sql)->all(); 

        $command1 = Yii::$app->db->createCommand("SELECT AVG(jumlahKomplain) FROM complain WHERE SUBSTRING(tanggal,6,2) = 04");
        $avgApril = $command1->queryScalar();
        
        $command2 = Yii::$app->db->createCommand("SELECT AVG(responKomplain) FROM complain WHERE SUBSTRING(tanggal,6,2) = 04");
        $avgResponApr = $command2->queryScalar();
        
        $command3 = Yii::$app->db->createCommand("SELECT round(AVG(responKomplain*100/jumlahKomplain), 2) FROM complain WHERE           SUBSTRING(tanggal,6,2) = 04");
        $avgPerResponApr = $command3->queryScalar();
        
        $avgNoResponApr = 100-$avgPerResponApr;
        
        $command4 = Yii::$app->db->createCommand("SELECT MAX(jumlahKomplain) FROM complain WHERE SUBSTRING(tanggal,6,2) = 04");
        $maxKomApr = $command4->queryScalar();
        
        $command5 = Yii::$app->db->createCommand("SELECT MIN(jumlahKomplain) FROM complain WHERE SUBSTRING(tanggal,6,2) = 04");
        $minKomApr = $command5->queryScalar();
        
         $command6 = Yii::$app->db->createCommand("SELECT round(MAX(jumlahKomplain*jumlahCS/responKomplain)) FROM complain WHERE SUBSTRING(tanggal,6,2) = 04");
        $maxCSApr = $command6->queryScalar();
       
        
       return $this->render('april', ['data' => $data, 'avgApril' => $avgApril,'avgResponApr' => $avgResponApr, 'avgPerResponApr' => $avgPerResponApr, 'avgNoResponApr' => $avgNoResponApr, 'maxKomApr' => $maxKomApr, 'minKomApr' => $minKomApr, 'maxCSApr' =>  $maxCSApr  ]);
    }
    
           public function actionMei()
    {
        $sql = 'SELECT * FROM complain WHERE SUBSTRING(tanggal,6,2) = 05';
        $data = Complain::findBySql($sql)->all(); 
       
        $command1 = Yii::$app->db->createCommand("SELECT AVG(jumlahKomplain) FROM complain WHERE SUBSTRING(tanggal,6,2) = 05");
        $avgMei = $command1->queryScalar();
        
        $command2 = Yii::$app->db->createCommand("SELECT AVG(responKomplain) FROM complain WHERE SUBSTRING(tanggal,6,2) = 05");
        $avgResponMei = $command2->queryScalar();
        
        $command3 = Yii::$app->db->createCommand("SELECT round(AVG(responKomplain*100/jumlahKomplain), 2) FROM complain WHERE           SUBSTRING(tanggal,6,2) = 05");
        $avgPerResponMei = $command3->queryScalar();
        
        $avgNoResponMei = 100-$avgPerResponMei;
        
        $command4 = Yii::$app->db->createCommand("SELECT MAX(jumlahKomplain) FROM complain WHERE SUBSTRING(tanggal,6,2) = 05");
        $maxKomMei = $command4->queryScalar();
        
        $command5 = Yii::$app->db->createCommand("SELECT MIN(jumlahKomplain) FROM complain WHERE SUBSTRING(tanggal,6,2) = 05");
        $minKomMei = $command5->queryScalar();
        
        $command6 = Yii::$app->db->createCommand("SELECT round(MAX(jumlahKomplain*jumlahCS/responKomplain)) FROM complain WHERE SUBSTRING(tanggal,6,2) = 05");
       $maxCSMei = $command6->queryScalar();
       
        
       return $this->render('mei', ['data' => $data, 'avgMei' => $avgMei,'avgResponMei' => $avgResponMei, 'avgPerResponMei' => $avgPerResponMei, 'avgNoResponMei' => $avgNoResponMei, 'maxKomMei' => $maxKomMei, 'minKomMei' => $minKomMei, 'maxCSMei' =>  $maxCSMei  ]);
    }
    
           public function actionJuni()
    {
        $sql = 'SELECT * FROM complain WHERE SUBSTRING(tanggal,6,2) = 06';
        $data = Complain::findBySql($sql)->all(); 
  
        $command1 = Yii::$app->db->createCommand("SELECT AVG(jumlahKomplain) FROM complain WHERE SUBSTRING(tanggal,6,2) = 06");
        $avgJuni = $command1->queryScalar();
        
        $command2 = Yii::$app->db->createCommand("SELECT AVG(responKomplain) FROM complain WHERE SUBSTRING(tanggal,6,2) = 06");
        $avgResponJuni = $command2->queryScalar();
        
        $command3 = Yii::$app->db->createCommand("SELECT round(AVG(responKomplain*100/jumlahKomplain), 2) FROM complain WHERE           SUBSTRING(tanggal,6,2) = 06");
        $avgPerResponJuni = $command3->queryScalar();
        
        $avgNoResponJuni = 100-$avgPerResponJuni;
        
        $command4 = Yii::$app->db->createCommand("SELECT MAX(jumlahKomplain) FROM complain WHERE SUBSTRING(tanggal,6,2) = 06");
        $maxKomJuni = $command4->queryScalar();
        
        $command5 = Yii::$app->db->createCommand("SELECT MIN(jumlahKomplain) FROM complain WHERE SUBSTRING(tanggal,6,2) = 06");
        $minKomJuni = $command5->queryScalar();
        
        $command6 = Yii::$app->db->createCommand("SELECT round(MAX(jumlahKomplain*jumlahCS/responKomplain)) FROM complain WHERE SUBSTRING(tanggal,6,2) = 06");
      $maxCSJuni = $command6->queryScalar();
       
        
       return $this->render('juni', ['data' => $data, 'avgJuni' => $avgJuni,'avgResponJuni' => $avgResponJuni, 'avgPerResponMei' => $avgPerResponJuni, 'avgNoResponJuni' => $avgNoResponJuni, 'maxKomJuni' => $maxKomJuni, 'minKomJuni' => $minKomJuni, 'maxCSJuni' =>  $maxCSJuni ]);
    }
           public function actionJuli()
    {
        $sql = 'SELECT * FROM complain WHERE SUBSTRING(tanggal,6,2) = 07';
        $data = Complain::findBySql($sql)->all(); 
               
        $command1 = Yii::$app->db->createCommand("SELECT AVG(jumlahKomplain) FROM complain WHERE SUBSTRING(tanggal,6,2) = 07");
        $avgJuli = $command1->queryScalar();
        
        $command2 = Yii::$app->db->createCommand("SELECT AVG(responKomplain) FROM complain WHERE SUBSTRING(tanggal,6,2) = 07");
        $avgResponJuli = $command2->queryScalar();
        
        $command3 = Yii::$app->db->createCommand("SELECT round(AVG(responKomplain*100/jumlahKomplain), 2) FROM complain WHERE           SUBSTRING(tanggal,6,2) = 07");
        $avgPerResponJuli = $command3->queryScalar();
        
        $avgNoResponJuli = 100-$avgPerResponJuli;
        
        $command4 = Yii::$app->db->createCommand("SELECT MAX(jumlahKomplain) FROM complain WHERE SUBSTRING(tanggal,6,2) = 07");
        $maxKomJuli = $command4->queryScalar();
        
        $command5 = Yii::$app->db->createCommand("SELECT MIN(jumlahKomplain) FROM complain WHERE SUBSTRING(tanggal,6,2) = 07");
        $minKomJuli = $command5->queryScalar();
        
        $command6 = Yii::$app->db->createCommand("SELECT round(MAX(jumlahKomplain*jumlahCS/responKomplain)) FROM complain WHERE SUBSTRING(tanggal,6,2) = 07");
      $maxCSJuli = $command6->queryScalar();
       
        
       return $this->render('juli', ['data' => $data, 'avgJuli' => $avgJuli,'avgResponJuli' => $avgResponJuli, 'avgPerResponJuli' => $avgPerResponJuli, 'avgNoResponJuli' => $avgNoResponJuli, 'maxKomJuli' => $maxKomJuli, 'minKomJuli' => $minKomJuli, 'maxCSJuli' =>  $maxCSJuli ]);
    }
    
           public function actionAgustus()
    {
        $sql = 'SELECT * FROM complain WHERE SUBSTRING(tanggal,6,2) = 08';
        $data = Complain::findBySql($sql)->all(); 
        
        $command1 = Yii::$app->db->createCommand("SELECT AVG(jumlahKomplain) FROM complain WHERE SUBSTRING(tanggal,6,2) = 08");
        $avgAgustus = $command1->queryScalar();
        
        $command2 = Yii::$app->db->createCommand("SELECT AVG(responKomplain) FROM complain WHERE SUBSTRING(tanggal,6,2) = 08");
        $avgResponAgs = $command2->queryScalar();
        
        $command3 = Yii::$app->db->createCommand("SELECT round(AVG(responKomplain*100/jumlahKomplain), 2) FROM complain WHERE           SUBSTRING(tanggal,6,2) = 08");
        $avgPerResponAgs = $command3->queryScalar();
        
        $avgNoResponAgs = 100-$avgPerResponAgs;
        
        $command4 = Yii::$app->db->createCommand("SELECT MAX(jumlahKomplain) FROM complain WHERE SUBSTRING(tanggal,6,2) = 08");
        $maxKomAgs = $command4->queryScalar();
        
        $command5 = Yii::$app->db->createCommand("SELECT MIN(jumlahKomplain) FROM complain WHERE SUBSTRING(tanggal,6,2) = 08");
        $minKomAgs = $command5->queryScalar();
        
        $command6 = Yii::$app->db->createCommand("SELECT round(MAX(jumlahKomplain*jumlahCS/responKomplain)) FROM complain WHERE SUBSTRING(tanggal,6,2) = 08");
      $maxCSAgs = $command6->queryScalar();
       
        
       return $this->render('agustus', ['data' => $data, 'avgAgustus' => $avgAgustus,'avgResponAgs' => $avgResponAgs, 'avgPerResponAgs' => $avgPerResponAgs, 'avgNoResponAgs' => $avgNoResponAgs, 'maxKomAgs' => $maxKomAgs, 'minKomAgs' => $minKomAgs, 'maxCSAgs' =>  $maxCSAgs ]);
    }
    
           public function actionSeptember()
    {
        $sql = 'SELECT * FROM complain WHERE SUBSTRING(tanggal,6,2) = 09';
        $data = Complain::findBySql($sql)->all(); 
               
        $command1 = Yii::$app->db->createCommand("SELECT AVG(jumlahKomplain) FROM complain WHERE SUBSTRING(tanggal,6,2) = 09");
        $avgSeptember = $command1->queryScalar();
        
        $command2 = Yii::$app->db->createCommand("SELECT AVG(responKomplain) FROM complain WHERE SUBSTRING(tanggal,6,2) = 09");
        $avgResponSep = $command2->queryScalar();
        
        $command3 = Yii::$app->db->createCommand("SELECT round(AVG(responKomplain*100/jumlahKomplain), 2) FROM complain WHERE           SUBSTRING(tanggal,6,2) = 09");
        $avgPerResponSep = $command3->queryScalar();
        
        $avgNoResponSep = 100-$avgPerResponSep;
        
        $command4 = Yii::$app->db->createCommand("SELECT MAX(jumlahKomplain) FROM complain WHERE SUBSTRING(tanggal,6,2) = 09");
        $maxKomSep = $command4->queryScalar();
        
        $command5 = Yii::$app->db->createCommand("SELECT MIN(jumlahKomplain) FROM complain WHERE SUBSTRING(tanggal,6,2) = 09");
        $minKomSep = $command5->queryScalar();
        
        $command6 = Yii::$app->db->createCommand("SELECT round(MAX(jumlahKomplain*jumlahCS/responKomplain)) FROM complain WHERE SUBSTRING(tanggal,6,2) = 09");
      $maxCSSep = $command6->queryScalar();
       
        
       return $this->render('september', ['data' => $data, 'avgSeptember' => $avgSep,'avgResponSep' => $avgResponSep, 'avgPerResponSep' => $avgPerResponSep, 'avgNoResponSep' => $avgNoResponSep, 'maxKomSep' => $maxKomSep, 'minKomSep' => $minKomSep, 'maxCSSep' =>  $maxCSSep ]);
    }
    
           public function actionOktober()
    {
        $sql = 'SELECT * FROM complain WHERE SUBSTRING(tanggal,6,2) = 10';
        $data = Complain::findBySql($sql)->all(); 
               
      $command1 = Yii::$app->db->createCommand("SELECT AVG(jumlahKomplain) FROM complain WHERE SUBSTRING(tanggal,6,2) = 10");
        $avgOkt = $command1->queryScalar();
        
        $command2 = Yii::$app->db->createCommand("SELECT AVG(responKomplain) FROM complain WHERE SUBSTRING(tanggal,6,2) = 10");
        $avgResponOkt = $command2->queryScalar();
        
        $command3 = Yii::$app->db->createCommand("SELECT round(AVG(responKomplain*100/jumlahKomplain), 2) FROM complain WHERE           SUBSTRING(tanggal,6,2) = 10");
        $avgPerResponOkt = $command3->queryScalar();
        
        $avgNoResponOkt = 100-$avgPerResponOkt;
        
        $command4 = Yii::$app->db->createCommand("SELECT MAX(jumlahKomplain) FROM complain WHERE SUBSTRING(tanggal,6,2) = 10");
        $maxKomOkt = $command4->queryScalar();
        
        $command5 = Yii::$app->db->createCommand("SELECT MIN(jumlahKomplain) FROM complain WHERE SUBSTRING(tanggal,6,2) = 10");
        $minKomOkt = $command5->queryScalar();
        
        $command6 = Yii::$app->db->createCommand("SELECT round(MAX(jumlahKomplain*jumlahCS/responKomplain)) FROM complain WHERE SUBSTRING(tanggal,6,2) = 10");
      $maxCSOkt = $command6->queryScalar();
       
        
       return $this->render('oktober', ['data' => $data, 'avgOkt' => $avgOkt,'avgResponOkt' => $avgResponOkt, 'avgPerResponOkt' => $avgPerResponOkt, 'avgNoResponOkt' => $avgNoResponOkt, 'maxKomOkt' => $maxKomOkt, 'minKomOkt' => $minKomOkt, 'maxCSOkt' =>  $maxCSOkt ]);
    }
    
           public function actionNovember()
    {
        $sql = 'SELECT * FROM complain WHERE SUBSTRING(tanggal,6,2) = 11';
        $data = Complain::findBySql($sql)->all(); 
        
        $command1 = Yii::$app->db->createCommand("SELECT AVG(jumlahKomplain) FROM complain WHERE SUBSTRING(tanggal,6,2) = 11");
        $avgNov = $command1->queryScalar();
        
        $command2 = Yii::$app->db->createCommand("SELECT AVG(responKomplain) FROM complain WHERE SUBSTRING(tanggal,6,2) = 11");
        $avgResponNov = $command2->queryScalar();
        
        $command3 = Yii::$app->db->createCommand("SELECT round(AVG(responKomplain*100/jumlahKomplain), 2) FROM complain WHERE           SUBSTRING(tanggal,6,2) = 11");
        $avgPerResponNov = $command3->queryScalar();
        
        $avgNoResponNov = 100-$avgPerResponNov;
        
        $command4 = Yii::$app->db->createCommand("SELECT MAX(jumlahKomplain) FROM complain WHERE SUBSTRING(tanggal,6,2) = 11");
        $maxKomNov = $command4->queryScalar();
        
        $command5 = Yii::$app->db->createCommand("SELECT MIN(jumlahKomplain) FROM complain WHERE SUBSTRING(tanggal,6,2) = 11");
        $minKomNov = $command5->queryScalar();
        
        $command6 = Yii::$app->db->createCommand("SELECT round(MAX(jumlahKomplain*jumlahCS/responKomplain)) FROM complain WHERE SUBSTRING(tanggal,6,2) = 11");
      $maxCSNov = $command6->queryScalar();
       
        
       return $this->render('november', ['data' => $data, 'avgNov' => $avgNov,'avgResponNov' => $avgResponNov, 'avgPerResponNov' => $avgPerResponNov, 'avgNoResponNov' => $avgNoResponNov, 'maxKomNov' => $maxKomNov, 'minKomNov' => $minKomNov, 'maxCSNov' =>  $maxCSNov ]);
    }
    
           public function actionDesember()
    {
        $sql = 'SELECT * FROM complain WHERE SUBSTRING(tanggal,6,2) = 12';
        $data = Complain::findBySql($sql)->all(); 
        
        $command1 = Yii::$app->db->createCommand("SELECT AVG(jumlahKomplain) FROM complain WHERE SUBSTRING(tanggal,6,2) = 12");
        $avgDes = $command1->queryScalar();
        
        $command2 = Yii::$app->db->createCommand("SELECT AVG(responKomplain) FROM complain WHERE SUBSTRING(tanggal,6,2) = 12");
        $avgResponDes = $command2->queryScalar();
        
        $command3 = Yii::$app->db->createCommand("SELECT round(AVG(responKomplain*100/jumlahKomplain), 2) FROM complain WHERE           SUBSTRING(tanggal,6,2) = 12");
        $avgPerResponDes = $command3->queryScalar();
        
        $avgNoResponDes = 100-$avgPerResponDes;
        
        $command4 = Yii::$app->db->createCommand("SELECT MAX(jumlahKomplain) FROM complain WHERE SUBSTRING(tanggal,6,2) = 12");
        $maxKomDes = $command4->queryScalar();
        
        $command5 = Yii::$app->db->createCommand("SELECT MIN(jumlahKomplain) FROM complain WHERE SUBSTRING(tanggal,6,2) = 12");
        $minKomDes = $command5->queryScalar();
        
        $command6 = Yii::$app->db->createCommand("SELECT round(MAX(jumlahKomplain*jumlahCS/responKomplain)) FROM complain WHERE SUBSTRING(tanggal,6,2) = 12");
      $maxCSDes = $command6->queryScalar();
       
        
       return $this->render('desember', ['data' => $data, 'avgDes' => $avgDes,'avgResponDes' => $avgResponDes, 'avgPerResponDes' => $avgPerResponDes, 'avgNoResponDes' => $avgNoResponDes, 'maxKomDes' => $maxKomDes, 'minKomDes' => $minKomDes, 'maxCSDes' =>  $maxCSDes ]);
    }

    /**
     * Displays a single Complain model.
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
     * Creates a new Complain model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Complain();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Complain model.
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
     * Deletes an existing Complain model.
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
     * Finds the Complain model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Complain the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Complain::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
