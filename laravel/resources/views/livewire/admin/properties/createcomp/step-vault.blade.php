<!-- Step 4: Private Vault -->
<div class="p-6 md:p-8 space-y-6" @if($currentStep !== 3) style="display: none;" @endif>
    <div class="flex items-center gap-2 mb-2">
        <div class="size-6 rounded-full bg-amber-100 text-amber-700 flex items-center justify-center text-xs font-bold">
            <span class="material-symbols-outlined text-xs">lock</span>
        </div>
        <h3 class="text-lg font-bold text-slate-900">Private Vault <span class="text-sm font-normal text-gray-500 ml-2">(Visible only to you)</span></h3>
    </div>

    <div class="bg-amber-50 border border-amber-100 rounded-xl p-6 space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-2">
                <label class="block text-sm font-bold text-gray-800">Owner Name</label>
                <input wire:model="ownerName"
                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 py-2.5 px-4 text-sm"
                       type="text">
            </div>
            <div class="space-y-2">
                <label class="block text-sm font-bold text-gray-800">Owner Phone</label>
                <input wire:model="ownerPhone"
                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 py-2.5 px-4 text-sm"
                       type="text">
            </div>
        </div>

        <div class="space-y-2">
            <label class="block text-sm font-bold text-gray-800">Net Price (Bottom Line)</label>
            <div class="relative rounded-md shadow-sm max-w-xs">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                    <span class="text-gray-500 sm:text-sm">₹</span>
                </div>
                <input wire:model="netPrice"
                       class="block w-full rounded-lg border-gray-300 pl-7 py-2.5 pr-12 focus:border-amber-500 focus:ring-amber-500 sm:text-sm"
                       placeholder="0.00"
                       type="number"
                       step="0.01">
            </div>
        </div>

        <div class="space-y-2">
            <label class="block text-sm font-bold text-gray-800">Private Notes</label>
            <textarea wire:model="privateNotes"
                      class="w-full rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 py-2.5 px-4 text-sm bg-white"
                      placeholder="Negotiation details, access codes, etc."
                      rows="3"></textarea>
        </div>
    </div>
</div>