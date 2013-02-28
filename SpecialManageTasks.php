<?php

/*
 * A page for:
 *  - adding/completing/removing tasks
 *  - adding/removing task types
 */
class SpecialManageTasks extends SpecialPage {
	
	public function __construct() {
		parent::__construct( 'ManageTasks', 'wikitask/manage' );
	}
	
	public function execute( $par ) {
		$this->setHeaders();

		if ( $this->getUser()->isBlocked() ) {
			$block = $this->getUser()->getBlock();
			throw new UserBlockedError( $block );
		}
		
		$this->getOutput()->setPageTitle("Manage Tasks");
		$this->displayManageTasks();
	}
	
	private function displayManageTasks() {	
		$out = $this->getOutput();
	}
	
}

class WikiTaskTask extends ORMRow {}
class WikiTaskTaskType extends ORMRow {}

class TasksTable extends ORMTable {
	protected $maxDaysTillDue;
	
	public function getName() {
		return 'wikitask_tasks';
	}
	
	public function getRowClass() {
		return 'WikiTaskTask';
	}
	
	protected function getFieldPrefix() {
		return 'task_';
	}
	
	public function getFields() {
		return array(
			'title' => 'str',
			'daysTillDue' => 'int',
			'type' => 'int',
			'cat' => 'int',
			'id' => 'int'
		);
	}
	
	public function insert() {
		// XXX
		// db master
		// get maxDaysTillDue
		// if($maxDaysTillDue < $params['daysTillDue']) then update property
		// insert
	}
	
	public function delete() {
		// XXX
		// db master
		// find max days till due without row->id
		// $max = SELECT daysTillDue FROM rows WHERE id != id
		// set new max
		// delete row
	}
}

class TaskTypesTable extends ORMTable {
	protected $maxPriority;

	public function getName() {
		return 'wikitask_tasktypes';
	}
	
	public function getFieldPrefix() {
		return 'tasktype_';
	}
	
	public function getRowClass() {
		return 'WikiTaskTaskType';
	}
	
	public function getFields() {
		return array(
			'cat' => 'int',
			'priority' => 'int',
			'id' => 'int'
		);
	}
	
	public function insert() {
		// XXX
		// db master
		// get maxpriority
		// if($maxpriority < $params['priority']) then update property
		// insert
	}
	
	public function delete() {
		// XXX
		// db master
		// find max days till due without row->id
		// $max = SELECT priority FROM rows WHERE id != id
		// set new max
		// delete row
	}
}

?>
