{{-- Edit Event Modal --}}
<x-ui.modal id="editEventModal" maxWidth="lg">
    <div class="p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 id="editEventModal-title" class="text-2xl font-bold text-text-main">Modifica Evento</h2>
            <button onclick="hideModal('editEventModal')" aria-label="Chiudi" class="text-text-muted hover:text-text-main transition-colors">
                <span class="material-icons" aria-hidden="true">close</span>
            </button>
        </div>

        <form id="editEventForm" action="" method="POST">
            @csrf
            @method('PUT')

            {{-- Messaggio --}}
            <div class="mb-4">
                <x-forms.textarea label="Messaggio" id="event_message" name="message" placeholder="Inserisci il messaggio dell'evento..."/>
            </div>

            {{-- Data Scadenza --}}
            <div class="mb-4">
                <x-forms.datepicker id="event_expires_at" name="expires_at" label="Data di Scadenza" helpText="Lascia vuoto per rendere l'evento permanente"/>
            </div>

            {{-- Stato Attivo --}}
            <div class="mb-6">
                <x-forms.toggle id="event_is_active" name="is_active" value="1" text="Evento Attivo" checked/>
            </div>

            {{-- Actions --}}
            <div class="flex justify-end gap-3">
                <x-forms.button onclick="hideModal('editEventModal')" variant="outline" size="lg">Annulla</x-forms.button>
                <x-forms.button type="submit" variant="primary" size="lg">Salva</x-forms.button>
            </div>
        </form>
    </div>
</x-ui.modal>
