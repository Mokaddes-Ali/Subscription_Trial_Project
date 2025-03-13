<x-app-layout>
    <div class="bg-gray-100 flex items-center justify-center min-h-screen">
        <div class="container mx-auto p-6">
            <h1 class="text-3xl font-bold text-center mb-8">Choose Your Plan</h1>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($plans as $plan)
                    <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                        <h2 class="text-2xl font-bold text-gray-800">{{ $plan->name }}</h2>
                        <p class="text-xl text-gray-600 mt-2">${{ number_format($plan->amount, 2) }}</p>
                        <p class="mt-4 text-gray-500">Get access for {{ strtolower($plan->name) }}.</p>
                        <button
                            onclick="showPlan('{{ $plan->name }}', '{{ $plan->amount }}', '{{ $plan->trial_days }}')"
                            class="mt-6 w-full py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition">
                            Choose Plan
                        </button>
                    </div>
                @endforeach
            </div>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                {{ __('Log Out') }}
            </button>
        </form>

        <!-- Modal -->
        <div id="planModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center">
            <div class="bg-white p-6 rounded-lg shadow-lg w-96 text-center">
                <h2 id="modalTitle" class="text-2xl font-bold text-gray-800"></h2>
                <p id="modalPrice" class="text-xl text-gray-600 mt-2"></p>
                <p id="modalTrial" class="mt-4 text-gray-500"></p>

                <button id="subscribeButton"
                    class="mt-6 w-full py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition">
                    Subscribe Now
                </button>
                <button onclick="closeModal()"
                    class="mt-3 w-full py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition">
                    Cancel
                </button>
            </div>
        </div>

        <script src="https://js.stripe.com/v3/"></script>
        <script>
            let planName = '';
            let amount = 0;
            let trial = 0;

            function showPlan(name, planAmount, planTrial) {
                planName = name;
                amount = planAmount;
                trial = planTrial;

                document.getElementById('modalTitle').innerText = name;
                document.getElementById('modalPrice').innerText = "Price: $" + amount;

                const trialText = document.getElementById('modalTrial');
                if (trial > 0) {
                    trialText.innerText = "Trial Days: " + trial;
                    trialText.style.display = "block";
                } else {
                    trialText.style.display = "none";
                }

                document.getElementById('planModal').classList.remove('hidden');
            }

            function closeModal() {
                document.getElementById('planModal').classList.add('hidden');
            }

            // Stripe.js Setup
            var stripe = Stripe("{{ env('STRIPE_PUBLIC_KEY') }}");

            document.getElementById('subscribeButton').addEventListener('click', function () {
                let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch('{{ route("create.checkout.session") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        planName: planName,
                        amount: amount,
                        trial: trial,
                    }),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        console.error("Error:", data.error);
                        alert("Payment error: " + data.error);
                    } else {
                        stripe.redirectToCheckout({ sessionId: data.id });
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                });
            });
        </script>
    </div>
</x-app-layout>

