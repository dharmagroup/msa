<?php
namespace App\Library;

class Event{
    public function send($data = [],$event){
        $options = array(
            'cluster' => 'ap1',
            'useTLS' => true
          );
          $pusher = new \Pusher\Pusher(
            '45651947dc7f127ec3e7',
     '014810c48decdc1834dd',
    '1873942',
            $options
          );

          $pusher->trigger('msa', $event, $data);
    }
   
    public function sending($event,$data = []){
        $options = array(
            'cluster' => 'ap1',
            'useTLS' => true
          );
          $pusher = new \Pusher\Pusher(
            '45651947dc7f127ec3e7',
     '014810c48decdc1834dd',
    '1873942',
            $options
          );

          $pusher->trigger('msa', $event, $data);
    }
}

