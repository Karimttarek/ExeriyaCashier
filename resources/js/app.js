import flatpickr from 'flatpickr'

import './bootstrap';
import 'laravel-datatables-vite';

import Alpine from 'alpinejs'
import collapse from '@alpinejs/collapse'
import PerfectScrollbar from 'perfect-scrollbar'

import './../../vendor/power-components/livewire-powergrid/dist/powergrid'
import './../../vendor/power-components/livewire-powergrid/dist/powergrid.css'


window.PerfectScrollbar = PerfectScrollbar

window.Alpine = Alpine

Alpine.start()
