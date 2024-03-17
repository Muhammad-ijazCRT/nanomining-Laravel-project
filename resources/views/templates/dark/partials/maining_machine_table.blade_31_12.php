<table class="table--responsive--lg table">
    <thead style="border: 2px solid red ">
        <tr>
            <th scope="col">@lang('Wallet Name')</th>
            <th scope="col">@lang('Miner status')</th>
            <th scope="col">@lang('Block Hash')</th>
            {{-- <th scope="col">@lang('per day mining')</th> --}}
            <th scope="col">@lang('Balance')</th>
        </tr>
    </thead>
    <tbody>

        @php
            $session = Session()->get('mining_servers');
            // dd($session);
        @endphp

        @if (isset($session))
            @forelse($mining_servers as $mining_server)
                <tr class="mining-server-row">
                    <td>
                        {{ strtoupper($mining_server->plan_details->miner) }}
                    </td>
                    <td class="budget">
                        <span class="badge badge--success">Powered On</span>
                    </td>
                    <td>
                        <div class="hash-container"></div>
                    </td>
                    <td>
                        {{-- <span class="mined-btc-container" id="mining_machine_{{ $mining_server->id }}">
                        </span> --}}

                        <span class="mined-btc-container" id="mining_machine_{{ $mining_server->id }}">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" width="10" height="10"
                                stroke="#007bff">
                                <circle cx="50" cy="50" r="45" fill="none" stroke-width="5"
                                    stroke-dasharray="226.195" stroke-dashoffset="0">
                                    <animate attributeName="stroke-dashoffset" dur="2s" repeatCount="indefinite"
                                        keyTimes="0;1" values="0;226.195"></animate>
                                </circle>
                            </svg>
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                </tr>
            @endforelse
        @else
            @forelse($mining_servers as $mining_server)
                <tr class="mining-server-row">
                    <td>
                        {{ strtoupper($mining_server->plan_details->miner) }}
                    </td>
                    <td class="budget">
                        <span class="badge badge--success">Powered On</span>
                    </td>
                    <td>
                        <div class="hash-container"></div>
                    </td>
                    <td>
                        <span class="mined-btc-container" id="mining_machine_{{ $mining_server->id }}">
                            {{ $mining_server->userCoinBalance->balance . ' BTC' ?? '$ 0.0000000000' }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                </tr>
            @endforelse
        @endif


    </tbody>
</table>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script>
    var btcToUsdRate; // Variable to store the BTC to USD conversion rate
    var mining_servers;


    // Function to fetch BTC to USD conversion rate using async/await
    async function fetchBtcToUsdRate() {
        try {
            const response = await fetch(
                'https://api.coingecko.com/api/v3/simple/price?ids=bitcoin&vs_currencies=usd');
            if (!response.ok) {
                throw new Error(`Error fetching CoinGecko data. Status: ${response.status}`);
            }

            const coinGeckoData = await response.json();
            // Store the conversion rate in the variable
            btcToUsdRate = coinGeckoData.bitcoin.usd;
        } catch (error) {
            console.error('Error fetching CoinGecko data:', error);
        }
    }


    function fetchDataAndDisplay() {
        // Check the last execution time stored in localStorage
        var lastExecutionTime = localStorage.getItem('lastExecutionTime');
        var currentTime = new Date().getTime();

        // Execute the logic only if more than 1000 milliseconds (1 second) have passed
        if (!lastExecutionTime || currentTime - lastExecutionTime > 1000) {
            // Store the current time in localStorage
            localStorage.setItem('lastExecutionTime', currentTime);

            // Your existing logic here...
            if (btcToUsdRate === undefined) {
                fetchBtcToUsdRate();
            }

            $.ajax({
                url: "{{ route('user.get.mining.server.data') }}",
                type: 'GET',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    data.forEach(function(item) {
                        console.log(item);
                        // Assuming item.wallet_amount is in BTC
                        console.log(item);
                        var btcAmount = item.wallet_amount;
                        var usdAmount = btcAmount * btcToUsdRate;

                        // console.log(item);
                        // Update the HTML content inside the span with ID 'mining_machine_{miner_machine_id}'

                        // $('#mining_machine_' + item.miner_machine_id).html(parseFloat(item
                        //     .wallet_amount).toFixed(10) + ' ≈ $' + usdAmount.toFixed(2));

                        console.log(item);

                        $('#mining_machine_' + item.miner_machine_id).html(parseFloat(item
                            .mined_coin).toFixed(10) + ' ≈ $' + usdAmount.toFixed(2));

                        const hashContainers = document.querySelectorAll('.userCoinBalances');

                        // console.log(hashContainers);
                        hashContainers.forEach(hashContainer => {
                            const planId = hashContainer.getAttribute('data-plan-id');
                            // console.log(planId);
                            // console.log(item.miner_currency_id);
                            if (planId == 1) {
                                // console.log('asdfasjdklfjasdlk');
                                if (item.btc_balance) {
                                    coinBalance = hashContainer.getAttribute(
                                        'data-CoinBalances');


                                    // const result = (parseFloat(item.wallet_amount)).toFixed(10);
                                    const result = (parseFloat(item.btc_balance))
                                        .toFixed(
                                            10);
                                    // Use innerHTML to set the content of the element
                                    hashContainer.innerHTML = result;
                                }

                            }

                            if (planId == 2) {
                                // console.log('asdfasjdklfjasdlk');
                                coinBalance = hashContainer.getAttribute(
                                    'data-CoinBalances');

                                if (item.ltc_balance) {
                                    // const result = (parseFloat(item.wallet_amount)).toFixed(10);
                                    const result = (parseFloat(item.ltc_balance))
                                        .toFixed(10);

                                    // Use innerHTML to set the content of the element
                                    hashContainer.innerHTML = result;
                                }
                            }
                        });

                    });
                },
                error: function(error) {
                    console.error('Error fetching data:', error);
                }
            });
        }
    }

    // Call the function every second (1000 milliseconds)
    setInterval(fetchDataAndDisplay, 1000);
    // Fetch BTC to USD conversion rate on window load
    $(window).on('load', function() {
        fetchBtcToUsdRate();
        setInterval(fetchDataAndDisplay, 1000);
    });

    function generateRandomString(length) {
        const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        let result = '';

        for (let i = 0; i < length; i++) {
            const randomIndex = Math.floor(Math.random() * characters.length);
            result += characters.charAt(randomIndex);
        }

        return result;
    }

    function generateRandomHash() {
        const hashLength = 30; // Truncated length

        // Generate a random string
        const randomString = generateRandomString(hashLength);

        // Create a hash using SHA-256
        const hashBuffer = new TextEncoder().encode(randomString);
        const hashArrayBuffer = crypto.subtle.digest('SHA-256', hashBuffer);

        // Convert the hash to a hex string and truncate
        const hashPromise = hashArrayBuffer.then(hashBuffer => {
            const hashArray = Array.from(new Uint8Array(hashBuffer));
            const truncatedHash = hashArray.slice(0, hashLength / 2)
                .map(byte => byte.toString(16).padStart(2, '0'))
                .join('');
            return truncatedHash;
        });
        return hashPromise;
    }

    function updateHashContainers() {
        const hashContainers = document.querySelectorAll('.hash-container');
        hashContainers.forEach(hashContainer => {
            generateRandomHash().then(hash => {
                // Insert '***' at the center of the hash
                const insertionIndex = Math.floor(hash.length / 2);
                const modifiedHash = hash.slice(0, insertionIndex) + '***' + hash.slice(
                    insertionIndex);
                hashContainer.textContent = modifiedHash;
            });
        });
    }
    // Update the hashes every 200 milliseconds
    setInterval(updateHashContainers, 150);

    // Initial update
    updateHashContainers();




    // Function to fetch BTC to USD conversion rate
    // function fetchBtcToUsdRate() {
    // $.ajax({
    // url: 'https://api.coingecko.com/api/v3/simple/price',
    // type: 'GET',
    // data: {
    // ids: 'bitcoin',
    // vs_currencies: 'usd'
    // },
    // success: function(coinGeckoData) {
    // // Store the conversion rate in the variable
    // btcToUsdRate = coinGeckoData.bitcoin.usd;
    // },
    // error: function(error) {
    // console.error('Error fetching CoinGecko data:', error);
    // }
    // });
    // }
</script>
