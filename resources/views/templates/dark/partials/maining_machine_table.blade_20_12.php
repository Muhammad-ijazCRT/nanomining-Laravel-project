<table class="table--responsive--lg table">
    <thead>
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
        @endphp

        @forelse($mining_servers as $mining_server)
            <tr class="mining-server-row">
                <td>
                    {{ strtoupper($mining_server->plan_details->miner) }}
                </td>
                <td class="budget">
                    <span class="badge badge--success">Powered On</span>
                </td>
                <td>
                    {{-- <div id="hash-container"></div> --}}
                    <div class="hash-container"></div>
                </td>

                {{-- <td>
                    <div class="">{{ $mining_server->min_return_per_day }}</div>
                </td> --}}

                <td>
                    <span class="mined-btc-container" id="mining_machine_{{ $mining_server->id }}">
                        {{-- 0.0000000000 BTC --}}
                        {{ $mining_server->userCoinBalance->balance . ' BTC' ?? '$ 0.0000000000' }}
                    </span>
                </td>
            </tr>



        @empty
            <tr>
                <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
            </tr>
        @endforelse
    </tbody>
</table>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>




<script>
    var btcToUsdRate; // Variable to store the BTC to USD conversion rate
    var mining_servers;

    // Function to fetch BTC to USD conversion rate
    function fetchBtcToUsdRate() {
        $.ajax({
            url: 'https://api.coingecko.com/api/v3/simple/price',
            type: 'GET',
            data: {
                ids: 'bitcoin',
                vs_currencies: 'usd'
            },
            success: function(coinGeckoData) {
                // Store the conversion rate in the variable
                btcToUsdRate = coinGeckoData.bitcoin.usd;
            },
            error: function(error) {
                console.error('Error fetching CoinGecko data:', error);
            }
        });
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
                    // console.log(data);
                    data.forEach(function(item) {
                        // console.log(item);
                        // Assuming item.wallet_amount is in BTC
                        var btcAmount = item.wallet_amount;
                        var usdAmount = btcAmount * btcToUsdRate;

                        // Update the HTML content inside the span with ID 'mining_machine_{miner_machine_id}'
                        $('#mining_machine_' + item.miner_machine_id).html(parseFloat(item
                            .wallet_amount).toFixed(10) + ' ≈ $' + usdAmount.toFixed(2));

                        const hashContainers = document.querySelectorAll('.userCoinBalances');

                        hashContainers.forEach(hashContainer => {
                            const planId = hashContainer.getAttribute('data-plan-id');
                            // console.log(item.miner_currency_id);
                            if (planId == item.miner_currency_id) {
                                // console.log('asdfasjdklfjasdlk');
                                coinBalance = hashContainer.getAttribute(
                                    'data-CoinBalances');

                                // Parse values to floats and add them, then format the result with toFixed(10)
                                const result = (parseFloat(coinBalance) + parseFloat(item
                                    .wallet_amount)).toFixed(10);

                                // Use innerHTML to set the content of the element
                                hashContainer.innerHTML = result;
                            }
                        });

                    });
                },
                error: function(error) {
                    console.error('Error fetching data:', error);
                }
            });

            // $.ajax({
            //     // ... (your existing ajax settings)
            // });
        }
    }

    // Call the function every second (1000 milliseconds)
    setInterval(fetchDataAndDisplay, 1000);

    // Fetch BTC to USD conversion rate on window load
    $(window).on('load', function() {
        fetchBtcToUsdRate();
        setInterval(fetchDataAndDisplay, 1000);
    });











    // // Function to fetch mining server data and display
    // function fetchDataAndDisplay() {
    //     // Fetch BTC to USD conversion rate if not fetched yet
    //     if (btcToUsdRate === undefined) {
    //         fetchBtcToUsdRate();
    //     }

    //     // Fetch mining server data
    //     $.ajax({
    //         url: "{{ route('user.get.mining.server.data') }}",
    //         type: 'GET',
    //         dataType: 'json',
    //         headers: {
    //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //         },
    //         success: function(data) {
    //             // console.log(data);
    //             data.forEach(function(item) {
    //                 // console.log(item);
    //                 // Assuming item.wallet_amount is in BTC
    //                 var btcAmount = item.wallet_amount;
    //                 var usdAmount = btcAmount * btcToUsdRate;

    //                 // Update the HTML content inside the span with ID 'mining_machine_{miner_machine_id}'
    //                 $('#mining_machine_' + item.miner_machine_id).html(parseFloat(item
    //                     .wallet_amount).toFixed(10) + ' ≈ $' + usdAmount.toFixed(2));

    //                 const hashContainers = document.querySelectorAll('.userCoinBalances');

    //                 hashContainers.forEach(hashContainer => {
    //                     const planId = hashContainer.getAttribute('data-plan-id');
    //                     // console.log(item.miner_currency_id);
    //                     if (planId == item.miner_currency_id) {
    //                         // console.log('asdfasjdklfjasdlk');
    //                         coinBalance = hashContainer.getAttribute('data-CoinBalances');

    //                         // Parse values to floats and add them, then format the result with toFixed(10)
    //                         const result = (parseFloat(coinBalance) + parseFloat(item
    //                             .wallet_amount)).toFixed(10);

    //                         // Use innerHTML to set the content of the element
    //                         hashContainer.innerHTML = result;
    //                     }
    //                 });

    //             });
    //         },
    //         error: function(error) {
    //             console.error('Error fetching data:', error);
    //         }
    //     });
    // }

    // // Call the function every second (1000 milliseconds)
    // setInterval(fetchDataAndDisplay, 1000);

    // // Fetch BTC to USD conversion rate on window load
    // $(window).on('load', function() {
    //     fetchBtcToUsdRate();
    // });



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
                const modifiedHash = hash.slice(0, insertionIndex) + '***' + hash.slice(insertionIndex);

                hashContainer.textContent = modifiedHash;
            });
        });
    }

    // Update the hashes every 200 milliseconds
    setInterval(updateHashContainers, 150);

    // Initial update
    updateHashContainers();
