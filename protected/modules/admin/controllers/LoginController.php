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
        // Проверяем является ли пользователь гостем
        // ведь если он уже зарегистрирован - формы он не должен увидеть.
        if (Yii::app()->user->isGuest) {
            if (!empty($_POST['user'])) {
                $identity = new UserIdentity($_POST['user'],$_POST['pass']);
                $i = $identity->authenticate();
                if($i){
                    Yii::app()->user->login($identity);
                    $this->redirect('/admin');
                }
                else
                    echo $identity->errorMessage;
            }
            $this->render('login');
        }
    }
}