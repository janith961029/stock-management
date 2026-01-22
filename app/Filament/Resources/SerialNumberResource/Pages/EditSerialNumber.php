<?php

namespace App\Filament\Resources\SerialNumberResource\Pages;

use App\Filament\Resources\SerialNumberResource;
use App\Models\SerialNumbers;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;

class EditSerialNumber extends EditRecord
{
    protected static string $resource = SerialNumberResource::class;

    public function save(bool $shouldRedirect = true, bool $shouldSendSavedNotification = true): void
    {
        try {
            parent::save($shouldRedirect, $shouldSendSavedNotification);
        } catch (QueryException $exception) {
            if ($this->isDuplicateKeyException($exception)) {
                $duplicate = $this->parseDuplicateKeyDetails($exception);
                $message = $duplicate['message'] ?? 'Duplicate serial number or barcode already exists.';
                $this->addError('serial', $message);
                Notification::make()
                    ->danger()
                    ->title('Duplicate serial or barcode')
                    ->body($message)
                    ->send();
                return;
            }

            throw $exception;
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->ensureUniqueSerials($data);

        return $data;
    }

    protected function afterSave(): void
    {
        Notification::make()
            ->success()
            ->title('Received Items')
            ->send();
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        try {
            return parent::handleRecordUpdate($record, $data);
        } catch (QueryException $exception) {
            if ($this->isDuplicateKeyException($exception)) {
                throw ValidationException::withMessages([
                    'serial' => 'Duplicate serial number or barcode already exists.',
                ]);
            }

            throw $exception;
        }
    }

    private function ensureUniqueSerials(array $data): void
    {
        $serialItems = $data['serial'] ?? [];
        $serialNumbers = [];
        $barcodes = [];
        $ignoreIds = [];

        foreach ($serialItems as $item) {
            $serialNumbers[] = trim((string) ($item['serial_number'] ?? ''));
            $barcodes[] = trim((string) ($item['barcode'] ?? ''));
            if (!empty($item['id'])) {
                $ignoreIds[] = $item['id'];
            }
        }

        $serialNumbers = array_filter($serialNumbers, static fn ($value) => $value !== '');
        $barcodes = array_filter($barcodes, static fn ($value) => $value !== '');

        $duplicateSerials = $this->findDuplicates($serialNumbers);
        if ($duplicateSerials !== []) {
            throw ValidationException::withMessages([
                'serial' => 'Duplicate serial number(s): ' . implode(', ', $duplicateSerials),
            ]);
        }

        $duplicateBarcodes = $this->findDuplicates($barcodes);
        if ($duplicateBarcodes !== []) {
            throw ValidationException::withMessages([
                'serial' => 'Duplicate barcode(s): ' . implode(', ', $duplicateBarcodes),
            ]);
        }

        if ($serialNumbers !== []) {
            $serialQuery = SerialNumbers::query()->whereIn('serial_number', $serialNumbers);
            if ($ignoreIds !== []) {
                $serialQuery->whereNotIn('id', $ignoreIds);
            }
            $existingSerials = $serialQuery->pluck('serial_number')->all();

            if ($existingSerials !== []) {
                throw ValidationException::withMessages([
                    'serial' => 'Serial number already exists: ' . implode(', ', $existingSerials),
                ]);
            }
        }

        if ($barcodes !== []) {
            $barcodeQuery = SerialNumbers::query()->whereIn('barcode', $barcodes);
            if ($ignoreIds !== []) {
                $barcodeQuery->whereNotIn('id', $ignoreIds);
            }
            $existingBarcodes = $barcodeQuery->pluck('barcode')->all();

            if ($existingBarcodes !== []) {
                throw ValidationException::withMessages([
                    'serial' => 'Barcode already exists: ' . implode(', ', $existingBarcodes),
                ]);
            }
        }
    }

    private function findDuplicates(array $values): array
    {
        $counts = array_count_values($values);

        return array_keys(array_filter($counts, static fn ($count) => $count > 1));
    }

    private function isDuplicateKeyException(QueryException $exception): bool
    {
        if ($exception->getCode() !== '23000') {
            return false;
        }

        $message = $exception->getMessage();

        return str_contains($message, 'serial_numbers_serial_number_unique')
            || str_contains($message, 'serial_numbers_barcode_unique');
    }

    private function parseDuplicateKeyDetails(QueryException $exception): array
    {
        $message = $exception->getMessage();
        $value = null;
        $field = null;

        if (preg_match("/Duplicate entry '([^']+)' for key '([^']+)'/i", $message, $matches)) {
            $value = $matches[1];
            $key = $matches[2];

            if (str_contains($key, 'serial_numbers_serial_number_unique')) {
                $field = 'serial number';
            } elseif (str_contains($key, 'serial_numbers_barcode_unique')) {
                $field = 'barcode';
            }
        }

        if ($value !== null && $field !== null) {
            return [
                'message' => ucfirst($field) . " '{$value}' is already registered.",
                'value' => $value,
                'field' => $field,
            ];
        }

        return [];
    }
}
