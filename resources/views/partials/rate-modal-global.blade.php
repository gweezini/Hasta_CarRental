<!-- Global Public Rate Modal -->
<!-- Included once per page -->
<div id="publicRateModal" 
     style="display: none; position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; z-index: 99999; background-color: rgba(0, 0, 0, 0.6); backdrop-filter: blur(4px); align-items: center; justify-content: center;">
    
    <div id="publicRateModalContent" 
         style="background-color: white; width: 90%; max-width: 500px; border-radius: 16px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); overflow: hidden; transform: scale(0.95); opacity: 0; transition: all 0.3s ease-out; margin: auto;">
        
        <!-- Header -->
        <div style="background: linear-gradient(to right, #f9fafb, #ffffff); padding: 16px 24px; border-bottom: 1px solid #f3f4f6; display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h3 id="prmTitle" style="font-size: 1.25rem; font-weight: 700; color: #111827; margin: 0; display: flex; align-items: center; gap: 8px;">
                    <!-- JS Populated -->
                </h3>
                <p id="prmTier" style="font-size: 0.75rem; color: #6b7280; margin-top: 4px; font-family: monospace; text-transform: uppercase;">
                    <!-- JS Populated -->
                </p>
            </div>
            <button onclick="closePublicRateModal()" style="background: transparent; border: none; cursor: pointer; padding: 4px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #9ca3af; transition: color 0.2s;">
                <i class="ri-close-line" style="font-size: 24px; line-height: 1;"></i>
            </button>
        </div>

        <!-- Body -->
        <div style="padding: 24px; background-color: white;">
            <div id="prmTableContainer" style="border: 1px solid #f3f4f6; border-radius: 12px; overflow: hidden; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                <table style="width: 100%; border-collapse: collapse; text-align: left;">
                    <thead style="background-color: #f9fafb; font-size: 0.75rem; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em;">
                        <tr>
                            <th style="padding: 12px 20px; font-weight: 600;">Duration</th>
                            <th style="padding: 12px 20px; text-align: right; font-weight: 600;">Price (RM)</th>
                        </tr>
                    </thead>
                    <tbody id="prmTableBody">
                        <!-- JS Populated -->
                    </tbody>
                </table>
            </div>
            
            <div id="prmNote" style="margin-top: 20px; font-size: 0.75rem; color: #6b7280; background-color: #eff6ff; padding: 16px; border-radius: 12px; border: 1px solid #dbeafe;">
                <p style="margin-bottom: 8px; font-weight: 700; color: #1e40af; display: flex; align-items: center; gap: 4px; margin-top: 0;">
                    <i class="ri-information-fill text-blue-600"></i> Pricing Note:
                </p>
                <ul style="margin: 0; padding-left: 20px; list-style-type: disc; color: #1d4ed8;">
                    <li>Prices are cumulative for longer durations.</li>
                    <li>Example: <strong>25 hours</strong> = 24h Price + 1h Price.</li>
                </ul>
            </div>

            <!-- Fallback for No Tier -->
            <div id="prmFallback" style="text-align: center; padding: 32px 0; display: none;">
                <div style="display: inline-flex; align-items: center; justify-content: center; width: 64px; height: 64px; background-color: #f3f4f6; border-radius: 50%; margin-bottom: 16px;">
                    <i class="ri-money-dollar-circle-line" style="font-size: 32px; color: #9ca3af;"></i>
                </div>
                <p style="color: #6b7280; margin-bottom: 8px; font-size: 0.875rem;">Standard Hourly Rate</p>
                <div style="font-size: 1.875rem; font-weight: 700; color: #ec5a29;">
                    RM <span id="prmFallbackPrice">0.00</span>
                    <span style="font-size: 1rem; font-weight: 400; color: #9ca3af;">/ hour</span>
                </div>
            </div>
        </div>
        
        <!-- Footer -->
         <div style="background-color: #f9fafb; padding: 16px 24px; border-top: 1px solid #f3f4f6; display: flex; justify-content: flex-end;">
            <button onclick="closePublicRateModal()" style="padding: 8px 16px; background-color: white; border: 1px solid #e5e7eb; color: #374151; font-weight: 500; border-radius: 8px; cursor: pointer; transition: background-color 0.2s; font-size: 0.875rem;">
                Close
            </button>
        </div>
    </div>
</div>

<script>
    function openPublicRateModal(element) {
        // Extract data
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
            <span style="padding: 2px 8px; background-color: rgba(236, 90, 41, 0.1); border-radius: 4px; color: #ec5a29; font-size: 0.75rem; text-transform: uppercase; font-weight: 700; letter-spacing: 0.05em;">Rates</span>
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
            fallbackDiv.style.display = 'none';
            tableDiv.style.display = 'block';
            noteDiv.style.display = 'block';

            // Populate Table
            tbody.innerHTML = '';
            rates.sort((a, b) => a.hour_limit - b.hour_limit);

            rates.forEach(rate => {
                const tr = document.createElement('tr');
                tr.style.borderBottom = '1px solid #f3f4f6';
                
                tr.innerHTML = `
                    <td style="padding: 14px 20px; font-weight: 500; color: #374151;">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <i class="ri-time-line" style="color: #9ca3af;"></i>
                            ${rate.hour_limit == 24 ? '24 Hours (1 Day)' : rate.hour_limit + ' Hours'}
                        </div>
                    </td>
                    <td style="padding: 14px 20px; text-align: right; font-weight: 700; color: #ec5a29; font-size: 1rem;">
                        ${parseFloat(rate.price).toFixed(2)}
                    </td>
                `;
                tbody.appendChild(tr);
            });

        } else {
            // No Tier - Fallback
            tierEl.style.display = 'none';
            fallbackDiv.style.display = 'block';
            tableDiv.style.display = 'none';
            noteDiv.style.display = 'none';
            document.getElementById('prmFallbackPrice').textContent = parseFloat(pricePerHour).toFixed(2);
        }

        // Show Modal
        const modal = document.getElementById('publicRateModal');
        const content = document.getElementById('publicRateModalContent');
        
        modal.style.display = 'flex';
        // Need specific flex alignment since we are setting display:flex manually
        modal.style.alignItems = 'center';
        modal.style.justifyContent = 'center';
        
        // Disable Body Scroll
        document.body.style.overflow = 'hidden';
        
        // Animation
        requestAnimationFrame(() => {
            content.style.opacity = '1';
            content.style.transform = 'scale(1)';
        });
    }

    function closePublicRateModal() {
        const modal = document.getElementById('publicRateModal');
        const content = document.getElementById('publicRateModalContent');
        
        content.style.opacity = '0';
        content.style.transform = 'scale(0.95)';
        
        setTimeout(() => {
            modal.style.display = 'none';
            document.body.style.overflow = ''; // Restore scroll
        }, 300);
    }

    // Close on backdrop click
    document.getElementById('publicRateModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closePublicRateModal();
        }
    });
</script>
