<x-app-layout>

    <div class="py-12">
        <div class="mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            {!! $purchase->renderHtml() !!}
                        </div>
                        <div>
                            {!! $sales->renderHtml() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('script')
    {!! $purchase->renderChartJsLibrary() !!}
    {!! $purchase->renderJs() !!}

    {!! $sales->renderChartJsLibrary() !!}
    {!! $sales->renderJs() !!}
    @endpush
</x-app-layout>
