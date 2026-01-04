/**
 * Admin Events Management
 */

function openCreateModal() {
    // Reset form
    const form = document.getElementById('editEventForm');
    form.action = '/admin/events';
    form.querySelector('input[name="_method"]').value = 'POST';
    
    // Clear fields
    document.getElementById('event_message').value = '';
    document.getElementById('event_expires_at').value = '';
    document.getElementById('event_is_active').checked = true;
    
    // Update modal title
    document.getElementById('editEventModal-title').textContent = 'Crea Nuovo Evento';
    
    // Show modal
    showModal('editEventModal');
}

function openEditModal(id, message, expiresAt, isActive) {
    // Set form for update
    const form = document.getElementById('editEventForm');
    form.action = `/admin/events/${id}`;
    form.querySelector('input[name="_method"]').value = 'PUT';

    // Populate fields
    document.getElementById('event_message').value = message;
    document.getElementById('event_expires_at').value = expiresAt || '';
    document.getElementById('event_is_active').checked = isActive;
    
    // Update modal title
    document.getElementById('editEventModal-title').textContent = 'Modifica Evento';

    // Show modal
    showModal('editEventModal');
}
