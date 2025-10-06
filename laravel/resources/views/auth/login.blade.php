<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Delete Account</title>
    <style>
        /* نفس التنسيقات السابقة */
        body {
            font-family: Arial, sans-serif;
            background-color: #f3f4f6;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            background-color: #ffffff;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        h2 {
            margin-bottom: 1rem;
            color: #333;
            text-align: center;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        label {
            display: block;
            font-size: 0.9rem;
            color: #555;
            margin-bottom: 0.5rem;
        }

        input {
            width: 100%;
            padding: 0.75rem;
            font-size: 1rem;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .role-buttons {
            display: flex;
            gap: 10px;
            margin-bottom: 1rem;
        }

        .role-button {
            flex: 1;
            padding: 0.75rem;
            font-size: 0.9rem;
            border: 1px solid #ccc;
            border-radius: 4px;
            background-color: #f3f4f6;
            cursor: pointer;
            text-align: center;
            transition: background-color 0.3s ease;
        }

        .role-button.active {
            background-color: #007bff;
            color: white;
            border-color: #0056b3;
        }

        .role-button:hover {
            color: #000;
            background-color: #e2e6ea;
        }

        button {
            width: 100%;
            padding: 0.75rem;
            font-size: 1rem;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 4px;
            cursor: not-allowed;
            opacity: 0.6;
            transition: background-color 0.3s ease, opacity 0.3s ease;
        }

        button:enabled {
            cursor: pointer;
            opacity: 1;
        }

        button:hover:enabled {
            background-color: #0056b3;
        }

        .message {
            margin-top: 1rem;
            font-size: 0.9rem;
            text-align: center;
            color: red;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Delete Account</h2>
        <form id="loginForm">
            <div class="form-group">
                <label>Login as:</label>
                <div class="role-buttons">
                    <div class="role-button" data-role="schools">School</div>
                    <div class="role-button" data-role="attendants">Driver or supervisor</div>
                    <div class="role-button" data-role="parents">Parent</div>
                </div>
            </div>
            <div class="form-group">
                <label for="email" id="emailLabel">Email:</label>
                <input type="text" id="email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>
            <button type="submit" id="loginButton" disabled>Delete Account</button>
        </form>
        <p id="message" class="message"></p>
    </div>
    <script>
        const roleButtons = document.querySelectorAll('.role-button');
        const emailLabel = document.getElementById('emailLabel');
        const emailInput = document.getElementById('email');
        const loginButton = document.getElementById('loginButton');
        const messageElement = document.getElementById('message');
        let selectedRole = null;
        let inputName = 'email';

        roleButtons.forEach(button => {
            button.addEventListener('click', function() {
                roleButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                selectedRole = this.getAttribute('data-role');

                if (selectedRole === 'attendants') {
                    inputName = 'username';
                    emailLabel.textContent = 'Username:';
                    emailInput.placeholder = 'Enter your username';
                } else {
                    emailLabel.textContent = 'Email:';
                    emailInput.placeholder = 'Enter your email';
                }

                loginButton.disabled = false;
            });
        });

        document.getElementById('loginForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const username = emailInput.value.trim();
            const password = document.getElementById('password').value.trim();
            const crad = { [inputName]: username };

            if (!selectedRole) {
                messageElement.textContent = 'Please select a role.';
                return;
            }

            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                console.log(csrfToken);
                const response = await fetch(`/api/${selectedRole}/login`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({
                        [inputName]: username,
                        password,
                        firebase_token: 'fack_fcm_token'
                    }),
                });

                const data = await response.json();
                if (response.ok) {
                    messageElement.textContent = `Your account will be removed after 90 days.`;
                    messageElement.style.color = 'green';
                } else {
                    messageElement.textContent = data.message || 'Invalid credentials.';
                    messageElement.style.color = 'red';
                }
            } catch (error) {
                messageElement.textContent = 'An error occurred. Please try again.';
                messageElement.style.color = 'red';
            }
        });
    </script>
</body>
</html>
