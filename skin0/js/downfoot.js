
window.onload = function(){
$(".artimglists img").each(function(){
if($(this).height()>130){
if($(this).height()>$(this).width()){$(this).css("width","180px");}else if($(this).width()>$(this).height()){$(this).css("height","130px");}
}
});

                var sst=$(".snopshot");
                    if(sst.length==1){
                        sst.css({"position":"relative","text-align":"center"}).find("img").css({"max-width":"600px","max-height":"550px;"}).next(".elementOverlay").hide();
                        $(".snap-shot-btn").hide();
                    }
                    else if(sst.length==2){
                        sst.css({"position":"relative","float":"left"}).find("img").css({"max-width":"400px","margin-right":"10px"}).next(".elementOverlay").hide();
                        $(".snap-shot-btn").hide();
                    }
                    else{
                      var img = new Image();
                                        
                   img.src =  $(".snapShotCont li").eq(0).find("img").attr("src");
                      //window.onload=function(){
                        var imgWidth = img.width;
                        var imgHeight = img.height;
                        if(imgWidth > imgHeight){
                            imgHeight = 343;
                            imgWidth = 600;
                        }else{
                            imgHeight = 600;
                            imgWidth = 343;
                        }
                        var snapShotWrap = new posterTvGrid(
                          'snapShotWrap',
                          {
                            imgHeight : imgHeight,//图片宽高，来调整框架样式
                            imgWidth : imgWidth,
                            imgP : parseInt(imgWidth/1.2)//小图与大图比例暂定1比1.2
                          }
                        );
                      //}; 
                    }
  }