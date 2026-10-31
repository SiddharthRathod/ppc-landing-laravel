<?php

namespace App\Filament\Resources\Pages\Schemas;

use App\Filament\Blocks\BlogBlock;
use App\Filament\Blocks\ClientsBlock;
use App\Filament\Blocks\ContactCtaBlock;
use App\Filament\Blocks\ContactFormBlock;
use App\Filament\Blocks\HeroBlock;
use App\Filament\Blocks\IndustriesBlock;
use App\Filament\Blocks\PortfolioBlock;
use App\Filament\Blocks\ProcessBlock;
use App\Filament\Blocks\ServicesBlock;
use App\Filament\Blocks\TestimonialBlock;
use App\Filament\Blocks\WhyBlock;
use App\Filament\Schemas\SeoSection;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class PageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Tabs::make()
                    ->persistTabInQueryString()
                    ->tabs([
                        Tab::make('Sections')
                            ->components([
                                // Each block = one section of the page, rendered by
                                // resources/views/sections/{block name}.blade.php, in this order.
                                Builder::make('content')
                                    ->label('Page sections')
                                    ->blocks([
                                        HeroBlock::make(),
                                        ClientsBlock::make(),
                                        ServicesBlock::make(),
                                        PortfolioBlock::make(),
                                        IndustriesBlock::make(),
                                        ProcessBlock::make(),
                                        WhyBlock::make(),
                                        TestimonialBlock::make(),
                                        BlogBlock::make(),
                                        ContactCtaBlock::make(),
                                        ContactFormBlock::make(),
                                    ])
                                    ->collapsible()
                                    ->collapsed()
                                    ->cloneable()
                                    ->addActionLabel('Add section')
                                    ->blockPickerColumns(2),
                            ]),
                        Tab::make('SEO')
                            ->components([
                                SeoSection::make()->collapsible(false),
                                Section::make('Search engines')
                                    ->components([
                                        Select::make('robots')
                                            ->options([
                                                'index,follow' => 'Show in Google (index, follow)',
                                                'noindex,follow' => 'Hide from Google (noindex, follow)',
                                                'noindex,nofollow' => 'Hide, and don\'t follow links (noindex, nofollow)',
                                            ])
                                            ->default('index,follow')
                                            ->required(),
                                    ]),
                            ]),
                        Tab::make('Settings')
                            ->components([
                                TextInput::make('title')
                                    ->required()
                                    ->maxLength(255)
                                    ->helperText('Name in the admin; also the browser title if Meta title is empty.'),
                                TextInput::make('slug')
                                    ->disabled()
                                    ->helperText('Fixed: the site\'s routes look pages up by this.'),
                                Toggle::make('is_published')
                                    ->label('Published')
                                    ->helperText('Unpublished pages return "404 Not found".'),
                            ]),
                    ]),
            ]);
    }
}
