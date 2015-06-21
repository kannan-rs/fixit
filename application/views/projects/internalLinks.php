<?php
	for($i = 0; $i < count($internalLinkArr); $i++) {
		if($i > 0) {
			echo "|";
		}
		switch($internalLinkArr[$i]) {
			case "tasks":
				echo "<span><a href=\"javascript:void(0);\" onclick=\"projectObj._tasks.viewAll('".$projectId."')\">Tasks</a></span>";
			break;
			case "project notes":
				echo "<span><a href=\"javascript:void(0);\" onclick=\"projectObj._notes.viewAll('".$projectId."', 'project')\">Project Notes</a></span>";
			break;
			case "task notes":
				echo "<span><a href=\"javascript:void(0);\" onclick=\"projectObj._notes.viewAll('".$taskId."', 'task')\">Task Notes</a></span>";
			break;
			case "documents":
				echo "<span><a href=\"javascript:void(0);\" onclick=\"projectObj._docs.viewAll('".$projectId."')\">Documents</a></span>";
			break;
			case "update project":
				echo "<span><a href=\"javascript:void(0);\" onclick=\"projectObj._projects.editProject('".$projectId."')\">Update Project</a></span>";
			break;
			case "delete project":
				$deleteText = "Delete Project";
				$deleteFn = $deleteText ? "projectObj._projects.deleteRecord(".$projectId.")" : "";
				echo "<span><a href=\"javascript:void(0);\" onclick=\"".$deleteFn."\"> ".$deleteText."</a></span>";
			break;
			case "create task":
				echo "<span><a href=\"javascript:void(0);\" onclick=\"projectObj._tasks.createForm('".$projectId."')\">Create Task</a></span>";
			break;
		}
	}
?>