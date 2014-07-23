<?php
/**
 * Created by PhpStorm.
 * User: jloosli
 * Date: 7/22/14
 * Time: 4:12 PM
 */

namespace AvantiDevelopment\JrBank;


use League\Fractal\TransformerAbstract;

class BasicTransformer extends TransformerAbstract {

    public function transform($collection) {
        if(is_a($collection, 'Illuminate\Database\Eloquent\Model')) {
            $atts = $collection->getAttributes();
            foreach ($atts as $key=>$value) {
                if('id' === $key) {
                    $collection->$key =  $value;
                }
            }
            $collection->save();
            $theKey = $collection->id;
//            if(isset($collection->id)) {
//                $collection->id = (int) $collection->id;
//            }
        }
        return $collection;
    }
} 