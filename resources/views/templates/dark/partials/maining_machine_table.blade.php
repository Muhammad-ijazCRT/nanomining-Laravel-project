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

        $.ajax({
            url: "{{ route('user.get.mining.server.data') }}",
            type: 'GET',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {
                data.forEach(function(item) {
                    // console.log(item.user_coin_balance.user_coin_balance_miner1.balance);
                    // Assuming item.wallet_amount is in BTC
                    // var btcAmount = item.wallet_amount;
                    // console.log(btcToUsdRate);
                    var btcAmount = item.user_coin_balance.user_coin_balance_miner1.balance
                    var usdAmount = btcAmount * btcToUsdRate;

                    // console.log(usdAmount);

                    // assign data to table columns
                    $('#mining_machine_' + item.id).html(parseFloat(
                            item.active_mined_coins[0]['mined_coins']).toFixed(10) +
                        ' ≈ $' + usdAmount.toFixed(2));

                    const UserBTCBalance = document.getElementById('User1Balance')
                    const UserLTCBalance = document.getElementById('User2Balance')
                    if (item.miner.name == 'BTC') {

                        // console.log(item.user_coin_balance.balance);

                        // console.log(item.miner.name);
                        const UpdatedBTCBalance = (parseFloat(item.user_coin_balance
                                .user_coin_balance_miner1.balance))
                            .toFixed(10);
                        UserBTCBalance.innerHTML = UpdatedBTCBalance;
                    }
                    // id="UserLTCBalance"
                    if (item.miner.name == 'LTC') {
                        const UpdatedLTCBalance = (parseFloat(item.user_coin_balance
                                .user_coin_balance_miner2.balance))
                            .toFixed(10);
                        UserLTCBalance.innerHTML = UpdatedLTCBalance;
                    }


                });
            },
            error: function(error) {
                console.error('Error fetching data:', error);
            }
        });
    }


    // fetchDataAndDisplay()
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
