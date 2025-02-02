<?php

namespace FilamentTiptapEditor\Actions;

use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Textarea;
use FilamentTiptapEditor\TiptapEditor;

class SourceAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'filament_tiptap_source';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->modalHeading(__('filament-tiptap-editor::source-modal.heading'))
            ->fillForm(fn ($arguments) => ['source' => $arguments['html']])
            ->form([
                TextArea::make('source')
                    ->label(__('filament-tiptap-editor::source-modal.labels.source'))
                    ->extraAttributes(['class' => 'source_code_editor'])
                    ->autosize(),
            ])
            ->modalWidth('screen')
            ->action(function (TiptapEditor $component, $data) {

                $content = $data['source'] ?? '<p></p>';

                if ($component->shouldSupportBlocks()) {
                    $content = tiptap_converter()->asJSON($content, decoded: true);
                    $content = $component->renderBlockPreviews($content, $component);
                }

                $component->getLivewire()->dispatch(
                    'insert-content',
                    type: 'source',
                    statePath: $component->getStatePath(),
                    source: $content,
                );

                $component->state($content);
            });
    }
}
