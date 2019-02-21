<?php
App::uses('Controller', 'Controller');
class DocumentsController extends AppController{

	public function view($id)
	{
	    // ...
	    
	    $params = array(
	        'download' => true,
	        'name' => 'example.pdf',
	        'paperOrientation' => 'portrait',
	        'paperSize' => 'legal'
	    );
	    $this->set($params);
	}
}