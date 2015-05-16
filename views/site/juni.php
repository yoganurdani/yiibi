<?php
use miloschuman\highcharts\Highcharts;
use yii\widgets\ActiveField;
use yii\bootstrap\ButtonDropdown;
use app\models\Complain;
use yii\widgets\ActiveForm;


//$keluhan = array();

$tanggal = array();
$bulan = "";
$tahun = "";
$jumlahKomplain = array();
$responKomplain = array();
foreach($data as $oke => $value){
    $tgl = date("j",strtotime($value->tanggal));
    $bulan = date("F",strtotime($value->tanggal));
    $tahun = date("Y",strtotime($value->tanggal));
    //print_r($value->tanggal);
    array_push($tanggal, $tgl);
    array_push($jumlahKomplain, $value->jumlahKomplain);
    array_push($responKomplain, $value->responKomplain);
    //array_push($keluhan, $value);
}

//echo $form->field($model, 'Complain')->dropDownList($complainList, ['id'=>'Complain-id']);
echo "Sorted By: <br> ";
echo "<br>";


echo ButtonDropdown::widget([
    'label' => 'Juni',
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


echo "<br>";
echo "<br>";


echo Highcharts::widget([
   'options' => [
      'title' => ['text' => 'Tabel Keluhan Travelpedia Bulan ' . $bulan . " Tahun " . $tahun],
      'xAxis' => [
         'categories' => $tanggal,
          'title' => ['text' => $bulan ],
      ],
      'yAxis' => [
         'title' => ['text' => 'Jumlah Keluhan'],
          'min' => 0
      ],
      'series' => [
         ['name' => 'Jumlah Komplain', 'data' => $jumlahKomplain, 'color' => red],
         ['name' => 'Respon Komplain', 'data' => $responKomplain, 'color' => blue]
      ]
   ]
]);


echo "Rata-rata jumlah Komplain per hari pada bulan " . $bulan . " " . $tahun . " adalah sebanyak:<b> ". $avgJuni ."</b> komplain.";

echo "<br> Rata-rata jumlah Komplain yang berhasil direspon Travelpedia per hari pada bulan " . $bulan . " " . $tahun . " adalah sebanyak:<b> ". $avgResponJuni ."</b> komplain.";
echo "<br> Rata-rata persentase jumlah Komplain yang berhasil direspon Travelpedia per hari pada  bulan " . $bulan . " " . $tahun . " adalah sebanyak:<b> ". $avgPerResponJuni ."</b> %.";
echo "<br> Rata-rata persentase jumlah Komplain yang tidak berhasil direspon Travelpedia per hari pada  bulan " . $bulan . " " . $tahun . " adalah sebanyak:<b> ". $avgNoResponJuni ."</b> %.";
echo "<br> Jumlah Komplain tertinggi pada  bulan " . $bulan . " " . $tahun . " adalah <b> " . $maxKomJuni .  "</b> Komplain."; 
echo "<br> Jumlah Komplain terendah pada  bulan " . $bulan . " " . $tahun . " adalah <b> " . $minKomJuni . "</b> Komplain."; 
echo "<br> Agar dapat menangani semua respon yang masuk, Idealnya Customer Service yang harus bertugas per hari pada bulan " . $bulan . " " . $tahun . "  adalah <b>" . $maxCSJuni .  "</b> Orang."; 

?>