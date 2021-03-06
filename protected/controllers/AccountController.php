<?php

class AccountController extends Controller {
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    //public $layout='//layouts/column2';

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow', // allow all users to perform 'index' and 'view' actions
                'actions' => array('index', 'view'),
                'users' => array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('create', 'update', "facebook", "link", "post", 'fbfeeds', 'fbpage'),
                'users' => array('@'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('admin', 'delete'),
                'users' => array('admin'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new Account;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Account'])) {
            $model->attributes = $_POST['Account'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Account'])) {
            $model->attributes = $_POST['Account'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $dataProvider = new CActiveDataProvider('Account');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionFacebook() {
        $fb = new Facebook\Facebook([
            'app_id' => '317411091935995',
            'app_secret' => 'efeee4a67b612f6f3cdfb5d818aee5c0',
            'default_graph_version' => 'v2.5',
        ]);

        $helper = $fb->getRedirectLoginHelper();
        $permissions = ['email', 'user_likes', "publish_actions", "user_posts"]; // optional
        $loginUrl = $helper->getLoginUrl('http://localhost/social/index.php?r=account/link', $permissions);
        return $this->redirect($loginUrl);
    }

    public function actionLink() {
        $fb = new Facebook\Facebook([
            'app_id' => '317411091935995',
            'app_secret' => 'efeee4a67b612f6f3cdfb5d818aee5c0',
            'default_graph_version' => 'v2.5',
        ]);

        $helper = $fb->getRedirectLoginHelper();
        try {
            $accessToken = $helper->getAccessToken();
        } catch (Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        if (isset($accessToken)) {
            // Logged in!
            $_SESSION['facebook_access_token'] = (string) $accessToken;
            var_dump($accessToken);
        }
    }

    public function actionPost() {
        $token = "EAAEgrwdh8vsBAC37Gz5MzxZBwejJreLjElQTRqgslNQTs63uhAngWgZBIYAcPVqMrXFXhebQLZC80pmeoyHHUgVS0WZAZBCvSoEu3pWhgmZAkDx2qS8NG1D0MM1Sq8ctlgRDJ1UjdFNWVRro8RbF2JSsz6LsFLWVgZD";
        $fb = new Facebook\Facebook([
            'app_id' => '317411091935995',
            'app_secret' => 'efeee4a67b612f6f3cdfb5d818aee5c0',
            'default_graph_version' => 'v2.5',
        ]);
        $linkData = [
            'link' => "www.google.com",
            'message' => 'Tembelea website yetu',
        ];

        try {
            // Returns a `Facebook\FacebookResponse` object
            $response = $fb->post('/me/feed', $linkData, $token);
        } catch (Facebook\Exceptions\FacebookResponseException $e) {
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        $graphNode = $response->getGraphNode();

        echo 'Posted with id: ' . $graphNode['id'];
    }

    public function actionfbfeeds() {
        $this->layout = 'fbmain';
        $token = "EAAEgrwdh8vsBAC37Gz5MzxZBwejJreLjElQTRqgslNQTs63uhAngWgZBIYAcPVqMrXFXhebQLZC80pmeoyHHUgVS0WZAZBCvSoEu3pWhgmZAkDx2qS8NG1D0MM1Sq8ctlgRDJ1UjdFNWVRro8RbF2JSsz6LsFLWVgZD";
        $fb = new Facebook\Facebook([
            'app_id' => '317411091935995',
            'app_secret' => 'efeee4a67b612f6f3cdfb5d818aee5c0',
            'default_graph_version' => 'v2.5',
        ]);

        $request = new Facebook\FacebookRequest(
                $fb->getApp(), $token, 'GET', '/me/feed?fields=full_picture,message,story'
        );

        try {
            $response = $fb->getClient()->sendRequest($request);
        } catch (Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }
        $feeds = $response->getGraphEdge();

        $this->render('feeds', array(
            'feeds' => $feeds,
        ));
    }

    public function actionFbpage() {
        $token = "EAAEgrwdh8vsBAC37Gz5MzxZBwejJreLjElQTRqgslNQTs63uhAngWgZBIYAcPVqMrXFXhebQLZC80pmeoyHHUgVS0WZAZBCvSoEu3pWhgmZAkDx2qS8NG1D0MM1Sq8ctlgRDJ1UjdFNWVRro8RbF2JSsz6LsFLWVgZD";
        $fb = new Facebook\Facebook([
            'app_id' => '317411091935995',
            'app_secret' => 'efeee4a67b612f6f3cdfb5d818aee5c0',
            'default_graph_version' => 'v2.5',
        ]);

        $request = new Facebook\FacebookRequest(
                $fb, 'POST', $token, '/1099207700172918/feed', array(
            'message' => 'This is a test message',
                )
        );
      

       

        try {
            // Returns a `Facebook\FacebookResponse` object
            $response = $fb->post('/1099207700172918/feed', $request, $token);
        } catch (Facebook\Exceptions\FacebookResponseException $e) {
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        $graphNode = $response->getGraphNode();

        echo 'Posted with id: ' . $graphNode['id'];
    }

    public function actionAdmin() {
        $model = new Account('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Account']))
            $model->attributes = $_GET['Account'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Account the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = Account::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Account $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'account-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
