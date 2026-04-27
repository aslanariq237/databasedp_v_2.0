<?php

namespace App\Imports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Support\Facades\Log;

class CustomersImport implements ToModel, WithHeadingRow, WithValidation
{
    use Importable;

    public $inserted = 0;
    public $skipped = 0;
    public $details = [];

    public function model(array $row)
    {
        Log::info('Excel Row Data:', $row);

        // Normalisasi key menjadi lowercase dan trim
        $normalizedRow = [];
        foreach ($row as $key => $value) {
            $normalizedKey = strtolower(trim($key));
            $normalizedRow[$normalizedKey] = is_string($value) ? trim($value) : $value;
        }

        if (empty($normalizedRow['code'])) {
            $this->details[] = "Baris dilewati: code kosong atau tidak ada";
            $this->skipped++;
            return null;
        }

        $code = $normalizedRow['code'];
        $name = $normalizedRow['name'];

        // Cek duplikat berdasarkan code
        if (Customer::where('code', $code)->exists()) {
            $this->details[] = "Skipped: code '{$code}-{$name}' sudah ada di database";
            $this->skipped++;
            return null;
        }

        $this->inserted++;
        $this->details[] = "Inserted: code '{$code}-{$name}' berhasil ditambahkan";

        return new Customer([
            'name'     => $normalizedRow['name'] ?? null,
            'code'     => $code,
            'company'  => $normalizedRow['company'] ?? null,
            'category' => $normalizedRow['category'] ?? null,
            'pic'      => $normalizedRow['pic'] ?? null,
            'telp'     => $normalizedRow['telp'] ?? null,
            'alamat'   => $normalizedRow['alamat'] ?? null,
        ]);
    }

    public function rules(): array
    {
        return [
            '*.name' => 'required|string|max:255',
            '*.code' => 'required|string|max:50',
            '*.company' => 'nullable|string|max:255',
            '*.category' => 'nullable|max:100',
            '*.pic' => 'nullable|string|max:100',
            '*.telp' => 'nullable|max:20',
            '*.alamat' => 'nullable|string|max:500',
        ];
    }
}