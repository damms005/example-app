<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Livewire\WithFileUploads;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\FileUpload;

class UploadScoresheet extends Page
{
    use WithFileUploads;

    protected static string $view = 'filament.pages.upload-scoresheet';

    protected static ?string $navigationGroup = 'Academic Result';

    protected static ?int $navigationSort = 2;

    public $file;

    protected function getFormSchema(): array
    {
        return [
            Grid::make(1)
                ->schema([
                    FileUpload::make('file')
                        ->label('Scoresheet')
                        ->acceptedFileTypes([
                            'text/csv',
                            'application/vnd.ms-excel',
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                        ])
                        ->maxSize(1 * 1024)
                        ->directory('score-sheets')
                        ->required(),
                ]),
        ];
    }
}
