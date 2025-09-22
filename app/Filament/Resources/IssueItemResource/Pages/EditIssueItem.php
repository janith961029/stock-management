<?php

namespace App\Filament\Resources\IssueItemResource\Pages;

use App\Filament\Resources\IssueItemResource;
use Filament\Actions;
use Filament\Notifications\Notification;

use Filament\Resources\Pages\EditRecord;
use App\Models\SerialNumber;

class EditIssueItem extends EditRecord
{
    protected static string $resource = IssueItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

  protected function afterSave(): void
{
    $data = $this->form->getState();

    if (!empty($data['serial_numbers'])) {
        foreach ($data['serial_numbers'] as $serialData) {
            if (!empty($serialData['id'])) {
                $serial = SerialNumber::find($serialData['id']);
                if ($serial) {
                    $serial->assigned_date = $serialData['assigned_date'] ?? null;
                    $serial->issue_place = $serialData['issue_place'] ?? null;
                    $serial->issuing_type = $serialData['issuing_type'] ?? null;
                    $serial->job_card_number = $serialData['job_card_number'] ?? null;
                    $serial->signal_unit = $serialData['signal_unit'] ?? null;

                    // Toggle OFF/ON update properly
                    $serial->issued = !empty($serialData['issued']) ? 1 : 0;
                    $serial->save();

                    // Notification for not issued
                    if ($serial->issued === 0) {
                        \Filament\Notifications\Notification::make()
                            ->title('Not Issued')
                            ->danger()
                            ->body("Serial number {$serial->serial_number} marked as not issued.")
                            ->send();
                    }
                }
            }
        }
    }
}}