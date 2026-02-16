<!DOCTYPE html>
<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dog Adoption Login - BestFriends</title>

    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script src="https://accounts.google.com/gsi/client" async defer></script>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;700;800&family=Noto+Sans:wght@400;500;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />

    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#2b93ee",
                        "background-light": "#f6f7f8",
                        "background-dark": "#101a22",
                    },
                    fontFamily: {
                        display: ["Plus Jakarta Sans", "Noto Sans", "sans-serif"]
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-background-light font-display">
<div class="min-h-screen flex flex-col lg:flex-row">

        <!-- LEFT -->
        <div class="hidden lg:flex w-1/2 bg-cover bg-center items-end relative"
            style="background-image:url('https://lh3.googleusercontent.com/aida-public/AB6AXuCIuhJiRB7a1bcpiwHUlNnhdCUkIg86tFkIi8TaLuCxDc7W4DivEr_19WzozW4-kc21CCTiiH2pZ1Q6sF50rrw2Cg2CeliMb7xvP7X-vLIJGvbdULaC30BCk9Sx9Trc_mXgOTyi-soS2qaa8LIbQSww0u_ZJEYuJbm-S4gkxYMOM55dAI5hj2Ff1FLuEuhXQbwaSq5WT5IbOs9kjRJbISfhPQfcSrl6dLibMJICvGeR3HMbiU_5EJQkzV3Z-98iRIa9JE4pyX5uNrVK');">
            <div class="absolute inset-0 bg-black/70"></div>
            <div class="relative p-16 text-white">
                <h1 class="text-5xl font-black">Find your new best friend.</h1>
                <p class="mt-4 text-lg text-gray-200">
                    Join our community and give a dog a forever home.
                </p>
            </div>
        </div>

        <!-- RIGHT -->
        <div class="w-full lg:w-1/2 flex justify-center items-center p-8 bg-white">
            <div class="w-full max-w-md flex flex-col gap-6">

                <a href="/" class="inline-flex items-center gap-2 text-sm font-semibold text-gray-600 hover:text-primary transition-colors">
                    <span class="material-symbols-outlined !text-base">arrow_back</span>
                    Back to Home
                </a>

                <!-- HEADER -->
                <div>
                    <h2 class="text-3xl font-bold">Welcome to Pet Finder</h2>
                    <p class="text-gray-500">Please enter your details</p>
                </div>

                <!-- TOGGLE -->
                <div class="flex bg-gray-100 rounded-lg p-1">
                    <label class="flex-1 cursor-pointer">
                        <input id="loginRadio" name="auth_mode" type="radio" class="peer hidden" @checked(!old('fullname'))>
                        <div class="text-center py-2 rounded-md peer-checked:bg-white font-medium">
                            Log In
                        </div>
                    </label>

                    <label class="flex-1 cursor-pointer">
                        <input id="signupRadio" name="auth_mode" type="radio" class="peer hidden" @checked(old('fullname'))>
                        <div class="text-center py-2 rounded-md peer-checked:bg-white font-medium">
                            Sign Up
                        </div>
                    </label>
                </div>

                <!-- FORM -->
                <form id="authForm" class="flex flex-col gap-4" action="/login" method="POST">
                    @csrf
                    @if ($errors->any())
                        <div class="rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-700">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <!-- AUTH TYPE -->


                    <!-- EMAIL -->
                    <div>
                        <input id="identifier" name="identifier" type="text"
                            placeholder="Email or phone number"
                            value="{{ old('identifier', old('email')) }}"
                            class="h-12 px-4 border rounded-lg w-full">
                        <p id="emailError" class="text-red-500 text-sm hidden"></p>
                    </div>

                    <!-- SIGNUP FIELDS -->
                    <div id="signupFields" class="hidden flex flex-col gap-4">

                        <div>
                            <input id="fullName" name="fullname" type="text"
                                placeholder="Full Name"
                                value="{{ old('fullname') }}"
                                class="h-12 px-4 border rounded-lg w-full">
                            <p id="nameError" class="text-red-500 text-sm hidden"></p>
                        </div>

                        <div>
                            <div class="flex gap-2">
                                <select id="phoneCountry" name="phone_country" class="h-12 px-3 border rounded-lg bg-white">
                                    <option value="IN" @selected(old('phone_country', 'IN') === 'IN')>🇮🇳 India (+91)</option>
                                    <option value="US" @selected(old('phone_country') === 'US')>🇺🇸 US (+1)</option>
                                    <option value="AU" @selected(old('phone_country') === 'AU')>🇦🇺 Australia (+61)</option>
                                    <option value="DE" @selected(old('phone_country') === 'DE')>🇩🇪 Germany (+49)</option>
                                </select>
                                <input id="phone" name="phone" type="tel"
                                    placeholder="Phone Number"
                                    value="{{ old('phone') }}"
                                    class="h-12 px-4 border rounded-lg w-full">
                            </div>
                            <p id="phoneError" class="text-red-500 text-sm hidden"></p>
                        </div>

                        <div>
                            <input id="signupPassword" name="signup_password" type="password"
                                placeholder="Password"
                                class="h-12 px-4 border rounded-lg w-full password">
                            <p id="signupPasswordError" class="text-red-500 text-sm hidden"></p>
                        </div>

                        <div>
                            <input id="confirmPassword" name="signup_password_confirmation" type="password"
                                placeholder="Confirm Password"
                                class="h-12 px-4 border rounded-lg w-full password">
                            <p id="confirmError" class="text-red-500 text-sm hidden"></p>
                        </div>
                    </div>

                    <!-- LOGIN PASSWORD -->
                    <div id="loginPasswordField">
                        <input id="loginPassword" name="login_password" type="password"
                            placeholder="Password"
                            class="h-12 px-4 border rounded-lg w-full password">
                        <p id="loginPasswordError" class="text-red-500 text-sm hidden"></p>
                    </div>

                    <button id="submitBtn"
                        class="h-12 bg-primary text-white rounded-lg font-bold">
                        Log In
                    </button>

                    <div class="relative my-1">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-200"></div>
                        </div>
                        <div class="relative flex justify-center text-xs uppercase">
                            <span class="bg-white px-2 text-gray-500">Or</span>
                        </div>
                    </div>

                    <div>
                        <div id="googleSignInButton" class="w-full"></div>
                        <p id="googleError" class="text-red-500 text-sm mt-2 hidden"></p>
                    </div>
                </form>


            </div>
        </div>
    </div>

    <!-- JS -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            const loginRadio = document.getElementById("loginRadio");
            const signupRadio = document.getElementById("signupRadio");
            const signupFields = document.getElementById("signupFields");
            const loginPasswordField = document.getElementById("loginPasswordField");
            const submitBtn = document.getElementById("submitBtn");
            const form = document.getElementById("authForm");
            const csrfToken = document.querySelector('input[name="_token"]')?.value || "";
            const googleSignInButton = document.getElementById("googleSignInButton");
            const googleError = document.getElementById("googleError");
            const googleClientId = @json(config('services.google.client_id'));

            const identifier = document.getElementById("identifier");
            const fullName = document.getElementById("fullName");
            const phoneCountry = document.getElementById("phoneCountry");
            const phone = document.getElementById("phone");
            const signupPassword = document.getElementById("signupPassword");
            const confirmPassword = document.getElementById("confirmPassword");
            const loginPassword = document.getElementById("loginPassword");

            function toggleFields() {
                if (signupRadio.checked) {
                    signupFields.classList.remove("hidden");
                    loginPasswordField.classList.add("hidden");
                    submitBtn.textContent = "Create Account";
                    identifier.placeholder = "Email address";
                } else {
                    signupFields.classList.add("hidden");
                    loginPasswordField.classList.remove("hidden");
                    submitBtn.textContent = "Log In";
                    identifier.placeholder = "Email or phone number";
                }
            }

            loginRadio.addEventListener("change", toggleFields);
            signupRadio.addEventListener("change", toggleFields);

            function showError(id, msg) {
                const el = document.getElementById(id);
                if (!el) return;
                el.textContent = msg;
                el.classList.remove("hidden");
            }

            function showGoogleError(msg) {
                if (!googleError) return;
                googleError.textContent = msg;
                googleError.classList.remove("hidden");
            }

            function clearGoogleError() {
                if (!googleError) return;
                googleError.textContent = "";
                googleError.classList.add("hidden");
            }

            function clearErrors() {
                document.querySelectorAll(".text-red-500").forEach(e => {
                    e.textContent = "";
                    e.classList.add("hidden");
                });
            }

            async function handleGoogleCredential(response) {
                clearErrors();
                clearGoogleError();

                const credential = response?.credential || "";
                if (!credential) {
                    showGoogleError("Google sign-in response is invalid.");
                    return;
                }

                try {
                    const authResponse = await fetch("/auth/google", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "Accept": "application/json",
                            "X-CSRF-TOKEN": csrfToken
                        },
                        body: JSON.stringify({
                            credential
                        })
                    });

                    const data = await authResponse.json().catch(() => ({}));
                    if (!authResponse.ok) {
                        showGoogleError(data.message || "Google sign-in failed.");
                        return;
                    }

                    window.location.href = data.redirect || "/profile";
                } catch (error) {
                    showGoogleError("Unable to sign in with Google right now. Please try again.");
                }
            }

            function initGoogleSignIn(attempt = 0) {
                if (!googleSignInButton) return;

                if (!googleClientId) {
                    googleSignInButton.innerHTML = "";
                    return;
                }

                if (typeof google === "undefined" || !google.accounts || !google.accounts.id) {
                    if (attempt < 20) {
                        setTimeout(() => initGoogleSignIn(attempt + 1), 150);
                        return;
                    }
                    showGoogleError("Google SDK failed to load.");
                    return;
                }

                google.accounts.id.initialize({
                    client_id: googleClientId,
                    callback: handleGoogleCredential
                });

                const buttonWidth = Math.max(220, Math.min(380, googleSignInButton.clientWidth || 320));
                google.accounts.id.renderButton(googleSignInButton, {
                    type: "standard",
                    theme: "outline",
                    size: "large",
                    text: "continue_with",
                    shape: "rectangular",
                    width: buttonWidth
                });
            }

            form.addEventListener("submit", function(e) {
                e.preventDefault();
                clearErrors();

                let valid = true;

                const loginValue = (identifier.value || "").trim();
                const isEmail = /^\S+@\S+\.\S+$/.test(loginValue);
                const isPhone = /^\+?[0-9]{6,15}$/.test(loginValue);

                if (signupRadio.checked) {
                    if (!loginValue) {
                        showError("emailError", "Email is required");
                        valid = false;
                    } else if (!isEmail) {
                        showError("emailError", "Invalid email format");
                        valid = false;
                    }

                    // Signup validation
                    if (!fullName.value.trim()) {
                        showError("nameError", "Full name is required");
                        valid = false;
                    }

                    if (!phone.value.trim()) {
                        showError("phoneError", "Phone number is required");
                        valid = false;
                    } else if (!/^[0-9]{6,15}$/.test(phone.value.trim())) {
                        showError("phoneError", "Enter a valid phone number (6-15 digits)");
                        valid = false;
                    }

                    if (!phoneCountry || !phoneCountry.value) {
                        showError("phoneError", "Select country code");
                        valid = false;
                    }

                    if (!signupPassword.value.trim()) {
                        showError("signupPasswordError", "Password is required");
                        valid = false;
                    } else if (signupPassword.value.length < 6) {
                        showError("signupPasswordError", "Minimum 6 characters required");
                        valid = false;
                    }

                    if (!confirmPassword.value.trim()) {
                        showError("confirmError", "Confirm your password");
                        valid = false;
                    } else if (confirmPassword.value !== signupPassword.value) {
                        showError("confirmError", "Passwords do not match");
                        valid = false;
                    }
                } else {
                    if (!loginValue) {
                        showError("emailError", "Email or phone number is required");
                        valid = false;
                    } else if (!isEmail && !isPhone) {
                        showError("emailError", "Enter a valid email or phone number");
                        valid = false;
                    }

                    // Login validation
                    if (!loginPassword.value.trim()) {
                        showError("loginPasswordError", "Password is required");
                        valid = false;
                    } else if (loginPassword.value.length < 6) {
                        showError("loginPasswordError", "Minimum 6 characters required");
                        valid = false;
                    }
                }

                if (!valid) {
                    return;
                }

                form.submit();
            });

            toggleFields();
            initGoogleSignIn();
        });
    </script>

</body>

</html>
