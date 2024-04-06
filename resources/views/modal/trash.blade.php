<div class="relative z-10 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true" id="TrashModal">
    <div class="fixed inset-0 bg-opacity-75 transition-opacity"></div>
    <div class="fixed inset-0 z-10 overflow-y-auto">
      <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-lg bg-white @if(LaravelLocalization::getCurrentLocale() == 'en') text-left @else text-right @endif shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
          <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4 mb-3">
            <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title">{{__('app.BULKUPLOAD')}}</h3>
            <div class="mt-2 modal-body text-gray-800">
            </div>
          </div>
          <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
            <div class="modal-footer inline-flex justify-center">
                <button type="button" class="hidden rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto subdel" id="trash"> {{__('app.TRASH')}}</button>
            </div>
            <div>
                <button type="button" class="mt-3 inline-flexjustify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto"
                    onclick="$('#TrashModal').addClass('hidden')">
                        {{__('app.CANCEL')}}
                </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
