<?php

$path = '/address';

if ($path = '/address')
{
  $controller = new \Controller();
  $return = $controller->exec();
  echo $return;
}

class Controller
{
  protected $events = [];

  function exec()
  {
    $this->records();
    $id = 1;
    $address = $this->events[$id];
    return json_encode($address);
  }

  function records()
  {
    $file = fopen('seeds.csv', 'r');
    while (($line = fgetcsv($file)) !== FALSE) {
        $this->events[] = [
           'event_name'=> $line[0],
            'location' => $line[1],
            'address' => $line[2]
        ];
    }

    fclose($file);
  }
}
?>
