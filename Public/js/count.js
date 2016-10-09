/**
 * 计数器js文件
 */

var newsIds = {};
$(".news_count").each(function(i){
    newsIds[i] = $(this).attr("news-id");
});
//调试
//console.log(newsIds);
var url = "/index.php?c=index&a=getCount";
$.post(url,newsIds,function(result){
    console.log(result);
    if(result.status == 1){
        counts = result.data;
        $.each(counts,function(news_id,count){
            $(".node-"+news_id).html(count)
        });
    }
},"JSON");
