// declair global variables for profile
var profile = new Array(2);
$(document).ready(function() {
//call ajax to generate message list on load
    //loadMsgList();
// call ajax for update profile
    $.fn.updateProfile = function (area) {
        switch(area) {
         case 'myinfo':
            var fdata = {'userName':$(".inpclsDivUN").val(),'userDesc':$(".inpclsDivUD").val()};
            if($().validateUsername($(".inpclsDivUN").val())){
                $.ajax({
                  url: app.gopogo.profilemyinfo_url,
                  type: 'POST',
                  dataType: 'json',
                  data:fdata,
                  timeout: 99999,
                  success: function(data){
                      $(".save_info").ajaxLoader();
                      //code to manage html after save process complete
                          var inpArr = new Array(2);
                          inpArr[0] = new Array("inpclsDivUN","clsDivUN");
                          inpArr[1] = new Array("inpclsDivUD","clsDivUD");
                          $().inplaceEditor(inpArr,"save");
                      if(data == 0) {
                          $(".clsDivUN").text(profile[0]);
                          $(".clsDivUD").text(profile[1]);
                      }
                   },
                   complete: function() {
                       $(".accountSectionBottomButtonBg").ajaxLoaderRemove();
                       $(".save_info").hide();
                       $(".clsEUPro").removeClass('save');
                       $(".clsEUPro").show();
                   }
                });
            }
         break;
         //for profile action
         //  case 'myAccEmail':
         case 'myAccEmail':
              var fdata = {'email':$('.inpclsUpdateEmail').val()};
              $.ajax({
              url: app.gopogo.accountemailupdate_url,
              type: 'POST',
              dataType: 'json',
              data:fdata,
              timeout: 99999,
              success: function(resp){
                  // do something with resp
                  if(resp.status == 1) { // show error message
                       $().updateSuccess(resp);
                       $(".clsSubSuccess").text('Please login to access gopogo');
                       var inpArr = new Array(1);
                       inpArr[0] = new Array("inpclsUpdateEmail","clsUpdateEmail");
                       //inpArr[1] = new Array("clsUpdateConfirmNewPass","clsUpdateConfirmNewPass","text");
                       inpArr[2] = new Array("clsUpdateAccUserEmail","clsUpdateAccUserEmailInfo","href");
                       $().inplaceEditor(inpArr,"save");
                       $().debugLog($("b", ".clsUpdateAccUserEmail").text());
                       var replacement = $("b", ".clsUpdateAccUserEmail").text() == "Save" ? "edit" : "Save";
                       $().debugLog(replacement);
                       $("b", ".clsUpdateAccUserEmail").fadeOut(function(){$("b",".clsUpdateAccUserEmail").text(replacement).fadeIn()});
                       $(".clsUpdateAccUserEmail").removeClass("save");
                  }
                  else {
                        $(".clsSubError").text('.');
                        $().updateError(resp);
                  }
               }
            });
          break;
         //for account password update action
          case 'myAccPass':
              var fdata = {'current_pass':$('.clsUpdateOldPass').val(),'new_pass':$('.clsUpdateNewPass').val(),'retype_pass':$('.clsUpdateConfirmPass').val()};

              $.ajax({
              url: app.gopogo.accountpassupdate_url,
              type: 'POST',
              dataType: 'json',
              data:fdata,
              timeout: 99999,
              success: function(resp){
                  // do something with resp
                  if(resp.status == 1) { // show error message
                        $(".clsSubSuccess").text('.');

                        $().updateSuccess(resp);
                        // $().inplaceEditor(inpArr,"save");
                        $().debugLog($("b", ".clsUpdateAccUserPass").text());
                        var replacement = $("b", ".clsUpdateAccUserPass").text() == "Save" ? "edit" : "Save";
                        //var replacement = "edit";
                        $().debugLog(replacement);
                        $("b", ".clsUpdateAccUserPass").fadeOut(function(){$("b",".clsUpdateAccUserPass").text(replacement).fadeIn()});
                        $(".clsUpdateAccUserPass").removeClass('save');

                        $(".clsUpdatePass").show();
                        $(".clsUpdatePassTxt").show();
                        //$(".clsUpdatePass").text('******');
                        //$(".clsUpdatePass").hide();
                        $(".clsResetPass").hide();

                  }
                  else {
                        $(".clsSubError").text('.');
                        $().updateError(resp);
                   }
               }
         });
          break;
         //for account username update action
         case 'myAccUserName':
              var fdata = {'username':$('.inpclsUpdateUserName').val()};
              $.ajax({
              url: app.gopogo.accountusernameupdate_url,
              type: 'POST',
              dataType: 'json',
              data:fdata,
              timeout: 99999,
              success: function(resp){
                  // do something with resp
                  if(resp.status == 1) {
                       $().updateSuccess(resp);
                       $(".clsSubSuccess").text('.');
                       var inpArr = new Array(1);
                       inpArr[0] = new Array("inpclsUpdateUserName","clsUpdateUserName");

                       $().inplaceEditor(inpArr,"save");

                       $().debugLog($("b", ".clsUpdateAccUserName").text());

                       var replacement = $("b", ".clsUpdateAccUserName").text() == "Save" ? "edit" : "Save";
                       $().debugLog(replacement);
                       $("b", ".clsUpdateAccUserName").fadeOut(function(){$("b",".clsUpdateAccUserName").text(replacement).fadeIn()});
                       $(".clsUpdateAccUserName").removeClass("save");


                  }
                  else {
                        $(".clsSubError").text('.');
                        $().updateError(resp);
                   }
               }
            });
            break;
            default :

        }
    }

    // inplace editor

    $.fn.inplaceEditor = function(inpArr,act) {

         switch (act) {
           case "edit" :
                var arrLen = inpArr.length;
                //alert(arrLength);
                for(i=0;i<arrLen-1;i++) {
                    var txtEditData;
                    if (inpArr[i][2] == "text") {
                        txtEditData = $("<input type='"+inpArr[i][2]+"' name='' id='' maxlength='"+inpArr[i][3]+"' class='inp"+inpArr[i][0]+"' value='"+$("."+inpArr[i][0]).text()+"'/>");
                    }
                    if (inpArr[i][2] == 'textarea') {
                        txtEditData = $("<textarea name='' id='' style='width:210px' class='inp"+inpArr[i][0]+"'>"+$("."+inpArr[i][0]).text()+"</textarea>");
                    }
                    $("."+inpArr[i][1]).html(txtEditData);
                }
                switch(inpArr[arrLen-1][2]) {
                    case "button":
                        $("."+inpArr[arrLen-1][1]).html("<input type='button' class='inplace"+inpArr[arrLen-1][0]+"save' name='' value='Save'/>");
                    case "href":
                        $("."+inpArr[arrLen-1][0]).addClass("save");
                }
                break;
            case "save" :
                arrLen = inpArr.length;
                for(i=0;i<arrLen;i++) {
                    $("."+inpArr[i][1]).html($("."+inpArr[i][0]).val());
                }
                break;
        }
   }

// update handler


    // update email event handler
    $(".clsUpdateAccUserEmail").click(function(){
        if($(".clsUpdateAccUserEmail").hasClass('save') ) {
            $().closeSuccessTooltip();
            $().closeErrorTooltip();
            $().updateProfile("myAccEmail");

           // change email text box to span /div with text

        } else {
           var replacement = $("b", this).text() == "edit" ? "Save" : "edit";
           //var replacement = "Save";
           $("b", ".clsUpdateAccUserEmail").fadeOut(function(){$("b",".clsUpdateAccUserEmail").text(replacement).fadeIn()});
           var inpArr = new Array(2);
           inpArr[0] = new Array("clsUpdateEmail","clsUpdateEmail","text");
           inpArr[1] = new Array("clsUpdateAccUserEmail","clsUpdateAccUserEmailInfo","href");
           $().inplaceEditor(inpArr,"edit");
        }
    });

    //update username event handler
    $(".clsUpdateAccUserName").click(function(){
        $().debugLog(1);
        if($(".clsUpdateAccUserName").hasClass('save') ) {
            $().debugLog(2);
            $().closeSuccessTooltip();
            $().closeErrorTooltip();

            var username = $('.inpclsUpdateUserName').val();
            if($().validateUsername(username)){
                $().updateProfile("myAccUserName");
            } else {

                var msg1 = 'Username not valid!';
                var msg2 = 'Username must start with alphabet character and allowed characters a-zA-Z0-9 and underscore.';
                $().showErrorTooltip(msg1);

                // top-Msg-window-s
                $('.top-Msg-window-s').text(msg2);
            }

            $().debugLog(3);
        } else {
            $().debugLog(4);
            var replacement = $("b", this).text() == "edit" ? "Save" : "edit";
            //var replacement = "Save";
            $("b", ".clsUpdateAccUserName").fadeOut(function(){$("b",".clsUpdateAccUserName").text(replacement).fadeIn()});
            var inpArr = new Array(2);
            inpArr[0] = new Array("clsUpdateUserName","clsUpdateUserName","text");
            inpArr[1] = new Array("clsUpdateAccUserName","clsUpdateAccUserNameInfo","href");
            $().inplaceEditor(inpArr,"edit");
            $().debugLog(5);
        }
    });



    // update password event handler
    $(".clsUpdateAccUserPass").click(function(){
        $().debugLog(1);
        $().debugLog($(".clsUpdateAccUserPass").hasClass('save'));
        if($(".clsUpdateAccUserPass").hasClass('save')) {
            $().debugLog(2);
            $().closeSuccessTooltip();
            $().closeErrorTooltip();

            $().updateProfile("myAccPass");
             $().debugLog(3);
        }
        else {
             $().debugLog(4);
             $(".clsUpdateAccUserPass").addClass('save');
            //$(".clsUpdatePass").hide();
            //$(".clsUpdatePassTxt").hide();
            $(".clsResetPass").show();
            var replacement = $("b", ".clsUpdateAccUserPass").text() == "edit" ? "Save" : "edit";
            //var replacement = "Save";
            $("b", ".clsUpdateAccUserPass").fadeOut(function(){$("b",".clsUpdateAccUserPass").text(replacement).fadeIn()});
            //$(".clsUpdatePass").html("<input type='text'/>");
            //var inpArr = new Array(3);
            $(".clsUpdatePass").hide();
            $().debugLog(5);
        }
    });

// update partner info - facebook event handler
 $(".clsUpdatePartnerInfo").click(function(){
     if($(".clsUpdatePartnerInfo").hasClass('save') ) {
           $().updateProfile("myinfo");
       } else {
       var inpArr = new Array(4);
           inpArr[0] = new Array("clsUpdateFullName","clsUpdateFullName","text");
           inpArr[1] = new Array("clsUpdateUserEmail","clsUpdateUserEmail","text");
           inpArr[2] = new Array("clsUpdateTwitterUserName","clsUpdateTwitterUserName","text");
           inpArr[3] = new Array("clsUpdateFbUserName","clsUpdateFbUserName","text");
           inpArr[4] = new Array("clsUpdatePartnerInfo","clsUpdateUserPartneInfo","href");
       $().inplaceEditor(inpArr,"edit");
       }
    });


$(".inplaceclsEUProsave").click(function() {
        $().updateProfile('myinfo');
   })



$(".clsEUPro").click(function() {
       if($(".clsEUPro").hasClass('save') ) {
           if (profile[0] != $(".inpclsDivUN").val() || profile[1] != $(".inpclsDivUD").val()) {
                $().updateProfile("myinfo");
           } else {
               // suggest to change value
               var inpArr = new Array(2);
               inpArr[0] = new Array("inpclsDivUN","clsDivUN");
               inpArr[1] = new Array("inpclsDivUD","clsDivUD");
               $().inplaceEditor(inpArr,"save");
               $(".clsEUPro").removeClass('save');
           }
       } else {
           $(".clsEUPro").hide();
           $(".save_info").show();
           // assign value to global variable of profile
           profile[0] = $(".clsDivUN").text();
           profile[1] = $(".clsDivUD").text();
           inpArr = new Array(2);
           inpArr[0] = new Array("clsDivUN","clsDivUN","text","20");
           inpArr[1] = new Array("clsDivUD","clsDivUD","textarea");
           inpArr[2] = new Array("clsEUPro","clsProAction","href");
           $().inplaceEditor(inpArr,"edit");
       }
    });



     $.fn.updateError = function(resp){
       $().showErrorTooltip(resp.msg);
   }

   $.fn.updateSuccess = function(resp){
       $().showSuccessTooltip(resp.msg);
   }

    // validate username : return true / false

    $.fn.validateUsername = function(username){
        /***
         *  if you want to allow underscore only as concatenation character and
         *  want to force that the username must start with a alphabet character:
         */
        var regex = /^[A-Za-z][A-Za-z0-9]*(?:_[A-Za-z0-9]+)*$/;
        var uvlFlag = regex.test(username);
        $().debugLog(uvlFlag);
        return uvlFlag;
    }

    // validate email : return true / false

    $.fn.validateEmail = function(email){
        var regex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
        var evlFlag = regex.test(email);
        $().debugLog(evlFlag);
        return evlFlag;

    }


    // add signin box click handlar

    $(".signinbox").click(function(event) {

        $().debugLog('signinbox');

        //var clsClassName = event.target.parentNode.className;

        var clsClassName = $(event.target.parentNode).attr('class');

        var regexSave = /save/;

        // || $.isEmptyObject($(event.target.parentNode).attr('class'))
        if( clsClassName == '' || clsClassName == null || regexSave.test(clsClassName) ){

            $().clearErrorMessage();
            //var actionClass = event.target.parentNode.parentNode.className;
            var actionClass = $(event.target.parentNode.parentNode).attr('class');

            // update email action
            // save
            $().debugLog('save');
            var regexUEmail = /email/;
            var regexUPassword = /password/;
            var regexUUsername = /username/;

            if(regexUEmail.test(actionClass) || regexUEmail.test(clsClassName) ){

                // save email
                var email = $('.inpclsUpdateEmail').val();
                $().debugLog('email - ' + email);

                // validate email
                if($().validateEmail(email)){

                    $().clearErrorMessage();
                    var fdata = {'email':email};
                    var settings = {'data':fdata,'url':app.gopogo.accountemailupdate_url};

                    $('.save-email').ajaxLoader();

                    $().updateSettings("email",settings);

                } else {

                    var msg1 = 'Email not valid!';
                    var msg2 = 'Enter valid email, like xyz@pqr.com';
                    $().showErrorTooltip(msg1);

                    // top-Msg-window-s
                    $('.top-Msg-window-s').text(msg2);
                }

            }else if(regexUPassword.test(actionClass) || regexUPassword.test(clsClassName) ){

                // save password
                var oldPassword = $('.inpclsUpdateOldPassword').val();
                var newPassword = $('.inpclsUpdateNewPassword').val();
                var confirmNewPassword = $('.inpclsUpdateConfirmNewPassword').val();

                var vlflag = true;

                // validate password
                var msg1 = '';

                if(confirmNewPassword!=newPassword){
                    msg1 = 'New Password and Re-type New Password does not match!';
                    vlflag = false;
                }

                if(confirmNewPassword==''){
                    msg1 = 'Re-type New Password must not be blank!';
                    vlflag = false;
                }else if(confirmNewPassword.length<6 || oldPassword.length>16){
                    msg1 = 'Re-type New Password length must be between 6 and 16!';
                    vlflag = false;
                }

                if(newPassword==''){
                    msg1 = 'New Password must not be blank!';
                    vlflag = false;
                }else if(newPassword.length<6 || oldPassword.length>16){
                    msg1 = 'New Password length must be between 6 and 16!';
                    vlflag = false;
                }

                if(oldPassword==''){
                    msg1 = 'Old Password must not be blank!';
                    vlflag = false;
                }else if(oldPassword.length<6 || oldPassword.length>16){
                    msg1 = 'Old Password length must be between 6 and 16!';
                    vlflag = false;
                }

                if(vlflag){
                    $().clearErrorMessage();

                    var fdata = {'current_pass':oldPassword,'new_pass':newPassword,'retype_pass':confirmNewPassword};
                    $().debugLog('password data - ' + fdata);
                    var settings = {'data':fdata,'url':app.gopogo.accountpassupdate_url};

                    $('.save-password').ajaxLoader();

                    $().updateSettings("password",settings);

                }else{
                    $().showErrorTooltip(msg1);
                    $(".clsSubError").text('.');
                    $('.top-Msg-window-s').text('.');
                }

            }else if(regexUUsername.test(actionClass) || regexUUsername.test(clsClassName) ){
                // save username
                var username = $('.inpclsUpdateUsername').val();
                $().debugLog('username - ' + username);

                // validate username
                if($().validateUsername(username)){

                    $().clearErrorMessage();
                    var fdata = {'username':username};
                    var settings = {'data':fdata,'url':app.gopogo.accountusernameupdate_url};

                    $('.save-username').ajaxLoader();

                    $().updateSettings("username",settings);

                } else {

                    var msg1 = 'Username not valid!';
                    var msg2 = 'Username must start with alphabet character and allowed characters a-zA-Z0-9 and underscore.';
                    $().showErrorTooltip(msg1);

                    // top-Msg-window-s
                    $('.top-Msg-window-s').text(msg2);
                }
            }

        }else if(typeof $(event.target.parentNode).attr('class') != undefined ){

            $().clearErrorMessage();
            //var actionClass = event.target.parentNode.className;
            var actionClass = $(event.target.parentNode).attr('class');

            var regexUpdate = /update/;
            var regexCancel = /cancel/;
            var regexSave = /save/;

            // update action
            if(regexUpdate.test(actionClass)){

                // update
                $().debugLog('update');

                var regexUEmail = /email/;
                var regexUPassword = /password/;
                var regexUUsername = /username/;

                if(regexUEmail.test(actionClass)){

                    $().resetClasses();
                    // email emailbox
                    $('.emailbox').removeClass('d1').addClass("d2");
                    $('.dsplfld-email').removeClass('d2').addClass("d1");

                }else if(regexUPassword.test(actionClass)){
                    $().resetClasses();
                    // password passwordbox
                    $('.passwordbox').removeClass('d1').addClass("d2");
                    $('.dsplfld-password').removeClass('d2').addClass("d1");

                }else if(regexUUsername.test(actionClass)){
                    $().resetClasses();
                    // username usernamebox
                    $('.usernamebox').removeClass('d1').addClass("d2");
                    $('.dsplfld-username').removeClass('d2').addClass("d1");
                }
            }else if(regexCancel.test(actionClass)){
                // cancel
                $().debugLog('cancel');
                $().resetClasses();

            }else if(regexSave.test(actionClass)){
                // save
            }

        } else {

        }
    });


    // reset classes
    $.fn.resetClasses = function(){

        $().debugLog('resetClasses');

        $('.emailbox').removeClass('d2').addClass("d1");
        $('.passwordbox').removeClass('d2').addClass("d1");
        $('.usernamebox').removeClass('d2').addClass("d1");


        $('.dsplfld-email').removeClass('d1').addClass("d2");
        $('.dsplfld-password').removeClass('d1').addClass("d2");
        $('.dsplfld-username').removeClass('d1').addClass("d2");

        // remove loader
        $().removeLoader();
    }

    // clear error message
    $.fn.clearErrorMessage = function(){

        $().closeSuccessTooltip();
        $().closeErrorTooltip();
        // clsSubSuccess
        //$('.top-Msg-window-s').text(msg2);
        $('.top-Msg-window-s').text('.');
    }

    // update user settings

    $.fn.updateSettings = function ( actionName, settings ){

        $.ajax({
          url: settings.url,
          type: 'POST',
          dataType: 'json',
          data:settings.data,
          timeout: 99999,
          error: function(resp){
                $().removeLoader();
                $().debugLog('error');
                $().debugLog(resp);
                if(resp.readyState == 4) {

                }
          },
          success: function(resp){

              $().debugLog('success');
              $().debugLog(resp);
              // do something with resp
              if(resp.status == 1) { // show error message
                    $().updateSuccess(resp);
                    $().successUpdate(actionName,settings.data);
              }
              else {
                    $().removeLoader();
                    $(".clsSubError").text('.');
                    $().updateError(resp);
              }
           },
           complete: function(resp) {
               $().debugLog('complete');
               $().debugLog(resp);
               if(resp.readyState == 4) {

               }
           }
        });

    }

    // profile success update
    $.fn.successUpdate = function ( actionName, data ){
        $().debugLog('successUpdate');
        $().resetClasses();

        // set values to display
        switch(actionName) {
            case 'email':
                $(".clsSubSuccess").text('Confirm again this new email : ' + data.email);
                $().debugLog(data.email);
                $('.dsplUpdateEmail').html(data.email);
                break;
            case 'password':
                $().debugLog(data.new_pass);
                //$('.dsplUpdatePassword').html(data.new_pass);
                break;
            case 'username':
                $().debugLog(data.username);
                $('.dsplUpdateUsername').html(data.username);
                break;

            case 'twitter':
                $().debugLog(data.partner);
                $('.dsplUpdateTwitter').html('');
                $('.twitterBox1').removeClass('d2').addClass("d1");
                $('.twitterBox2').removeClass('d1').addClass("d2");
                break;
            case 'facebook':
                $().debugLog(data.partner);
                $('.dsplUpdateFacebook').html('');

                $('.facebookBox1').removeClass('d2').addClass("d1");
                $('.facebookBox2').removeClass('d1').addClass("d2");

                break;
            default :
        }

    }

    // remove loader

    $.fn.removeLoader = function ( ){
        $('.save-email').ajaxLoaderRemove();
        $('.save-password').ajaxLoaderRemove();
        $('.save-username').ajaxLoaderRemove();
        $('.remove-twitter').ajaxLoaderRemove();
        $('.remove-facebook').ajaxLoaderRemove();
    }

    // twitter oauth popup

    $.fn.oauthpopup = function (options){

        $().debugLog('twitter-connect : oauthpopup');

        options.windowName = options.windowName ||  'ConnectWithOAuth'; // should not include space for IE
        options.windowOptions = options.windowOptions || 'location=0,status=0,width=785,height=350';
        options.callback = options.callback || function(){
            $().debugLog('twitter-connect : options.callback');
            window.location.reload();
        };
        var that = this;

        that._oauthWindow = window.open(options.path, options.windowName, options.windowOptions);

        that._oauthInterval = window.setInterval(function(){
            $().debugLog('twitter-connect : _oauthInterval');
            if (that._oauthWindow.closed) {
                window.clearInterval(that._oauthInterval);
                options.callback();
            }
        }, 1000);

        // centering popup
        $().debugLog(that._oauthWindow);

        $().centerPopup(that._oauthWindow);
    }

    // centering popup
    $.fn.centerPopup = function (newWindow){
        //request data for centering
        var windowWidth = document.documentElement.clientWidth;
        $().debugLog('windowWidth : ' + windowWidth);
        var windowHeight = document.documentElement.clientHeight;
        $().debugLog('windowHeight : ' + windowHeight);
        var popupHeight = $(newWindow).height();
        $().debugLog('popupHeight : ' + popupHeight);
        var popupWidth = $(newWindow).width();
        $().debugLog('popupWidth : ' + popupWidth);

        var wleft = windowWidth/2-popupWidth/2;
        var wtop = windowHeight/2-popupHeight/2;
        //centering
        $(newWindow).css({
            "position": "absolute",
            "top": windowHeight/2-popupHeight/2,
            "left": windowWidth/2-popupWidth/2
        });

        newWindow.moveTo(wleft, wtop);
        newWindow.focus();
    }
    // twitter connect event handlar


    $('#twitter-connect').click(function(){
        $().debugLog('twitter-connect');
        $().oauthpopup({
            path: app.gopogo.baseurl + '/Twitter/index/redirect',
            callback: function(){

                $().debugLog('twitter-connect : callback');

            }
        });
    });

    // remove twitter

    $(".remove-twitter").click(function(event) {
        $().debugLog('remove-twitter');
        $().debugLog(event);

        $().clearErrorMessage();
        var fdata = {'partner':'twitter'};
        var settings = {'data':fdata,'url':app.gopogo.accountremovepartner_url};

        $('.remove-twitter').ajaxLoader();

        $().updateSettings("twitter",settings);

    });

    // remove facebook

    $(".remove-facebook").click(function(event) {

        $().debugLog('remove-facebook');
        $().debugLog(event);

        $().clearErrorMessage();
        var fdata = {'partner':'facebook'};
        var settings = {'data':fdata,'url':app.gopogo.accountremovepartner_url};

        $('.remove-facebook').ajaxLoader();

        $().updateSettings("facebook",settings);

    });

    // facebook connect event handlar

    $('#facebook-connect').click(function(){
        $().debugLog('facebook-connect');

        // opeen FB login pupup
        fblogin();

    });


});

