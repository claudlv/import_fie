<?php

namespace App\Import;
use App\Product;

class BrokenFileImport
{

    /**
     * @var int
     */
    private $existItems = 0;

    /**
     * @param $file
     * @return array
     */
    public function ImportProductToDB($file)
    {
        $collection = fastexcel()->withoutHeaders()->import($file);
        $collection->shift();
        $allItems = $collection->count();
        $first = $collection->take(5609);            /* 5609*/
        $data1 = $first->values()->map(function ($line) {
            if (!empty($line['0'])) {
                $line['1'] = $line['0'] . '>' . $line['1'];
            }
            return [
                'section' => $line['1'],
                'category' => $line['2'],
                'manufacturer' => $line['3'],
                'name' => $line['4'],
                'model' => trim($line['5']),
                'description' => $line['6'],
                'price' => $line['7'],
                'warranty' => $line['8'],
                'stock' => $line['9']
            ];
        });
        $second = $collection->slice(5610);
        $data2 = $second->values()->map(function ($line) {
            if (!empty($line['1']) && !empty($line['0'])) {
                $line['2'] = $line['0'] . '>' . $line['1'] . '>' . $line['2'];
            }
            if (!empty($line['1']) && empty($line['0'])) {
                $line['2'] = $line['1'] . '>' . $line['2'];
            }
            if (empty($line['1']) && !empty($line['0'])) {
                $line['2'] = $line['0'] . '>' . $line['2'];
            }
            return [
                'section' => $line['2'],
                'category' => $line['3'],
                'manufacturer' => $line['4'],
                'name' => $line['5'],
                'model' => trim($line['6']),
                'description' => $line['7'],
                'price' => $line['8'],
                'warranty' => $line['9'],
                'stock' => $line['10']
            ];
        });
        $merge = $data2->merge($data1);
        $unique = $merge->unique('model');
        $duplicateItems = $allItems - $unique->count();
        $products = Product::pluck('model')->toArray();
        $unique->chunk(400)
            ->each(function ($list) use ($products) {
                $data = $list->filter(function ($item) use ($products) {
                    if (!in_array($item['model'], $products)) {
                        return true;
                    } else {
                        $this->existItems += 1;
                    }
                });
                Product::insert($data->toArray());

            });
        $existItems = $this->existItems;
        $savedItems = $unique->count() - $existItems;
        return ['all' => $allItems, 'duplicate' => $duplicateItems, 'exist' => $existItems, 'saved' => $savedItems];
    }


}
