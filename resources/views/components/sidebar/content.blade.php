<x-perfect-scrollbar
    as="nav"
    aria-label="main"
    class="flex flex-col flex-1 gap-4 px-3"
>

    {{-- <x-sidebar.link
        title="home"
        href="{{ route('home') }}"
        :isActive="request()->routeIs('home')"
    >
        <x-slot name="icon">
            <x-icons.dashboard class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
        </x-slot>
    </x-sidebar.link> --}}

    <!-- Main UL -->
    <x-sidebar.dropdown
        title="{{__('app.ADDONS')}}"
        :active="Str::contains(request()->route()->uri(), ['product','clients','manufactur','revenues','expenses','users'])"
    >

        <x-sidebar.sublink
            title="{{__('app.PRODUCTS')}}"
            href="{{ route('Product.get') }}"
            :active="Str::contains(request()->route()->uri(), 'product')"
        />
        <x-sidebar.sublink
            title="{{__('app.CLIENTS')}}"
            href="{{ route('Client.get') }}"
            :active="request()->routeIs('Client.get')"
        />
         <x-sidebar.sublink
            title="{{__('app.EXPENSES')}}"
            href="{{ route('Expenses.get') }}"
            :active="request()->routeIs('Expenses.get')"
        />
        {{-- <x-sidebar.sublink
            title="{{__('app.REVENUES')}}"
            href="{{ route('Revenues.get') }}"
            :active="request()->routeIs('Revenues.get')"
        />
        <x-sidebar.sublink
            title="{{__('app.MANUFACTUR')}}"
            href="{{ route('Manufactur.get') }}"
            :active="request()->routeIs('Manufactur.get')"
        /> --}}
        <x-sidebar.sublink
            title="{{__('app.USERS')}}"
            href="{{ route('User.get') }}"
            :active="request()->routeIs('User.get')"
        />
    </x-sidebar.dropdown>
    <!-- Main UL -->

    {{-- <div x-transition x-show="isSidebarOpen || isSidebarHovered" class="text-sm text-gray-500">
        {{__('app.Point Of Sales')}}
    </div> --}}
    <!-- POS -->
    <x-sidebar.dropdown
        title="{{__('app.POS')}}"
        :active="Str::contains(request()->route()->uri(), ['pos/create','pos/return'])"
    >

        <x-sidebar.sublink
            title="{{__('app.POS')}}"
            href="{{ route('POS.index') }}"
            :active="request()->routeIs('POS.*')"
        />

        <x-sidebar.sublink
            title="{{__('app.POSRETURN')}}"
            href="{{ route('POS.return.index') }}"
            :active="request()->routeIs('POS.*')"
        />

    </x-sidebar.dropdown>

    <x-sidebar.link title="{{__('app.Stocktaking')}}" href="{{route('stocktaking.index')}}" />

    <!-- -->

    {{-- <div x-transition x-show="isSidebarOpen || isSidebarHovered" class="text-sm text-gray-500">
        {{__('app.Transaction')}}
    </div> --}}
     <!-- Transaction UL -->
    <x-sidebar.dropdown
        title="{{__('app.Transaction')}}"
        :active="Str::contains(request()->route()->uri(), ['invoices/purchase' ,'invoices/sales','receipts/voucher','receipts/cash','finances/expenses','finances/revenues'])"
    >
    <!-- Purchase UL -->
    <x-sidebar.dropdown
        title="{{__('app.PURCHASES')}}"
        :active="request()->routeIs('Pur.*')"
    >

        <x-sidebar.sublink
            title="{{__('app.PURCHASESINVOICES')}}"
            href="{{ route('Pur.get') }}"
            :active="request()->routeIs('Pur.*')"
        />
        <x-sidebar.sublink
            title="{{__('app.PURRETURN')}}"
            href="{{ route('PurReturn.get') }}"
            :active="request()->routeIs('PurReturn.*')"
        />
        {{-- <x-sidebar.sublink
            title="{{__('app.ITEMSANALYSIS')}}"
            href="{{ route('PurAnalysisRep.get') }}"
            :active="request()->routeIs('PurAnalysisRep.get')"
        /> --}}
    </x-sidebar.dropdown>
    <!-- Purchase UL -->

    <!-- Sales UL -->
    <x-sidebar.dropdown
        title="{{__('app.SALES')}}"
        :active="request()->routeIs('Sales.*')"
    >

        <x-sidebar.sublink
            title="{{__('app.SALESINVOICES')}}"
            href="{{ route('Sales.get') }}"
            :active="request()->routeIs('Sales.get')"
        />
        <x-sidebar.sublink
            title="{{__('app.SALESRETURN')}}"
            href="{{ route('SalesReturn.get') }}"
            :active="request()->routeIs('SalesReturn.get')"
        />
        {{-- <x-sidebar.sublink
            title="{{__('app.ITEMSANALYSIS')}}"
            href="{{ route('SalesAnalysisRep.get') }}"
            :active="request()->routeIs('SalesAnalysisRep.get')"
        /> --}}
    </x-sidebar.dropdown>
    <!-- Sales UL -->


    <!-- receipts UL -->
    <x-sidebar.dropdown
        title="{{__('app.RECEIPTS')}}"
        :active="Str::contains(request()->route()->uri(), 'receipts')"
    >


        <x-sidebar.sublink
            title="{{__('app.VOUCHERRECEIPT')}}"
            href="{{ route('Voucher.get') }}"
            :active="request()->routeIs('Voucher.get')"
        />
        <x-sidebar.sublink
            title="{{__('app.CASHRECEIPT')}}"
            href="{{ route('Cash.get') }}"
            :active="request()->routeIs('Cash.get')"
        />
    </x-sidebar.dropdown>
    <!-- receipts UL -->

        <!-- Finances UL -->
        <x-sidebar.dropdown
            title="{{__('app.Finances')}}"
            :active="Str::contains(request()->route()->uri(), 'finances')"
        >

            <x-sidebar.sublink
                title="{{__('app.EXPENSES')}}"
                href="{{ route('Finances.Expenses.get') }}"
                :active="request()->routeIs('Expenses.get')"
            />
            {{-- <x-sidebar.sublink
                title="{{__('app.REVENUES')}}"
                href="{{ route('Finances.Revenues.get') }}"
                :active="request()->routeIs('Finances.Revenues.get')"
            /> --}}
        </x-sidebar.dropdown>
        <!-- Finances UL -->

    </x-sidebar.dropdown>
    <!-- Transaction UL -->
{{--
    <div x-transition x-show="isSidebarOpen || isSidebarHovered" class="text-sm text-gray-500">
        {{__('app.Reports')}}
    </div> --}}

     <!-- Reports UL -->
     <x-sidebar.dropdown
        title="{{__('app.Reports')}}"
        :active="Str::contains(request()->route()->uri(), ['report'])"
        >

    <!-- General Reports UL -->
    <x-sidebar.dropdown
        title="{{__('app.General Reports')}}"
        :active="Str::contains(request()->route()->uri(), ['daily/cashbox','profit'])"
    >

        <x-sidebar.sublink
            title="{{__('app.DAILYCASHBOX')}}"
            href="{{ route('DailyCashBox.get') }}"
            :active="request()->routeIs('DailyCashBox.get')"
        />
        <x-sidebar.sublink
            title="{{__('app.Products Profits')}}"
            href="{{ route('ItemProfits.get') }}"
            :active="request()->routeIs('ItemProfits.get')"
        />
    </x-sidebar.dropdown>
    <!-- General Reports UL -->

	     <!-- Stock Reports UL -->
     <x-sidebar.dropdown
         title="{{__('app.Stock Reports')}}"
         :active="Str::contains(request()->route()->uri(), ['in/out/stock','in/out/stock/detailed'])"
     >

         <x-sidebar.sublink
             title="{{__('app.In Out Stock Report')}}"
             href="{{ route('InOutStockRep.get') }}"
             :active="request()->routeIs('InOutStockRep.get')"
         />

         <x-sidebar.sublink
             title="{{__('app.In Out Stock Report Detailed')}}"
             href="{{ route('InOutStockDetailedRep.get') }}"
             :active="request()->routeIs('InOutStockDetailedRep.get')"
         />

     </x-sidebar.dropdown>
     <!-- Stock Reports UL -->
	 
     <!-- Finances Reports UL -->
     <x-sidebar.dropdown
         title="{{__('app.Financial Reports')}}"
         :active="Str::contains(request()->route()->uri(), ['report/expenses','report/revenues'])"
     >

         <x-sidebar.sublink
             title="{{__('app.EXPENSESTATEMENT')}}"
             href="{{ route('ExpenseRep.get') }}"
             :active="request()->routeIs('ExpenseRep.get')"
         />
         {{-- <x-sidebar.sublink
             title="{{__('app.Revenues Statement')}}"
             href="{{ route('RevenuesRep.get') }}"
             :active="request()->routeIs('RevenuesRep.get')"
         /> --}}
     </x-sidebar.dropdown>
     <!-- Finances Reports UL -->

    <!-- POS Reports UL -->
    <x-sidebar.dropdown
        title="{{__('app.Pos Reports')}}"
        :active="Str::contains(request()->route()->uri(), ['pos/sales'])"
    >

        <x-sidebar.sublink
            title="{{__('app.PosSalesRep')}}"
            href="{{ route('PosSales.index') }}"
            :active="request()->routeIs('PosSales.index')"
        />
    </x-sidebar.dropdown>
    <!-- POS Reports UL -->

     <!-- Suppliers Reports UL -->
     <x-sidebar.dropdown
     title="{{__('app.Suppliers Reports')}}"
     :active="Str::contains(request()->route()->uri(), ['report/suppliers/card','report/suppliers/withdrawals/invoices','report/suppliers/withdrawals/items'])"
     >

     <x-slot name="icon">
         <x-heroicon-o-view-grid class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
     </x-slot>

     <x-sidebar.sublink
         title="{{__('app.SUPPLIERCARD')}}"
         href="{{ route('supplierCard.get') }}"
         :active="request()->routeIs('supplierCard.get')"/>

     <x-sidebar.sublink
         title="{{__('app.Invoices withdrawals')}}"
         href="{{ route('SupplierInvoiceWithdrawals.index') }}"
         :active="request()->routeIs('SupplierInvoiceWithdrawals.index')"/>

     <x-sidebar.sublink
         title="{{__('app.Products withdrawals')}}"
         href="{{ route('SupplierItemWithdrawals.index') }}"
         :active="request()->routeIs('SupplierItemWithdrawals.index')"/>
    </x-sidebar.dropdown>
    <!-- Suppliers Reports UL -->

    <!-- Customers Reports UL -->
    <x-sidebar.dropdown
    title="{{__('app.Customers Reports')}}"
    :active="Str::contains(request()->route()->uri(), ['report/customers/card','report/customers/withdrawals/invoices','report/customers/withdrawals/items'])"
    >
    <x-slot name="icon">
        <x-heroicon-o-view-grid class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
    </x-slot>

    <x-sidebar.sublink
        title="{{__('app.CUSTOMERSCARD')}}"
        href="{{ route('CustomerCard.get') }}"
        :active="request()->routeIs('CustomerCard.get')"/>

    <x-sidebar.sublink
        title="{{__('app.Invoices withdrawals')}}"
        href="{{ route('CustomerInvoiceWithdrawals.index') }}"
        :active="request()->routeIs('CustomerInvoiceWithdrawals.index')"/>

    <x-sidebar.sublink
        title="{{__('app.Products withdrawals')}}"
        href="{{ route('CustomerItemWithdrawals.index') }}"
        :active="request()->routeIs('CustomerItemWithdrawals.index')"/>
   </x-sidebar.dropdown>
   <!-- Customers Reports UL -->

     </x-sidebar.dropdown>
   <!-- Others -->
    {{-- <div x-transition x-show="isSidebarOpen || isSidebarHovered" class="text-sm text-gray-500">
        {{__('app.OTHER')}}
    </div> --}}

    <x-sidebar.link title="{{__('app.BRANCHES')}}" href="{{route('Branch.get')}}" />
    {{-- <x-sidebar.link title="{{__('app.Configuration')}}" href="{{route('System.index')}}" /> --}}
    <!-- Others -->

   <!-- Others -->
    {{-- <div x-transition x-show="isSidebarOpen || isSidebarHovered" class="text-sm text-gray-500">
        {{__('app.NEWS')}}
    </div>
    <x-sidebar.link title="{{__('app.UPDATES')}}" href="{{route('Updates.index')}}" /> --}}
    <!-- Others -->

   <!-- Database -->
    {{-- <div x-transition x-show="isSidebarOpen || isSidebarHovered" class="text-sm text-gray-500">
        {{__('app.NEWS')}}
    </div>
    <x-sidebar.link title="{{__('app.UPDATES')}}" href="{{route('Updates.index')}}" /> --}}
    <!-- Others -->


    <!-- Backup -->
    <x-sidebar.link title="{{__('app.BACKUP')}}" href="{{route('DB.backup')}}" />

</x-perfect-scrollbar>
