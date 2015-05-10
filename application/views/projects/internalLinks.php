<?php
	for($i = 0; $i < count($internalLinkArr); $i++) {
		switch($internalLinkArr[$i]) {
			case "tasks":
				echo "<span><a href=\"javascript:void(0);\" onclick=\"projectObj._tasks.viewAll('".$projectId."')\">Tasks</a></span>";
			break;
			case "notes":
				echo "<span><a href=\"javascript:void(0);\" onclick=\"projectObj._notes.viewAll('".$projectId."')\">Notes</a></span>";
			break;
			case "documents":
				echo "<span><a href=\"javascript:void(0);\" onclick=\"projectObj._docs.viewAll('".$projectId."')\">Documents</a></span>";
			break;
			case "update project":
				echo "<span><a href=\"javascript:void(0);\" onclick=\"projectObj._projects.editProject('".$projectId."')\">Update Project</a></span>";
			break;
			case "delete project":
				$deleteText = "Delete Project";
				$deleteFn = $deleteText ? "projectObj._projects.delete(".$projectId.")" : "";
				echo "<span><a href=\"javascript:void(0);\" onclick=\"".$deleteFn."\"> ".$deleteText."</a></span>";
			break;
			case "create task":
				echo "<span><a href=\"javascript:void(0);\" onclick=\"projectObj._tasks.createForm('".$projectId."')\">Create Task</a></span>";
			break;
		}
	}
?>