function fblogin() {
    FB.login(function(response) {
      if (response.session) {
        // Get path of the url to chek it is Account update or fb signup
        var wndwLocation = window.location.pathname;
        if (response.perms) {
          // user is logged in and granted some permissions.
          // perms is a comma separated list of granted permissions
          if ( wndwLocation == '/account') {
              FB.api('/me', function(fbData) {

                $().debugLog(fbData);

                // Call ajax to update profile information
                var fdata = {'email':fbData.email};
                $.ajax({
                  url: app.gopogo.fbemailupdate_url,
                  type: 'POST',
                  dataType: 'json',
                  data:fdata,
                  timeout: 99999,
                  success: function() {
                        $(".dsplUpdateFacebook").html(fbData.email);
                        //$(".remove-facebook").show();
                        $('.facebookBox1').removeClass('d1').addClass("d2");
                        $('.facebookBox2').removeClass('d2').addClass("d1");
                   }
                });

                // Logout from facebook
                FB.logout(function() {
                    // user is now logged out
                });
              });
          } else {
              // Call ajax for facebook sign up action
              $.ajax({
                  url: app.gopogo.fbsignup_url,
                  type: 'POST',
                  timeout: 99999,
                  success: function() {
                      // Logout from facebook
                        FB.logout(function() {
                            // user is now logged out
                            $(location).attr('href',app.gopogo.profile_url);
                        });
                   }
                });
          }
        } else {
          // user is logged in, but did not grant any permissions
        }
      } else {
        // user is not logged in
      }
    }, {perms:'email'});
  }

  // Global variable for storing messages id
  var msgIds;
  // Global variable for storting message list ( Asc, Desc)
  var msgSort = "Desc";
  // Global variable for storing sender and receiver ids
  var senderId;
  var receiverId;
  function loadMsgList() {
    //call ajax to generate message list on load
    var pData = {'msgSort':msgSort};
    $.ajax({
          url: app.gopogo.msglist_url,
          type: 'POST',
          data: pData,
          timeout: 99999,
          error: function(data){
              //alert(data);
              $().debugLog(data);
           },
          success: function(data) {
                if (data == 'logout') {
                    $(location).attr('href',app.gopogo.baseurl);
                }
                else {
                    $(".clsMsgRply").hide();
                    $(".clsCommentList").hide();
                    $(".clsMsgList").html(data);
                    $(".clsMsgList").show();
                    msgIds = $(".clsHdnChkMsg").val();
                }
           },
           complete: function() {

            }
     });
}

