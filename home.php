<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tradeverse - Home</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .menu-button {
            display: none; /* Hide for larger screens */
            position: absolute;
            top: 1rem;
            right: 1rem;
            font-size: 1.5rem;
            cursor: pointer;
            color: #4b5563; /* Tailwind gray-700 */
        }
        @media (max-width: 768px) {
            .menu-button {
                display: block; /* Show on small screens */
            }
            .bottom-nav {
                display: none; /* Hide bottom nav on small screens */
                flex-direction: column;
                position: fixed;
                bottom: 0;
                left: 0;
                width: 100%;
                background-color: #f0f0f0; /* Light gray */
                padding: 1rem;
                text-align: center;
                box-shadow: 0px -2px 4px rgba(0,0,0,0.1); /* Subtle top shadow */
            }
            .bottom-nav.show {
                display: flex; /* Show nav when menu is clicked */
            }
            .bottom-nav a {
                margin: 0.5rem 0;
                padding: 0.75rem;
                border-radius: 0.375rem;
                color: #4b5563;
                text-decoration: none;
                font-weight: 500;
            }
             .bottom-nav a:hover {
                background-color: #e0e0e0;
            }
        }
        .bottom-nav {
            display: flex;
            justify-content: space-around;
            background-color: #f0f0f0;
            padding: 1rem;
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            box-shadow: 0px -2px 4px rgba(0,0,0,0.1);
        }
        .bottom-nav a {
            color: #4b5563;
            text-decoration: none;
            padding: 0.5rem;
            border-radius: 0.375rem;
            font-weight: 500;
        }
        .bottom-nav a:hover {
            background-color: #e0e0e0;
        }
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            background-color: #ffffff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        header h1 {
            color: #334155;
            margin: 0;
            font-size: 1.875rem;
        }
        main {
            padding: 2rem;
            min-height: calc(100vh - 14rem); /* Adjust based on header and nav height */
            margin-bottom: 4rem; /* Make space for fixed bottom nav */
        }
        .hero-section {
            background-color: #e0f2fe; /* Light blue */
            padding: 4rem 2rem;
            border-radius: 0.5rem;
            text-align: center;
            margin-bottom: 2rem;
        }
        .hero-section h2 {
            font-size: 2.5rem;
            color: #1e3a8a; /* Darker blue */
            margin-bottom: 1rem;
        }
        .hero-section p {
            font-size: 1.1rem;
            color: #4b5563;
            line-height: 1.75rem;
            margin-bottom: 2rem;
        }
       
        .info-section {
            display: grid;
            grid-template-columns: 1fr;
            gap: 2rem;
            margin-bottom: 2rem;
        }
        @media (min-width: 768px) {
            .info-section {
                grid-template-columns: 1fr 1fr;
            }
        }
        .info-card {
            background-color: #ffffff;
            padding: 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06);
        }
        .info-card h3 {
            font-size: 1.5rem;
            color: #1e293b;
            margin-bottom: 1rem;
        }
        .info-card p {
            font-size: 1rem;
            color: #4b5563;
            line-height: 1.75rem;
        }
        .tradingview-widget-container {
            margin-top: 2rem;
            border-radius: 0.5rem;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06);
        }
        .feature-section {
            background-color: #f7fafc;
            padding: 4rem 2rem;
            border-radius: 0.5rem;
            margin-bottom: 2rem;
            text-align: center;
        }
        .feature-section h2 {
            font-size: 2.5rem;
            color: #1e293b;
            margin-bottom: 2rem;
        }
        .feature-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 2rem;
            margin-top: 2rem;
        }
        @media (min-width: 768px) {
            .feature-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }
        .feature-item {
            background-color: #ffffff;
            padding: 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06);
            text-align: center;
        }
        .feature-item h3 {
            font-size: 1.25rem;
            color: #1e293b;
            margin-bottom: 1rem;
        }
        .feature-item p {
            font-size: 1rem;
            color: #4b5563;
            line-height: 1.75rem;
        }
    </style>
</head>
<body class="bg-gray-100">
    <header>
        <h1>Tradeverse</h1>
        <button id="menu-button" class="menu-button">☰</button>
    </header>

    <main>
        <section class="hero-section">
            <h2>Welcome to Tradeverse</h2>
            <p>Explore a world of cryptocurrency insights and trading tools. Tradeverse empowers you with the knowledge and resources to navigate the crypto landscape with confidence.</p>
        </section>

        <section class="info-section">
            <div class="info-card">
                <h3>About Tradeverse</h3>
                <p>Tradeverse is a platform dedicated to providing comprehensive information and tools for cryptocurrency enthusiasts. We offer a user-friendly interface, educational resources, and market analysis to help you make informed decisions.</p>
            </div>
            <div class="info-card">
                <h3>About Cryptocurrency</h3>
                <p>Cryptocurrencies are revolutionizing finance with their decentralized nature and transparent technology.  Learn about the potential of digital currencies and blockchain technology.</p>
            </div>
        </section>

        <section class="feature-section">
            <h2>Key Features</h2>
            <div class="feature-grid">
                <div class="feature-item">
                    <h3>Market Analysis</h3>
                    <p>Stay up-to-date with real-time market data and expert analysis.</p>
                </div>
                <div class="feature-item">
                    <h3>Educational Resources</h3>
                    <p>Expand your knowledge with our curated collection of articles and guides.</p>
                </div>
                <div class="feature-item">
                    <h3>Portfolio Tracking</h3>
                    <p>Monitor your investments and track your performance over time.</p>
                </div>
            </div>
        </section>

        <div class="tradingview-widget-container">
            <div class="tradingview-widget-container__widget"></div>
            <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-symbol-overview.js" async>
            {
            "symbols": [
              [
                "BINANCE:BTCUSDT|1D"
              ]
            ],
            "chartOnly": false,
            "width": "100%",
            "height": "600",
            "locale": "en",
            "colorTheme": "dark",
            "autosize": false,
            "showVolume": false,
            "showMA": false,
            "hideDateRanges": false,
            "hideMarketStatus": false,
            "hideSymbolLogo": false,
            "scaleType": "exchanges",
            "isTransparent": false,
            "displayMode": "regular",
            "symbolInfoBackgroundColor": "#48515c",
            "enableScrolling": false,
            "hideLeftSideToolbar": false,
            "borderWidth": 0,
            "borderColor": "#ffffff",
            "plotLineColor": "#9db2bd",
            "candleStyle": {
              "upColor": "#5c6bc0",
              "downColor": "#ec407a",
              "drawWick": true,
              "wickUpColor": "#9db2bd",
              "wickDownColor": "#9db2bd",
              "barColorsOnPrevClose": false
            },
            "studies": [
              "RSI",
              "MACD"
            ]
            }
            </script>
        </div>

    </main>

</body>
</html>
