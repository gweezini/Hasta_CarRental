<!-- Global Public Rate Modal -->
<!-- Included once per page -->
<div id="publicRateModal" class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/60 backdrop-blur-sm hidden transition-opacity duration-300" style="display: none;">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg overflow-hidden transform transition-all scale-95 border border-gray-100" id="publicRateModalContent">
        
        <!-- Header -->
        <div class="bg-gradient-to-r from-gray-50 to-white px-6 py-4 border-b border-gray-100 flex justify-between items-center">
            <div>
                <h3 class="text-xl font-bold text-gray-900 tracking-tight flex items-center gap-2" id="prmTitle">
                    <!-- JS Populated -->
                </h3>
                <p class="text-xs text-gray-500 mt-1 font-mono uppercase tracking-wide" id="prmTier">
                    <!-- JS Populated -->
                </p>
            </div>
            <button onclick="closePublicRateModal()" class="text-gray-400 hover:text-red-500 transition-colors p-2 hover:bg-red-50 rounded-full">
                <i class="ri-close-line text-2xl leading-none"></i>
            </button>
        </div>

        <!-- Body -->
        <div class="p-6 bg-white">
            <div id="prmTableContainer" class="overflow-hidden rounded-xl border border-gray-100 shadow-sm">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-gray-500 uppercase bg-gray-50 font-semibold tracking-wider">
                        <tr>
                            <th scope="col" class="px-5 py-4">Duration</th>
                            <th scope="col" class="px-5 py-4 text-right">Price (RM)</th>
                        </tr>
                    </thead>
                    <tbody id="prmTableBody" class="divide-y divide-gray-100">
                        <!-- JS Populated -->
                    </tbody>
                </table>
            </div>
            
            <div id="prmNote" class="mt-5 text-xs text-gray-500 bg-blue-50/50 p-4 rounded-xl border border-blue-50">
                <p class="mb-2 font-bold text-blue-800 flex items-center gap-1">
                    <i class="ri-information-fill text-blue-600"></i> Pricing Note:
                </p>
                <ul class="space-y-1 ml-5 list-disc text-blue-700/80">
                    <li>Prices are cumulative for longer durations.</li>
                    <li>Example: <strong>25 hours</strong> = 24h Price + 1h Price.</li>
                </ul>
            </div>

            <!-- Fallback for No Tier -->
            <div id="prmFallback" class="text-center py-8 hidden">
                <div class="inline-flex items-center justify-center w-40 h-16 bg-gray-100 rounded-full mb-4">
                    <i class="ri-money-dollar-circle-line text-3xl text-gray-400"></i>
                </div>
                <p class="text-gray-500 mb-2">Standard Hourly Rate</p>
                <div class="text-3xl font-bold text-[#ec5a29]">
                    RM <span id="prmFallbackPrice">0.00</span>
                    <span class="text-base font-normal text-gray-400">/ hour</span>
                </div>
            </div>
        </div>
        
        <!-- Footer -->
         <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 flex justify-end">
            <button onclick="closePublicRateModal()" class="px-4 py-2 bg-white border border-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-50 hover:text-gray-900 transition-all shadow-sm">
                Close
            </button>
        </div>
    </div>
</div>

<script>
    function openPublicRateModal(element) {
        // Extract data from data attributes
        const brand = element.getAttribute('data-brand');
        const model = element.getAttribute('data-model');
        const tierName = element.getAttribute('data-tier');
        const pricePerHour = element.getAttribute('data-price-per-hour');
        
        let rates = [];
        try {
            rates = JSON.parse(element.getAttribute('data-rates'));
        } catch (e) {
            console.error('Error parsing rates JSON', e);
            rates = [];
        }

        // Set Titles
        const titleEl = document.getElementById('prmTitle');
        titleEl.innerHTML = `
            <span class="p-1 px-2 bg-[#ec5a29]/10 rounded text-[#ec5a29] text-xs uppercase tracking-wider">Rates</span>
            ${brand} ${model}
        `;

        const tierEl = document.getElementById('prmTier');
        const fallbackDiv = document.getElementById('prmFallback');
        const tableDiv = document.getElementById('prmTableContainer');
        const noteDiv = document.getElementById('prmNote');
        const tbody = document.getElementById('prmTableBody');

        if (rates && rates.length > 0) {
            // Has Tier
            tierEl.textContent = `Tier: ${tierName}`;
            tierEl.style.display = 'block';
            fallbackDiv.classList.add('hidden');
            tableDiv.classList.remove('hidden');
            noteDiv.classList.remove('hidden');

            // Populate Table
            tbody.innerHTML = '';
            // Sort
            rates.sort((a, b) => a.hour_limit - b.hour_limit);

            rates.forEach(rate => {
                const tr = document.createElement('tr');
                tr.className = 'hover:bg-orange-50/30 transition-colors duration-150';
                tr.innerHTML = `
                    <td class="px-5 py-3.5 font-medium text-gray-700">
                        <div class="flex items-center gap-2">
                            <i class="ri-time-line text-gray-400"></i>
                            ${rate.hour_limit == 24 ? '24 Hours (1 Day)' : rate.hour_limit + ' Hours'}
                        </div>
                    </td>
                    <td class="px-5 py-3.5 text-right font-bold text-[#ec5a29] text-base">
                        ${parseFloat(rate.price).toFixed(2)}
                    </td>
                `;
                tbody.appendChild(tr);
            });

        } else {
            // No Tier - Fallback
            tierEl.style.display = 'none';
            fallbackDiv.classList.remove('hidden');
            tableDiv.classList.add('hidden');
            noteDiv.classList.add('hidden');
            document.getElementById('prmFallbackPrice').textContent = parseFloat(pricePerHour).toFixed(2);
        }

        // Show Modal
        const modal = document.getElementById('publicRateModal');
        modal.style.display = 'flex';
        modal.classList.remove('hidden');
        
        // Animate in
        setTimeout(() => {
            modal.firstElementChild.classList.remove('scale-95');
            modal.firstElementChild.classList.add('scale-100');
        }, 10);
    }

    function closePublicRateModal() {
        const modal = document.getElementById('publicRateModal');
        modal.firstElementChild.classList.remove('scale-100');
        modal.firstElementChild.classList.add('scale-95');
        
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.style.display = 'none';
        }, 200);
    }

    // Close on backdrop click
    document.getElementById('publicRateModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closePublicRateModal();
        }
    });
</script>
