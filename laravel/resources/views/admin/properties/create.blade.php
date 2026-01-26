@extends('layouts.admin')

@section('title', 'Add New Property - BrokerBase')

@section('head')
<style>
    /* Custom scrollbar */
    ::-webkit-scrollbar {
        width: 6px;
    }
    ::-webkit-scrollbar-track {
        background: transparent;
    }
    ::-webkit-scrollbar-thumb {
        background-color: #cbd5e1;
        border-radius: 20px;
    }
</style>
@endsection

@section('header-content')
<nav aria-label="Breadcrumb" class="hidden sm:flex">
    <ol class="inline-flex items-center space-x-1 md:space-x-2 text-sm text-gray-500">
        <li class="inline-flex items-center">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-royal-blue transition-colors">Home</a>
        </li>
        <li>
            <div class="flex items-center">
                <span class="material-symbols-outlined text-[16px] text-gray-400">chevron_right</span>
                <a href="{{ route('admin.inventory') }}" class="ml-1 hover:text-royal-blue transition-colors">Properties</a>
            </div>
        </li>
        <li>
            <div class="flex items-center">
                <span class="material-symbols-outlined text-[16px] text-gray-400">chevron_right</span>
                <span class="ml-1 font-medium text-gray-700">Add New</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('content')
@livewire('admin.properties.create-property')
@endsection