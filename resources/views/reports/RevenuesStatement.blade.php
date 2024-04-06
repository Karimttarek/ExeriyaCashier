<x-report-layout>

    <!-- Table -->
    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg dark:bg-gray-800 table-responsive">
        {{$dataTable->table([] , true)}}
    </div>
    <!-- Table -->
    @push('script')
        {{$dataTable->scripts()}}
    @endpush
</x-report-layout>
