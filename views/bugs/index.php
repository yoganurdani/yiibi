<h4> Laporan Bugs </h4>

<?php
use miloschuman\highcharts\Highcharts;
use yii\widgets\ActiveField;
use yii\bootstrap\ButtonDropdown;
use app\models\Bugs;
use yii\widgets\ActiveForm;


//$keluhan = array();
// assume $_GET = ['id' => 123, 'src' => 'google'], current route is "post/view"

echo Yii::$app->controller->getRoute();

//echo $form->field($model, 'Complain')->dropDownList($complainList, ['id'=>'Complain-id']);
echo "Sorted By: <br> ";
echo "<br>";
/*
echo ButtonDropdown::widget([
    'label' => 'Month', 
    'dropdown' => [
        'items' => [
            ['label' => 'All', 'url' => './index'],
            ['label' => 'January', 'url' => './januari'],
            ['label' => 'February', 'url' => './februari'],
            ['label' => 'March', 'url' => './maret'],
            ['label' => 'April', 'url' => './april'],
            ['label' => 'May', 'url' => './mei'],
            ['label' => 'June', 'url' => './juni'],
            ['label' => 'July', 'url' => './juli'],
            ['label' => 'August', 'url' => './agustus'],
            ['label' => 'September', 'url' => './september'],
            ['label' => 'October', 'url' => './oktober'],
            ['label' => 'November', 'url' => './november'],
            ['label' => 'December', 'url' => './desember'],
        ],
    ],
]);
*/
?>

<?php $form = ActiveForm::begin() ?>

<select name="id">
    <option value="All" <?php if ($selector == "All"){echo selected;} ?>>Semua</option>
    <option value="January" <?php if ($selector == "January"){echo selected;} ?>>Januari</option>
    <option value="February" <?php if ($selector == "February"){echo selected;} ?>>Februari</option>
    <option value="March" <?php if ($selector == "March"){echo selected;} ?>>Maret</option>
    <option value="April" <?php if ($selector == "April"){echo selected;} ?>>April</option>
    <option value="May" <?php if ($selector == "May"){echo selected;} ?>>Mei</option>
    <option value="June" <?php if ($selector == "June"){echo selected;} ?>>Juni</option>
    <option value="July" <?php if ($selector == "July"){echo selected;} ?>>Juli</option>
    <option value="August" <?php if ($selector == "August"){echo selected;} ?>>Agustus</option>
    <option value="September" <?php if ($selector == "September"){echo selected;} ?>>September</option>
    <option value="October" <?php if ($selector == "October"){echo selected;} ?>>Oktober</option>
    <option value="November" <?php if ($selector == "November"){echo selected;} ?>>November</option>
    <option value="December" <?php if ($selector == "December"){echo selected;} ?>>Desember</option>
</select>

<button type="submit">Pilih</button>

<?php ActiveForm::end() ?>

<?php
echo "<br>";
echo "<br>";

echo Highcharts::widget([
   'options' => [
       'chart' => ['type' => 'column'],
      'title' => ['text' => 'Tabel Laporan Bug Travelpedia Bulan '. $selector ],
      'xAxis' => [
            'categories' => [
                'Fitur Pencarian Tiket Promo',
                'Fitur Pemesanan Tiket',
                'Fitur Pencarian Tiket',
                'Fitur Tutorial',
                'Fitur Login',
                'Fitur Registrasi',
                'Lain-lain',
            ]
        ],
        'yAxis' => [
            'title' => [
                'text' => 'Jumlah Laporan Bugs'
            ]
        ],
      'series' => [
            ['type' => 'column', 'name' => 'Bugs', 'data' => $tipe[1]]
        ]
   ]
]);


foreach($tipe[1] as $key => $value)
{
    $semuaBugs += $value;
}
$maxBugs = 0;
$maxBugsTipe = "<i>not found</i>";
$minBugs = 1000000;
$minBugsTipe = "<i>not found</i>";
$i = 0;
foreach($tipe[1] as $key => $value)
{
    if($minBugs == $value)
    {
        $minBugs = $value;
        $minBugsTipe = $minBugsTipe.", ".$tipe[0][$i];
    }
    else if($minBugs > $value)
    {
        $minBugs = $value;
        $minBugsTipe = $tipe[0][$i];
    }
    
    if($maxBugs == $value)
    {
        $maxBugs = $value;
        $maxBugsTipe = $maxBugsTipe.", ".$tipe[0][$i];
    }
    else if($maxBugs < $value)
    {
        $maxBugs = $value;
        $maxBugsTipe = $tipe[0][$i];
    }
    $i = $i+1;
    
}

echo "<br> Jumlah laporan bugs <b>Fitur Pencarian Tiket Promo</b> pada bulan ".$selector. " adalah sebanyak: <b>".$tipe[1][0]."</b> bugs";
echo "<br> Jumlah laporan bugs <b>Fitur Pemesanan Tiket</b> pada bulan ".$selector. " adalah sebanyak: <b>".$tipe[1][1]."</b> bugs";
echo "<br> Jumlah laporan bugs <b>Fitur Pencarian Tiket</b> pada bulan ".$selector. " adalah sebanyak: <b>".$tipe[1][2]."</b> bugs";
echo "<br> Jumlah laporan bugs <b>Fitur Tutorial</b> pada bulan ".$selector. " adalah sebanyak: <b>".$tipe[1][3]."</b> bugs";
echo "<br> Jumlah laporan bugs <b>Fitur Login</b> pada bulan ".$selector. " adalah sebanyak: <b>".$tipe[1][4]."</b> bugs";
echo "<br> Jumlah laporan bugs <b>Fitur Registrasi</b> pada bulan ".$selector. " adalah sebanyak: <b>".$tipe[1][5]."</b> bugs";
echo "<br> Jumlah laporan bugs <b>Fitur Lain-lain</b> pada bulan ".$selector. " adalah sebanyak: <b>".$tipe[1][6]."</b> bugs";

echo "<hr> Analisa : ";
echo "<br> Jumlah laporan bugs semua fitur pada bulan ".$selector. " adalah sebanyak: <b>".$semuaBugs. "</b> bugs";
echo "<br> Fitur dengan laporan bugs terkecil adalah (".$minBugsTipe.") dengan <b>".$minBugs."</b> bugs";
echo "<br> Fitur dengan laporan bugs terbesar adalah (".$maxBugsTipe.") dengan <b>".$maxBugs."</b> bugs";
echo "<br> Saran : ";
echo "<br> Fitur yang harus segera ditangani bugs-nya adalah : <b>".$maxBugsTipe."</b> (<i>berdasarkan banyaknya jumlah bugs</i>).";


?>


   





 
  