// function to sort message listing
function sortMsgList() {
    if (msgSort == 'Desc') {
        $(".black-arrow-bg").style('background','url(/themes/default/images/inner-page-img.png) no-repeat scroll -173px -30px transparent;');
        msgSort = 'Asc'; alert(1);
    } else if (msgSort == 'Asc') {
        msgSort = 'Desc';
    }
    loadMsgList();
}

//call ajax to reply for an message
function doReplyMsg(receiverId) {
    fdata = {'receiverId':receiverId};
    $.ajax({
          url: app.gopogo.replymessage_url,
          type: 'POST',
          data:fdata,
          timeout: 99999,
          error: function(data) {
              $().debugLog(data);
           },
          onload: function() {

          },
          success: function(data) {
               if (data == 'logout') {
                    $(location).attr('href',app.gopogo.baseurl);
                }
                else {
                    //$('.gray_date_subject_bg').ajaxLoader();
                    $(".clsMsgList").hide();
                    $(".clsMsgRply").html(data);
                    $(".clsMsgRply").show();
                }
           },
           complete: function() {
                //$('.gray_date_subject_bg').ajaxLoaderRemove();
            }
     });
}

function backMsgClick() {
     $(".clsMsgRply").hide();
     loadMsgList();
     $(".clsMsgList").show();
 }

