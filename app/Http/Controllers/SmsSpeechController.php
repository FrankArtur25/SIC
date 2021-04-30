<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SmsSpeech;

class SmsSpeechController extends Controller
{
    public function carteraSpeech($cartera){
        return SmsSpeech::carteraSpeech($cartera);
    }   

    public function etiquetas(){
        return SmsSpeech::etiquetas();
    }
    
    public function insertSpeech(Request $rq){
        return SmsSpeech::insertSpeech($rq);
    }

}
