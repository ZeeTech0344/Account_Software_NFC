<?php

use App\Models\HeadLocation;

function userName(){

    return "irfan";

}


function branches(){
     return  HeadLocation::all();
}

function hblReserveAmount(){
    return 9850;
}


function EasypaisaReserveAmount(){
    return 28733;
}


function LockerReserveAmount(){
    return 556160;
}