function savemyinfo() {
    if (profile[0] != $(".inpclsDivUN").val() || profile[1] != $(".inpclsDivUD").val()) {
                $().updateProfile("myinfo");
           } else {
               // suggest to change value
               var inpArr = new Array(2);
               inpArr[0] = new Array("inpclsDivUN","clsDivUN");
               inpArr[1] = new Array("inpclsDivUD","clsDivUD");
               $().inplaceEditor(inpArr,"save");
               $(".clsEUPro").removeClass('save');
               $("b", ".clsEUPro").fadeOut(function(){$(this).text("edit").fadeIn()});
               $(".save_info").hide();
               $(".clsEUPro").show();
           }
}

function selAllChk() {
    if($("input[name=selectAll]")[0].checked == true) {
        for (i = 0; i < $("input[name=msgChk]").length; i++)
        $("input[name=msgChk]")[i].checked = true ;
    }
    else {
        for (i = 0; i < $("input[name=msgChk]").length; i++)
        $("input[name=msgChk]")[i].checked = false ;
    }
}

//function to delete messages
function delMsg() {
    var delMsgIds="";
    for (i = 0; i < $("input[name=msgChk]").length; i++) {
        if($("input[name=msgChk]")[i].checked == true ) {
            delMsgIds+=$("input[name=msgChk]")[i].value;
        }
        // If value is not last one then append the comma else not
        if( i+1 != $("input[name=msgChk]").length ) {
            delMsgIds+=",";
        }
    }

    /* @security code check */
    // Code to check return ids are actually present or not in our global variable msgIds
    // make array of global variable
    var arrMsgIds = explode(",",msgIds);
    // make array of del msg ids
    var arrDelMsgIds = explode(",",delMsgIds);
    var sDelMsgIds="";
    for(i=0;i<arrDelMsgIds.length;i++) {
        if(in_array(arrDelMsgIds[i],arrMsgIds)) {
            // Append it to delete list
            sDelMsgIds+=arrDelMsgIds[i]+",";
        }
    }
    fdata = {'delMsgIds':delMsgIds};
    // call an ajax to delete messages
    // Post : Message ids
    $.ajax({
          url: app.gopogo.deletemessage_url,
          type: 'POST',
          data:fdata,
          timeout: 99999,
          error: function(data) {
              $().debugLog(data);
           },
          onload: function() {

          },
          success: function() {
                // Call ajax to refresh message listing
                loadMsgList();
           },
           complete: function() {
                //$('.gray_date_subject_bg').ajaxLoaderRemove();
            }
     });
}

