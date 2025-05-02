<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Investment Coins</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #000000; /* Black background for the entire page */
        }
        .coin-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 1rem;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
            width: 250px;
            height: auto;
            margin-bottom: 1rem;
            background-color: #FFFFFF; /* White background for the coin boxes */
        }

        .coin-container:hover {
            transform: translateY(-0.5rem) scale(1.05);
            box-shadow: 0 6px 8px -1px rgba(0, 0, 0, 0.15), 0 3px 6px -1px rgba(0, 0, 0, 0.08);
        }

        .coin-image {
            width: 150px;
            height: auto;
            margin-bottom: 0.75rem;
            border-radius: 50%;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border: 2px solid #e0e0e0;
        }

        .coin-name {
            font-size: 1.25rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.25rem;
        }

        .coin-price {
            font-size: 1rem;
            color: #4b5563;
            margin-bottom: 0.5rem;
        }

        .clickable-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            background-color: #4CAF50;
            color: white;
            font-size: 1.1rem;
            font-weight: 600;
            text-decoration: none;
            transition: background-color 0.3s ease;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            width: auto;
            margin-top: 0.5rem;
        }

        .clickable-link:hover {
            background-color: #45a049;
            transform: translateY(-0.2rem);
            box-shadow: 0 3px 7px rgba(0, 0, 0, 0.3);
        }

        .clickable-link:active {
            background-color: #388e3c;
            transform: translateY(0);
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
        }

        .coins-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            padding: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="container mx-auto">
        <h1 class="text-2xl font-semibold text-white text-center mb-4">Invest in Cryptocurrency</h1>
        <p class="text-white text-center mb-8">Click on a coin to invest via M-PESA</p>

        <div class="coins-grid">
            <div class="coin-container">
                <img src="https://cryptologos.cc/logos/solana-sol-logo.png?v=029" alt="Solana" class="coin-image">
                <h2 class="coin-name">Solana</h2>
                <p class="coin-price">KSh 500.00</p>
                <a href="manual_payment.php?coin=Solana&amount=500" class="clickable-link">Invest Now</a>
            </div>

            <div class="coin-container">
                <img src="https://cryptologos.cc/logos/cardano-ada-logo.png?v=029" alt="Cardano" class="coin-image">
                <h2 class="coin-name">Cardano</h2>
                <p class="coin-price">KSh 1,200.00</p>
                <a href="manual_payment.php?coin=Cardano&amount=1200" class="clickable-link">Invest Now</a>
            </div>

            <div class="coin-container">
                <img src="https://cryptologos.cc/logos/xrp-xrp-logo.png?v=029" alt="XRP" class="coin-image">
                <h2 class="coin-name">XRP</h2>
                <p class="coin-price">KSh 2,000.00</p>
                <a href="manual_payment.php?coin=XRP&amount=2000" class="clickable-link">Invest Now</a>
            </div>

            <div class="coin-container">
                <img src="https://cryptologos.cc/logos/polkadot-new-pdot-logo.png?v=029" alt="Polkadot" class="coin-image">
                <h2 class="coin-name">Polkadot</h2>
                <p class="coin-price">KSh 4,500.00</p>
                <a href="manual_payment.php?coin=Polkadot&amount=4500" class="clickable-link">Invest Now</a>
            </div>

            <div class="coin-container">
                <img src="https://cryptologos.cc/logos/avalanche-avax-logo.png?v=029" alt="Avalanche" class="coin-image">
                <h2 class="coin-name">Avalanche</h2>
                <p class="coin-price">KSh 7,500.00</p>
                <a href="manual_payment.php?coin=Avalanche&amount=7500" class="clickable-link">Invest Now</a>
            </div>

            <div class="coin-container">
                <img src="https://cryptologos.cc/logos/chainlink-link-logo.png?v=029" alt="Chainlink" class="coin-image">
                <h2 class="coin-name">Chainlink</h2>
                <p class="coin-price">KSh 10,500.00</p>
                <a href="manual_payment.php?coin=Chainlink&amount=10500" class="clickable-link">Invest Now</a>
            </div>

            <div class="coin-container">
                <img src="https://cryptologos.cc/logos/uniswap-uni-logo.png?v=029" alt="Uniswap" class="coin-image">
                <h2 class="coin-name">Uniswap</h2>
                <p class="coin-price">KSh 16,500.00</p>
                <a href="manual_payment.php?coin=Uniswap&amount=16500" class="clickable-link">Invest Now</a>
            </div>

            <div class="coin-container">
                <img src="https://cryptologos.cc/logos/dogecoin-doge-logo.png?v=029" alt="Dogecoin" class="coin-image">
                <h2 class="coin-name">Dogecoin</h2>
                <p class="coin-price">KSh 22,500.00</p>
                <a href="manual_payment.php?coin=Dogecoin&amount=22500" class="clickable-link">Invest Now</a>
            </div>

            <div class="coin-container">
                <img src="https://cryptologos.cc/logos/cosmos-atom-logo.png?v=029" alt="Cosmos" class="coin-image">
                <h2 class="coin-name">Cosmos</h2>
                <p class="coin-price">KSh 35,000.00</p>
                <a href="manual_payment.php?coin=Cosmos&amount=35000" class="clickable-link">Invest Now</a>
            </div>

            <div class="coin-container">
                <img src="https://cryptologos.cc/logos/binance-coin-bnb-logo.png?v=029" alt="Binance" class="coin-image">
                <h2 class="coin-name">Binance</h2>
                <p class="coin-price">KSh 75,000.00</p>
                <a href="manual_payment.php?coin=Binance&amount=75000" class="clickable-link">Invest Now</a>
            </div>

            <div class="coin-container">
                <img src="https://cryptologos.cc/logos/bitcoin-btc-logo.png?v=029" alt="Bitcoin" class="coin-image">
                <h2 class="coin-name">Bitcoin</h2>
                <p class="coin-price">KSh 100,000.00</p>
                <a href="manual_payment.php?coin=Bitcoin&amount=100000" class="clickable-link">Invest Now</a>
            </div>
        </div>
    </div>
</body>
</html>
