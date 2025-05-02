<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Non-Clickable Buttons Webpage</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #111827; /* Dark background */
            color: #f9fafb; /* Light text */
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px; /* Increased max-width for larger screens */
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center; /* Center items horizontally */
            padding: 2rem;
            border-radius: 0.75rem; /* Rounded corners */
            /* No background color, inheriting from body */
        }
        .grid-container {
            display: grid;
            grid-template-columns: 1fr; /* Start with one column for small screens */
            gap: 1.5rem; /* Increased gap */
            width: 100%;
            margin-bottom: 0; /* Removed bottom margin */
        }
        @media (min-width: 768px) {
            .grid-container {
                grid-template-columns: repeat(2, 1fr); /* Two columns on medium screens and up */
            }
        }
        .button-item {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 1.5rem; /* Increased padding */
            border-radius: 0.75rem; /* Rounded corners */
            background-color: #374151; /* Darker button color */
            text-align: center;
            cursor: not-allowed; /* Indicate non-clickable */
            opacity: 0.7; /* Reduce opacity for non-clickable state */
            transition: opacity 0.2s ease; /* Smooth transition */
            border: 2px solid #4b5563; /* Add a border */
        }
        .button-item:hover {
            opacity: 0.7; /* Keep opacity on hover */
        }
        .button-icon {
            font-size: 2rem; /* Increased icon size */
            margin-bottom: 0.5rem; /* Space between icon and text */
            color: #cbd5e0; /* Light icon color */
        }
        .button-text {
            font-size: 1rem;
            font-weight: 500;
            color: #f9fafb; /* Light text color */
        }
        .heading {
            font-size: 2rem; /* Larger heading */
            font-weight: 600;
            color: #ffffff; /* White heading */
            margin-bottom: 2rem; /* Increased margin */
            text-align: center; /* Center heading */
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-50">
    <div class="container">
        <h1 class="heading">Options</h1>
        <div class="grid-container">
            <div class="button-item">
                <div class="button-icon">📅</div>
                <div class="button-text">Recent Activity</div>
            </div>
            <div class="button-item">
                <div class="button-icon">🎧</div>
                <div class="button-text">Customer Service</div>
            </div>
            <div class="button-item">
                <div class="button-icon">🔒</div>
                <div class="button-text">Change Password</div>
            </div>
            <div class="button-item">
                <div class="button-icon">ℹ</div>
                <div class="button-text">About Us</div>
            </div>
            <div class="button-item">
                <div class="button-icon">❓</div>
                <div class="button-text">FAQ Guide</div>
            </div>
            <div class="button-item">
                <div class="button-icon">📰</div>
                <div class="button-text">News</div>
            </div>
            <div class="button-item">
                <div class="button-icon">💳</div>
                <div class="button-text">Withdraw</div>
            </div>
            <div class="button-item">
                <div class="button-icon">➡</div>
                <div class="button-text">Logout</div>
            </div>
        </div>
    </div>
    <script>
        // No JavaScript needed for non-clickable buttons
    </script>
</body>
</html>