//function to display comments on comment tab click
$('.clsComments').click(function(){
    loadCommentList();
});

//function to display messages on message tab click
$('.clsMessages').click(function(){
    loadMsgList();
});

//function to display photos on Photos tab click
$('.clsPhotos').click(function(){
    loadPhotosList();
});

function loadCommentList() {
    $.ajax({
          url: app.gopogo.commentlist_url,
          type: 'POST',
          timeout: 99999,
          error: function(data) {
              $().debugLog(data);
           },
          onload: function() {

          },
          success: function(data) {
                if (data == 'logout') {
                    $(location).attr('href',app.gopogo.baseurl);
                }
                else {
                    // Show output data in clsComment div
                    $(".clsMsgList").hide();
                    $(".clsCommentList").html(data);
                    $(".clsCommentList").show();
                }
           },
           complete: function() {
                //$('.gray_date_subject_bg').ajaxLoaderRemove();
            }
     });
}

function loadPhotosList() {
    $.ajax({
          url: app.gopogo.photoslist_url,
          type: 'POST',
          timeout: 99999,
          error: function(data) {
              $().debugLog(data);
           },
          onload: function() {

          },
          success: function(data) {
              if (data == 'logout') {
                  $(location).attr('href','/index/index');
              }
              else {
                // Show output data in clsComment div
                $(".clsMsgList").hide();
                $(".clsReplyMsg").hide();
                $(".clsCommentList").hide();
                $(".clsPhotosList").html(data);
                $(".clsPhotosList").show();
              }
           },
           complete: function() {
                //$('.gray_date_subject_bg').ajaxLoaderRemove();
            }
     });
}

