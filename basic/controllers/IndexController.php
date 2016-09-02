<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Test;
use yii\web\UploadedFile;


class IndexController extends Controller{

    //登录
    public function actionLogin(){
  /*      $session  = \Yii::$app->session;
        $session->open();
        if($session->isActive){
            echo "kai";
        }*/
        return $this->render('login.html');
    }

    //添加
    public function actionAdd(){

        // 判断文件夹是否存在不存在则创建

        $path = date ( 'Ymd'); // 接收文件目录
        if (! file_exists ( $path )) {
            mkdir ( "$path", 0777, true );
        }
        $test = new Test();
        $request =  \Yii::$app->request;
        $names = $request->post('user');
        $pwd  = $request->post('pwd');

        $upload=new UploadedFile(); //实例化上传类
        $name=$upload->getInstanceByName('myfile'); //获取文件原名称
        $img=$_FILES['myfile']; //获取上传文件参数
        $upload->tempName=$img['tmp_name']; //设置上传的文件的临时名称
        $img_path=$path.'/'.$name; //设置上传文件的路径名称(这里的数据进行入库)
        $upload->saveAs($img_path); //保存文件
        // var_dump($img);

        $test->name = $names;
        $test->pwd = $pwd;
        $test->img = $img_path;

        if($test->hasErrors()){
            echo "添加失败";
        }else{
            $test->save();
            return $this->redirect(array('/index/show'));
        }
    }
    //展示
    public  function actionShow(){
      $res =  Test::find()->asArray()->all();
        return $this->render('show.html',['res'=>$res]);
    }
    //删除
    public function actionDel(){
          //也可以用形参接值 写到上面的括号里
        $request = \Yii::$app->request;
        $id = $request->get('id');
        $arr = Test::deleteAll(array('id'=>$id));
        if($arr){
            return $this->redirect(array('/index/show'));
        }else{
            return $this->redirect(array('/index/show'));
        }
    }

}