</script>



{{-- <script>
    function fetchDataAndDisplay() {
        $.ajax({
            url: "{{ route('user.get.mining.server.data') }}",
            type: 'GET',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {
                console.log(data);


                data.forEach(function(item) {

                    data.forEach(function(item, id) {
                        console.log(item.miner_machine_id);
                        // Access the 'name' property of each item and append it to the HTML element with ID 'newdata'
                        $('#mining_machine_' + item.miner_machine_id).html(item
                            .wallet_amount ' ≈ '
                            'convert to USD');
                    });

                });
            },
            error: function(error) {
                console.error('Error fetching data:', error);
            }
        });
    }

    // Call the function every second (1000 milliseconds)
    setInterval(fetchDataAndDisplay, 1000);
</script> --}}

{{-- <script>
    let minedBTC = 0;
    let minedUSD = 0;
    let btcPrice;
    const store = <?php echo json_encode($mining_servers); ?>;

    console.log(store);

    //  ====================== hash start ================================= 
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
        const hashLength = 30;

        const randomString = generateRandomString(hashLength);
        const hashBuffer = new TextEncoder().encode(randomString);
        const hashArrayBuffer = crypto.subtle.digest('SHA-256', hashBuffer);

        const hashPromise = hashArrayBuffer.then(hashBuffer => {
            const hashArray = Array.from(new Uint8Array(hashBuffer));
            const truncatedHash = hashArray.slice(0, hashLength / 2)
                .map(byte => byte.toString(16).padStart(2, '0'))
                .join('');
            return truncatedHash;
        });

        return hashPromise;
    }

    async function updateHashContainerForRow(row) {
        // console.log(row);
        const hashContainer = row.querySelector('.hash-container');
        generateRandomHash().then(hash => {
            const insertionIndex = Math.floor(hash.length / 2);
            const modifiedHash = hash.slice(0, insertionIndex) + '***' + hash.slice(insertionIndex);
            hashContainer.textContent = modifiedHash;
        });
    }



    // ====================== getBTCPrice start =================================
    async function getBTCPrice() {
        try {
            const response = await fetch(
                'https://api.coingecko.com/api/v3/simple/price?ids=bitcoin&vs_currencies=usd');
            const data = await response.json();
            // console.log(data);
            return data.bitcoin && typeof data.bitcoin.usd === 'number' ? data.bitcoin.usd : 0;
        } catch (error) {
            console.error('Error fetching BTC price:', error);
            return 0;
        }
    }

    async function updateMiningStatusForRow(row) {
        const minedBTCContainer = row.querySelector('.mined-btc-container');
        const minedUSDContainer = row.querySelector('.mined-usd-container');

        const targetBTC = 0.002; // Set your desired amount of BTC to mine in 24 hours

        if (typeof btcPrice === 'number' && btcPrice > 0) {
            const miningRatePerSecond = targetBTC / (24 * 60 * 60);

            minedBTC += miningRatePerSecond;
            minedUSD = minedBTC * btcPrice;

            const formattedBTC = minedBTC.toFixed(10);
            const formattedUSD = minedUSD.toFixed(2);

            minedBTCContainer.textContent = `${formattedBTC} BTC`;
            minedUSDContainer.textContent = `$${formattedUSD}`;
        } else {
            console.error('Invalid BTC price:', btcPrice);
        }
    }


    // ====================== controller start =================================
    async function fetchBTCPrice() {
        btcPrice = await getBTCPrice();
        if (typeof btcPrice === 'number' && btcPrice > 0) {
            setInterval(fetchBTCPrice, 300000);
        } else {
            console.error('Invalid BTC price:', btcPrice);
        }
    }


    // ====================== controller start =================================
    $(document).ready(function() {
        // Make an AJAX request when the page is ready
        $.ajax({
            url: '/user/get-mining-machine', // replace '/your-route' with the actual route
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                // Handle the successful response
                console.log(data);
                // You can update your HTML or perform other actions with the received data
            },
            error: function(error) {
                // Handle any errors that occurred during the request
                console.error('Error:', error);
            }
        });
    });


    // ====================== controller start =================================



    function updateAllRows() {
        const rows = document.querySelectorAll('.mining-server-row');
        // console.log(rows);
        rows.forEach((row, index) => {
            console.log(index);

            updateHashContainerForRow(row);
            updateMiningStatusForRow(row);
        });
    }

    fetchBTCPrice();
    setInterval(updateAllRows, 500);
</script> --}}
