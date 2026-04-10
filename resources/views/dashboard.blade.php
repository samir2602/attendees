<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">                    
                    <form method="POST" action="{{ route('attendance.store') }}">
                        @csrf
                        <!-- Name -->                        
                        <div>
                            <x-input-label for="date" :value="__('Date')" />
                            <x-text-input id="date" class="block mt-1 w-full" type="date" name="date" :value="date('Y-m-d')" required autofocus autocomplete="name"/>
                            <x-input-error :messages="$errors->get('date')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="work_in" :value="__('In time')" />
                            <x-text-input id="work_in" class="block mt-1 w-full" type="time" name="work_in" :value="old('work_in', '10:00')" required autocomplete="username" />
                            <x-input-error :messages="$errors->get('work_in')" class="mt-2" />
                        </div>
                        
                        <div class="mt-4">
                            <x-input-label for="work_out" :value="__('Out time')" />
                            <x-text-input id="work_out" class="block mt-1 w-full" type="time" name="work_out" :value="old('work_out', '19:30')" required autocomplete="username" />
                            <x-input-error :messages="$errors->get('work_out')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="break_hours" :value="__('Break time')" />
                            <x-text-input id="break_hours" class="block mt-1 w-full" type="text" name="break_hours" :value="old('break_hours', 60)" required autocomplete="username" />
                            <x-input-error :messages="$errors->get('break_hours')" class="mt-2" />
                        </div>
                        
                        <div class="flex items-center justify-end mt-4">
                            <div class="flex items-center">
                                <input id="leave_day" name="leave_day" type="checkbox" value="1" class="w-4 h-4 border border-default-medium rounded-xs bg-neutral-secondary-medium focus:ring-2 focus:ring-brand-soft">
                                <label for="leave_day" class="select-none ms-2 text-sm font-medium text-heading">Leave</label>
                            </div>
                            <x-primary-button class="ms-4">
                                {{ __('Add') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p>Payable amount</p>                    
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        {{ $records->sum('day_pay') }} Rs.
                    </h2>                    
                </div>
                <div class="p-6 text-gray-900">
                    <p>Working hours</p>                    
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        {{ min_to_hour($records->sum('working_hours')) }}    
                    </h2>
                </div>
                <div class="p-6 text-gray-900">
                    <p>Break hours</p>                    
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        {{ min_to_hour($records->sum('break_hours')) }}
                    </h2>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
