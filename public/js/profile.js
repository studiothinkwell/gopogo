
$(document).ready(function(){

// basic update functions

    //call ajax for update profile

    $.fn.updateProfile = function (area) {
        switch(area) {
         case 'myinfo':
            $.ajax({
              url: app.gopogo.profilemyinfo_url,
              type: 'POST',
              dataType: 'json',
              data:fdata,
              timeout: 99999,
              success: function(resp){
                  // do something with resp
                  if(resp.status == 1) { // show error message

                  }
                  else {

                   }
               }
         });
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
                  if(resp.status == 1) { // show error message

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
           case "edit":
                var arrLen = inpArr.length;
                for(i=0;i<arrLen-1;i++) {

                    var inpval = $.trim($("."+inpArr[i][0]).text());

                    var txtEditData = $("<input type='"+inpArr[i][2]+"' name='' id='' class='inp"+inpArr[i][0]+"' value='"+inpval+"'/>");
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
                    $("."+inpArr[0][1]).html($("."+inpArr[0][0]).val());
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


$(".inplaceclsEUProsave").click(function(){
        $().updateProfile('myinfo');
   })

$(".clsEUPro").click(function(){
       if($(".clsEUPro").hasClass('save') ) {
           $().updateProfile("myinfo");
       } else {
           var replacement = $("b", this).text() == "edit" ? "Save" : "edit";
           $("b", this).fadeOut(function(){$(this).text(replacement).fadeIn()});
           var inpArr = new Array(2);
           inpArr[0] = new Array("clsDivUN","clsDivUN","text");
           inpArr[1] = new Array("clsDivUD","clsDivUD","text");
           inpArr[2] = new Array("clsEUPro","clsProAction","href");
           $().inplaceEditor(inpArr);
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
         *  And if you want to allow underscore only as concatenation character and
         * want to force that the username must start with a alphabet character:
         */
        var regex = /^[A-Za-z][A-Za-z0-9]*(?:_[A-Za-z0-9]+)*$/;
        var uvlFlag = regex.test(username);
        $().debugLog(uvlFlag);
        return uvlFlag;
    }


});