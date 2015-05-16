<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\ResetPasswordForm;
use app\models\PasswordResetRequestForm;
use app\models\SignupForm;
use app\models\User;
use app\models\Complain;
use app\models\ImportCsv;
use app\models\UploadForm;
use yii\helpers\Url;
use yii\web\UploadedFile;

//use app\widgets\Alert;


class SiteController extends Controller {

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
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

    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex() {
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

    public function actionLogin() {
        $this->layout = 'login';

        if (!\Yii::$app->user->isGuest) {
            $this->redirect(Yii::$app->urlManager->createAbsoluteUrl('site/upload'));
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->redirect('site/upload');
        } else {
            return $this->render('login', [
                        'model' => $model,
            ]);
        }
    }

    public function actionLogout() {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionMe() {
        Yii::$app->util->tab = 5;
        $tab = (int) $_GET['tab'];
        $active = [];
        for ($i = 1; $i <= 3; $i++) {
            if ($i == $tab)
                $active[$i] = true;
            else
                $active[$i] = false;
        }

        if (Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }
        $user = Yii::$app->user->identity;
        Yii::$app->util->member = $user;

        $model = $user;
        $model->scenario = 'update';
        if ($model->loadWithFiles(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Well done! successfully to create data!  ');
            //return $this->redirect(['index', 'username' => $user->username]);
        } else {

//            return $this->render('profile', [
//                        'model' => $model,
//                        'active' => $active
//            ]);
        }

        return $this->render('profile', [
                    'model' => $model,
                    'active' => $active
        ]);
    }

    public function actionForgot_password() {
        $this->layout = 'login';
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendMail()) {
                Yii::$app->getSession()->setFlash('success', 'Check your email for further instructions.');
                return $this->redirect(Yii::$app->urlManager->createAbsoluteUrl('site/login'));
            } else {
                Yii::$app->getSession()->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
            }
        }

        return $this->render('requestPasswordResetToken', [
                    'model' => $model,
        ]);
    }

    public function actionResetPassword($token) {
        $this->layout = 'login';
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->getSession()->setFlash('success', 'New password was saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
                    'model' => $model,
        ]);
    }

    public function actionChange_password() {
        Yii::$app->util->tab = 5;
        $user = Yii::$app->user->identity;
        $token = Yii::$app->security->generateRandomString() . '_' . time();
        //echo $token; exit(0);
        $user->password_reset_token = $token;
        $user->save();
        Yii::$app->util->member = $user;
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->getSession()->setFlash('success', 'New password was saved.');

            return $this->goHome();
        }

