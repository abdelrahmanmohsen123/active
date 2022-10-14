<?php

namespace App\Models;



class Card 
{
    public $items=null;
    public $totalPrice =0;
    public $totalQuantity =0;

    public function __construct($oldCart){
        if($oldCart){
            $this->items = $oldCart->items;
            $this->totalPrice = $oldCart->totalPrice;
            $this->totalQuantity = $oldCart->totalQuantity;

        }


    }

    public function add($item,$id){
        $storedItem = ['Qty' => 0,'price' =>$item->price,'item' =>$item];
        if($this->items){
            if(array_key_exists($id,$this->items)){
                $storedItem  = $this->items[$id];
            }

        }
        $storedItem['Qty']++; 
        $storedItem['price'] = $item->price * $item->quantity; 
        $this->items[$id] = $storedItem ;

        $this->totalPrice += $item->price;
        $this->totalQuantity++;  
    }

}
