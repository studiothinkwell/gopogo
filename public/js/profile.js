<<<<<<< HEAD
// declair global variables for profile
var profile = new Array(2);
$(document).ready(function() {
    loadMsgList();    
    
    $(".clsEUPro").click(function() {
=======

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
>>>>>>> ab3ff2b52b75114b4863e2cf96ba033fff1db0e7
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
               $("b", ".clsEUPro").fadeOut(function(){$(this).text("edit").fadeIn()});
           }
       } else {
<<<<<<< HEAD
           // assign value to global variable of profile
           profile[0] = $(".clsDivUN").text();
           profile[1] = $(".clsDivUD").text();
           var replacement = $("b", this).text() == "edit" ? "save" : "edit";
           $("b", this).fadeOut(function(){$(this).text(replacement).fadeIn()});
           inpArr = new Array(2);
           inpArr[0] = new Array("clsDivUN","clsDivUN","text","20");
           inpArr[1] = new Array("clsDivUD","clsDivUD","textarea");
=======
           var replacement = $("b", this).text() == "edit" ? "Save" : "edit";
           $("b", this).fadeOut(function(){$(this).text(replacement).fadeIn()});
           var inpArr = new Array(2);
           inpArr[0] = new Array("clsDivUN","clsDivUN","text");
           inpArr[1] = new Array("clsDivUD","clsDivUD","text");
>>>>>>> ab3ff2b52b75114b4863e2cf96ba033fff1db0e7
           inpArr[2] = new Array("clsEUPro","clsProAction","href");
           $().inplaceEditor(inpArr,"edit");
       }
    });

<<<<<<< HEAD
    $.fn.inplaceEditor = function(inpArr, act) {
        switch(act) {
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
    //call ajax for update profile
    $.fn.updateProfile = function (area) {  
        switch(area) {
        case 'myinfo':
        var fdata = {'userName':$(".inpclsDivUN").val(),'userDesc':$(".inpclsDivUD").val()};
        $.ajax({
              url: app.gopogo.updatemyinfo_url,
              type: 'POST',
              data: fdata,
              timeout: 99999,
              error: function(data){
                  alert(data);
               },
              success: function(data) {
                      $('.clsProfileLoader').ajaxLoader();
                      //code to manage html after save process complete
                      var inpArr = new Array(2);
                      inpArr[0] = new Array("inpclsDivUN","clsDivUN");
                      inpArr[1] = new Array("inpclsDivUD","clsDivUD");
                      $().inplaceEditor(inpArr,"save");
                  if(data == 0) { 
                      $(".clsDivUN").text(profile[0]);
                      $(".clsDivUD").text(profile[1]);
                  }
                  $(".clsEUPro").removeClass('save');
                  $("b", ".clsEUPro").fadeOut(function(){$(this).text("edit").fadeIn()});
                  // do something with resp                  
               },
              complete: function() {
                    $('.clsProfileLoader').ajaxLoaderRemove();
              }
         });
        }
    }    
});

 function backMsgClick() {
     $(".clsMsgRply").hide();
     loadMsgList();
     $(".clsMsgList").show();
 }
    
//call ajax to display message detail
    function showMsgDtl() {
        $.ajax({
              url: app.gopogo.messagedtl_url,
              type: 'POST',
              timeout: 99999,
              error: function(data){
                  alert(data);
               },
              success: function(data) {
                    $(".clsMsgDiv").slideUp();
                    $(".dtlMsgDiv").html(data);
                    $(".clsBackMsg").show();
                    $(".clsDeleteMsg").show();
                    $(".dtlMsgDiv").show();
                  // do something with resp
               },
               complete: function() {

                }
         });
    }

//call ajax to reply for an message
    function doReplyMsg() {
        $.ajax({
              url: app.gopogo.replymessage_url,
              type: 'POST',
              timeout: 99999,
              error: function(data){
                  alert(data);
               },
              onload: function() {
                  
              },
              success: function(data) {
                    $('.gray_date_subject_bg').ajaxLoader();
                    $(".clsMsgList").hide();
                    $(".clsMsgRply").html(data);
                    $(".clsMsgRply").show();
                  // do something with resp
               },
               complete: function() {
                    $('.gray_date_subject_bg').ajaxLoaderRemove();
                }
         });
    }

    function showloader() {
                $().finish();
                $('.con-right-ro1').ajaxLoader();
            }

    function loadMsgList() {
        //call ajax to generate message list on load
        $.ajax({
              url: app.gopogo.msglist_url,
              type: 'POST',
              timeout: 99999,
              error: function(data){
                  alert(data);
               },
              success: function(data) {
                    $(".clsMsgList").html(data);
               },
               complete: function() {

                }
         });
    }
=======


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
>>>>>>> ab3ff2b52b75114b4863e2cf96ba033fff1db0e7
