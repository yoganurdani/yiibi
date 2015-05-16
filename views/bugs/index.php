<h4> Laporan Bugs </h4>

<?php
use miloschuman\highcharts\Highcharts;
use yii\widgets\ActiveField;
use yii\bootstrap\ButtonDropdown;
use app\models\Bugs;
use yii\widgets\ActiveForm;


//$keluhan = array();


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

echo "<br>Rata-rata jumlah Komplain per hari pada tahun " . $tahun . " adalah sebanyak:<b> ". $avg ."</b> komplain.";
echo "<br> Rata-rata jumlah Komplain yang berhasil direspon Travelpedia per hari pada tahun " . $tahun . " adalah sebanyak:<b> ". $avgRespon ."</b> komplain.";
echo "<br>Rata-rata jumlah Customer Service yang bertugas per hari pada tahun " . $tahun . " adalah sebanyak:<b> ". $avgCS ."</b> orang.";
echo "<br> Rata-rata persentase jumlah Komplain yang berhasil direspon Travelpedia per hari pada tahun " . $tahun . " adalah sebanyak:<b> ". $avgPerRespon ."</b> %.";
echo "<br> Rata-rata persentase jumlah Komplain yang tidak berhasil direspon Travelpedia per hari pada tahun " . $tahun . " adalah sebanyak:<b> ". $avgNoRespon ."</b> %.";
echo "<br> Jumlah Komplain tertinggi pada tahun " . $tahun . " adalah <b> " . $maxKom .  "</b> Komplain."; 
echo "<br> Jumlah Komplain terendah pada tahun " . $tahun . " adalah <b> " . $minKom .  "</b> Komplain."; 
echo "<br> Agar dapat menangani semua respon yang masuk, Idealnya Customer Service yang harus bertugas per hari adalah <b>" . $maxCS .  "</b> orang."; 

?>


   





 
  