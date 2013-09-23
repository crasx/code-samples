<?php
	class AEDController extends Controller{
		protected $_CLASS="";
		protected $_OBJ="";
		protected $_PATH="";
        protected $callback=false;
        protected $ajaxIndex=1;
		
		protected function pullForm(&$obj, $form, $scenario){
			if(!isset($_POST[$form]))return false;
			return $obj->setData($_POST[$form], $scenario);
		}


		public function actionAdd()
		{
		$c=$this->_CLASS;
		$obj=$c::model();
		if(!$this->pullForm($obj, $this->_OBJ, 'add')){
			$this->render('add', array($this->_OBJ=>$obj));
		}else { //we processed it ok
			if(!$obj->save(false)){ //false=don't re-validate	
				$this->render('add', array($this->_OBJ=>$obj));
			}elseif($this->callback==null)header("Location:"._BASE."/".$this->_PATH."/?message=added");
            else{
                $c=$this->callback;
                $this->$c($obj, "added");
            }
		}


	}
        
    
    public function actionDelete()
    {
        $id=(int)$_GET['id'];
        $c=$this->_CLASS;
        $obj=$c::model()->findByPk($id);
        if($obj==null){
            if($this->callback==null)header("Location:"._BASE."/".$this->_PATH."/?message=invalid");
            else{
                $c=$this->callback;
                $this->$c($obj, "invalid");
            }
            return;
        }
        if(isset($_POST[$this->_OBJ])){
            $obj->delete();
            if($this->callback==null)header("Location:"._BASE."/".$this->_PATH."/?message=deleted");
            else{
                $c=$this->callback;
                $this->$c($obj, "deleted");
            }
        }

        $this->render('delete', array($this->_OBJ=>$obj));
    }
    
	
	protected function actionNotFound(){		
			if($this->callback==null)header("Location:"._BASE."/".$this->_PATH."/?message=invalid");
            else{
                $c=$this->callback;
                $this->$c($obj, "invalid");
            }
			exit;
	}
	
	public function actionEdit()
	{
		$c=$this->_CLASS;
		$id=(int)$_GET['id'];
		$obj=$c::model()->findByPk($id);
		if($obj==null){
			$this->actionNotFound();
			return;
		}
		if(!$this->pullForm($obj, $this->_OBJ, 'edit'))
			$this->render('edit', array($this->_OBJ=>$obj));
		else { //we processed it ok
			$obj->update();
            if($this->callback==null)header("Location:"._BASE."/".$this->_PATH."/?message=edited");
            else{
                $c=$this->callback;
                $this->$c($obj, "edited");
            }
		}
	}
    
    protected function pullDTData($searchable=array(), $sortable=array(),
        $vars=array('offset'=>0, 'limit'=>10, 'order'=>'')//defaults
        
    ) {
    
    	$criteria=new CDbCriteria($vars);
    	
        if(!$this->ajaxIndex)return false;
        if ( isset( $_GET['iDisplayStart'] ) && isset($_GET['iDisplayLength'])) {
            $s=intval($_GET['iDisplayStart']);
            $l=intval($_GET['iDisplayLength']);
            if($l>=0&&s>=0) {
                $criteria->offset=$s;
                $criteria->limit=$l;
            }
        }
        
        
        if ( isset( $_GET['iSortCol_0'] ) ) {
            $sort=array();
            for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ ) {
                $col=intval($_GET['iSortCol_'.$i]);
                if(array_key_exists($col, $sortable))
                                $sort[]=$sortable[$col].' '.($_GET['sSortDir_'.$i]=='asc'?'asc':'desc');
            }
            if(!empty($sort))$criteria->order=implode(', ', $sort);
        }
        
        
        if ( $_GET['sSearch'] != "" ) {
            foreach($searchable as $k=>$v) {
                if(!is_array($vars['search'][$v]))$vars['search'][$v]=array();
               $criteria->addSearchCondition($v, $_GET['sSearch']);
            }
        }


        for ( $i=0 ; $i<count($aColumns) ; $i++ ) {
            if (array_key_exists($i, $searchable)&&isset($_GET['sSearch_'.$i])) {
                $v=$searchable[$i];
                $criteira->addSearchCondition($v, $_GET['sSearch_'.$i]);
            }
        }
        return $criteria;
    }

	public function actionIndex()
	{
		$this->datatables();
        if(!$this->ajaxIndex){
    		$c=$this->_CLASS;
    		$obj=$c::model()->findAll();
    		$this->render('index', array($this->_OBJ."s"=>$obj));
        }else $this->render('index', array($this->_OBJ."s"=>array()));
		
	}
    
 
	
/*
    public function actionDelete()
    {
        $id=(int)$_GET['id'];
        $user=User::model()->findByPk($id);
        if($user==null){
            header("Location:"._BASE."/settings/users/?message=invalid");
            return;
        }
        if(isset($_POST['user'])){
            if($user->hasScores()){
                if(isset($_POST['user']['scores'])){
                    //delete scores
                }
            }
            $user->delete();
            header("Location:"._BASE."/settings/users/?message=deleted");
        }

        $this->render('delete', array("user"=>$user));
    }
*/
	
	}

?>