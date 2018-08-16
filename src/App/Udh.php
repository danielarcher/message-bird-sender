<?php 

namespace App;

class Udh
{

    private $length;

    private $iei; 

    private $iedl;

    private $reference;

    private $total;

    private $current;

    public function __construct(int $total, int $current)
    {
        $this->length    = dechex(5);
        $this->iei       = dechex(0);
        $this->iedl      = dechex(3);
        $this->reference = dechex(mt_rand(1, 255));
        $this->total     = dechex($total);
        $this->current   = dechex($current); 
    }

    public function toString()
    {
        $originalValues = [
            $this->length,
            $this->iei,
            $this->iedl,
            $this->reference,
            $this->total,
            $this->current
        ];

        $paddedValues = $this->getPaddedValues($originalValues);

        return implode('', $paddedValues);
    }

    public function getPaddedValues($array)
    {
        return array_map(function($value){
            return str_pad($value, 2, '0', STR_PAD_LEFT);
        }, $array);
    }
}