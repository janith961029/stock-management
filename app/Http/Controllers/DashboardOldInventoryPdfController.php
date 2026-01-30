<?php

namespace App\Http\Controllers;

use App\Models\Quantities;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardOldInventoryPdfController extends Controller
{
    public function download(Request $request)
    {
        $records = $this->buildQuery($request)
            ->get()
            ->map(function (Quantities $record): array {
                return [
                    'barcode' => $this->cleanPdfValue($record->barcode ?? null),
                    'name' => $this->cleanPdfValue($record->name ?? null),
                    'serial_number' => $this->cleanPdfValue($record->serial_number ?? null),
                    'issue_place' => $this->cleanPdfValue($record->issue_place ?? null),
                    'issuing_type' => $this->cleanPdfValue($record->issuing_type_name ?? null),
                    'signal_unit' => $this->cleanPdfValue($record->signal_unit_name ?? null),
                    'issue_date' => $this->cleanPdfValue($record->issue_date ?? null),
                    'warrenty_expiry_date' => $this->cleanPdfValue($record->warrenty_expiry_date ?? null),
                ];
            })
            ->all();

        return Pdf::loadView('pdf.dashboard-old-inventory', [
            'records' => $records,
            'logo' => $this->getLogoDataUri(),
            'title' => 'Stroop Management System',
        ])->download('stroop-management-system-old-inventory.pdf');
    }

    protected function buildQuery(Request $request): Builder
    {
        $query = Quantities::query();

        $hasIssuingTypesTable = Schema::hasTable('issuing_types');
        $hasSignalUnitsTable = Schema::hasTable('signal_units');

        if ($hasIssuingTypesTable) {
            $query->leftJoin('issuing_types', 'quantities.issuing_type', '=', 'issuing_types.id');
        }

        if ($hasSignalUnitsTable) {
            $query->leftJoin('signal_units', 'quantities.signal_unit', '=', 'signal_units.id');
        }

        $select = [
            'quantities.id',
            $this->selectColumnOrFallback('quantities.barcode', 'barcode'),
            $this->selectColumnOrFallback('quantities.name', 'name'),
            $this->selectColumnOrFallback('quantities.serial_number', 'serial_number'),
            $this->selectColumnOrFallback('quantities.issue_place', 'issue_place'),
            $this->selectColumnOrFallback('quantities.issue_date', 'issue_date'),
            $this->selectColumnOrFallback('quantities.warrenty_expiry_date', 'warrenty_expiry_date'),
            $this->selectIssuingTypeColumn($hasIssuingTypesTable),
            $this->selectSignalUnitColumn($hasSignalUnitsTable),
        ];

        $query->select($select)
            ->orderByDesc('quantities.id')
            ->limit($this->resolveLimit($request));

        if ($request->filled('issue_place') && Schema::hasColumn('quantities', 'issue_place')) {
            $decoded = $this->decodeFilterValue($request->input('issue_place'));
            if ($decoded !== null) {
                $query->where('quantities.issue_place', $decoded);
            }
        }

        if ($request->filled('issuing_type') && Schema::hasColumn('quantities', 'issuing_type')) {
            $query->where('quantities.issuing_type', $request->input('issuing_type'));
        }

        if ($request->filled('signal_unit') && Schema::hasColumn('quantities', 'signal_unit')) {
            $query->where('quantities.signal_unit', $request->input('signal_unit'));
        }

        return $query;
    }

    protected function resolveLimit(Request $request): int
    {
        $hasFilters = $request->filled('issue_place')
            || $request->filled('issuing_type')
            || $request->filled('signal_unit');

        $default = $hasFilters ? 2000 : 500;
        $limit = (int) $request->input('limit', $default);

        if ($limit < 1) {
            return $default;
        }

        return min($limit, 5000);
    }

    protected function selectColumnOrFallback(string $column, string $alias)
    {
        [$table, $name] = explode('.', $column, 2);

        if (Schema::hasColumn($table, $name)) {
            return DB::raw($this->convertToUtf8Expression($column) . " as {$alias}");
        }

        return DB::raw("'N/A' as {$alias}");
    }

    protected function selectIssuingTypeColumn(bool $hasIssuingTypesTable)
    {
        if (! Schema::hasColumn('quantities', 'issuing_type')) {
            return DB::raw("'N/A' as issuing_type_name");
        }

        if ($hasIssuingTypesTable && Schema::hasColumn('issuing_types', 'issuing_type')) {
            return DB::raw($this->convertToUtf8Expression('COALESCE(issuing_types.issuing_type, quantities.issuing_type)') . ' as issuing_type_name');
        }

        return DB::raw($this->convertToUtf8Expression('quantities.issuing_type') . ' as issuing_type_name');
    }

    protected function selectSignalUnitColumn(bool $hasSignalUnitsTable)
    {
        if (! Schema::hasColumn('quantities', 'signal_unit')) {
            return DB::raw("'N/A' as signal_unit_name");
        }

        if ($hasSignalUnitsTable && Schema::hasColumn('signal_units', 'sig_unit_name')) {
            return DB::raw($this->convertToUtf8Expression('COALESCE(signal_units.sig_unit_name, quantities.signal_unit)') . ' as signal_unit_name');
        }

        return DB::raw($this->convertToUtf8Expression('quantities.signal_unit') . ' as signal_unit_name');
    }

    protected function decodeFilterValue(?string $value): ?string
    {
        if (! $value) {
            return null;
        }

        $decoded = base64_decode($value, true);

        if ($decoded === false || $decoded === '') {
            return null;
        }

        return $decoded;
    }

    protected function convertToUtf8Expression(string $column): string
    {
        return "CONVERT(CAST({$column} AS BINARY) USING utf8mb4)";
    }

    protected function getLogoDataUri(): ?string
    {
        $logoPath = public_path('images/logo.png');

        if (! is_file($logoPath)) {
            return null;
        }

        $contents = file_get_contents($logoPath);

        if ($contents === false) {
            return null;
        }

        return 'data:image/png;base64,' . base64_encode($contents);
    }

    protected function cleanPdfValue(mixed $value): string
    {
        if ($value === null || $value === '') {
            return 'N/A';
        }

        $string = (string) $value;

        if (function_exists('mb_check_encoding') && mb_check_encoding($string, 'UTF-8')) {
            return $string;
        }

        if (function_exists('mb_convert_encoding')) {
            $string = mb_convert_encoding($string, 'UTF-8', 'UTF-8,ISO-8859-1,WINDOWS-1252');
        } elseif (function_exists('iconv')) {
            $converted = @iconv('ISO-8859-1', 'UTF-8//IGNORE', $string);

            if ($converted !== false) {
                $string = $converted;
            }
        }

        if (function_exists('iconv')) {
            $clean = @iconv('UTF-8', 'UTF-8//IGNORE', $string);

            if ($clean !== false) {
                $string = $clean;
            }
        }

        return $string;
    }
}