        return $this->render('_change_password', ['model' => $model, 'user' => $user]);
    }

    public function actionContact() {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        } else {
            return $this->render('contact', [
                        'model' => $model,
            ]);
        }
    }

    public function actionAbout() {
        return $this->render('about');
    }

    public function actionSignup() {
        $this->layout = 'login';
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    Yii::$app->session->setFlash('success', 'Thank you for register. We will respond to you as soon as possible.');
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
                    'model' => $model,
        ]);
    }

    public function actionThanks($id) {
        $user = User::find()->where(['id' => $id])->one();
        return $this->render('thanks', ['user' => $user]);
    }

    public function actionParsing() {
        $cacheMethod = \PHPExcel_CachedObjectStorageFactory::cache_to_sqlite3;  /* here i added */
        $cacheEnabled = \PHPExcel_Settings::setCacheStorageMethod($cacheMethod);
        if (!$cacheEnabled) {
            echo "### WARNING - Sqlite3 not enabled ###" . PHP_EOL;
        }
        $objPHPExcel = new \PHPExcel();

        $fileExcel = Yii::getAlias('@webroot/templates/operator.xls');
        $inputFileType = \PHPExcel_IOFactory::identify($fileExcel);

        $objReader = \PHPExcel_IOFactory::createReader($inputFileType);

        $objReader->setReadDataOnly(true);

        /**  Load $inputFileName to a PHPExcel Object  * */
        $objPHPExcel = $objReader->load($fileExcel);

        $total_sheets = $objPHPExcel->getSheetCount();

        $allSheetName = $objPHPExcel->getSheetNames();
        $objWorksheet = $objPHPExcel->setActiveSheetIndex(0);
        $highestRow = $objWorksheet->getHighestRow();
        $highestColumn = $objWorksheet->getHighestColumn();
        $highestColumnIndex = \PHPExcel_Cell::columnIndexFromString($highestColumn);
        for ($row = 1; $row <= $highestRow; ++$row) {
            for ($col = 0; $col < $highestColumnIndex; ++$col) {
                $value = $objWorksheet->getCellByColumnAndRow($col, $row)->getValue();

                $arraydata[$row - 1][$col] = $value;
            }
        }



        echo '<pre>';
        print_r($arraydata);
    }

    public function actionTesting() {
        $message = "#sintret ada aja kali";
        $pos = strpos($message, "#");
        if ($pos !== FALSE) {
            echo 'ada #';
            $usernameSendgrid = \Yii::$app->params['sendgrid_username'];
            $passwordSendgrid = \Yii::$app->params['sendgrid_password'];
            $users = \app\models\User::find()->where(['status' => \app\models\User::STATUS_ACTIVE])->all();
            foreach ($users as $model) {
                echo $model->username;
                $aprot = '#' . strtolower($model->username);
                if (strpos($message, $aprot) !== false) {
                    echo 'ada' . $aprot;
                    $sendgrid = new \SendGrid($usernameSendgrid, $passwordSendgrid, array("turn_off_ssl_verification" => true));
                    $email = new \SendGrid\Email();
                    $email->addTo($model->email)->
                            setFrom(\Yii::$app->params['supportEmail'])->
                            setSubject('Chat from ' . \Yii::$app->name)->
                            setHtml($message);
                    $sendgrid->send($email);
                } else {
                    
                }
            }
        }
    }
    
    public function actionImportcsv() {
	$model = new ImportCsv;
	if (isset($_POST['ImportCsv'])) {
		$model->attributes = $_POST['ImportCsv'];
		$file = CUploadedFile::getInstance($model, 'file');
		if (($fp = fopen($file->tempName, "r")) !== false) {
			while (($line = fgetcsv($fp, 1000, ",")) !== false) {
				$new_user = new User;
				$new_user->nama = $line[0];
				$new_user->username = $line[1];
				$new_user->password = md5($line[2]);
				$new_user->save();
			}
			fclose($fp);
			$this->redirect(array('admin'));
		}
	}
	$this->render('importcsv', array('model'=>$model));
}
    
      public function actionUpload()
    {
	   Complain::deleteAll();
        $model = new UploadForm();
        if (Yii::$app->request->isPost) {
            $model->file = UploadedFile::getInstance($model, 'file');
           if ($model->file && $model->validate()) {                
               if($fp = fopen($model->file->tempName, 'r')){
                    while (($line = fgetcsv($fp, 1000, ",")) !== false) {
                        $new = new Complain;
                        $new->jumlahKomplain = $line[0];
                        $new->tanggal = $line[1];
                        $new->responKomplain = $line[2];
                        $new->jumlahCS = $line[3];
                        $new->save();
                     }
               }
               $model->file->saveAs('uploads/' . $model->file->baseName . '.' . $model->file->extension);
               fclose($fp);
               $this->redirect('index');
            }
         
        }

        return $this->render('upload', ['model' => $model]);
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
       
        
       return $this->render('juli', ['data' => $data, 'avgJuli' => $avgJuni,'avgResponJuli' => $avgResponJuli, 'avgPerResponJuli' => $avgPerResponJuli, 'avgNoResponJuli' => $avgNoResponJuli, 'maxKomJuli' => $maxKomJuli, 'minKomJuli' => $minKomJuli, 'maxCSJuli' =>  $maxCSJuli ]);
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
       
        
       return $this->render('agustus', ['data' => $data, 'avgAgustus' => $avgJuni,'avgResponAgs' => $avgResponAgs, 'avgPerResponAgs' => $avgPerResponAgs, 'avgNoResponAgs' => $avgNoResponAgs, 'maxKomAgs' => $maxKomAgs, 'minKomAgs' => $minKomAgs, 'maxCSAgs' =>  $maxCSAgs ]);
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
}



    
