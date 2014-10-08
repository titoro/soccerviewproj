<!--
**************** PHPの処理が出来たらサイドメニューを表示させる ********************

<!DOCTYPE html>
<html lang="en" class="no-js">
	<head>
		<meta charset="UTF-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge"> 
		<meta name="viewport" content="width=device-width, initial-scale=1"> 
		<title>Off-Canvas Menu Effects - Side Slide</title>
		<meta name="description" content="Modern effects and styles for off-canvas navigation with CSS transitions and SVG animations using Snap.svg" />
		<meta name="keywords" content="sidebar, off-canvas, menu, navigation, effect, inspiration, css transition, SVG, morphing, animation" />
		<meta name="author" content="Codrops" />
		<link rel="shortcut icon" href="../favicon.ico">
		<link rel="stylesheet" type="text/css" href="css/normalize.css" />
		<link rel="stylesheet" type="text/css" href="css/demo.css" />
		<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.2.0/css/font-awesome.min.css" />
		<link rel="stylesheet" type="text/css" href="css/menu_sideslide.css" />
		<!--[if IE]>
  		<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
 <!--
	</head>
        <body>
                <div class="menu-wrap">
                    <nav class="menu">
                        <div class="icon-list">
                                <a href="#"><i class="fa fa-fw fa-star-o"></i><span>Favorites</span></a>
                                <a href="#"><i class="fa fa-fw fa-bell-o"></i><span>Alerts</span></a>
                                <a href="#"><i class="fa fa-fw fa-envelope-o"></i><span>Messages</span></a>
                                <a href="#"><i class="fa fa-fw fa-comment-o"></i><span>Comments</span></a>
                                <a href="#"><i class="fa fa-fw fa-bar-chart-o"></i><span>Analytics</span></a>
                                <a href="#"><i class="fa fa-fw fa-newspaper-o"></i><span>Reading List</span></a>
                        </div>
                    </nav>
                    <button class="close-button" id="close-button">Close Menu</button>
                </div>
                    <button class="menu-button" id="open-button">Open Menu</button>
-->
<!--
レスポンシブ対応させるjQueryプラグイン
■参照・引用
http://respontent.frebsite.nl/

※JQurey理解してから実行するようにする。

<script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.js"></script>
		<script type="text/javascript" language="javascript" src="src/js/jquery.respontent.all.min.js"></script>
		<script type="text/javascript" language="javascript">
			$(function() {
				$('#examples').respontent({
					tables: function() {
						if ( $(this).hasClass( 'list' ) )
						{
							return 'list';
						}
					}
				});
			});
</script>

		<link type="text/css" media="all" rel="stylesheet" href="src/css/jquery.respontent.all.css" />
		<style type="text/css" media="all">
			html, body {
				padding: 0;
				margin: 0;
				height: 100%;
			}
			body, div, p {
				font-family: Arial, Helvetica, Verdana;
				color: #333;
				-webkit-text-size-adjust: none;
			}
			body {
				background-color: #f3f3f3;
			}
			a, a:link, a:active, a:visited {
				color: black;
				text-decoration: underline;
			}
			a:hover {
				color: #9E1F63;
			}


			#wrapper {
				background-color: #fff;
				width: 50%;
				min-width: 220px;
				padding: 50px;
				margin: 0 auto;
				border: 1px solid #ccc;
				box-shadow: 0 0 5px #ccc;
			}
			#intro {
				margin-bottom: 60px;
			}
			#intro p {
				font-size: 18px;
			}
			#examples > em
			{
				display: block;
				margin: 50px 0 10px 0;
			}
		</style>
-->
<?php

/**
 * ライブ
 * 
 * 
 */


/**
 * toto 対戦カード　投票率
 * toto goal3 対戦カード　投票率
 *
 */

//スクレイピング(Goutte)
require_once '../library/goutte.phar';
use Goutte\Client;

$toto_vote =array();       //スケジュール、投票率

//今回のtotoマッチングと投票率の取得
define('TOTO_VOTE', 'http://www.totoone.jp/blog/datawatch/');

//Goutteオブジェクト生成
$client_vote = new Client();

