<?php
App::uses('AppModel', 'Model');
class AuditLog extends AppModel {
	public $belongsTo=array('Prison','User');
}
