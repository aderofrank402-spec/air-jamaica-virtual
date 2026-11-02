@section('scripts')
    @parent
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // --- NUCLEAR DIRECT BIDDING (Independent of ajv.js) ---
            document.querySelectorAll("button.save_flight").forEach(button => {
                button.addEventListener('click', async function(e) {
                    e.preventDefault();
                    
                    // Always get the button, even if the icon was clicked
                    const btn = e.currentTarget;
                    const flight_id = btn.getAttribute('x-id');
                    const isBidded = btn.classList.contains('btn-danger') || btn.classList.contains('saved');
                    
                    // Visual feedback
                    const originalHtml = btn.innerHTML;
                    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                    btn.disabled = true;

                    // Pull Auth Data directly from Meta Tags
                    const apiKey = document.querySelector('meta[name="api-key"]')?.content;
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

                    try {
                        const method = isBidded ? 'DELETE' : 'POST';
                        const response = await fetch(window.location.origin + '/api/user/bids', {
                            method: 'POST', // Laravel uses POST + _method for DELETE
                            headers: {
                                'Content-Type': 'application/json',
                                'X-API-KEY': apiKey,
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                _method: method,
                                flight_id: flight_id
                            })
                        });

                        if (response.ok) {
                            // Success: Production-style reload
                            location.reload();
                        } else {
                            const errorData = await response.json();
                            throw new Error(errorData.message || errorData.details || 'Server rejected request');
                        }
                    } catch (error) {
                        btn.innerHTML = originalHtml;
                        btn.disabled = false;
                        alert(`Bidding Error: ${error.message}`);
                        console.error("Direct Bidding Failure:", error);
                    }
                });
            });

            // --- ROBUST REMOVE BID LOGIC ---
            let flightToRemove = null;
            const confirmBtn = document.getElementById('confirmRemoveBidBtn');
            
            document.querySelectorAll('.remove_bid_btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    flightToRemove = this.getAttribute('data-flight-id');
                });
            });

            if (confirmBtn) {
                confirmBtn.addEventListener('click', async function() {
                    if (!flightToRemove) return;
                    
                    const originalHtml = confirmBtn.innerHTML;
                    confirmBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Removing...';
                    confirmBtn.disabled = true;

                    const apiKey = document.querySelector('meta[name="api-key"]')?.content;
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

                    try {
                        const response = await fetch(window.location.origin + '/api/user/bids', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-API-KEY': apiKey,
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                _method: 'DELETE',
                                flight_id: flightToRemove
                            })
                        });

                        if (response.ok) {
                            location.reload();
                        } else {
                            throw new Error('Failed to remove bid');
                        }
                    } catch (error) {
                        confirmBtn.innerHTML = originalHtml;
                        confirmBtn.disabled = false;
                        alert(`Error: ${error.message}`);
                    }
                });
            }
        });
    </script>
    @include('scripts.airport_search')
@endsection
