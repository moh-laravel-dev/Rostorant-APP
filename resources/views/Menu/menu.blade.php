{{-- resources/views/Menu/menu.blade.php --}}

@extends('admin.layouts.app')

@section('title', 'قائمة الطعام')

@section('content')
    <div class="restaurant-menu min-h-screen overflow-hidden bg-[#f7f8f5] pb-28 text-gray-800" dir="rtl">
        @include('Menu.components.styles')

        <div id="order-notification" role="status" aria-live="polite"
            style="display:none; position:fixed; top:20px; right:20px;
            z-index:99999; background:#174c3c; color:white;
            padding:16px 22px; border-radius:12px;">
        </div>

        @include('Menu.components.hero')

        <main class="mx-auto max-w-7xl px-4 py-8 sm:px-8 sm:py-10">
            @include('Menu.components.search-stats')
            @include('Menu.components.categories')
            @include('Menu.components.food-grid')
        </main>

        @include('Menu.components.order-bar')
    </div>
    @include('Menu.components.orders-modal')
    @include('Menu.components.scripts')
@endsection
