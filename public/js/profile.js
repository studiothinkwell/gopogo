
$(document).ready(function(){
    $(".clsEUPro").click(function(){
       if($(".clsEUPro").hasClass('save') ) {
           $().updateProfile("myinfo");
       } else {
           var inpArr = new Array(2);
               inpArr[0] = new Array("clsDivUN","clsDivUN","text");
               inpArr[1] = new Array("clsDivUD","clsDivUD","text");
               inpArr[2] = new Array("clsEUPro","clsProAction","href");
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
              //  $("."+inpArr[arrLen-1][0]).removeClass("edit");
                $("."+inpArr[arrLen-1][0]).addClass("save");
        }
    }

    $(".inplaceclsEUProsave").click(function(){
        $().updateProfile('myinfo');
    })
    //call ajax for update profile
    $.fn.updateProfile = function (area) {
        switch(area) {
        default : alert(2);
        $.ajax({
              url: app.gopogo.profilemyinfo_url,
              type: 'POST',
              dataType: 'json',
              //data:fdata,
              timeout: 99999,
              success: function(resp){
                  // do something with resp
                  if(resp.status == 1) { // show error message

                  }
                  else {

                   }
               }
         });
        }
    }
});

