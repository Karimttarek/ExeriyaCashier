{{-- <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg dark:bg-gray-800"> --}}

<div class="relative overflow-x-auto rounded">
    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-900 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">
                    {{__('app.NAME')}}
                </th>
                <th scope="col" class="px-6 py-3">
                    {{__('app.EMAIL')}}
                </th>
                <th scope="col" class="px-6 py-3">
                    {{__('app.CREATEDAT')}}
                </th>
                <th scope="col" class="px-6 py-3">*</th>
                <th scope="col" class="px-6 py-3">*</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pendingUsers as $user)
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    {{$user->name}}
                </th>
                <td class="px-6 py-4">
                    {{$user->email}}
                </td>
                <td class="px-6 py-4">
                    {{$user->created_at}}
                </td>
                <td class="px-6 py-4">
                    <a href="{{route('User.accept',$user->id)}}" class="bg-blue-100 text-blue-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-blue-400 border border-blue-400">
                    {{__('app.ACCEPT')}}
                </a>
                </td>
                <td class="px-6 py-4">
                    <a href="{{route('User.refuse',$user->id)}}" class="bg-red-100 text-red-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-red-400 border border-red-400">
                        {{__('app.REFUSE')}}
                    </a>
                </td>
            </tr>
            @empty
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{__('app.NODATAAVAILABE')}}
                    </th>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- </div> --}}

