<table class="table--responsive--lg table">
    <thead>
        <tr>
            <th scope="col">@lang('Wallet Name')</th>
            <th scope="col">@lang('Miner status')</th>
            <th scope="col">@lang('Block Hash')</th>
            <th scope="col">@lang('Balance')</th>
        </tr>
    </thead>
    <tbody>
        {{-- @forelse($mining_servers as $mining_server) --}}

        <tr>
            <td>
                {{ strtoupper($mining_server->plan_details->miner) }}
            </td>
            <td class="budget">
                <span class="badge badge--success">Powered On</span>
            </td>
            <td>
                <div id="hash-container" class="hash-container"></div>
            </td>
            <td>
                <span id="mined-btc-container" class="mined-btc-container">0.0000000000 BTC</span>
                <span id="mined-usd-container" class="mined-usd-container">≈ $0.00</span>
            </td>
        </tr>
        {{-- @empty --}}
        {{-- <tr>
                <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
            </tr> --}}
        {{-- @endforelse --}}
    </tbody>
</table>


<script>
    let minedBTC = 0;
    let minedUSD = 0;
    let btcPrice;
    // var mining_server = @json($mining_server);



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

    async function updateHashContainer() {
        const hashContainer = document.getElementById('hash-container');
        generateRandomHash().then(hash => {
            // Insert '***' at the center of the hash
            const insertionIndex = Math.floor(hash.length / 2);
            const modifiedHash = hash.slice(0, insertionIndex) + '***' + hash.slice(insertionIndex);

            hashContainer.textContent = modifiedHash;
        });
    }

    async function getBTCPrice() {
        try {
            const response = await fetch(
                'https://api.coingecko.com/api/v3/simple/price?ids=bitcoin&vs_currencies=usd');
            const data = await response.json();
            console.log(data.bitcoin.usd);
            return data.bitcoin && typeof data.bitcoin.usd === 'number' ? data.bitcoin.usd : 0;
        } catch (error) {
            console.error('Error fetching BTC price:', error);
            return 0; // Return a default value in case of an error
        }
    }

    async function fetchBTCPrice() {
        btcPrice = await getBTCPrice();
        if (typeof btcPrice === 'number' && btcPrice > 0) {
            // Fetch BTC price once every 5 minutes after the initial fetch
            setInterval(fetchBTCPrice, 300000);
        } else {
            console.error('Invalid BTC price:', btcPrice);
        }
    }

    async function updateMiningStatus() {
        const minedBTCContainer = document.getElementById('mined-btc-container');
        const minedUSDContainer = document.getElementById('mined-usd-container');

        const targetUSD = 48;
        if (typeof btcPrice === 'number' && btcPrice > 0) {
            const targetBTC = targetUSD / btcPrice;
            const miningRatePerSecond = targetBTC / (24 * 60 * 60);

            minedBTC += miningRatePerSecond;

            minedUSD = minedBTC * btcPrice;

            // Update the mined BTC and its USD value
            const formattedBTC = minedBTC.toFixed(10);
            const formattedUSD = minedUSD.toFixed(2);

            minedBTCContainer.textContent = `${formattedBTC} BTC`;
            minedUSDContainer.textContent = `$${formattedUSD}`;
        } else {
            console.error('Invalid BTC price:', btcPrice);
        }
    }

    // Fetch BTC price immediately upon loading
    fetchBTCPrice();

    // Update the mining status every second
    setInterval(updateMiningStatus, 1000);

    // Update the hash container every 200 milliseconds
    setInterval(updateHashContainer, 130);

    // Initial updates
    updateMiningStatus();
    updateHashContainer();
</script>
