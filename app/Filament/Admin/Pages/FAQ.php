<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Page;
use BackedEnum;

class FAQ extends Page
{
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-question-mark-circle';
    
    // Remove static here:
    protected string $view = 'filament.admin.pages.f-a-q';

    protected static ?int $navigationSort = 5;
}