//totoマッチング、投票率HTMLを取得
$crawler_vote = $client_vote->request('GET', TOTO_VOTE);

$crawler_vote->filter('td')->each(function($node)use(&$toto_vote)
{
        if($node->text() !== NULL){
            //echo (string)$node->text() . "<br />";
            //var_dump($node->text());
            $toto_vote[] =(string)$node->text();
        }
            
});

//totoGOAL3マッチングと投票率を取得
/* Yahoo Japan toto より取得 */
$toto_goal3_title;               //開催回の取得
$toto_goal3_team =array();       //チーム
$toto_goal3_date =array();       //開催日
$toto_goal3_vote =array();       //Goal3投票率（加工前　○○% )

//今回のtotoマッチングと投票率の取得
define('TOTO_GOAL3_VOTE', 'http://toto.yahoo.co.jp/vote/index.html');

//Goutteオブジェクト生成
$client_goal3_vote = new Client();

//totoマッチング、投票率HTMLを取得
$crawler_goal3_vote = $client_vote->request('GET', TOTO_GOAL3_VOTE);

//開催回の格納
$crawler_goal3_vote->filter('.vote_wr_w02 p:nth-of-type(1)')->each(function($node)use(&$toto_goal3_title)
{
        if(preg_match('/^第.+/', $node->text())){
            //echo (string)$node->text() . "<br />";
            //var_dump($node->text());
            $toto_goal3_title =(string)$node->text();
        }
            
});

//開催日の格納
$crawler_goal3_vote->filter('.bg_grn td')->each(function($node)use(&$toto_goal3_date)
{
        if(preg_match('/^[0-9]+\/[0-9]+/', $node->text())){
            //echo (string)$node->text() . "<br />";
            //var_dump($node->text());
            $toto_goal3_date[] =(string)$node->text();
        }
            
});

//対戦チームの格納
$crawler_goal3_vote->filter('.td_team td')->each(function($node)use(&$toto_goal3_team)
{
        if($node->text() !== NULL){
            //echo (string)$node->text() . "<br />";
            //var_dump($node->text());
            $toto_goal3_team[] =(string)$node->text();
        }
        
});

//投票率の格納
$crawler_goal3_vote->filter('.td_vote02 img')->each(function($node)use(&$toto_goal3_vote)
{
        if($node->attr('title') !== NULL){
            //echo (string)$node->attr('title') . "<br />";
            //var_dump($node->text());
            $toto_goal3_vote[] =(string)$node->attr('title');
        }
        
});

/*投票率を処理して格納
 * $goal3_vote          点数ごとの投票率を全て格納
 * $toto_goal3_taam     チームを順番に格納  
 * $team_goal3_vote     チーム毎に連想配列で得点別点数投票率を格納
 */

//チーム毎に連想配列で得点別点数投票率を格納
$team_goal3_vote = array();

for($i = 0; $i < count($toto_goal3_vote); $i++){
    //投票率から%を取り除く
    preg_match('/^[0-9]+/', $toto_goal3_vote[$i],$goal3_vote[]);
}

//var_dump($toto_goal3_team);



//各チーム毎に配列（投票率）を作成
/*
 * 連想配列　に格納するか、
 * 多重配列　に格納するか
 * 検討
 * 
for($i = 0; $i < count($goal3_vote); $i++){
    if($i < 4){
        //1チーム目
        $team_goal3_vote += array($toto_goal3_team[0] => $goal3_vote[$i]);
    }
    elseif($i <= 4 && $i < 8){
        //2チーム目
        $team_goal3_vote += array($toto_goal3_team[1] => $goal3_vote[$i]);
    }
    elseif($i <= 8 && $i < 12){
        //3チーム目
        $team_goal3_vote += array($toto_goal3_team[2] => $goal3_vote[$i]);
    }
    elseif($i <= 12 && $i < 16){
        //4チーム目
        $team_goal3_vote += array($toto_goal3_team[3] => $goal3_vote[$i]);
    }
    elseif($i <= 16 && $i < 20){
        //5チーム目
        $team_goal3_vote += array($toto_goal3_team[4] => $goal3_vote[$i]);
    }
    else{
        //6チーム目
        $team_goal3_vote += array($toto_goal3_team[5] => $goal3_vote[$i]);
    }
    echo $i."回目"."<br />";
}
*/

