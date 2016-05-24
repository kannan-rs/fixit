var _home_content = (function () {
	/* Local / Private, Variables / Functions */

	/* Public Functions */
	return {
		showHomePageContent : function() {
            var parent = this;
			$.ajax({
                method: "POST",
                url: "/web_contents/home_content/showAll",
                data: {},
                success: function( response ) {
                    if(!_utils.is_logged_in( response )) { return false; }
                    $("#home_content").html(response);
                    new nicEditor({ onSave : function(content, id, instance) { parent.UpdateNewsValidate(content, id, instance)} }).panelInstance('newEditSection');
                    new nicEditor({ onSave : function(content, id, instance) { parent.UpdateResourceValidate(content, id, instance)} }).panelInstance('resourceEditSection');
                },
                error: function( error ) {
                    error = error;
                }
            })
            .fail(function ( failedObj ) {
                fail_error = failedObj;
            });
		},

        UpdateNewsValidate : function (newNews, id, instance) {
            var oldNews = $("#news_db_value").val();

            /*if( newNews.localeCompare(oldNews) == 0) {
                alert(" News is not modified");
            } else {*/
                this.updateNewsSubmit( newNews );
            //}
        },

        updateNewsSubmit : function ( newNews ) {
            $.ajax({
                method: "POST",
                url: "/web_contents/home_content/addNews",
                data: {
                    news_content : newNews
                },
                success: function( response ) {
                    if(!_utils.is_logged_in( response )) { return false; }
                    response = JSON.parse(response);
                    alert( response.message );
                },
                error: function( error ) {
                    error = error;
                }
            })
            .fail(function ( failedObj ) {
                fail_error = failedObj;
            });
        },

        UpdateResourceValidate : function (newResource, id, instance) {
            var oldResource = $("#resource_db_value").val();

            /*if( newResource.localeCompare(oldResource) == 0) {
                alert(" Resource is not modified");
            } else {*/
                this.updateResourceSubmit( newResource );
            //}
        },

        updateResourceSubmit : function ( newResource ) {
            $.ajax({
                method: "POST",
                url: "/web_contents/home_content/addResource",
                data: {
                    resource_content : newResource
                },
                success: function( response ) {
                    if(!_utils.is_logged_in( response )) { return false; }
                    response = JSON.parse(response);
                    alert( response.message );
                },
                error: function( error ) {
                    error = error;
                }
            })
            .fail(function ( failedObj ) {
                fail_error = failedObj;
            });
        }
	}
})();