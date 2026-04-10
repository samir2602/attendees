<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Attendance') }}
        </h2>
    </x-slot>
    
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('attendance.index') }}" method="GET">
                        <input type="month" id="month" name="month" value="{{ (isset($_GET['month'])) ? $_GET['month'] : date('Y-m') }}" onchange="this.form.submit()">                        
                    </form>
                    @if($attendance->count() == 0)
                    <p class="text-center text-gray-500 mt-4">No attendance records found for this month.</p>
                    @else                    
                    <div class="relative overflow-x-auto bg-neutral-primary-soft shadow-xs rounded-base">
                        <table class="w-full text-sm text-left rtl:text-right text-body">
                            <thead class="text-sm text-body bg-neutral-secondary-soft border-b rounded-base border-default">
                                <tr>
                                    <th scope="col" class="px-6 py-3 font-medium">Date</th>
                                    <th scope="col" class="px-6 py-3 font-medium">Work in</th>
                                    <th scope="col" class="px-6 py-3 font-medium">Work out</th>
                                    <th scope="col" class="px-6 py-3 font-medium">Break</th>
                                    <th scope="col" class="px-6 py-3 font-medium">Work Hours</th>
                                    <th scope="col" class="px-6 py-3 font-medium">Today's Pay</th>
                                    <th scope="col" class="px-6 py-3 font-medium">OT/UT</th>
                                    <th scope="col" class="px-6 py-3 font-medium">Edit</th>
                                    <th scope="col" class="px-6 py-3 font-medium">Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($attendance as $item)                                
                                <tr class="bg-neutral-primary border-b border-default">
                                    <th scope="row" class="px-6 py-4 font-medium text-heading whitespace-nowrap">{{date('M, d, Y, D', strtotime($item['date'])) }}</th>
                                    <th class="px-6 py-4">{{ ($item['work_in'] != '00:00:00') ? date("h:i:s a", strtotime($item['work_in'])) : '-'}}</th>
                                    <th class="px-6 py-4">{{ ($item['work_out'] != '00:00:00') ? date("h:i:s a", strtotime($item['work_out'])) : '-'}}</th>
                                    <th class="px-6 py-4">                                        
                                        @if($item['break_hours'] > 60)
                                        <span class="bg-red-100 text-red-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded-full"> {{$item->breakhours()}}</span>
                                        @else
                                        <span class="bg-dark-100 text-dark-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded-full"> {{$item->breakhours()}}</span>                                            
                                        @endif
                                    </th>
                                    <th class="px-6 py-4">{{ $item->workinghours() }}</th>
                                    <th class="px-6 py-4">{{ day_pay($item['date'], $item['work_in'], $item['work_out'], $item['break_hours']) }} </th>
                                    {{-- <th class="px-6 py-4">{{ $item['day_pay']}}</th> --}}
                                    <th class="px-6 py-4">
                                        @if ($item['over_time'])                                    
                                        <span class="bg-green-100 text-green-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded-full dark:bg-green-900 dark:text-green-300">{{$item['over_time']}}</span>
                                        @else
                                        <span class="bg-red-100 text-red-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded-full dark:bg-red-900 dark:text-red-300">{{$item['under_time']}}</span>
                                        @endif
                                    </th>
                                    <th class="px-6 py-4">
                                        <a href="{{ route('attendance.edit', $item->id) }}" class="bg-green-100 text-green-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded-full dark:bg-green-900 dark:text-green-300">Edit</a>
                                    </th>
                                    <th class="px-6 py-4">
                                        <form method="POST" action="{{ route('attendance.destroy', $item->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button class="bg-red-100 text-red-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded-full dark:bg-red-900 dark:text-red-300">Delete</button>
                                        </form>
                                    </th>
                                </tr>                               
                                @endforeach
                                <tr class="bg-neutral-primary border-b border-default">
                                    <th class="px-6 py-4">Total</th>
                                    <th class="px-6 py-4"></th>
                                    <th class="px-6 py-4"></th>
                                    <th class="px-6 py-4">
                                        {{ min_to_hour($attendance->sum('break_hours')) }}
                                    </th>
                                    <th class="px-6 py-4">
                                        {{ min_to_hour($attendance->sum('working_hours')) }}
                                    </th>
                                    <th class="px-6 py-4">{{ $attendance->sum('day_pay') }} Rs</th>
                                    <th class="px-6 py-4">                                        
                                        <p><span class="bg-green-100 text-green-800 text-sm font-medium me-2 px-2.5 my-1 py-0.5 rounded-full dark:bg-green-900 dark:text-green-300">{{ min_to_hour($attendance->sum('over_time')) }}</span></p>
                                        <p><span class="bg-red-100 text-red-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded-full dark:bg-red-900 dark:text-red-300">{{ min_to_hour($attendance->sum('under_time')) }}</span></p>
                                    </th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>