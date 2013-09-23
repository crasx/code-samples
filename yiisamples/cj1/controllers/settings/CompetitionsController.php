<?php

class CompetitionsController extends AEDController
{

	public function beforeAction($action){
	
		$this->_OBJ="competition";
		$this->_CLASS="Competitions";		
		$this->_PATH="settings/competitions";
			$this->ajaxIndex=0;

		return parent::beforeAction($action);
	}
	
	
	public function accessRules() {
		return array(
			array('allow','actions'=>array('index', 'edit', 'add', 'delete', 'deletescores'), 'users'=>array('@'), 'roles'=>array('settings_competition')),
			array('deny','users'=>array('*'))
		);
	}
	
	    public function actionDeletescores()
    {
        $id=(int)$_GET['id'];
        $c=$this->_CLASS;
        $obj=$c::model()->findByPk($id);
        if($obj==null){
            header("Location:"._BASE."/".$this->_PATH."/?message=invalid");

        }
        if(isset($_POST[$this->_OBJ])){
            $obj->removeScores();
            header("Location:"._BASE."/".$this->_PATH."/?message=cleared");
            
        }
		$this->render('deletescores', array($this->_OBJ=>$obj));
    
	}
}