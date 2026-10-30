<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

/**
 * One form for the single site_settings row: values shared by the
 * header, footer and WhatsApp button. Same structure as Filament's
 * own EditProfile page: form() defines the fields, content() renders
 * them with a Save button.
 */
class SiteSettings extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static ?string $navigationLabel = 'Site settings';

    protected static ?int $navigationSort = 10;

    protected static ?string $title = 'Site settings';

    /**
     * Form state (statePath 'data').
     *
     * @var array<string, mixed>|null
     */
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(SiteSetting::current()->attributesToArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Section::make('Contact')
                    ->description('Used in the header, contact page, quote section and WhatsApp button.')
                    ->columns(3)
                    ->components([
                        TextInput::make('phone')
                            ->tel()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->email()
                            ->maxLength(255),
                        TextInput::make('whatsapp_number')
                            ->label('WhatsApp number')
                            ->regex('/^\d{8,15}$/')
                            ->validationMessages(['regex' => 'Digits only, with country code, e.g. 966551116121.'])
                            ->helperText('Digits only, with country code: 966551116121'),
                    ]),
                Section::make('Logos')
                    ->columns(2)
                    ->components([
                        FileUpload::make('logo')
                            ->label('Header logo')
                            ->image()
                            ->disk('public')
                            ->directory('settings')
                            ->maxSize(1024),
                        FileUpload::make('footer_logo')
                            ->label('Footer logo (for the dark background)')
                            ->image()
                            ->disk('public')
                            ->directory('settings')
                            ->maxSize(1024),
                    ]),
                Section::make('Footer')
                    ->columns(2)
                    ->components([
                        TextInput::make('footer_tagline')
                            ->maxLength(255),
                        TextInput::make('footer_tagline_ar')
                            ->label('Footer tagline (Arabic)')
                            ->extraInputAttributes(['dir' => 'rtl', 'lang' => 'ar'])
                            ->maxLength(255),
                        TextInput::make('cr_number')
                            ->label('CR number')
                            ->maxLength(255),
                        TextInput::make('vat_number')
                            ->label('VAT number')
                            ->maxLength(255),
                        TagsInput::make('cities')
                            ->placeholder('Add a city')
                            ->columnSpanFull(),
                        Repeater::make('footer_links')
                            ->label('Footer link columns')
                            ->columnSpanFull()
                            ->grid(2)
                            ->reorderable()
                            ->itemLabel(fn (array $state): ?string => $state['heading'] ?? null)
                            ->addActionLabel('Add column')
                            ->schema([
                                TextInput::make('heading')
                                    ->required()
                                    ->maxLength(50),
                                Repeater::make('links')
                                    ->columns(2)
                                    ->reorderable()
                                    ->addActionLabel('Add link')
                                    ->schema([
                                        TextInput::make('label')
                                            ->required()
                                            ->maxLength(100),
                                        TextInput::make('url')
                                            ->label('URL')
                                            ->required()
                                            ->maxLength(255)
                                            ->helperText('A path like /contact or a full URL'),
                                    ]),
                            ]),
                    ]),
                Section::make('Social links')
                    ->collapsible()
                    ->components([
                        Repeater::make('social_links')
                            ->hiddenLabel()
                            ->columns(2)
                            ->addActionLabel('Add social link')
                            ->schema([
                                Select::make('platform')
                                    ->required()
                                    ->options([
                                        'linkedin' => 'LinkedIn',
                                        'instagram' => 'Instagram',
                                        'x' => 'X',
                                        'youtube' => 'YouTube',
                                        'facebook' => 'Facebook',
                                        'tiktok' => 'TikTok',
                                    ]),
                                TextInput::make('url')
                                    ->label('URL')
                                    ->required()
                                    ->url()
                                    ->maxLength(255),
                            ]),
                    ]),
            ]);
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                Form::make([EmbeddedSchema::make('form')])
                    ->id('form')
                    ->livewireSubmitHandler('save')
                    ->footer([
                        Actions::make([
                            Action::make('save')
                                ->label('Save settings')
                                ->submit('save')
                                ->keyBindings(['mod+s']),
                        ]),
                    ]),
            ]);
    }

    public function save(): void
    {
        // getState() validates the form and returns the cleaned values.
        SiteSetting::current()->update($this->form->getState());

        Notification::make()->title('Settings saved')->success()->send();
    }
}
