# 萬年曆作業

## 作業說明
1. 請以PHP程式撰寫一個萬年曆系統
2. 提供連結可以切換不同的月份
3. 善用目前為止學會的html標籤來建構內容
4. 善用目前為止學會的CSS來美化畫面
5. 可加入圖片但要注意檔案格式、大小、傳輸速度及智財權問題
6. 可加入影音但要注意檔案大小、傳輸速度、及是否會對用戶造成干擾
7. 首頁請命名為 **index.php**

## 參考作品
* [109年度第一期](http://220.128.133.15/mackliu/10901/calendar/)
* [109年度第二期](http://220.128.133.15/mackliu/10902)
* [110年度第一期](http://220.128.133.15/mackliu/11001)
* [110年度第二期](http://220.128.133.15/mackliu/11002)
* [111年度第一期](http://220.128.133.15/mackliu/calendar/11101/)

## 完成期限
* 老師交代作業後兩周(含假日共14天)


<?php 
header("Content-Type:text/html;charset=utf-8"); 
$lunar=new Lunar();
$month=$lunar->convertSolarToLunar(2013,07,08);//將陽歷轉換為農曆 
echo '<pre>'; 
print_r($month);