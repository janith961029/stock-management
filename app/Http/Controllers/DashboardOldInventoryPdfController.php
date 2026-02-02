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
        $this->prepareLongRunningExport();

        $records = $this->buildQuery($request)
            ->toBase()
            ->cursor()
            ->map(function (object $record): array {
                return [
                    'barcode' => $this->cleanPdfValue($this->recordValue($record, 'barcode')),
                    'name' => $this->cleanPdfValue($this->recordValue($record, 'name')),
                    'serial_number' => $this->cleanPdfValue($this->resolveSnNumberValue($record)),
                    'issue_place' => $this->cleanPdfValue($this->recordValue($record, 'issue_place')),
                    'issuing_type' => $this->cleanPdfValue($this->recordValue($record, 'issuing_type_name')),
                    'signal_unit' => $this->cleanPdfValue($this->recordValue($record, 'signal_unit_name')),
                    'issue_date' => $this->cleanPdfValue($this->recordValue($record, 'issue_date')),
                    'warrenty_expiry_date' => $this->cleanPdfValue($this->recordValue($record, 'warrenty_expiry_date')),
                ];
            });

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
            $query->leftJoin('signal_units', function ($join) {
                $join->on(
                    DB::raw('CAST(signal_units.id AS CHAR)'),
                    '=',
                    DB::raw('CAST(quantities.signal_unit AS CHAR)')
                );
            });
        }

        $select = [
            'quantities.id',
            $this->selectColumnOrFallback('quantities.barcode', 'barcode'),
            $this->selectColumnOrFallback('quantities.name', 'name'),
            $this->selectSerialNumberColumn(),
            $this->selectColumnOrFallback('quantities.issue_place', 'issue_place'),
            $this->selectColumnOrFallback('quantities.issue_date', 'issue_date'),
            $this->selectColumnOrFallback('quantities.warrenty_expiry_date', 'warrenty_expiry_date'),
            $this->selectIssuingTypeColumn($hasIssuingTypesTable),
            $this->selectSignalUnitColumn($hasSignalUnitsTable),
        ];

        $query->select($select)
            ->orderBy('quantities.id')
            ->limit($this->resolveLimit($request));

        if ($request->filled('issue_place') && Schema::hasColumn('quantities', 'issue_place')) {
            $decoded = $this->decodeFilterValue($request->input('issue_place'));
            $normalizedKey = $this->getIssuePlaceNormalizationKey($decoded);

            if ($normalizedKey !== null) {
                $variants = $this->getIssuePlaceVariantsByNormalizedKey($normalizedKey);

                if ($variants === []) {
                    $query->whereRaw('1 = 0');
                } else {
                    $query->whereIn('quantities.issue_place', $variants);
                }
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

    protected function prepareLongRunningExport(): void
    {
        if (function_exists('set_time_limit')) {
            @set_time_limit(0);
        }

        @ini_set('max_execution_time', '0');
        @ini_set('memory_limit', '-1');
    }

    protected function resolveLimit(Request $request): int
    {
        $limit = (int) $request->input('limit', 1000);

        if ($limit < 1) {
            return 1000;
        }

        return min($limit, 1000);
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

    protected function selectSerialNumberColumn()
    {
        if (Schema::hasColumn('quantities', 'serial_number')) {
            return DB::raw($this->convertToUtf8Expression('quantities.serial_number') . ' as serial_number');
        }

        if (Schema::hasColumn('quantities', 'sn_number')) {
            return DB::raw($this->convertToUtf8Expression('quantities.sn_number') . ' as serial_number');
        }

        if (Schema::hasColumn('quantities', 'sn_no')) {
            return DB::raw($this->convertToUtf8Expression('quantities.sn_no') . ' as serial_number');
        }

        if (Schema::hasColumn('quantities', 'serial_no')) {
            return DB::raw($this->convertToUtf8Expression('quantities.serial_no') . ' as serial_number');
        }

        return DB::raw("'N/A' as serial_number");
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

    protected function normalizeIssuePlace(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $normalized = (string) $value;

        $normalized = preg_replace('/[\x{200B}-\x{200D}\x{FEFF}]/u', '', $normalized) ?? $normalized;
        $normalized = preg_replace('/\x{00A0}/u', ' ', $normalized) ?? $normalized;
        $normalized = preg_replace('/\s+/u', ' ', $normalized) ?? $normalized;
        $normalized = trim($normalized);

        return $normalized === '' ? null : $normalized;
    }

    protected function getIssuePlaceNormalizationKey(mixed $value): ?string
    {
        $normalized = $this->normalizeIssuePlace($value);

        if ($normalized === null) {
            return null;
        }

        if (function_exists('mb_strtolower')) {
            return mb_strtolower($normalized, 'UTF-8');
        }

        return strtolower($normalized);
    }

    protected function getIssuePlaceVariantsByNormalizedKey(string $normalizedKey): array
    {
        return Quantities::query()
            ->whereNotNull('issue_place')
            ->where('issue_place', '!=', '')
            ->pluck('issue_place')
            ->filter(fn ($place) => $this->getIssuePlaceNormalizationKey($place) === $normalizedKey)
            ->unique()
            ->values()
            ->all();
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

    protected function resolveSnNumberValue(object|array $record): ?string
    {
        $serial = $this->recordValue($record, 'serial_number');

        if ($serial !== null && $serial !== '') {
            return (string) $serial;
        }

        $barcode = (string) ($this->recordValue($record, 'barcode') ?? '');

        if ($barcode !== '' && preg_match('/SN\s*CODE\s*:?\s*(.+)$/i', $barcode, $matches) === 1) {
            $parsed = trim((string) ($matches[1] ?? ''));
            if ($parsed !== '') {
                return $parsed;
            }
        }

        return null;
    }

    protected function recordValue(object|array $record, string $key): mixed
    {
        if (is_array($record)) {
            return $record[$key] ?? null;
        }

        return $record->{$key} ?? null;
    }
}
