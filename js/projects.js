//Projects JS
function projects() {

	this._projects = new project();
	this._tasks = new task();
	this._notes = new note();
	this._docs = new docs();
};

projects.prototype.clearRest = function(excludeList) {
	var containers = ["project_content", "task_content", "note_content", "new_note_content", "attachment_list", "new_attachment"];

	for(var i=0; i < containers.length; i++) {
		if(!excludeList || !$.isArray(excludeList) || excludeList.indexOf(containers[i]) == -1) {
			$("#"+containers[i]).html("");
		}
	}
}

projects.prototype.resetCounter = function( module ) {
	switch (module) {
		case "docs":
			this.resetNoteCounter();
		break;
		case "notes":
			this.resetDocsCounter();
		break;
		default:
		break;
	}
}
projects.prototype.resetNoteCounter = function() {
	this._notes.noteRequestSent 			= 0;
	this._notes.noteListStartRecord 		= 0;
}

projects.prototype.resetDocsCounter = function() {
	this._docs.docsListStartRecord			= 0;
	this._docs.docsRequestSent				= 0;
}

projects.prototype.toggleAccordiance = function(page, module) {
	$("#project_section_accordion").hide();
	if((page == "project" && module == "viewOne") || page == "task") {
		$("#project_section_accordion").show();
	}
}

var projectObj = new projects();

$().ready(function() {
	var module = session.module != "" ? session.module : "projects";
	if(module) {
		switch (module) {
			case "projects":
				projectObj._projects.viewAll();
			break;
			case "create_project":
				projectObj._projects.createForm();
			break;
			default:
			break;
		}
	}
});

window.onscroll = function() {
	if($("#note_content").text().length) {
		/*
		var content_height = $(document).height();
	    var content_scroll_pos = $(window).scrollTop();
	    var percentage_value = content_scroll_pos * 100 / content_height;
		
		if(percentage_value > 50) {
			var projectId = $("#projectId").val();
			projectObj._notes.viewAll(projectId);
		}
		*/
	} else if($("#attachment_list").text().length) {
		var content_height = $(document).height();
	    var content_scroll_pos = $(window).scrollTop();
	    var percentage_value = content_scroll_pos * 100 / content_height;
		
		if(percentage_value > 20) {
			var projectId = $("#projectId").val();
			projectObj._docs.viewAll( projectId );
		}
	}
}