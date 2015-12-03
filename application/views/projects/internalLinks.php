<?php
	for($i = 0; $i < count($internalLinkArr); $i++) {
		if($i > 0) {
			echo "|";
		}
		switch($internalLinkArr[$i]) {
			case "tasks":
				echo "<span><a href=\"javascript:void(0);\" onclick=\"_tasks.viewAll('".$projectId."')\">".$this->lang->line_arr('projects->buttons_links->tasks')."</a></span>";
			break;
			case "project notes":
				echo "<span><a href=\"javascript:void(0);\" onclick=\"_notes.viewAll('".$projectId."', 'project')\">".$this->lang->line_arr('projects->buttons_links->project_notes')."</a></span>";
			break;
			case "task notes":
				echo "<span><a href=\"javascript:void(0);\" onclick=\"_notes.viewAll('".$taskId."', 'task')\">".$this->lang->line_arr('projects->buttons_links->task_notes')."</a></span>";
			break;
			case "documents":
				echo "<span><a href=\"javascript:void(0);\" onclick=\"_docs.viewAll('".$projectId."')\">".$this->lang->line_arr('projects->buttons_links->documents')."</a></span>";
			break;
			case "update project":
				echo "<span><a href=\"javascript:void(0);\" onclick=\"_projects.editProject('".$projectId."')\">".$this->lang->line_arr('projects->buttons_links->update_project')."</a></span>";
			break;
			case "delete project":
				$deleteText = $this->lang->line_arr('projects->buttons_links->delete_project');
				$deleteFn = $deleteText ? "_projects.deleteRecord(".$projectId.")" : "";
				echo "<span><a href=\"javascript:void(0);\" onclick=\"".$deleteFn."\"> ".$deleteText."</a></span>";
			break;
			case "create task":
				echo "<span><a href=\"javascript:void(0);\" onclick=\"_tasks.createForm('".$projectId."')\">".$this->lang->line_arr('projects->buttons_links->create_task')."</a></span>";
			break;
		}
	}
?>