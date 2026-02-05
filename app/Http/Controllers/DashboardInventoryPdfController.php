<?php

namespace App\Http\Controllers;

use App\Models\SerialNumbers;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardInventoryPdfController extends Controller
{
    public function download(Request $request)
    {
        $records = $this->buildQuery($request)
            ->get()
            ->map(function (SerialNumbers $record): array {
                return [
                    'title_name' => $this->cleanPdfValue($record->title_name),
                    'model_name' => $this->cleanPdfValue($record->model_name),
                    'serial_number' => $this->cleanPdfValue($record->serial_number),
                    'barcode' => $this->cleanPdfValue($record->barcode),
                    'issued' => ((int) $record->issued === 1),
                ];
            })
            ->all();

        return Pdf::loadView('pdf.dashboard-inventory', [
            'records' => $records,
            'logo' => $this->getLogoDataUri(),
            'title' => 'Stroop Management System',
        ])->download('stroop-management-system-inventory.pdf');
    }

    protected function buildQuery(Request $request): Builder
    {
        $serialJoinColumn = $this->resolveSerialJoinColumn();
        $reciveItemsJoinColumn = $this->resolveReciveItemsJoinColumn();

        $query = SerialNumbers::query()
            ->leftJoin('recive_items', $serialJoinColumn, '=', 'recive_items.id')
            ->leftJoin('items', $reciveItemsJoinColumn, '=', 'items.id')
            ->leftJoin('model_names', 'recive_items.model_name', '=', 'model_names.id')
            ->select([
                'serial_numbers.id',
                'serial_numbers.issued',
                DB::raw($this->convertToUtf8Expression('serial_numbers.serial_number') . ' as serial_number'),
                DB::raw($this->convertToUtf8Expression('serial_numbers.barcode') . ' as barcode'),
                DB::raw($this->convertToUtf8Expression('model_names.model_names') . ' as model_name'),
                DB::raw($this->convertToUtf8Expression('items.title_names_id') . ' as title_name'),
            ])
            ->orderByDesc('serial_numbers.id');

        if ($request->filled('title') && Schema::hasColumn('items', 'title_names_id')) {
            $decoded = $this->decodeTitleFilterValue($request->input('title'));
            if ($decoded !== null) {
                $query->where('items.title_names_id', $decoded);
            }
        }

        if ($request->filled('model') && Schema::hasColumn('recive_items', 'model_name')) {
            $decoded = $this->decodeModelNameFilterValue($request->input('model'));
            if ($decoded !== null) {
                $query->where('recive_items.model_name', $decoded);
            }
        }

        $status = $request->input('status');
        if ($status === 'issued') {
            $query->where('serial_numbers.issued', 1);
        } elseif ($status === 'available') {
            $query->where(function (Builder $query): Builder {
                return $query
                    ->whereNull('serial_numbers.issued')
                    ->orWhere('serial_numbers.issued', '!=', 1);
            });
        }

        return $query;
    }

    protected function resolveSerialJoinColumn(): string
    {
        if (Schema::hasColumn('serial_numbers', 'serial_id')) {
            return 'serial_numbers.serial_id';
        }

        if (Schema::hasColumn('serial_numbers', 'recive_items_id')) {
            return 'serial_numbers.recive_items_id';
        }

        return 'serial_numbers.items_id';
    }

    protected function resolveReciveItemsJoinColumn(): string
    {
        if (Schema::hasColumn('recive_items', 'recive_items_id')) {
            return 'recive_items.recive_items_id';
        }

        return 'recive_items.items_id';
    }

    protected function decodeModelNameFilterValue(?string $value): ?string
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

    protected function decodeTitleFilterValue(?string $value): ?string
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
