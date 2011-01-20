
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
              success: function(resp){ alert('status-'+resp.status);
                  // do something with resp
                  if(resp.status == 1) { // show error message
                      alert(resp);
                        $().updateSuccess(resp);
                  }
                  else {
                      alert(resp);
                        $().updateError(resp);
                   }
               }
         });
         //for other action

        default :

        }
    }


   $.fn.updateError = function(resp){
       $().showErrorTooltip(resp.msg);
   }

   $.fn.updateSuccess = function(resp){
       $().showSuccessTooltip(resp.msg);
   }
    
    $(".clsEUPro").click(function(){
       var inpArr = new Array(2);
           inpArr[0] = new Array("clsDivUN","clsDivUN","text");
           inpArr[1] = new Array("clsDivUD","clsDivUD","text");
           inpArr[2] = new Array("clsEUPro","clsProAction","button");
       $().inplaceEditor(inpArr);
    });


$(".clsUpdateAccUserEmail").click(function(){
    if($(".clsUpdateAccUserEmail").hasClass('save') ) {
           $().updateProfile("myAccEmail");
       } else {
           var replacement = $("b", this).text() == "Update" ? "Save" : "Update";
           $("b", this).fadeOut(function(){ $(this).text(replacement).fadeIn() });
       var inpArr = new Array(2);
           inpArr[0] = new Array("clsUpdateEmail","clsUpdateEmail","text");
           inpArr[1] = new Array("clsUpdateConfirmNewPass","clsUpdateConfirmNewPass","text");
           inpArr[2] = new Array("clsUpdateAccUserEmail","clsUpdateAccUserEmailInfo","href");
           $().inplaceEditor(inpArr);
       }
    });


$(".clsUpdateAccUserPass").click(function(){
     $(".clsResetPass").show();
     $(".clsUpdatePass").html("<input type='text'/>");
       var inpArr = new Array(3);
          // inpArr[0] = new Array("clsUpdateEmail","clsUpdateEmail","text");
//           inpArr[1] = new Array("clsUpdatePass","clsUpdatePass","text");
//           inpArr[2] = new Array("clsUpdateNewPass","clsUpdateNewPass","text");
//           inpArr[3] = new Array("clsUpdateConfirmNewPass","clsUpdateConfirmNewPass","text");
//           inpArr[4] = new Array("clsUpdateAccUserPass","clsUpdateAccUserPassInfo","href");
//       $().inplaceEditor(inpArr);
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
       $().inplaceEditor(inpArr);
       }
    });

    $.fn.inplaceEditor = function(inpArr) {  //alert(inpArr[0][1]); alert(inpArr[0][2]); alert(inpArr[0][3]);
        var arrLen = inpArr.length;
        //alert(arrLength);
        for(i=0;i<arrLen-1;i++) {
            var txtEditData = $("<input type='"+inpArr[i][2]+"' name='' id='' class='inp"+inpArr[i][0]+"' value='"+$("."+inpArr[i][0]).text()+"'/>");
            $("."+inpArr[i][1]).html(txtEditData);
        }
        switch(inpArr[arrLen-1][2]) {
            case "button":
                $("."+inpArr[arrLen-1][1]).html("<input type='button' class='inplace"+inpArr[arrLen-1][0]+"save' name='' value='Save'/>");
            case "href":
                $("."+inpArr[arrLen-1][0]).addClass("save");

                //$("."+inpArr[arrLen-1][1]).html("<a href='#' onclick='updateProfile()' class='inplace"+inpArr[arrLen-1][0]+"save'>Save</a>");

        }
    }




$(".inplaceclsEUProsave").click(function(){
        $().updateProfile('myinfo');
    })


//    $(".inplaceclsUpdateAccUserEmailsave").click(function(){
//        $().updateProfile('myAccEmail');
//    })
    
});