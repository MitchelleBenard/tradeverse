<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Withdraw Funds</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto mt-8 p-4">
        <h1 class="text-3xl font-semibold text-gray-800 text-center mb-6">Withdraw Funds</h1>

        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-700 mb-4">Balance</h2>
            <p id="balance" class="text-2xl text-green-600">KSh 0.00</p>
            <input type="hidden" id="initialBalance" value="0">
        </div>

        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-xl font-semibold text-gray-700 mb-4">Enter amount to withdraw</h2>
            <div class="flex items-center mb-4">
                <input type="number" id="amount" placeholder="Enter amount" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <button id="withdraw" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">WITHDRAW</button>
            <div id="message" class="mt-4 text-yellow-600 font-semibold"></div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const balanceDisplay = document.getElementById('balance');
            const amountInput = document.getElementById('amount');
            const withdrawButton = document.getElementById('withdraw');
            const messageDisplay = document.getElementById('message');
            const initialBalanceInput = document.getElementById('initialBalance');

            // Function to get balance from cookie
            const getBalanceFromCookie = () => {
                const balanceCookie = document.cookie.split('; ').find(row => row.startsWith('balance='));
                if (balanceCookie) {
                    return parseFloat(balanceCookie.split('=')[1]);
                }
                return 0; // Default balance if cookie doesn't exist
            };

            // Function to set balance in cookie
            const setBalanceInCookie = (balance) => {
                document.cookie = `balance=${balance}; path=/; max-age=${60 * 60 * 24 * 30}`; // Set cookie for 30 days
            };

            // Initialize balance from cookie or set to 0 if not available
            let balance = getBalanceFromCookie();
            balanceDisplay.textContent = `KSh ${balance.toFixed(2)}`;
            initialBalanceInput.value = balance.toFixed(2);

            // Withdraw function
            withdrawButton.addEventListener('click', () => {
                const amount = parseFloat(amountInput.value);
                if (isNaN(amount) || amount <= 0) {
                    messageDisplay.textContent = "Please enter a valid amount.";
                    return;
                }

                if (amount > balance) {
                    messageDisplay.textContent = "Insufficient balance.";
                    return;
                }

                // Update balance after withdrawal
                balance -= amount;
                balanceDisplay.textContent = `KSh ${balance.toFixed(2)}`;
                initialBalanceInput.value = balance.toFixed(2);
                setBalanceInCookie(balance);  // Store updated balance in cookie

                messageDisplay.textContent = "Withdrawal successful.";
                amountInput.value = '';  // Clear the input field
            });
        });
    </script>
</body>
</html>
