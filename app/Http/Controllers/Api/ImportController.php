<?php

namespace App\Http\Controllers\Api;

use App\Imports\SuppliesImport;
use App\Models\Item;
use App\Models\Supplies;
use App\Models\Unit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportController extends BaseController
{
    public function importSupplies(Request $request)
    {
        try 
        {
            $projectId = (int) $request->input('projectId');

            set_time_limit(500);

            DB::beginTransaction();

            $reader      = IOFactory::createReader("Xlsx");
            $spreadsheet = $reader->load($request->file('file'));
            $worksheet   = $spreadsheet->getActiveSheet();

            $getCellName  = [];
            $getCellValue = [];
            $findCell     = [];

            $inserted   = 0;
            $duplicated = 0;

            foreach ($worksheet->getRowIterator() as $row) 
            {
                $rowIndex = $row->getRowIndex();

                if ($rowIndex == 1) 
                {
                    array_push($getCellName, 'A'); array_push($getCellValue, $worksheet->getCell('A' . $rowIndex)->getValue());
                    array_push($getCellName, 'B'); array_push($getCellValue, $worksheet->getCell('B' . $rowIndex)->getValue());
                    array_push($getCellName, 'C'); array_push($getCellValue, $worksheet->getCell('C' . $rowIndex)->getValue());

                    $findCell[] = $getCellName[array_search('MSVT', $getCellValue)];
                    $findCell[] = $getCellName[array_search('TENVT', $getCellValue)];
                    $findCell[] = $getCellName[array_search('DVTINH', $getCellValue)];
                }

                if ($findCell[0] && $findCell[1] && $findCell[2] && $rowIndex !== 1) 
                {
                    $unit = Unit::firstOrCreate([
                        'name' => $worksheet->getCell($findCell[2] . $rowIndex)->getValue()
                    ]);

                    $checkSupply = Supplies::where(
                        [
                            ['name',    $worksheet->getCell($findCell[1].$rowIndex)->getValue()],
                            ['unit_id', isset($unit) ? $unit->id : null],
                            ['code',    $worksheet->getCell($findCell[0].$rowIndex)->getValue()]
                        ]
                    )->first();

                    if(is_null($checkSupply)) 
                    {
                        $createdSupply = Supplies::create([
                            'code'       => $worksheet->getCell($findCell[0] . $rowIndex)->getValue(),
                            'name'       => $worksheet->getCell($findCell[1] . $rowIndex)->getValue(),
                            'unit_id'    => isset($unit) ? $unit->id : null,
                            'project_id' => $projectId,
                        ]);

                        DB::table('item_supplies')->insert([
                            [
                                'supply_id'         => $createdSupply->id,
                                'item_id'           => 1,
                                'quantity'          => 0,
                                'unit_price_budget' => 0,
                                'total'             => 0,
                            ],
                        ]);

                        $inserted++;
                    }
                    else
                    {
                        $duplicated++;
                    }
                }
            }

            DB::commit();

            return $this->responseSuccess(['inserted' => $inserted, 'duplicated' => $duplicated]);
        } 
        catch (\Exception $e) 
        {
            Log::error($e->getMessage());

            DB::rollBack();

            return $this->responseError('api.code.common.validate_failed', errorMessage($e));
        }
    }

    public function importItems(Request $request)
    {
        try {
            DB::beginTransaction();

            $reader = IOFactory::createReader("Xlsx");

            $spreadsheet = $reader->load($request->file('file'));
            $worksheet = $spreadsheet->getActiveSheet();
            foreach ($worksheet->getRowIterator() as $key => $row) {
                $rowIndex = $row->getRowIndex();
                if (strpos($worksheet->getCell('A' . $rowIndex), 'HẠNG MỤC : ') !== false) {
                    $itemName = str_replace('HẠNG MỤC : ', '', $worksheet->getCell('A' . $rowIndex));

                    $createdItem = Item::create([
                        'name' => $itemName,
                        'project_id' => $request->currentProjectId,
                        'code' => generate_code_for_model(new Item()).$key,
                        'created_by' => auth()->id(),
                        'created_at' => Carbon::now(),
                    ]);

                    $supplyIndex = $rowIndex + 5;

                    $supplies = [];

                    while ($worksheet->getCell('B' . $supplyIndex)->getValue() !== 'TCVL') {
                        $supplies[] = $worksheet->getCell('B' . $supplyIndex)->getValue();

                        $unit = Unit::firstOrCreate([
                            'name' => $worksheet->getCell('D' . $supplyIndex)->getValue()
                        ]);

                        $checkSupply = Supplies::where([['name',$worksheet->getCell('C' . $supplyIndex)->getValue()],['unit_id',isset($unit) ? $unit->id : null]])->first();

                        if(is_null($checkSupply)) {
                            $createdSupply = Supplies::create([
                                'code' => $worksheet->getCell('B' . $supplyIndex)->getValue(),
                                'name' => $worksheet->getCell('C' . $supplyIndex)->getValue(),
                                'unit_id' => isset($unit) ? $unit->id : null,
                            ]);
                        } else {
                            $createdSupply = $checkSupply;
                        }

                        DB::table('item_supplies')->insert([
                            [
                                'supply_id' => $createdSupply->id,
                                'item_id' => $createdItem->id,
                                'quantity' => (double)$worksheet->getCell('E' . $supplyIndex)->getValue(),
                                'unit_price_budget' => (double)$worksheet->getCell('F' . $supplyIndex)->getValue(),
                                'total' => (double)$worksheet->getCell('G' . $supplyIndex)->getValue(),
                                'is_vlk' => $worksheet->getCell('B' . $supplyIndex)->getValue() === 'VLK',
                            ],
                        ]);

                        $supplyIndex++;
                    }
                }
            }

            DB::commit();

            return $this->responseSuccess();
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            DB::rollBack();

            return $this->responseError('api.code.common.validate_failed');
        }
    }

    public function importSubcontractors(Request $request)
    {
        try {
            DB::beginTransaction();

            $reader = IOFactory::createReader("Xlsx");

            $spreadsheet = $reader->load($request->file('file'));

            $worksheet = $spreadsheet->getActiveSheet();
            $data = array();
            foreach ($worksheet->getRowIterator() as $key=>$row) {
                $rowIndex = $row->getRowIndex();
                if (strpos($worksheet->getCell('C' . $rowIndex), 'LOẠI NHÀ THẦU') !== false) {
                    //
                    $subcontractorIndex = $rowIndex + 2;

                    while ($worksheet->getCell('A' . $subcontractorIndex)->getValue() !== null) {
                        $data[$subcontractorIndex]['name'] = $worksheet->getCell('B' . $subcontractorIndex)->getValue();
                        $data[$subcontractorIndex]['type'] = $worksheet->getCell('C' . $subcontractorIndex)->getValue();
                        $data[$subcontractorIndex]['code'] = $worksheet->getCell('D' . $subcontractorIndex)->getValue();
                        $data[$subcontractorIndex]['tax_code'] = $worksheet->getCell('E' . $subcontractorIndex)->getValue();
                        $data[$subcontractorIndex]['representative'] = $worksheet->getCell('F' . $subcontractorIndex)->getValue();
                        $data[$subcontractorIndex]['bank'] = $worksheet->getCell('G' . $subcontractorIndex)->getValue();
                        $data[$subcontractorIndex]['account_name'] = $worksheet->getCell('H' . $subcontractorIndex)->getValue();
                        $data[$subcontractorIndex]['account_owner'] = $worksheet->getCell('I' . $subcontractorIndex)->getValue();

                        $subcontractorIndex++;
                    }
                }
            }
            $collection = collect($data);
            $dupplicateFile = $collection->unique('name');//check dupplicate in file
            $getContractor = DB::table('subcontractors')->get();
            $arrMerge = $collection->merge($getContractor);
            DB::table('subcontractors')->delete();

            $result = $arrMerge->unique('name');


            $nunmberAdd = (int)$dupplicateFile->count()-(int)$getContractor->count();
            $dupplicate = (int)$dupplicateFile->count()-(int)$nunmberAdd;
            $subdata = array('count'=>$nunmberAdd,'dupplicate'=>$dupplicate);


            $result->each(function ($item, $key) {
                DB::table('subcontractors')->insert((array)$item);
            });
            
            DB::commit();

            return $this->responseSuccess($subdata);
        } 
        catch (\Exception $e) 
        {
            Log::error($e->getMessage());

            DB::rollBack();
            return $this->responseError('api.code.common.validate_failed');
        }
    }

    public function importDevices(Request $request)
    {
        try 
        {
            $projectId = (int) $request->input('projectId');

            DB::beginTransaction();

            $reader      = IOFactory::createReader("Xlsx");
            $spreadsheet = $reader->load($request->file('file'));
            $worksheet   = $spreadsheet->getActiveSheet();
            $data        = array();

            foreach ($worksheet->getRowIterator() as $key => $row) 
            {
                $rowIndex = $row->getRowIndex();

                if (strpos($worksheet->getCell('A' . $rowIndex), 'STT') !== false) 
                {
                    $deviceIndex = $rowIndex + 1;

                    while ($worksheet->getCell('A' . $deviceIndex)->getValue() !== null) 
                    {

                        $data[$deviceIndex]['code']    = $worksheet->getCell('C' . $deviceIndex)->getValue();
                        $data[$deviceIndex]['name']    = $worksheet->getCell('D' . $deviceIndex)->getValue();
                        $data[$deviceIndex]['unit_id'] = $worksheet->getCell('E' . $deviceIndex)->getValue();

                        $deviceIndex++;
                    }
                }
            }

            $collection = collect($data);

            $dupplicateFile = $collection->unique('code'); // Check dupplicate in file
            $getDevice      = DB::table('devices')->get();
            $arrMerge       = $collection->merge($getDevice);
            DB::table('devices')->delete();

            $result = $arrMerge->unique('code');

            $nunmberAdd = (int)$dupplicateFile->count() - (int)$getDevice->count();
            $dupplicate = (int)$dupplicateFile->count() - (int)$nunmberAdd;
            $subdata    = array('count' => $nunmberAdd, 'dupplicate' => $dupplicate);

            $getUnits = DB::table('units')->get();

            $allDevices = $result->map(function ($devies_custom) use ($getUnits) {

                return [
                    'code'    => $devies_custom['code'],
                    'name'    => $devies_custom['name'],
                    'unit_id' => $getUnits->filter(function ($value, $key) use ($devies_custom) {

                        if(strtoupper($value->name) == strtoupper($devies_custom['unit_id']))
                        {
                            return $value->id;
                        }
                    })->pluck('id')->get(0)
                ];
            });

            $allDevices->each(function ($item, $key) use ($projectId) {

                DB::table('devices')->insert([
                    [
                        'code'    => ($item['code']    != '' && $item['code']    !=null) ? $item['code']    : 'null',
                        'name'    => ($item['name']    != '' && $item['name']    !=null) ? $item['name']    : 'null',
                        'unit_id' => ($item['unit_id'] != '' && $item['unit_id'] !=null) ? $item['unit_id'] : 0,
                        'project_id' => $projectId,
                    ],
                ]);
            });

            DB::commit();

            return $this->responseSuccess($subdata);
        } 
        catch (\Exception $e) 
        {
            Log::error($e->getMessage());

            DB::rollBack();

            return $this->responseError('api.code.common.validate_failed', errorMessage($e));
        }
    }
}
