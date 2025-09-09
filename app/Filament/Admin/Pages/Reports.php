<?php
// app\Filament\Admin\Pages\Reports.php
namespace App\Filament\Admin\Pages;

use Filament\Pages\Page;
use BackedEnum;  // Import BackedEnum if necessary

class Reports extends Page
{
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-chart-pie';

    protected static string $view = 'filament.admin.pages.reports';

    protected static ?int $navigationSort = 8;
}
