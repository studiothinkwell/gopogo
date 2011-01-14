
$(document).ready(function(){
// login url
app.gopogo.signin_url = app.gopogo.baseurl + 'User/Account/login/';
// signup url
app.gopogo.signup_url = app.gopogo.baseurl + 'User/Account/signup/';
// profile url
app.gopogo.profile_url = app.gopogo.baseurl + 'profile';
// logout url
app.gopogo.logout_url = app.gopogo.baseurl + 'User/Account/logout/';
// forgot url
app.gopogo.forgot_url = app.gopogo.baseurl + 'User/Account/forgotpassword/';

// add events on load

/**
 * This will apply toggle effect to bottom of page for advance search
 */
    $(".your-personal-button").click(function() {
        $(".footer-middle").slideToggle("show");
        $("#personalArrow").toggleClass('arrow-up');
    });


/**
 * This will apply toggle effect to login functionality of login modal window
 */
     $("#loginPassword").click(function(){
            $("#errorMsg").text('');
           // $("#toggleForgot").slideToggle("hide");
           // $("#toggleLogin").slideToggle("show");
            $().setdefaultval();
            $(".clsSignInEmail").focus();
    });

/**
 * This will hide/show top main menus
 */
    $('.submenu-box').hover(
           function(){
                       $(this).addClass('selected');
                    },
           function(){
                       $(this).removeClass('selected');
                     }
       );

    /**
     * This will show success message when profile page loads
     */
    if($("#msgInput").val() == 'fbSignUp') {
        $(".clsSuccessMsg").removeAttr('style');
        var body=document.getElementsByTagName('body')[0];
        body.style.backgroundImage='url(/themes/default/images/bg-left2.png)';
        $(".clsBlankDiv").attr('style','height:208px');
        $(".clsErrorText").text();
    }

    // add create account event
    $(".clsSignUp").click(function(){
        $("#loginBox").css({display:'none'});
        $().displayModalBox("#signupBox", ".create-ac-head", ".create-ac-centerbg");
        $().setdefaultval();
        $(".clsSignUpEmail").focus();
    });

    // add signin  event
    $(".login").click(function(){
        $("#signupBox").css({display:'none'});
        $().displayModalBox("#loginBox", ".create-ac-head", ".sign-in-centerbg");
        $().setdefaultval();
        $(".clsSignInEmail").focus();
    });

    // add login event
    $("#loginSubmitBox").click(function(){
        $().doLogin();
    });
    // add signup event
    $("#signupSubmitBox").click(function(){
        $().doSignup();
        $(".clsSignUpEmail").focus();
    });
    // add logout event
    $("#logout").click(function(){
        $().doLogout();
    });
    // add forgot password event
    $("#forgotSubmitBox").click(function(){
        $().doForgot();
    });


// functions to handle the login / signin

        // do login
        $.fn.doLogin = function(){

             // get serialized form data of login form
             var fdata = $("#loginBoxForm").serialize();

             // make ajax request for login
             $.ajax({
                url: app.gopogo.signin_url,
                type: 'POST',
                dataType: 'json',
                data:fdata,
                timeout: 99999,
                error: function(resp){
                    if(resp.readyState == 4) {
                        $().loginFail(resp);
                    }
                },
                success: function(resp){
                    // do something with resp
                    if(resp.status == 1) // show error message
                    {
                        $().loginSuccess(resp);
                    }
                    else
                    {
                        $().loginFail(resp);
                    }
                },
                complete: function(resp){
                    if(resp.readyState == 4) {
                        $().loginWelcome(resp);
                    }
                }

            });

        }; // end of do signin

        // login success

        $.fn.loginSuccess = function(resp){
            //$().showSuccessTooltip(resp);
            $().debugLog('loginSuccess');
            $().debugLog(resp);

            // show message

            //$().errorMessage(resp.msg,'errorMsg');

            $().finish();

            // redirect to profile
            $().redirect(app.gopogo.profile_url);
        };

        // login welcome

        $.fn.loginWelcome = function() {
            //$().showSuccessTooltip();
            $().debugLog('loginWelcome');
            //$().debugLog(resp);
        };


        // login fail

        $.fn.loginFail = function(resp){
            $().debugLog('loginFail');
            $().debugLog(resp);

            if(resp.status == 0) // show error message
            {
                $().errorMessage(resp.msg,'errorMsg');
            }
        };

        // show error message
        $.fn.errorMessage = function(msg,msgid){
            $().showErrorTooltip(msg,msgid);
            $().debugLog('errorMessage');
            $().debugLog(msg);
            $().debugLog(msgid);

            if(typeof msgid == undefined || msgid=='' )
                msgid = 'error_msg';

            $().debugLog(msgid);

            $('#'+msgid).html(msg);
        }

        // redirect to some url or reload the page
        $.fn.redirect = function(url){
            $().debugLog('redirect');
            $().debugLog(url);
            if(typeof url == undefined || url=='' || url=='/' ) // //location.reload();
                window.location = app.gopogo.baseurl
            else
                window.location = url;
        }

// functions to handle the signup
        // do signup
        $.fn.doSignup = function(){

             // get serialized form data of login form
             var fdata = $("#signupBoxForm").serialize();

             // make ajax request for signup
             $.ajax({
                url: app.gopogo.signup_url,
                type: 'POST',
                dataType: 'json',
                data:fdata,
                timeout: 99999,
                error: function(resp){
                    if(resp.readyState == 4) {
                        $().signupFail(resp);
                    }
                },
                success: function(resp){
                    // do something with resp
                    if(resp.status == 1) // show error message
                    {
                        $().signupSuccess(resp);
                    }
                    else
                    {
                        if(resp.status != 1)
                            $().signupFail(resp);
                    }
                },
                complete: function(resp){
                    if(resp.readyState == 4)
                        $().signupWelcome(resp);
                }
            });
        }; // end of do signup

        // signup success

        $.fn.signupSuccess = function(resp){
            $().debugLog('signupSuccess');
            $().debugLog(resp);
            // show message
            //$().errorMessage(resp.msg,'signup_error_msg');

            $().finish();

            // redirect to home
            $().redirect(app.gopogo.profile_url);

        };

        // signup welcome

        $.fn.signupWelcome = function(){
            $().debugLog('signupWelcome');
            $().debugLog();
        };

        // signup fail

        $.fn.signupFail = function(resp){
            $().debugLog('signupFail');
            $().debugLog(resp);
            if(resp.status == 0) // show error message
            {
                $().errorMessage(resp.msg,'signup_error_msg');
            }
        };

// functions to handle the logout
        // do logout
        $.fn.doLogout = function(){

             // destroy cookie using javascript

             $().delCookie();

             // make ajax request for logout
             $.ajax({
                url: app.gopogo.logout_url,
                dataType: 'json',
                timeout: 1000,
                error: function(resp){
                    $().logoutFail(resp);
                },
                success: function(resp){
                    // do something with resp
                    if(resp.status == 1) // show error message
                    {
                        $().logoutSuccess(resp);
                    }
                    else
                    {
                        // show message box popup
                        $().addModalWindow('messageBox');
                        $().logoutFail(resp);
                    }
                },
                complete: function(resp){
                    $().logoutWelcome(resp);
                }

            });

        }; // end of do logout

        // logout success

        $.fn.logoutSuccess = function(resp){
            $().debugLog('logoutSuccess');
            $().debugLog(resp);
            // redirect to home
            $().redirect(app.gopogo.baseurl);
        };

        // logout welcome

        $.fn.logoutWelcome = function(){
            $().debugLog('logoutWelcome');
            $().debugLog(resp);
        };

        // logout fail

        $.fn.logoutFail = function(resp){
            $().debugLog('logoutFail');
            $().debugLog(resp);
            if(resp.status == 0) // show error message
            {
                $().errorMessage(resp.msg,'message_error_msg');
            }
        };


// functions to handle the forgot possword

        // do forgot
        $.fn.doForgot = function(){

             // get serialized form data of forgot form
             var fdata = $("#forgotBoxForm").serialize();

             // make ajax request for signup
             $.ajax({
                url: app.gopogo.forgot_url,
                type: 'POST',
                dataType: 'json',
                data:fdata,
                timeout: 99999,
                error: function(resp){
                    if(resp.readyState == 4) {
                        $().forgotFail(resp);
                    }
                },
                success: function(resp){
                    // do something with resp
                    if(resp.status == 1) // show error message
                    {
                        $().forgotSuccess(resp);
                    }
                    else
                    {

                        $().forgotFail(resp);
                    }
                },
                complete: function(resp){
                    if(resp.readyState == 4) {
                        $().forgotWelcome(resp);
                    }
                }

            });

        }; // end of do forgot

        // forgot success

        $.fn.forgotSuccess = function(resp){
            $().debugLog('forgotSuccess');
            $().debugLog(resp);
            // show message
            $(".clsMSuccess").text(resp.msg);
            $(".clsSubSuccess").text('.');
            $().showSuccessTooltip();
            //$().errorMessage(resp.msg,'errorMsg');

            $().finish();

            // redirect to home
            //$().redirect(app.gopogo.baseurl);

        };

        // forgot welcome

        $.fn.forgotWelcome = function(resp){
            $().debugLog('forgotWelcome');
            $().debugLog(resp);
        };

        // signup fail

        $.fn.forgotFail = function(resp){
            $().debugLog('forgotFail');
            $().debugLog(resp);
            if(resp.status == 0) // show error message
            {
                $().errorMessage(resp.msg,'errorMsg');
            }
        };

// some utilities

        // delete all cookies
        $.fn.delCookie = function (){
            var new_date = new Date()
            new_date = new_date.toGMTString()
            var thecookie = document.cookie.split(";")
            for (var i = 0;i < thecookie.length;i++)
            {
                document.cookie = thecookie[i] + "; expires ="+ new_date;
            }
        };

        // log message if console is available else not
        // if force then alert if console is not available
        $.fn.debugLog = function (msg,force){
            var debugFlag = true;
            // console is defined
            if(app.gopogo.debug==1 && typeof console !== 'undefined' )
            {
                debugFlag = false;
                console.log(msg);
            }
            // if force debug
            if(app.gopogo.debug==1 && debugFlag && ( force || force==1) )
            {
                alert(msg);
            }
        }

        //function to show tooltip for error messages
        $.fn.showErrorTooltip = function (msg,msgid) {
            $().finish();
            $(".clsErrorMsg").removeAttr('style');
            var body=document.getElementsByTagName('body')[0];
            body.style.backgroundImage='url(/themes/default/images/bg-left1.png)';
            $(".clsBlankDiv").attr('style','height:208px');
            $(".clsErrorText").text(msg);
            if(msgid=="signup_error_msg") {
                $(".toolSignIn").hide();
                $(".toolSignUp").show();
            }
            else {
                $(".toolSignIn").show();
                $(".toolSignUp").hide();
            }
        }

        //function to show tooltip for success messages
        $.fn.showSuccessTooltip = function (msg) {
            $().finish();
            $(".clsSuccessMsg").removeAttr('style');
            var body=document.getElementsByTagName('body')[0];
            body.style.backgroundImage='url(/themes/default/images/bg-left2.png)';
            $(".clsBlankDiv").attr('style','height:208px');
            $(".clsErrorText").text(msg);
        }

        //function to hide tooltip for error messages
        $(".clsCloseError").click(function(){
            $().closeErrorTooltip();
        });

        //function to hide tooltip for error messages
        $(".clsCloseSuccess").click(function(){
            $(".clsSuccessMsg").attr('style','display:none');
            var body=document.getElementsByTagName('body')[0];
            body.style.backgroundImage='url(/themes/default/images/bg-left.png)';
            $(".clsBlankDiv").attr('style','height:150px');
            $(".clsErrorText").text('');
        });
        $(".toolSignIn").click(function(){
            $().closeErrorTooltip();
        })
        $(".toolSignUp").click(function(){
            $().closeErrorTooltip();
        })
        //function to close tooltip for error messages
        $.fn.closeErrorTooltip = function () {
            $(".clsErrorMsg").attr('style','display:none');
            var body=document.getElementsByTagName('body')[0];
            body.style.backgroundImage='url(/themes/default/images/bg-left.png)';
            $(".clsBlankDiv").attr('style','height:150px');
            $(".clsErrorText").text('');
        };

    $('.submenu-box .submenu-div').hover
          (
                   function()
                                {
                                        var id = $(this).attr("id");
                                        $(this).addClass(id+'-selected');
                            },
                   function()
                                {
                                        var id = $(this).attr("id");
                                        $(this).removeClass(id+'-selected');
                                }
           );


// functions to explode string works same as php explode function
$.fn.explode = function (delimiter, string, limit) {
     var emptyArray = { 0: '' };

    // third argument is not required
    if ( arguments.length < 2 ||
        typeof arguments[0] == 'undefined' ||        typeof arguments[1] == 'undefined' ) {
        return null;
    }

    if ( delimiter === '' ||        delimiter === false ||
        delimiter === null ) {
        return false;
    }
     if ( typeof delimiter == 'function' ||
        typeof delimiter == 'object' ||
        typeof string == 'function' ||
        typeof string == 'object' ) {
        return emptyArray;    }

    if ( delimiter === true ) {
        delimiter = '1';
    }
    if (!limit) {
        return string.toString().split(delimiter.toString());
    } else {
        // support for limit argument        var splitted = string.toString().split(delimiter.toString());
        var partA = splitted.splice(0, limit - 1);
        var partB = splitted.join(delimiter.toString());
        partA.push(partB);
        return partA;    }
}

}); 