<x-app-layout>

    <x-slot name="header">
        <x-header heading="Ticket Produkt bearbeiten">
            <x-slot name="beforeHeading">
                <a href="{{ route('tickets.products.show', ['product' => $product]) }}" class="opacity-50">
                    <x-icon name="chevron-left" />
                </a>
            </x-slot>
        </x-header>

    </x-slot>
    <x-body>
        <x-body-box>
            <livewire:ticket-admin-form :product="$product" :editable="true" />
        </x-body-box>

    </x-body>
</x-app-layout>
