/* ページ内ジャンプの上位置補正 */
var page_inner_jump_length = 0;

function pageInnerJump(){

  jQuery(function($){

    // ページ内ジャンプ処理
    $('a[href^="#"]').click(function(){

      var Hash = $(this.hash);
      var name = this.hash.substr(1,this.hash.length - 1);
      if($(Hash).length){
        var HashOffset = $(Hash).offset().top;
      }else{
        if( name == "" ){
          var HashOffset = 0;
        }else{
          var HashOffset = $("a[name=" + name + "]").offset().top;
        }
      }
      $("html,body").animate({
          scrollTop: HashOffset - page_inner_jump_length
      }, 700);

      return false;
    });

  });

}
