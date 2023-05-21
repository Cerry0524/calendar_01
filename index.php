<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="#">
    <title>萬年曆作業</title>
    <link rel="stylesheet" href="./style.css">
</head>

<body>
    <?php
    $month = $_GET['month'] ?? date("n"); //取get月沒有取今日月
    $year = $_GET['year'] ?? date("Y");   //取get年沒有取今日年
    $firstDayTime = strtotime("$year-$month-1"); //用strtotime取的當月第一日
    $days = date("t", $firstDayTime); //用date"t"參數取的這個月有幾天
    $finalDayTime = strtotime("$year-$month-$days"); //用days變數結合年跟月可組成當月最後一日
    $firstDayWeek = date("w", $firstDayTime); //date"w"參數取的第一天是星期幾
    $finalDayWeek = date("w", $finalDayTime); //date"w"參數取的最後一天是星期幾
    $weeks = ceil(($firstDayWeek + $days) / 7); //用無條件進位ceil將第一天的星期+當月天數除7取得週次
    $firstWeekSpace = $firstDayWeek - 1; //為了後面計算方便設計此變數

    $today = date('Y-n-j'); //將今天日期轉變為無零值純數字方便接下來運算
    $prevMonth = ($month == 1) ? 12 : $month - 1; //用判斷式判斷回傳get值是否跨年度
    $prevYear = ($month == 1) ? $year - 1 : $year;
    $nextMonth = ($month == 12) ? 1 : $month + 1;
    $nextYear = ($month == 12) ? $year + 1 : $year;

    $cal = []; //設日期空矩陣
    $lunarDay = []; //設農曆空矩陣
    $Newmonth = 0; //清空指定變數

    include_once "./hoilday.php"; //載入年度節日矩陣
    include_once "./makeupday.php"; //載入年度補班日期
    include_once "./lunar.php"; //載入農曆計算函釋<上網找的不是自己做的>

    for ($i = 0; $i < $weeks; $i++) { //建立日期矩陣值

        for ($j = 0; $j < 7; $j++) {

            if ($i == 0 && $j < $firstDayWeek) {

                $x = $firstDayWeek - $j;
                $cal[] = date('Y-n-j', strtotime("-$x day", $firstDayTime)); //計算前一月尾巴的日期
            } else if (($i == $weeks - 1) && $j > $finalDayWeek) {

                $x = $j - $finalDayWeek;
                $cal[] = date('Y-n-j', strtotime("+$x day", $finalDayTime)); //計算次月開始的日期
            } else {

                $cal[] = ("$year-$month-" . ($j + $i * 7 - $firstWeekSpace)); //計算當月日期星期
            }
        }
    }


    for ($k = 0; $k < count($cal); $k++) { //建立農曆日期矩陣
        $lunar = new Lunar();

        $l_year = date('Y', strtotime($cal[$k])); //將國曆矩陣日期取得年月日
        $l_month = date('n', strtotime($cal[$k]));
        $l_days = date('j', strtotime($cal[$k]));

        $Newmonth = $lunar->convertSolarToLunar($l_year, $l_month, $l_days); //帶入國曆日期計算

        $lunarDay[] = $Newmonth[1] . "／" . $Newmonth[2]; //取矩陣值農曆月跟日以"/"合併納入lunarDay新矩陣
    }
    ?>


    <!-- 建立網頁畫面 -->
    <div class="container">

        <!-- 分成背景層(3層森林3層兔子)跟內容層(月曆本體) -->
        <div class="content">

            <!-- 月曆最上面的選擇清單 -->
            <div class="top-select">

                <!-- 月曆左邊前五個月選單 -->
                <div class="select" id="selectLeft">
                    <?php
                    for ($i = -5; $i < 0; $i++) {

                        echo "<a href='?year=";
                        echo date('Y', strtotime("$i month", $firstDayTime));
                        echo "&month=";
                        echo date('n', strtotime("$i month", $firstDayTime));
                        echo "' class='selectDiv'>";
                        echo date('Y', strtotime("$i month", $firstDayTime));
                        echo "<br>";
                        echo "<span style='font-size:30px'>";
                        echo date('n', strtotime("$i month", $firstDayTime));
                        echo "</span>";
                        echo "</a>";
                    }

                    ?>
                </div>

                <!-- 顯示今天日期同時觸發js按鈕功能後隱藏 -->
                <div id="top" type="button" onclick="clickTop()">
                    <?= $year; ?>年<br>
                    <span style='font-size:30px'><?= $month; ?>月</span>
                </div>

                <!-- 初始隱藏js觸發按鈕後出現返回今日按鈕 -->
                <div id="reToday" type="button" onclick="clickToday()">
                    <a href="?year=<?= date('Y'); ?>&month=<?= date('n'); ?>">
                        Today
                    </a>
                </div>

                <!-- 月曆右邊後五個月選單 -->
                <div class="select" id="selectRight">

                    <?php
                    for ($i = 1; $i < 6; $i++) {

                        echo "<a href='?year=";
                        echo date('Y', strtotime("$i month", $firstDayTime));
                        echo "&month=";
                        echo date('n', strtotime("$i month", $firstDayTime));
                        echo "' class='selectDiv'>";
                        echo date('Y', strtotime("$i month", $firstDayTime));
                        echo "<br>";
                        echo "<span style='font-size:30px'>";
                        echo date('n', strtotime("$i month", $firstDayTime));
                        echo "</span>";
                        echo "</a>";
                    }

                    ?>

                </div>
            </div>

            <!-- 日曆本體英文星期標題套用title標籤 (第一層div開頭-start)-->
            <div class='calendar'>

                <!-- (第二層div-start-end -->
                <div class='title' style='color:red'>Sun</div>
                <div class='title'>Mon</div>
                <div class='title'>Tue</div>
                <div class='title'>Wed</div>
                <div class='title'>Thu</div>
                <div class='title'>Fri</div>
                <div class='title' style='color:red'>Sat</div>

                <!-- 列印日曆矩陣 -->
                <?php
                for ($i = 0; $i < count($cal); $i++) {

                    // 拆解矩陣成year.month.day各為0.1.2取第三組day
                    $day = explode("-", $cal[$i])[2];

                    // 判斷Today套印id(第二層div開頭-start)
                    echo ($today == $cal[$i]) ? "<div id='today' class=" : "<div class=";

                    // 拆解矩陣判斷前月日期.後月日期class
                    echo (explode("-", $cal[$i])[1] == $prevMonth || explode("-", $cal[$i])[1] == $nextMonth) ? "'notMonth " : "'thisMonth ";

                    // 初始化週末變數
                    $weekDay = "0";

                    // 利用矩陣holidayN判斷六日是否為補班日，存入weekday變數
                    if (isset($holidayN[$cal[$i]])) {

                        $weekDay = (date('w', strtotime($cal[$i])) == 0 || date('w', strtotime($cal[$i])) == 6) ? "補班日" : "";
                    } else {

                        $weekDay = (date('w', strtotime($cal[$i])) == 0 || date('w', strtotime($cal[$i])) == 6) ? "例假日" : "";
                    }

                    // 套印light發光跟hover功能(第二層開頭-end)
                    echo "light day'>";

                    // 利用矩陣holidayN判斷六日是否為補班日，並套用class dDay，否則套用例假classh hDay(第三層div開頭-start)
                    if ((isset($holiday[$cal[$i]])) || $weekDay == "例假日") {

                        // 套印平常日class dDay(第四層div-start-end)
                        echo "<div class='dDay'>" . $day . "</div>";
                        // 套印例假日Class hDay(第四層div-start-end)
                        echo "<div class='hDay'>" . (($holiday[$cal[$i]]) ?? $weekDay) . "</div>";
                        // 套印農曆日期Class lunarDay(第四層div-start-end)
                        echo "<div class='lunarDay'>" . $lunarDay[$i] . "</div>";
                    } else if ($weekDay == "補班日") {

                        // 套印補班日平常日class workDay(第四層div-start-end)
                        echo "<div class='workdDay'>" . $day . "</div>";
                        // 套印補班日Class workDay(第四層div-start-end)
                        echo "<div class='workhDay'>" . $weekDay . "</div>";
                        // 套印農曆日期Class lunarDay(第四層div-start-end)
                        echo "<div class='lunarDay'>" . $lunarDay[$i] . "</div>";
                    } else {

                        // 套印平常日class day(第四層div-start-end)
                        echo "<div class='workdDay'>" . $day . "</div>";
                        // 套印平常日內容空格(第四層div-start-end)
                        echo "<div class='workhDay'>&nbsp</div>";
                        // 套印農曆日期Class lunarDay(第四層div-start-end)
                        echo "<div class='lunarDay'>" . $lunarDay[$i] . "</div>";
                    }
                    
                    // (第三層div-end)
                    echo "</div>";
                
                }
                // (第二層div-end)
                echo "</div>";

                ?>
                <!-- 日曆左側選擇前一月兔子超連結(第二層div-start-end) -->
                <div class="background bg2-left">

                    <a href="?year=<?= $prevYear; ?>&month=<?= $prevMonth; ?>"><?= $prevMonth ?>月</a>

                </div>

                <!-- 日曆左側選擇後一月兔子超連結(第二層div-start-end) -->
                <div class="background bg2-right">

                    <a href="?year=<?= $nextYear; ?>&month=<?= $nextMonth; ?>"><?= $nextMonth ?>月</a>

                </div>


            <!-- (第一層div-end) -->
            </div>
        </div>
        
        <!-- 背景層最後層底圖<可套color> -->
        <div class="background bg1"></div>

        <!-- 背景層左邊兔子-->
        <div class="background rabbitup"></div>

        <!-- 背景層右邊兔子-->
        <div class="background rabbitup2"></div>

        <!-- 背景層第二層-->
        <div class="background bg3"></div>

        <!-- 背景層中間兔子-->
        <div class="background rabbitup3"></div>
        
        <!-- 背景層第一層-->
        <div class="background bg4"></div>
        
        <!-- JS層-->
        <script>
            // 綁定層
            let titleTop = document.getElementById('top');
            let reToday = document.getElementById('reToday');
            let left = document.getElementById('selectLeft');
            let right = document.getElementById('selectRight');

            // 綁定按鈕
            function clickTop() {
                titleTop.style.display = 'none';
                reToday.style.display = 'block';
                left.style.display = 'flex';
                right.style.display = 'flex';
            };
        </script>

</body>

</html>