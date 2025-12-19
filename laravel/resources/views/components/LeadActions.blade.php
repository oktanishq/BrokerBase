@props(['leadId' => null, 'compact' => false])

<div class="flex items-center justify-end gap-2">
    <button class="size-8 rounded-full bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white flex items-center justify-center transition-all" title="Call" onclick="callLead({{ $leadId }})">
        <span class="material-symbols-outlined text-[18px]">call</span>
    </button>
    <button class="size-8 rounded-full bg-green-50 text-green-600 hover:bg-green-600 hover:text-white flex items-center justify-center transition-all" title="WhatsApp" onclick="whatsAppLead({{ $leadId }})">
        <span class="material-symbols-outlined text-[18px]">chat</span>
    </button>
    <button class="size-8 rounded-full bg-gray-100 text-gray-500 hover:bg-gray-600 hover:text-white flex items-center justify-center transition-all" title="Add Note" onclick="addNote({{ $leadId }})">
        <span class="material-symbols-outlined text-[18px]">edit</span>
    </button>
</div>

@push('scripts')
<script>
function callLead(leadId) {
    // TODO: Implement call functionality
    console.log('Calling lead:', leadId);
}

function whatsAppLead(leadId) {
    // TODO: Implement WhatsApp functionality  
    console.log('WhatsApp lead:', leadId);
}

function addNote(leadId) {
    // TODO: Implement add note functionality
    console.log('Adding note for lead:', leadId);
}
</script>
@endpush