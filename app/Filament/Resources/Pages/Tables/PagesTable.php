<?php

namespace App\Filament\Resources\Pages\Tables;

use App\Models\Page;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->description(fn (Page $record): string => $record->url()),
                TextColumn::make('content')
                    ->label('Sections')
                    ->state(fn (Page $record): int => count($record->content ?? [])),
                IconColumn::make('is_published')
                    ->label('Published')
                    ->boolean(),
                TextColumn::make('updated_at')
                    ->label('Last edited')
                    ->since(),
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('view')
                    ->label('View on site')
                    ->icon(Heroicon::OutlinedArrowTopRightOnSquare)
                    ->url(fn (Page $record): string => $record->url())
                    ->openUrlInNewTab(),
            ])
            ->paginated(false);
    }
}
