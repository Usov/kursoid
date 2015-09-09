<?php
/**
 * Created by JetBrains PhpStorm.
 * User: alekseenko
 * Date: 5/14/15
 * Time: 11:35 PM
 * To change this template use File | Settings | File Templates.
 */

class LoginController extends Controller{

    public function actionIndex(){
        if (Yii::app()->user->isGuest) {
            $user = Yii::app()->request->getParam('user');
            if(!empty($user)) {
                $identity = new UserIdentity($user, Yii::app()->request->getParam('pass'));
                $i = $identity->authenticate();
                if($i){
                    Yii::app()->user->login($identity);
                    $this->redirect('/admin');
                }
                else
                    echo $identity->errorMessage;
            }
            $this->render('auth');
        }
    }
}