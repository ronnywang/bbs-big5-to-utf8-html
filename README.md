這是把 BBS Big5 以及色碼轉換成 UTF-8 HTML 的程式
主要是 Converter.php 的 Converter::convert($big5_content); 會回傳 HTML

- Converter.php 轉換的程式
- big5uni.txt Big5 和 UTF-8 的轉換表
- test.in 測試用的輸入資料
- test.php 產生測試結果的 script, 可以用 php test.php > test.out.html 產生
- test.out.html 測試結果 : http://ronnywang.github.io/bbs-big5-to-utf8-html/test.out.html
- bbs.css 處理各種色碼的 css
- bbs.js 處理雙色字和連結的 js

以上程式以 BSD License 授權 
