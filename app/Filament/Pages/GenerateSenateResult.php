<?php

namespace App\Filament\Pages;

use App\Course;
use App\Department;
use Filament\Pages\Page;
use App\Models\ScoreType;
use Illuminate\Support\Collection;
use Filament\Forms\Components\Grid;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Forms\Contracts\HasForms;
use App\Services\AcademicSessionService;
use Filament\Forms\Concerns\InteractsWithForms;

class GenerateSenateResult extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $view = 'filament.pages.generate-senate-result';

    protected static ?string $navigationGroup = 'Academic Result';

    protected static ?int $navigationSort = 3;

    public $file;
    public ?Collection $courses;
    public array $sessions = [];
    public ?string $session;
    public int $level = 100;
    public int $semester = 1;
    public int $faculty;
    public ?int $department;
    public ?int $scoreType = null;

    public function mount()
    {
        abort_if(empty(Auth::user()->staff_id), 403, 'You are not allowed to access this page.');

        $this->sessions = AcademicSessionService::getAvailableSessions();
        $this->session = (new AcademicSessionService())->getCurrentSession();
        $this->department = Department::first()?->id;
    }

    protected function getFormSchema(): array
    {
        return [
            Grid::make(3)
                ->schema([
                    Select::make('session')
                        ->options(collect($this->sessions)->mapWithKeys(fn ($session) => [$session => $session]))
                        ->reactive()
                        ->required(),
                    Select::make('level')
                        ->options([100 => 100, 200 => 200, 300 => 300, 400 => 400, 500 => 500, 600 => 600])
                        ->required(),
                    Select::make('semester')
                        ->label('Semester')
                        ->options([1 => 1, 2 => 2])
                        ->required(),
                    Select::make('department')
                        ->options(Department::all()->sortBy('name')->mapWithKeys(fn ($department) => [$department->id => $department->name]))
                        ->searchable()
                        ->required(),
                    Select::make('scoreType')
                        ->label('Score Type')
                        ->options(ScoreType::all()->mapWithKeys(fn ($scoreType) => [$scoreType->id => "{$scoreType->name} ($scoreType->description_for_senate_result)"]))
                        ->required(),
                ]),
        ];
    }

    public function isValid()
    {
        return $this->session && $this->level && $this->semester && $this->department && $this->scoreType;
    }

    public function generate()
    {
        $this->validate();

        $this->dispatch(
            're-render',
            department: Department::findOrFail($this->department),
            session: $this->session,
            semester: $this->semester,
            level: $this->level,
            scoreType: ScoreType::findOrFail($this->scoreType)
        )
            ->to('senate-result-format');
    }
}
