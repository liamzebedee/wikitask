<?php

class ApiPutTask extends ApiBase {
	public function execute() {
		$params = $this->extractRequestParams();
		if(!$this->getUser()->matchEditToken($params['wpEditToken'])) {
			return false; // XXX not the best way to fail
		}
		
		$tcat = $params['cat'];
		$ttype = $params['type'];
		$ttitle = $params['title'];
		$tdaysTillDue = $params['daysTillDue'];
		
		$tasks = TasksTable::singleton();
		$tasktypes = TaskTypesTable::singleton();
		
		// Get cat and type ids
		// WikiPage->getId()
		
		/*$task = $tasks->newRow(array(
			'title' => '',
			'daysTillDue' => '',
			'type' => '',
			'cat' => '',
		));
		$task->save();*/
		
		return true;
	}
	
	public function getAllowedParams() {
		return array(
			'cat' => array(
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_REQUIRED => true,
			),
			'type' => array(
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_REQUIRED => true,
			),
			'title' => array(
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_REQUIRED => true,
			),
			'daysTillDue' => array(
				ApiBase::PARAM_TYPE => 'integer',
				ApiBase::PARAM_REQUIRED => true
			),
			'wpEditToken' => array(
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_REQUIRED => true
			),
		);
	}
	
	public function getParamDescription() {
		return array(
			'cat' => '',
			'type' => '',
			'title' => '',
			'daysTillDue' => '',
			'wpEditToken' => '',
		);
	}
	
	public function getDescription() {
		return 'A module for managing tasks';
	}
	
	public function getVersion() {
		return __CLASS__ . ': 1.0';
	}
	
}

class ApiRemTask extends ApiBase {
	public function execute() {
		$params = $this->extractRequestParams();
		if(!$this->getUser()->matchEditToken($params['wpEditToken'])) {
			return false; // XXX not the best way to fail
		}
		
		$tasks = TasksTable::singleton();
		$task = $tasks->delete( array('id' => $params['taskid']) );
		return true;
	}
	
	public function getAllowedParams() {
		return array(
			'taskid' => array(
				ApiBase::PARAM_TYPE => 'integer',
				ApiBase::PARAM_REQUIRED => true,
			),
			'wpEditToken' => array(
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_REQUIRED => true
			),
		);
	}
	
	public function getParamDescription() {
		return array(
			'taskid' => '',
			'wpEditToken' => '',
		);
	}
	
	public function getDescription() {
		return 'A module for managing tasks';
	}
	
	public function getVersion() {
		return __CLASS__ . ': 1.0';
	}
	
}

?>
