<?php
use miloschuman\highcharts\Highcharts;

echo '<input type="file" name="file">';

echo Highcharts::widget([
   'options' => [
  'chart' => ['type' => 'column',
              'options3d'=>['enabled'=>true,
                            'alpha'=>45,
                            'beta'=>20,
                            ]  
            ],

  'title' => ['text' => 'Fruit Consumption'],
  'xAxis' => [
     'categories' => ['Apples', 'Bananas', 'Oranges']
  ],
  'yAxis' => [
     'title' => ['text' => 'Fruit eaten']
  ],
  'series' => [
     ['name' => 'Jane', 'data' => [1, 0, 4]],
     ['name' => 'John', 'data' => [5, 7, 3]]
  ]

]
]);





   





 
  