<?php
class ArticlesController extends AppController {
	public $layout='table';
	public function index() {
		$this->loadModel('Article');
		if (isset($this->data['Article']) && is_array($this->data['Article']) && count($this->data['Article'])>0){	
            if ($this->Article->saveAll($this->data)) {


                $this->Session->write('message_type','success');
                $this->Session->write('message','Save Successfully.');
                $this->redirect(array('action'=>'index'));
            }
		}
		$article = $this->Article->find('first');
		//debug($article);
        if(isset($article['Article']['id']) && (int)$article['Article']['id'] != 0){
        	$this->set(array(
        		'article'=>$article

        )); 
        }
	}

}