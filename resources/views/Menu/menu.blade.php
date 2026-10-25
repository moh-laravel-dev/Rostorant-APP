{{-- resources/views/Menu/menu.blade.php --}}

@extends('admin.layouts.app')

@section('title', 'قائمة الطعام')

@section('content')
    <div class="restaurant-menu min-h-screen overflow-hidden bg-[var(--restaurant-color-hex-f7f8f5)] pb-28 text-gray-800"
        dir="rtl">
        @include('Menu.components.styles')

        <div id="order-notification" role="status" aria-live="polite"
            style="display:none; position:fixed; top:20px; right:20px;
            z-index:99999; background:var(--restaurant-color-hex-174c3c); color:white;
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const billNumber = @json($billNumber);

            if (!billNumber || typeof Echo === 'undefined') {
                return;
            }

            Echo.channel(`bill.${billNumber}`)
                .listen('.user.bill.end', function(event) {
                    if (String(event.billNumber) !== String(billNumber)) {
                        return;
                    }

                    // Clear application storage
                    // localStorage.clear();
                    sessionStorage.clear();

                    // Replace current history entry with a fresh URL
                    const url = new URL(window.location.href);
                    url.searchParams.set('_refresh', Date.now().toString());

                    window.location.replace(url.toString());
                });
        });
    </script>
@endsection