//var_dump($team_goal3_vote);

/**
 * toto 結果
 *
 * GOAL3の結果を取得
 * 
 * 拡張
 * totoの結果を取得
 * miniA miniBの結果を取得
 * BiGの結果を取得
 */

/*
//スクレイピング（PHP Simple HTML DOM Parser）
require_once '../library/simple_html_dom.php';


//HTMLの取得
$html = file_get_html( 'http://www.totoone.jp/kekka/' );
//var_dump($html);$
$ret = $html->find('table.kekka-hyou td, text');
var_dump($ret);
$html->clear();
*/

$toto_all_result =array();       //totoの結果を取得配列の初期化
$result_count = 0;           //toto結果取得の為のカウンター

//toto結果取得URL
define('TOTO_RESULT', 'http://www.totoone.jp/kekka/');

//Goutteオブジェクト生成
$client = new Client();

//toto結果HTMLを取得
$crawler = $client->request('GET', TOTO_RESULT);

$crawler->filter('.kekka-hyou tr')->each(function($node) use(&$toto_all_result)
{
        if($node->text() !== NULL || $node->text() === ""){
            echo (string)$node->text() . "<br />";
            //var_dump($node->text());
            $toto_all_result[] =(string)$node->text();
        }
            
});

//空白を除去する
$toto_all_result = array_filter($toto_all_result,"strlen");
//添え字を振り直す
$toto_all_result = array_values($toto_all_result);

//var_dump($toto_all_result);

/*toto結果を種別に格納*/
$toto_goal3_result = array();

//totoGOAL3の結果格納
for($i = 0; $i < count($toto_all_result); $i++) {
    if(preg_match('/.*totoＧＯＡＬ３.*/', $toto_all_result[$i])){
        //totoGOAL3 の結果が見つかった場合
        $toto_goal3_result = array_slice($toto_all_result, $i, 9);
        break;
    }
}

/*totoGOADL2の結果格納*/
/*
   今週金曜日発表なので、土曜日記述する
 * 
 *  */

echo "<br /><br />";

foreach ($toto_goal3_result as $value){
    echo $value . "<br />";
}

// カンマまたは " ", \r, \t, \n , \f などの空白文字で句を分割する。
$goal3_result = preg_split("/[\s,]+/",$toto_goal3_result[2]);


//先頭の”結果”を削除し、結果のみを格納
array_shift($goal3_result);
//空白を除去する
$goal3_result = array_filter($goal3_result,"strlen");
//添え字を振り直す
$goal3_result = array_values($goal3_result);

//結果を配列で格納
//var_dump($goal3_result);

/*toto totominiA totominiB totoGoal3 の場合
　現在使用していない
 *
 */
/*
$toto_result = array_slice($toto_all_result, 0, 10);
//$toto_miniA_result = array_slice($toto_all_result, 10, 8);
//$toto_miniB_result = array_slice($toto_all_result, 18, 8);
//$toto_goal3_result = array_slice($toto_all_result, 26, 9);

//var_dump($toto_goal3_result);

//echo '<br />';

//var_dump($toto_goal3_result[2]);

//$goal = array();
//ゴール結果から空白を取り除く
//$goal = explode(" ", $toto_goal3_result[2]);
//var_dump($goal);

/* 正規表現で点数部分だけ取り出し */
/*
var_dump($toto_goal3_result[2]);


$pattern = '/[0-9] ?[0-9] ?[0-9] ? [0-9] ?[0-9] ?[0-9]/';
preg_match($pattern, $toto_goal3_result[2],$goal);
var_dump($goal);
*/

/*
foreach ($toto_goal3_result as $result){
    echo $result."<br />";
}
*/

/*
for ($i = 0; $i < 100; $i++) {
    $toto_result[] = "あああ";
}

var_dump($toto_result);
foreach ($toto_result as $result){
    echo $result;
}
*/
//$toto_result[] = $crawler->filter('.kekka-hyou table tr:nth-child(-n+7)')->$node;

//カウンターを元に戻す
//$result_count = 0;


