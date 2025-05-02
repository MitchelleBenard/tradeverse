<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deposit Funds</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto mt-8 p-4">
        <h1 class="text-3xl font-semibold text-gray-800 text-center mb-6">Deposit Funds</h1>

        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-700 mb-4">Balance</h2>
            <p id="balance" class="text-2xl text-green-600">KSh 0.00</p>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-xl font-semibold text-gray-700 mb-4">Enter amount to deposit</h2>
            <div class="flex items-center mb-4">
                <input type="number" id="amount" placeholder="Enter amount" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <button id="add" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">ADD</button>
            <div id="message" class="mt-4 text-yellow-600 font-semibold"></div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const balanceDisplay = document.getElementById('balance');
            const amountInput = document.getElementById('amount');
            const addButton = document.getElementById('add');
            const messageDisplay = document.getElementById('message');

            // Function to get balance from cookie
            const getBalanceFromCookie = () => {
                const balanceCookie = document.cookie.split('; ').find(row => row.startsWith('balance='));
                if (balanceCookie) {
                    return parseFloat(balanceCookie.split('=')[1]);
                }
                return 0; // Default balance if no cookie is set
            };

            // Function to set balance in cookie
            const setBalanceInCookie = (balance) => {
                document.cookie = `balance=${balance}; path=/; max-age=${60 * 60 * 24 * 30}`; // Cookie expires in 30 days
            };

            // Initialize balance from cookie or set to 0 if not available
            let balance = getBalanceFromCookie();
            balanceDisplay.textContent = `KSh ${balance.toFixed(2)}`;

            // Function to update balance display and cookie
            const updateBalance = () => {
                balanceDisplay.textContent = `KSh ${balance.toFixed(2)}`;
                setBalanceInCookie(balance); // Store balance in cookie
            };

            // Event listener for the Add button
            addButton.addEventListener('click', () => {
                const amount = parseFloat(amountInput.value);

                if (isNaN(amount) || amount <= 0) {
                    messageDisplay.textContent = 'Please enter a valid amount.';
                    setTimeout(() => messageDisplay.textContent = '', 3000);  // Clear message after 3 seconds
                    return;
                }

                balance += amount;
                messageDisplay.textContent = 'Deposit successful.';
                updateBalance(); // Update the balance both in cookie and display
                amountInput.value = '';  // Clear the input field
            });
        });
    </script>
</body>
</html>
