
$(document).ready(function(){
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
                       //inpArr[2] = new Array("clsUpdateAccUserEmail","clsUpdateAccUserEmailInfo","href");
                       $().inplaceEditor(inpArr,"save");
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

$(".clsUpdateAccUserEmail").click(function(){
    if($(".clsUpdateAccUserEmail").hasClass('save') ) {
           $().updateProfile("myAccEmail");
           var replacement = $("b", ".clsUpdateAccUserEmail").text() == "Save" ? "edit" : "Save";
           $("b", this).fadeOut(function(){$(this).text(replacement).fadeIn()});
           $(".clsUpdateAccUserEmail").removeClass("save");
       } else {
           replacement = $("b", this).text() == "edit" ? "Save" : "edit";
           $("b", this).fadeOut(function(){$(this).text(replacement).fadeIn()});
           var inpArr = new Array(2);
           inpArr[0] = new Array("clsUpdateEmail","clsUpdateEmail","text");
//           inpArr[1] = new Array("clsUpdateConfirmNewPass","clsUpdateConfirmNewPass","text");
           inpArr[1] = new Array("clsUpdateAccUserEmail","clsUpdateAccUserEmailInfo","href");
           $().inplaceEditor(inpArr,"edit");
       }
    });

//Username Update
$(".clsUpdateAccUserName").click(function(){
    if($(".clsUpdateAccUserName").hasClass('save') ) {
           $().updateProfile("myAccUserName");
           var replacement = $("b", ".clsUpdateAccUserName").text() == "Save" ? "edit" : "Save";
           $("b", this).fadeOut(function(){$(this).text(replacement).fadeIn()});
           $(".clsUpdateAccUserName").removeClass("save");
       } else {
           replacement = $("b", this).text() == "edit" ? "Save" : "edit";
           $("b", this).fadeOut(function(){$(this).text(replacement).fadeIn()});
           var inpArr = new Array(2);
           inpArr[0] = new Array("clsUpdateUserName","clsUpdateUserName","text");
//           inpArr[1] = new Array("clsUpdateConfirmNewPass","clsUpdateConfirmNewPass","text");
           inpArr[1] = new Array("clsUpdateAccUserName","clsUpdateAccUserNameInfo","href");
           $().inplaceEditor(inpArr,"edit");
       }
    });


    $.fn.inplaceEditor = function(inpArr,act) {  
       
       switch (act) {
           case "edit":
                var arrLen = inpArr.length; 
                for(i=0;i<arrLen-1;i++) {
                    var txtEditData = $("<input type='"+inpArr[i][2]+"' name='' id='' class='inp"+inpArr[i][0]+"' value='"+$("."+inpArr[i][0]).text()+"'/>");
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

$(".clsUpdateAccUserPass").click(function(){
    if($(".clsUpdateAccUserPass").hasClass('save')) {
        $(".clsUpdatePass").show();
        $(".clsUpdatePassTxt").show();
        $(".clsUpdatePass").text('******');
        $(".clsResetPass").hide();
        $().updateProfile("myAccPass");
        var replacement = $("b", ".clsUpdateAccUserPass").text() == "Save" ? "edit" : "Save";
        $("b", this).fadeOut(function(){$(this).text(replacement).fadeIn()});
        $(".clsUpdateAccUserPass").removeClass('save');
    }
    else {
        $(".clsUpdateAccUserPass").addClass('save');
        //$(".clsUpdatePass").hide();
        //$(".clsUpdatePassTxt").hide();
        $(".clsResetPass").show();
        replacement = $("b", ".clsUpdateAccUserPass").text() == "edit" ? "Save" : "edit";
        $("b", this).fadeOut(function(){$(this).text(replacement).fadeIn()});
        $(".clsUpdatePass").html("<input type='text'/>");
        var inpArr = new Array(3);
       //
    }
    });

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
    
});