//スクレイピング(http_request)
/*
////require_once '../library/Request.php';
//require_once "HTTP/Request.php";

function get_html($url){  
    $rdata = http_request($url);  
    $data = mb_convert_encoding($rdata['data'],"utf-8","auto");  
    return ($data);  
}  
echo '<a href="'.TOTO_RESULT.'"-->'.TOTO_RESULT.'<br>';  
$data = get_html(TOTO_RESULT);  
$html = htmlspecialchars($data);
var_dump($html);
*/
    //xpathを使用したクロール
    //例）ニコニコ動画
    /*
    $url = 'http://www.nicovideo.jp/';
    
    libxml_use_internal_errors(true);
    $doc = new DOMDocument();
    $doc->loadHTML(file_get_contents($url));
    
    $xpath = new DOMXPath($doc);
    
    foreach ($xpath->query('//a') as $node){
        echo $node->textContent . "\n";
    }
    */

/*
 * チーム情報
 *  J1とJ2チームのリーグ情報を取得
 *  */


$j1_ranking =array();           //J1ランキング情報取得配列の初期化
$j1_ranking_koumoku = array();  //J1ランキング情報の項目格納配列の初期化

/*J1ランキングを取得*/
//J1チーム情報取得URL
define('J1_RANKING', 'http://www.jsgoal.jp/ranking/j1.html');

//Goutteオブジェクト生成
$client = new Client();

//J1ランキングのHTMLを取得
$crawler_J1_rank = $client->request('GET', J1_RANKING);

//項目を取得
$crawler_J1_rank->filter('#rankingArea table th')->each(function($node) use(&$j1_ranking_koumoku)
{
        if($node->text() !== NULL && $node->text() !== ""){
            //echo (string)$node->text() . "<br />";
            //var_dump($node->text());
            $j1_ranking_koumoku[] =(string)$node->text();
        }
            
});

//var_dump($j1_ranking_koumoku);

//順位表の取得
$crawler_J1_rank->filter('#rankingArea table tr td')->each(function($node) use(&$j1_ranking)
{
        if($node->text() !== NULL && $node->text() != ""){
            //echo (string)$node->text() . "<br />";
            //var_dump($node->text());
            $j1_ranking[] =(string)$node->text();
        }
            
});

//var_dump($j1_ranking);

/*順位表を分割して格納*/
$j1_rank = array(); //リーグ順位順にチーム情報を格納

//順位表を格納
for($i = 0; $i < count($j1_ranking); $i++) {
    if(preg_match('/^[^\x01-\x7E]+/', $j1_ranking[$i])){
        //チーム名を発見した場合、配列から情報を抜き出す
        $j1_rank[$i][] = array_slice($j1_ranking, $i, 9);
    }
}

/*
 *  DBへ情報を登録
 * 
 *  */




//J2チーム情報取得URL
define('J2_RANKING', 'http://www.jsgoal.jp/ranking/j2.html');

$j2_ranking = array();  //J2ランキング格納用変数

//Goutteオブジェクト生成
$client = new Client();

//J2ランキングのHTMLを取得
$crawler_J2_rank = $client->request('GET', J2_RANKING);

//順位表の取得
$crawler_J2_rank->filter('#rankingArea table tr td')->each(function($node) use(&$j2_ranking)
{
        if($node->text() !== NULL && $node->text() != ""){
            //echo (string)$node->text() . "<br />";
            //var_dump($node->text());
            $j2_ranking[] =(string)$node->text();
        }
            
});

//var_dump($j1_ranking);

/*順位表を分割して格納(J2)*/
$j2_rank = array(); //リーグ順位順にチーム情報を格納

//順位表を格納
for($i = 0; $i < count($j2_ranking); $i++) {
    if(preg_match('/^[^\x01-\x7E]+/', $j2_ranking[$i])){
        //チーム名を発見した場合、配列から情報を抜き出す
        $j2_rank[$i][] = array_slice($j2_ranking, $i, 9);
    }
}


/* 確認用コード */
/*
foreach ($j2_rank as $value){
    var_dump($value);
    echo "<br />";
}
 */

/*
   最近の試合状況
 * 
 *  */
