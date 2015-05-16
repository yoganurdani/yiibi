<h4> Laporan Komplain </h4>

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
    $bln = date("M",strtotime($value->tanggal));
    $bulan = date("F",strtotime($value->tanggal));
    $tahun = date("Y",strtotime($value->tanggal));
    $tb = $tgl . " " . $bln;
    //print_r($value->tanggal);
    array_push($tanggal, $tb);
    array_push($jumlahKomplain, $value->jumlahKomplain);
    array_push($responKomplain, $value->responKomplain);
    //array_push($keluhan, $value);
}

//echo $form->field($model, 'Complain')->dropDownList($complainList, ['id'=>'Complain-id']);
echo "Sorted By: <br> ";
echo "<br>";
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


echo "<br>";
echo "<br>";

echo Highcharts::widget([
   'options' => [
      'title' => ['text' => 'Tabel Komplain Travelpedia Tahun '. $tahun ],
      'xAxis' => [
         'categories' => $tanggal,
          'title' => ['text' => 'Tanggal' ],
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

echo "<br>Rata-rata jumlah Komplain per hari pada tahun " . $tahun . " adalah sebanyak:<b> ". $avg ."</b> komplain.";
echo "<br> Rata-rata jumlah Komplain yang berhasil direspon Travelpedia per hari pada tahun " . $tahun . " adalah sebanyak:<b> ". $avgRespon ."</b> komplain.";
echo "<br>Rata-rata jumlah Customer Service yang bertugas per hari pada tahun " . $tahun . " adalah sebanyak:<b> ". $avgCS ."</b> orang.";
echo "<br> Rata-rata persentase jumlah Komplain yang berhasil direspon Travelpedia per hari pada tahun " . $tahun . " adalah sebanyak:<b> ". $avgPerRespon ."</b> %.";
echo "<br> Rata-rata persentase jumlah Komplain yang tidak berhasil direspon Travelpedia per hari pada tahun " . $tahun . " adalah sebanyak:<b> ". $avgNoRespon ."</b> %.";
echo "<br> Jumlah Komplain tertinggi pada tahun " . $tahun . " adalah <b> " . $maxKom .  "</b> Komplain."; 
echo "<br> Jumlah Komplain terendah pada tahun " . $tahun . " adalah <b> " . $minKom .  "</b> Komplain."; 
echo "<br> Agar dapat menangani semua respon yang masuk, Idealnya Customer Service yang harus bertugas per hari adalah <b>" . $maxCS .  "</b> orang."; 

?>


   





 
  