<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\Card;
use Maatwebsite\Excel\Concerns\WithStartRow;

class CardsImport implements ToCollection, WithStartRow
{

    public function startRow(): int
    {
        return 2;
    }
    
    private $category_id;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($category_id)
    {
        $this->category_id = $category_id;

    }

    public function collection(Collection $rows)
    {
        foreach ($rows->toArray() as $row) 
        {

            try {
                $Card = new Card;
                $Card->category_id = $this->category_id;
                $Card->code = $row[0];
                $Card->expiry_date = $row[1];
                $Card->buy_price = $row[2];
                $Card->save();
            } catch (\Throwable $th) {  
                //throw $th;
            }
               
        }
    }
}
