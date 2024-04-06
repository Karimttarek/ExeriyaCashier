<div class="relative z-10 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true" id="bulkUploadModal">
    <div class="fixed inset-0 bg-opacity-75 transition-opacity"></div>
    <div class="fixed inset-0 z-10 overflow-y-auto">
      <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-lg bg-white @if(LaravelLocalization::getCurrentLocale() == 'en') text-left @else text-right @endif shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
          <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4 mb-3">
            <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title">{{__('app.BULKUPLOAD')}}</h3>
            <div class="mt-2">
                <p class="text-sm text-gray-500 mb-5">ProductsBulkTemplate_{{LaravelLocalization::getCurrentLocale()}}.xlsx.
                    <a href="{{URL::asset('examples/ProductsBulkTemplate_'.LaravelLocalization::getCurrentLocale().'.xlsx')}}" download="ProductsBulkTemplate_{{LaravelLocalization::getCurrentLocale()}}" class="text-blue-500 hover:underline">
                        {{__('app.DOWNLOAD')}}
                    </a>
                </p>
                <div class="bordered">
                    <input type="file" name="file" class="@error('file') is-invalid @enderror" id="file" multiple>
                </div>
                <p class="text-sm text-gray-500">*{{__('app.FILLANDSUBMIT')}}.</p>
            </div>
          </div>
          <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
            <button type="button" class="mt-3 inline-flex justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto"
             onclick="$('#bulkUploadModal').addClass('hidden')">
                {{__('app.CANCEL')}}
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>

