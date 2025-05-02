<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tradeverse Account</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <header>
        <h1>Tradeverse</h1>
        <button class="menu-button">☰</button>
    </header>
    
    <main>
        <div class="account-box">
            <h2>My Account</h2>
            <p>
                From your account dashboard you can view your recent orders,
                manage your shipping and billing addresses,
                and edit your password and account details.
            </p>
        </div>
    </main>
    
    <!-- ✅ Updated Navigation -->
    <nav class="bottom-nav">
        <a href="/frontend/home.php" class="nav-item">Home</a>
        <a href="/frontend/invest.php" class="nav-item">Invest</a>
        <a href="/frontend/deposit.php" class="nav-item">Deposit</a>
        <a href="/frontend/withdraw.php" class="nav-item">Withdraw</a>
        <a href="/frontend/account.php" class="nav-item">Account</a>
    </nav>

    <!-- ✅ TradingView Widget -->
    <div class="tradingview-widget-container">
        <div class="tradingview-widget-container__widget"></div>
        <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-symbol-overview.js" async>
        {
            "symbols": [["BINANCE:BTCUSDT|1D"]],
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
                "drawBorder": false
            },
            "areaStyle": {
                "color": "#664caf",
                "linecolor": "#9db2bd",
                "linewidth": 1
            }
        }
        </script>
    </div>

    <script src="index.js"></script>
</body>
</html>
