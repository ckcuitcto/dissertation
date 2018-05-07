<?php

/**
 * Created by PhpStorm.
 * User: huynh
 * Date: 15-Apr-18
 * Time: 1:55 PM
 */
namespace App\Helpers;

class Convert
{

    public static function numberToRomanRepresentation($number) {
        $map = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
        $returnValue = '';
        while ($number > 0) {
            foreach ($map as $roman => $int) {
                if($number >= $int) {
                    $number -= $int;
                    $returnValue .= $roman;
                    break;
                }
            }
        }
        return $returnValue;
    }

    private function checkExtension($fileExtensions)
    {
        $arr = array('jpg', 'png', 'jpeg', 'JPG', 'PNG', 'JPEG');
        //$fileExtensions = $this->convert_vi_to_en($fileExtensions);
        if (in_array($fileExtensions, $arr)) {
            return true;
        }
        return false;
    }
}