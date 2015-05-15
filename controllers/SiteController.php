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
        $data = Complain::find()->all();
        return $this->render('index', ['data'=> $data]);
    }

    public function actionLogin() {
        $this->layout = 'login';

        if (!\Yii::$app->user->isGuest) {
            $this->redirect(Yii::$app->urlManager->createAbsoluteUrl('site/index'));
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
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
	
        $model = new UploadForm();
        $listIsi = array();
        array_push($listIsi, "masuk -2");
        if (Yii::$app->request->isPost) {
            $model->file = UploadedFile::getInstance($model, 'file');
            array_push($listIsi, "masuk -1");
           if ($model->file && $model->validate()) {                
               if($fp = fopen($model->file->tempName, 'r')){
                   array_push($listIsi, "masuk -122");
                    while (($line = fgetcsv($fp, 1000, ";")) !== false) {
                        array_push($listIsi, "masuk .....");
                        array_push($listIsi, $line[0], $line[1], $line[2], $line[3]);
                    
                        $new = new Complain;
                        $new->jumlahKomplain = $line[0];
                        $new->tanggal = $line[1];
                        $new->responKomplain = $line[2];
                        $new->jumlahCS = $line[3];
                        if(!$new->save()){
                            //array_push($listIsi, $new->getErrors());
                               
                        }
                     }
               }
               $model->file->saveAs('uploads/' . $model->file->baseName . '.' . $model->file->extension);
            }
            array_push($listIsi, $model->file->tempName);
            $tpm = tempnam("C:\xampp\htdocs\yiibi\web\uploads\complain.csv", "complain");
            array_push($listIsi, $tpm);
            
            if (($fp = fopen("C:\xampp\htdocs\yiibi\web\uploads\complain.csv", "r")) !== false) {
                array_push($listIsi, "masuk 1");
			     while (($line = fgetcsv($fp, 1000, ",")) !== false) {
                    array_push($listIsi, "masuk 2");
                    array_push($listIsi, $line[0], $line[1], $line[2], $line[3]);
                    $new = new Complain;
                    $new->jumlahKomplain = $line[0];
                    $new->tanggal = $line[1];
                    $new->responKomplain = $line[2];
                    $new->jumlahCS = $line[3];
                    $new->save();
			     }
			     fclose($fp);
			     $this->redirect(array('upload'));
        	}
        }

        return $this->render('upload', ['model' => $model, 'listisi' => $listIsi]);
      }
}



    
