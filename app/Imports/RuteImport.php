<?php

namespace App\Imports;


use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;



class RuteImport implements ToCollection
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public $collection;

    public function collection(Collection $collection){
        $this->collection = $collection->transform(function($row){
            return [
                'tipe'=>$row[0],
                'shipfrom'=>$row[1],
                'shipto'=>$row[2],
                'harga'=>$row[3],
            ];
        });    
    }

}
