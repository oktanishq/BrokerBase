@extends('layouts.public')

@section('title', 'BrokerBase - Dealer Homepage')

@section('content')
<?php
use App\Models\Property;
$featuredProperties = Property::where('is_featured', true)
    ->where('status', 'available')
    ->latest()
    ->take(6)
    ->get();
?>
<?php $settings = \App\Models\Setting::first()?->toArray() ?? []; ?>

<livewire:public.hero />
<x-public.Collection />
<x-public.featured-section :properties="$featuredProperties" :settings="$settings" />
<x-public.available />
<x-public.contact-section :settings="$settings" />
@endsection
