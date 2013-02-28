<?php

/*
 * Lists existing tasks. 
 * 
 * Category priorities are stored in a separate area. 
 */
class SpecialTasks extends IncludableSpecialPage {
	
	public function __construct() {
		parent::__construct( 'Tasks', 'wikitask/view' );
	}
	
	public function execute( $par ) {
		$this->setHeaders();
		
		if ( $this->getUser()->isBlocked() ) {
			$block = $this->getUser()->getBlock();
			throw new UserBlockedError( $block );
		}
		
		$this->getOutput()->addModules("ext.wikitask");
		$this->getOutput()->setPageTitle("Tasks");
		
		$this->displayTasks();
	}
	
	/*
	 * This contains some ugly hackish code for displaying tasks using tables.
	 */
	private function displayTasks($par) {
		$out = $this->getOutput();
		
		//$tasks = TasksTable::singleton();
		//$tasktypes = TaskTypesTable::singleton();
		
		// Sort - $rank = (($categoryPriorities[$task.category] / $maxPriority) + ($daysUntilDue / $maxDaysUntilDue)) / 2;
		
		$tasks = array();
		
		$out->addHTML( "<table class='wikitable'><tbody class='wikitask-tasks'>" );
		foreach ( $tasks as $task ) {
			$id = $task->id;
			$out->addHTML( "<tr class='task' data-task-id='$id'>" );
			$title = $task->title; // XXX wikitext to html
			$daysTillDue = $task->daysTillDue;
			$daysTillDue_suffix = ($task->daysTillDue > 1) ? "days" : "day";
			$type = $task->type; // XXX get title from id
			$cat = $task->cat; // XXX get title from id
			$out->addHTML( "<th><div id='cat'>$cat</div> <div id='type'>$type</div></th><td id='title'>$title</td><th id='daysTillDue'>$daysTillDue $daysTillDue_suffix</th></tr>" );
		}
		
		if ( $this->including() && $out->getUser()->isAllowed( 'wikitask/manage' ) ) {
			$out->addHTML("<tr class='task' data-task-id='42'>
				<th>
					<div id='cat'>cat</div> 
					<div id='type'>type</div>
				</th>
				<td id='title'>title</td>
				<th id='daysTillDue'>daysTillDue daysTillDue_suffix</th>
			</tr>");
			$out->addHTML("<tr class='task' data-task-id='42'><th><div id='cat'>cat</div> <div id='type'>type</div></th><td id='title'>title</td><th id='daysTillDue'>daysTillDue daysTillDue_suffix</th></tr>"); // XXX TEST CODE REMOVE
			
			$out->addHTML("<tr id='addtask'><td colspan='4'><h4>Add a task...</h4></td></tr>");
			$out->addHTML(
				"<tr id='newtask'>
					<th>
						<span id='cat'><input type='text' placeholder='Category' autocomplete='off'></span><br>
						<span id='type'><input type='text' placeholder='Type' autocomplete='off'></span>
					</th>
					<td><textarea type='text' placeholder='Title' id='title'></textarea></td>
					<th>
						<input type='number' id='daysTillDue' placeholder=1 min=1></input>
						<div id='controls'><button id='submit'>Submit</button><button id='cancel'>Reset</button></div>
					</th>
				</tr>");
			
		}
		$out->addHTML( "</tbody></table>" );
	}
		
}

?>