/*全体の取得*/
//今年度の試合結果の情報取得URL
define('RECENTLY_ALL', 'http://www.jsgoal.jp/schedule/2014/j1.html');

$recently_all = array();        //今年度の試合結果を全て格納
$temp_home_array =  array();     //
$temp_away_array = array();     //
$temp_score_array = array();    //

//Goutteオブジェクト生成
$client = new Client();

//今年度試合結果のHTMLを取得
$crawler_recently_all = $client->request('GET', RECENTLY_ALL);

//ホームチームの取得
$crawler_recently_all->filter('#scheduletable td:nth-child(2)')->each(function($node) use(&$temp_home_array)
{
        if($node->text() !== NULL && $node->text() != ""){
            //echo (string)$node->text() . "<br />";
            //var_dump($node->text());
            $temp_home_array[] =(string)$node->text();
        }
            
});

//アウェイチームの取得
$crawler_recently_all->filter('#scheduletable td:nth-child(4)')->each(function($node) use(&$temp_away_array)
{
        if($node->text() !== NULL && $node->text() != ""){
            //echo (string)$node->text() . "<br />";
            //var_dump($node->text());
            $temp_away_array[] =(string)$node->text();
        }
            
});

//スコアの取得
$crawler_recently_all->filter('#scheduletable td:nth-child(3)')->each(function($node) use(&$recently_all)
{
        if($node->text() !== NULL && $node->text() != ""){
            //echo (string)$node->text() . "<br />";
            //var_dump($node->text());
            $temp_score_array[] =(string)$node->text();
        }
            
});



/**各チームサイトから個別に取得**/

/*セレッソ大阪*/
define('RECENTLY_SELLESO', 'http://www.jsgoal.jp/schedule/2014/j1.html');


/*
 * 選手情報
 * 
 *  */

/*得点ランキング*/
//J1得点ランキング情報取得URL
//取得元１　http://www.jsgoal.jp/goalrank/j1.html
//取得元２  http://www.football-lab.jp/summary/player_ranking/j1/?year=2014
define('J1_GOAL_RANKING', 'http://www.jsgoal.jp/goalrank/j1.html');

$j1_goal_ranking = array(); //ゴールランキング格納用

/*アシストランキング ゴールランキング取得
　　取得元２より取得
 *  */
define('J1_ASSIST_RANKING','http://www.football-lab.jp/summary/player_ranking/j1/?year=2014');

$j1_assist_ranking = array();   //アシストランキング格納用

//Goutteオブジェクト生成
$client = new Client();

//アシストランキングのHTMLを取得
$crawler_assist_ranking_all = $client->request('GET', J1_ASSIST_RANKING);

$temp_assist_ranking = array();

//ランキングの取得
$crawler_assist_ranking_all->filter('.halfbox td')->each(function($node) use(&$temp_assist_ranking)
{
        if($node->text() !== NULL && $node->text() != ""){
            //echo (string)$node->text() . "<br />";
            //var_dump($node->text());
            $temp_assist_ranking[] =(string)$node->text();
        }
            
});

//ランキングの格納
for($i = 0;  $i < count($temp_assist_ranking); $i++){
    if($i  < count($temp_assist_ranking) / 2 ){
        if(preg_match('/^[0-9]+位/', $temp_assist_ranking[$i])){
        //ゴール順位を発見した場合、配列から情報を抜き出す
        $j1_goal_ranking[$i][] = array_slice($temp_assist_ranking, $i, 4);
        }
    }
    else{
        if(preg_match('/^[0-9]+位/', $temp_assist_ranking[$i])){
        //アシスト順位を発見した場合、配列から情報を抜き出す
        $j1_assist_ranking[$i][] = array_slice($temp_assist_ranking, $i, 4);
        }
    }
}

//添え字を振り直す
$j1_goal_ranking = array_values($j1_goal_ranking);
$j1_assist_ranking = array_values($j1_assist_ranking);

var_dump($j1_goal_ranking[9]);
var_dump($j1_assist_ranking[9]);

/*出場停止情報**/
define('J1_SUSPENSION', 'http://www.jsgoal.jp/suspension/j1.html');

?>
<!--
    <script src="js/classie.js"></script>
    <script src="js/main.js"></script>
</body>
</html
-->