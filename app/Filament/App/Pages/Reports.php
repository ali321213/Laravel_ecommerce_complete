<?php
// app\Filament\App\Pages\Reports.php
namespace App\Filament\App\Pages;

use Filament\Pages\Page;
use BackedEnum;

class Reports extends Page
{
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-chart-pie';

    protected static string $view = 'filament.app.pages.reports';

    protected static ?int $navigationSort = 9;
}
