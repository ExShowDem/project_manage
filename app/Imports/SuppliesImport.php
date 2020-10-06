<?php

namespace App\Imports;

use App\Models\Supplies;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SuppliesImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading
{
    /**
     * @param array $row
     *
     * @return Model|Model[]|null
     */
    public function model(array $row)
    {
        $unit = Unit::firstOrCreate([
            'name' => $row['dvtinh']
        ]);

        return new Supplies([
            'code' => $row['msvt'],
            'name' => $row['tenvt'],
            'unit_id' => isset($unit) ? $unit->id : null,
        ]);
    }

    /**
     * @return int
     */
    public function batchSize(): int
    {
        return 500;
    }

    /**
     * @return int
     */
    public function chunkSize(): int
    {
        return 500;
    }
}
