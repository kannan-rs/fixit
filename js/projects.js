//Projects JS
function projects() {

	this._projects 		= new project();
	this._tasks 		= new task();
	this._notes 		= new note();
	this._docs 			= new docs();

	// Contractors
	this._contractors	= new contractors();
};

projects.prototype.clearRest = function(excludeList) {
	var containers = ["project_content", "task_content", "note_content", "attachment_content", "popupForAll", "contractor_content"];

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
	if(page == "project" && module == "viewOne") {
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
			case "contractors":
				projectObj._contractors.viewAll();
			break;
			case "create_contractor":
				projectObj._contractors.createForm();
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
/*		var content_height = $(document).height();
	    var content_scroll_pos = $(window).scrollTop();
	    var percentage_value = content_scroll_pos * 100 / content_height;
		
		if(percentage_value > 20) {
			var projectId = $("#projectId").val();
			projectObj._docs.viewAll( projectId );
		}
*/	}
}

$(document).on("click", function(e) {
	if(e && e.target && 
		(e.target.id == "contractorSearchResult" || 
			(e.target.parentElement && e.target.parentElement.id == "contractorSearchResult") || 
			(e.target.parentElement.parentElement && e.target.parentElement.parentElement.id == "contractorSearchResult")
		)
	) {
		return;
	}
	if($(".contractor-search-result").length) { 
		$(".contractor-search-result").hide();
	}
});