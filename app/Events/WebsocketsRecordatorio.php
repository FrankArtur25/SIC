<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;


class WebsocketsRecordatorio implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $data; 
    // public $user; 
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
        // $this->user= $user;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * 
     */
    public function broadcastOn()
    { 
      
        return new PrivateChannel('user.'.$this->data['recordatorios']->idempleado); 
      
    }

      // public function broadcastOn(){
      //   return ['channel-recordatorio'];
      // }
      public function broadcastAs() {
        return 'evento-recordatorio';
      }
    
}
