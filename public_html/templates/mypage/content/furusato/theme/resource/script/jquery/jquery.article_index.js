/* 目次の自動生成処理 */
function articleIndexInit(startHw = 2){

  jQuery(function($){

    if(
      Number.isNaN( startHw )
    ){
      return false;
    }

    $(".c-articleIndex--js").each(function( wrapIndex ){

      var __this = $(this);
      var __this_wrap = __this.closest(".p-article");

      var hwSelector = "";

      hwLv1 = "H" + startHw;
      hwLv2 = "H" + ( startHw + 1 );

      hwSelector = hwLv1 + "," + hwLv2;

      // 記事の範囲を指定するクラスが存在している場合のみ目次を有効化する
      if( __this_wrap.length > 0 && __this_wrap.children(hwSelector).length > 0 ){

        __this.children(".c-articleIndex__list").remove();
        __this.append('<ul class="c-articleIndex__list"></ul>');

        // hwLv1とhwLv2の関係を配列として保存し、その結果をもとにul>li構造を作成する
        __this_wrap.children(hwSelector).each(function(index){

          var target_id = "";

          // 要素にアンカーとなるIDが指定してあるかを調べ、IDがなければ連番を振る
          if($(this).attr("id")){

            // 既にIDが登録されている場合はその値を取得する
            target_id = $(this).attr("id");

          }else{

            // IDが登録されていない場合はIDを作成して付与する
            target_id = "__index_" + wrapIndex + "__" + index;
            $(this).attr("id", target_id);

          }

          // リスト用のhtmlを作成しておく
          var html_insert_li = '<li><a href="#' + target_id + '">' + $(this).text() + '</a></li>';

          // hwLv1かhwLv2かを判定する
          if($(this).prop("tagName") == hwLv1 ){

            // hwLv1の場合は__this.children(".article_index__list")にリストを追加する
            __this.children(".c-articleIndex__list").append(html_insert_li);

          }else if($(this).prop("tagName") == hwLv2 ){

            // hwLv2の場合は__this.children(".article_index__list li:last-child")を取得
            // そこに、ulが存在しているか検証する
            if(!(__this.find(".c-articleIndex__list>li:last-child>ul").length > 0)){

              // ulが存在していなければulを挿入する
              __this.find(".c-articleIndex__list>li:last-child").append("<ul></ul>");
            }
            // ulにリストを追加する
            __this.find(".c-articleIndex__list>li:last-child>ul").append(html_insert_li);

          }

        });

        $(this).show();

      }else{

        $(this).hide();

      }

    });

  });

}
