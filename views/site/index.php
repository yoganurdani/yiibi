<?php
use miloschuman\highcharts\Highcharts;

echo '<input type="file" name="file"><br>';

//$keluhan = array();
$abc = array();
$jumlahKomplain = array();
$responKomplain = array();
foreach($data as $oke => $value){
    $tgl = date("j",strtotime($value->tanggal));
    //print_r($value->tanggal);
    array_push($abc, $tgl);
    array_push($jumlahKomplain, $value->jumlahKomplain);
    array_push($responKomplain, $value->responKomplain);
    //array_push($keluhan, $value);
}
//print_r($keluhan);

print_r(date("M"));

echo Highcharts::widget([
   'options' => [
      'title' => ['text' => 'Tabel Keluhan Travelpedia'],
      'xAxis' => [
         'categories' => $abc,
          'title' => ['text' => 'Januari'],
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

?>


   





 
  