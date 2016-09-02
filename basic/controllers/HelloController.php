<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/8/30
 * Time: 19:33
 */
namespace app\controllers;
use yii\web\Controller;
use app\models\User2;
use app\models\Tutu;
use yii\web\UploadedFile;
class HelloController extends Controller{

   // public $enableCsrfValidation=false;//防止form表单阻止提交
   //展示表单页面
    public function actionIndex(){
        return $this->render('index.html');
    }

   //入库操作
    public function actionAdd(){

        $user2 = new User2();
        $request = \YII::$app->request;
        $session = \YII::$app->session;
        $user = $request->post('user');

        $password = $request->post('pwd');
        $user2->name = $user;
        $user2->pwd =  $password;
        if($user2->hasErrors()){
            echo  "登录失败";
        }else{
            $session->open();
            $session->set('user',$user);
            $user2->save();
            return $this->redirect(array('hello/insert'));
            }
    }
    //展示添加界面
    public function actionInsert(){
        return $this->render('insert.html');
    }


    //用户添加图片
    public function actionUp(){

            $path = date('Ymd'); // 接收文件目录
            if (!file_exists($path)) {
                mkdir("$path", 0777, true);
            }
            $tutu = new Tutu();
            $upload = new UploadedFile(); //实例化上传类
            $name = $upload->getInstanceByName('myfile'); //获取文件原名称
            $img = $_FILES['myfile']; //获取上传文件参数
            $upload->tempName = $img['tmp_name']; //设置上传的文件的临时名称
            $img_path = $path . '/' . $name; //设置上传文件的路径名称(这里的数据进行入库)
            $upload->saveAs($img_path); //保存文件
            $tutu->img = $img_path;
            if ($tutu->hasErrors()) {
                echo "添加失败";
            } else {
                $tutu->save();
                return $this->redirect(array('hello/list'));
            }

    }
    //展示图片
    public function actionList(){
        $results = Tutu::find()->asArray()->all();
        return $this->render('list.html',['arr'=>$results]);

    }


    //查询展示
    public function actionShow(){
        $results = User2::find()->asArray()->all();
        return $this->render('show.html',['arr'=>$results]);
    }
    //删除
    public  function actionDel(){
            $request = \YII::$app->request;
            $id = $request->get('id');
       // print_r($id);
            $results = User2::deleteAll(array('id'=>$id));
            if($results){
                return $this->redirect(array('/hello/show'));
            }else{
                return $this->redirect(array('/hello/show'));
        }
    }
    //修改
    public function actionSave(){
         $request = \YII::$app->request;
         $id = $request->get('id');
         $user2 = User2::find()->where(['id'=>$id])->one();
         $user2->name = 'name';
         $user2->save();

    }
}