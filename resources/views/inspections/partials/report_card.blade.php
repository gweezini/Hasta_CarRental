<div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
    <div class="bg-gray-50 px-8 py-6 border-b border-gray-100 flex justify-between items-start">
            <div>
            <h1 class="text-2xl font-bold text-gray-900">Inspection Report</h1>
            <p class="text-sm text-gray-500 mt-1">Recorded on {{ $inspection->created_at->format('M d, Y h:i A') }}</p>
            </div>
            <span class="px-4 py-1.5 rounded-full text-sm font-bold uppercase tracking-wider bg-green-100 text-green-700 shadow-sm">
            Inspection Verified
        </span>
    </div>

    <div class="p-8 space-y-8">
        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                <p class="text-xs text-gray-400 font-bold uppercase">Vehicle</p>
                <p class="text-lg font-bold text-gray-800">{{ $inspection->booking->vehicle->brand }} {{ $inspection->booking->vehicle->model }}</p>
                <p class="text-xs text-gray-500">{{ $inspection->booking->vehicle->plate_number }}</p>
            </div>
            <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                <p class="text-xs text-gray-400 font-bold uppercase">Mileage</p>
                <p class="text-lg font-bold text-gray-800">{{ number_format($inspection->mileage) }} km</p>
            </div>
            <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                <p class="text-xs text-gray-400 font-bold uppercase">Fuel Level</p>
                <div class="flex items-center gap-2 mt-1">
                        <div class="flex-1 bg-gray-200 rounded-full h-2">
                            <div class="bg-[#ec5a29] h-2 rounded-full" style="width: {{ ($inspection->fuel_level/10)*100 }}%"></div>
                        </div>
                        <span class="font-bold text-gray-800">{{ $inspection->fuel_level }}/10</span>
                </div>
            </div>
        </div>

        <!-- Photos -->
        <div>
            <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="ri-camera-line text-[#ec5a29]"></i> Captured Photos
            </h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach(['prefix' => 'photo_'] as $prefix)
                    @foreach(['front', 'back', 'left', 'right', 'dashboard'] as $view)
                        @php $col = $prefix . $view; @endphp
                        @if($inspection->$col)
                            <div class="space-y-2 group">
                                <p class="text-xs font-bold text-gray-500 uppercase text-center">{{ ucfirst($view) }}</p>
                                <div class="relative overflow-hidden rounded-xl border border-gray-200 aspect-square shadow-sm group-hover:shadow-md transition">
                                    <a href="{{ asset('storage/'.$inspection->$col) }}" target="_blank">
                                        <img src="{{ asset('storage/'.$inspection->$col) }}" class="w-full h-full object-cover transform group-hover:scale-110 transition duration-500">
                                    </a>
                                </div>
                            </div>
                        @endif
                    @endforeach
                @endforeach
            </div>
        </div>

        <!-- Damage Report -->
        @if(!empty($inspection->damage_photos) || $inspection->damage_description)
        <div class="bg-red-50 rounded-xl border border-red-100 p-6">
            <h3 class="font-bold text-red-700 mb-4 flex items-center gap-2">
                <i class="ri-alert-line"></i> Reported Damage
            </h3>
            <div class="space-y-6">
                @if(!empty($inspection->damage_photos))
                <div>
                    <p class="text-xs font-bold text-red-400 uppercase mb-2">Damage Photos</p>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach($inspection->damage_photos as $photo)
                        <div class="rounded-xl overflow-hidden border border-red-200 shadow-sm aspect-video">
                            <a href="{{ asset('storage/'.$photo) }}" target="_blank">
                                <img src="{{ asset('storage/'.$photo) }}" class="w-full h-full object-cover">
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
                
                @if($inspection->damage_description)
                <div>
                    <p class="text-xs font-bold text-red-400 uppercase mb-2">Description</p>
                    <div class="p-4 bg-white rounded-xl border border-red-100 text-gray-700 italic">
                        "{{ $inspection->damage_description }}"
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Remarks -->
        @if($inspection->remarks)
        <div>
            <h3 class="font-bold text-gray-900 mb-2">Remarks</h3>
            <div class="p-4 bg-gray-50 rounded-xl border border-gray-200 text-gray-700 italic">
                "{{ $inspection->remarks }}"
            </div>
        </div>
        @endif
    </div>
</div>
