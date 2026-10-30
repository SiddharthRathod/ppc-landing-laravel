<?php

namespace App\Filament\Resources\Posts\Schemas;

use App\Filament\Schemas\SeoSection;
use App\Models\Post;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        // Two columns of three: content on the left, publishing + SEO on the right.
        return $schema
            ->columns(3)
            ->components([
                Group::make()
                    ->columnSpan(['lg' => 2])
                    ->components([
                        Section::make('Content')
                            ->components([
                                TextInput::make('title')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    // Fill the slug from the title, unless the admin has edited the slug by hand.
                                    ->afterStateUpdated(function (Get $get, Set $set, ?string $old, ?string $state) {
                                        if (($get('slug') ?? '') !== Str::slug($old)) {
                                            return;
                                        }

                                        $set('slug', Str::slug($state));
                                    }),
                                TextInput::make('slug')
                                    ->required()
                                    ->maxLength(255)
                                    ->alphaDash()
                                    ->unique() // ignores the post being edited (Filament v5 default)
                                    ->helperText('The URL: /blog/{slug}'),
                                TextInput::make('category')
                                    ->maxLength(255)
                                    // Suggest categories already used, but allow a new one.
                                    ->datalist(fn () => Post::query()->whereNotNull('category')->distinct()->pluck('category')->all()),
                                Textarea::make('excerpt')
                                    ->rows(3)
                                    ->maxLength(500)
                                    ->helperText('Short summary for the blog cards.'),
                                RichEditor::make('body')
                                    ->fileAttachmentsDisk('public')
                                    ->fileAttachmentsDirectory('posts/attachments'),
                            ]),
                    ]),
                Group::make()
                    ->columnSpan(['lg' => 1])
                    ->components([
                        Section::make('Publishing')
                            ->components([
                                DateTimePicker::make('published_at')
                                    ->label('Publish date')
                                    ->helperText('Empty = draft. A future date schedules the post.'),
                                FileUpload::make('image')
                                    ->label('Card image')
                                    ->image()
                                    ->disk('public')
                                    ->directory('posts')
                                    ->maxSize(2048),
                            ]),
                        SeoSection::make(),
                    ]),
            ]);
    }
}
