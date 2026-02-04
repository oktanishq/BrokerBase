<div class="max-w-4xl mx-auto flex flex-col gap-6 pb-24">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <h1 class="text-slate-900 text-2xl md:text-3xl font-black leading-tight tracking-tight">Add New Property</h1>
        <button wire:click="showExitModal" class="text-sm font-medium text-gray-500 hover:text-royal-blue underline decoration-1 underline-offset-2">
            Cancel & Exit
        </button>
    </div>

    <!-- Main Form Container -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden relative">
        <!-- Step Indicator -->
        <div class="bg-white border-b border-gray-100 px-6 py-4">
            <!-- Mobile Step Indicator -->
            <div class="md:hidden flex justify-between items-center mb-2">
                <span class="text-sm font-semibold text-royal-blue">Step <span>{{ $currentStep + 1 }}</span> of 4</span>
                <span class="text-xs text-gray-400">{{ $stepTitles[$currentStep] }}</span>
            </div>
            <div class="md:hidden w-full bg-gray-200 rounded-full h-1.5 mb-2">
                <div class="bg-royal-blue h-1.5 rounded-full transition-all duration-300"
                     style="width: {{ (($currentStep + 1) / 4) * 100 }}%"></div>
            </div>

            <!-- Desktop Step Indicator -->
            <div class="hidden md:flex items-center justify-between w-full relative">
                <div class="absolute top-1/2 left-0 w-full h-0.5 bg-gray-200 -z-10 transform -translate-y-1/2"></div>

                @foreach($steps as $index => $step)
                    <div class="flex flex-col items-center gap-2 bg-white px-2 z-10">
                        <div @class([
                            'flex items-center justify-center size-8 rounded-full ring-4 ring-white shadow-sm',
                            'bg-royal-blue text-white' => $index <= $currentStep,
                            'bg-white border-2 border-gray-300 text-gray-400' => $index > $currentStep,
                        ])>
                            @if($index < $currentStep)
                                <span class="material-symbols-outlined text-sm font-bold">check</span>
                            @else
                                <span class="text-sm font-medium">{{ $index + 1 }}</span>
                            @endif
                        </div>
                        <span @class([
                            'text-xs font-bold uppercase tracking-wider',
                            'text-royal-blue' => $index <= $currentStep,
                            'text-gray-400' => $index > $currentStep
                        ])>
                            {{ $stepTitles[$index] }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Form -->
        <form>
            @csrf

            @include('livewire.admin.properties.createcomp.step-basics')
            @include('livewire.admin.properties.createcomp.step-location')
            @include('livewire.admin.properties.createcomp.step-media')
            @include('livewire.admin.properties.createcomp.step-vault')
        </form>

        <!-- Sticky Bottom Navigation -->
        <div class="sticky bottom-0 bg-white border-t border-gray-200 px-6 py-4 flex items-center justify-between z-20">
            <div class="flex items-center gap-3">
                <button wire:click="saveAsDraft"
                        @if($isSavingDraft) disabled @endif
                        type="button"
                        class="text-sm font-medium text-gray-500 hover:text-gray-700 transition-colors disabled:opacity-50">
                    @if($isSavingDraft)
                        <span class="flex items-center gap-2">
                            <span class="animate-spin rounded-full h-4 w-4 border-b-2 border-gray-500"></span>
                            Saving...
                        </span>
                    @else
                        Save as Draft
                    @endif
                </button>
                @if($draftSaved && $draftSavedAt)
                    <span class="text-xs text-green-600 flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">check_circle</span>
                        Saved {{ $draftSavedAt }}
                    </span>
                @endif
            </div>
            <div class="flex items-center gap-3">
                <button wire:click="previousStep"
                        @if($currentStep <= 0) disabled @endif
                        type="button"
                        class="px-5 py-2.5 rounded-lg border border-gray-300 text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-royal-blue transition-all disabled:opacity-50">
                    Back
                </button>
                @if($currentStep < 3)
                    <button wire:click="nextStep"
                            @if($isProcessingStep) disabled @endif
                            type="button"
                            class="px-5 py-2.5 rounded-lg border border-transparent text-sm font-bold text-white bg-amber-500 hover:bg-amber-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 shadow-md shadow-amber-200 transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                        @if($isProcessingStep)
                            <span class="flex items-center gap-2">
                                <span class="animate-spin rounded-full h-4 w-4 border-b-2 border-white"></span>
                                Processing...
                            </span>
                        @else
                            Next
                        @endif
                    </button>
                @else
                    <button wire:click="publishLive"
                            @if($isPublishing) disabled @endif
                            type="button"
                            class="px-5 py-2.5 rounded-lg border border-transparent text-sm font-bold text-white bg-amber-500 hover:bg-amber-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 shadow-md shadow-amber-200 transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                        @if($isPublishing)
                            <span class="flex items-center gap-2">
                                <span class="animate-spin rounded-full h-4 w-4 border-b-2 border-white"></span>
                                Publishing...
                            </span>
                        @else
                            Publish Live
                        @endif
                    </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Exit Confirmation Modal -->
    @if($showExitModal)
        <div class="fixed inset-0 z-50 overflow-y-auto"
             style="background: rgba(0, 0, 0, 0.5);">

            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative w-full max-w-md bg-white rounded-xl shadow-xl transform transition-all">

                    <div class="p-6">
                        <div class="flex items-center justify-center w-12 h-12 mx-auto bg-amber-100 rounded-full">
                            <span class="material-symbols-outlined text-amber-600">warning</span>
                        </div>

                        <div class="mt-3 text-center">
                            <h3 class="text-lg font-bold text-gray-900">Exit Property Creation?</h3>
                            <p class="mt-2 text-sm text-gray-500">
                                Your progress will be saved as a draft. You can continue later.
                            </p>
                        </div>

                        <div class="mt-6 flex gap-3">
                            <button wire:click="handleExit"
                                    type="button"
                                    class="flex-1 px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600 font-medium transition-colors">
                                Save Draft & Exit
                            </button>
                            <button wire:click="hideExitModal"
                                    type="button"
                                    class="flex-1 px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 font-medium transition-colors">
                                Cancel
                            </button>
                        </div>

                        <button onclick="window.location.href = '{{ route('admin.inventory') }}'"
                                type="button"
                                class="w-full mt-2 px-4 py-2 text-red-600 hover:bg-red-50 rounded-lg font-medium transition-colors">
                            Don't Save & Exit
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>