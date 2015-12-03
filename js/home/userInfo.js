var _userInfo = (function () {
    'use strict';
    return {
        getUserData: function() {
            _users.viewOne();
        },
        editPage: function() {
            _users.editUser();
        },
        changePassForm: function() {
                $.ajax({
                method: "POST",
                url: "/home/home/changePassForm",
                data: {},
                success: function( response ) {
                    $("#index_content").html(response);
                },
                error: function( error ) {
                    error = error;
                }
            })
            .fail(function ( failedObj ) {
                fail_error = failedObj;
            });
        },
        updatePasswordValidate: function() {
            $("#update_password_form").validate({
                rules: {
                    confirmPassword: {
                        required: true,
                        equalTo: "#password"
                    }
                }
            });

            var validator = $( "#update_password_form" ).validate();

            if(validator.form()) {
                this.updatePasswordSubmit();
            }
        },
        updatePasswordSubmit: function() {
            var sno                 = $("#user_details_sno").val();
            var password             = $("#password").val();
            var passwordHint         = $("#passwordHint").val();
            var email                 = $("#email").val();
            
            $.ajax({
                method: "POST",
                url: "/home/home/updatePassword",
                data: { 
                    sno: sno,
                    password: password, 
                    passwordHint: passwordHint,
                    email: email
                },
                success: function( response ) {
                    response = $.parseJSON(response);
                    if(response.status.toLowerCase() == "success") {
                        alert(response.message);
                        $("#password").val("");
                        $("#confirmPassword").val("");
                        $("#passwordHint").val("");
                    } else if(response.status.toLowerCase() == "error") {
                        alert(response.message);
                    }
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