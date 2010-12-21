
$(document).ready(function(){

// login url
app.gopogo.signin_url = app.gopogo.baseurl + 'User/Account/login/';
// signup url
app.gopogo.signup_url = app.gopogo.baseurl + 'User/Account/signup/';
// profile url
app.gopogo.profile_url = app.gopogo.baseurl + 'User/Account/profile/';
// logout url
app.gopogo.logout_url = app.gopogo.baseurl + 'User/Account/logout/';

// add events on load

/**
 * This will apply toggle effect to bottom of page for advance search
 */
    $(".your-personal-button").click(function(){
            $(".footer-middle").slideToggle("show");         
    });

/**
 * This will apply toggle effect to forgot password functionality of login modal window
 */
    $("#forgotPassword").click(function(){
            $("#errorMsg").text('');
            $("#toggleLogin").slideToggle("hide");
            $("#toggleForgot").slideToggle("show");
    });
    
/**
 * This will apply toggle effect to login functionality of login modal window
 */
     $("#loginPassword").click(function(){
            $("#errorMsg").text('');
            $("#toggleForgot").slideToggle("hide");
            $("#toggleLogin").slideToggle("show");
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
    
    // add create account event
    $(".createAccount").click(function(){                  
        $("#loginBox").css({display:'none'});        
        $().displayModalBox("#signupBox", ".create-ac-head", ".create-ac-centerbg");
    });

    // add signin  event
    $(".login").click(function(){       
        $("#signupBox").css({display:'none'});        
        $().displayModalBox("#loginBox", ".create-ac-head", ".sign-in-centerbg");
        
    });

    // add login event
    $("#loginSubmitBox").click(function(){
        $().doLogin();
    });
    // add signup event
    $("#signupSubmitBox").click(function(){
        $().doSignup();
    });
    // add logout event
    $("#logout").click(function(){
        $().doLogout();
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
                timeout: 1000,
                error: function(resp){
                    $().loginFail(resp);
                },
                success: function(resp){
                    // do something with resp

                    if(resp.status == 1) // show error message
                    {
                        $().loginSuccess(resp);
                    }
                    else
                    {
                        //$().errorMessage(resp.msg);
                        $().loginFail(resp);
                    }

                    //$().loginSuccess(resp);
                },
                complete: function(resp){
                    $().loginWelcome(resp);
                }

            });

        }; // end of do signin

        // login success

        $.fn.loginSuccess = function(resp){
           // console.log('loginSuccess');
          //  console.log(resp);

            // show message
            
            $().errorMessage(resp.msg,'errorMsg');

            //var dspid = 'loginBox';
            $().finish();

            // profile url
            $().redirect(app.gopogo.profile_url);    
            
           
        };

        // login welcome

        $.fn.loginWelcome = function(){
            console.log('loginWelcome');
            console.log(resp);
        };


        // login fail

        $.fn.loginFail = function(resp){
            console.log('loginFail');
            console.log(resp);
            if(resp.status == 0) // show error message
            {
                $().errorMessage(resp.msg,'errorMsg');
            }
        };

        // show error message
        $.fn.errorMessage = function(msg,msgid){
            console.log('errorMessage');

            console.log(typeof msgid);
            if(typeof msgid == undefined || msgid=='' )
                msgid = 'errorMsg';
            console.log(msgid);
            // errorMsg
            $('#'+msgid).html(msg);
        }

        // redirect to some url or reload the page
        $.fn.redirect = function(url){
            console.log('redirect');
            console.log(url);            
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
                timeout: 1000,
                error: function(resp){
                    $().signupFail(resp);
                },
                success: function(resp){
                    // do something with resp

                    if(resp.status == 1) // show error message
                    {
                        $().signupSuccess(resp);
                    }
                    else
                    {
                        //$().errorMessage(resp.msg);
                        $().signupFail(resp);
                    }

                    //$().loginSuccess(resp);
                },
                complete: function(resp){
                    $().signupWelcome(resp);
                }

            });

        }; // end of do signup
        
        // signup success

        $.fn.signupSuccess = function(resp){
            console.log('signupSuccess');
            console.log(resp);

            // show message

            $().errorMessage(resp.msg,'signup_error_msg');

            //var dspid = 'signupBox';
            $().finish();

            // profile url
            $().redirect(app.gopogo.baseurl);
           
        };

        // signup welcome

        $.fn.signupWelcome = function(){
            console.log('signupWelcome');
            console.log(resp);
        };

        // signup fail

        $.fn.signupFail = function(resp){
            console.log('signupFail');
            console.log(resp);
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

             // show popup

             //___addModalWindow('messageBox');

             //$().addModalWindow('messageBox');


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

                        // show popup
                        //___addModalWindow('messageBox');
                        $().addModalWindow('messageBox');
                        //$().errorMessage(resp.msg);
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
            console.log('logoutSuccess');
            console.log(resp);            

            // show message
            //$().errorMessage(resp.msg,'message_error_msg');

            //var dspid = 'logoutBox';
            //$().finish(dspid);

            // profile url
            $().redirect(app.gopogo.baseurl);

        };

        // logout welcome

        $.fn.logoutWelcome = function(){
            console.log('logoutWelcome');
            console.log(resp);
        };

        // logout fail

        $.fn.logoutFail = function(resp){
            console.log('logoutFail');
            console.log(resp);

            if(resp.status == 0) // show error message
            {
                $().errorMessage(resp.msg,'message_error_msg');
            }
        };


// functions to handle the forgot possword


        

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
        }


});