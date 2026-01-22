<?php

namespace App\Filament\Resources\IssueItemResource\Pages;

use App\Filament\Resources\IssueItemResource;
use App\Models\Serial;
use App\Models\SerialNumbers; // Use SerialNumbers instead of Serial
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditIssueItem extends EditRecord
{
    protected static string $resource = IssueItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

//    protected function afterSave(): void
// {
//     $data = $this->form->getState();

//     if (!empty($data['serial_numbers'])) {
//         foreach ($data['serial_numbers'] as $serialData) {
//             if (!empty($serialData['id'])) {
//                 $serial = SerialNumbers::find($serialData['id']);
//                 if ($serial) {
                    
//                     if (!empty($serialData['issued'])) {
//                         // Only update when issued is true
//                         $serial->assigned_date = $serialData['assigned_date'] ?? null;
//                         $serial->issue_place = $serialData['issue_place'] ?? null;
//                         $serial->issuing_type = $serialData['issuing_type'] ?? null;
//                         $serial->job_card_number = $serialData['job_card_number'] ?? null;
//                         $serial->signal_unit = $serialData['signal_unit'] ?? null;
//                         $serial->issued = 1;
//                         $serial->save();

//                         Notification::make()
//                             ->title('Issued Successfully')
//                             ->success()
//                             ->body("Serial number {$serial->serial_number} has been issued.")
//                             ->send();
//                     } else {
//                         // When not issued, just notify without saving
//                         Notification::make()
//                             ->title('Not Issued')
//                             ->danger()
//                             ->body("Serial number {$serial->serial_number} was not issued - data not saved.")
//                             ->send();
//                     }
//                 }
//             }
//         }
//     }
// }
}