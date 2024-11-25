<header class="header">
    <script src="scripts/signup.js" defer></script>
    <div id="wordmark">
        <img src="images/logo.svg" alt="Pizza Shop Logo">
        <h2>PIZZA SHOP</h2>
    </div>
    <a class="sign-in-link" href="#">SIGN IN</a>
    <div class="modal user-modal">
        <form class="sign-in modal-content modal-form visible" aria-modal="true">
            <button type="button" class="modal-close">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="0.00012207" y="17.6777" width="25" height="2.5" rx="1"
                        transform="rotate(-45 0.00012207 17.6777)" fill="currentColor" />
                    <rect x="1.73669" width="25" height="2.5" rx="1" transform="rotate(44 1.73669 0)"
                        fill="currentColor" />
                </svg>
            </button>
            <h1>Welcome Back</h1>
            <div class="sign-in modal-item">
                <label for="email">Email</label>
                <input type="email" id="sign-in-email" name="email" placeholder="john.doe@example.com">
            </div>
            <div class="sign-in modal-item">
                <label for="password">Password</label>
                <input type="password" id="sign-password" name="password" placeholder="Enter your password">
            </div>
            <button type="submit" id="sign-in-submit" class="modal-submit">Sign In</button>
            <p class="account-prompt sign-up">No account? <a href="#">Make an account <u>here!</u></a></p>
        </form>
        <form class="sign-up modal-content modal-form" aria-modal="true">
            <button type="button" class="modal-close">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="0.00012207" y="17.6777" width="25" height="2.5" rx="1" transform="rotate(-45 0.00012207 17.6777)" fill="currentColor" />
                    <rect x="1.73669" width="25" height="2.5" rx="1" transform="rotate(44 1.73669 0)" fill="currentColor" />
                </svg>
            </button>
            <h1>Sign up</h1>
            <div class="sign-up modal-item double">
                <div class="modal-item">
                    <label for="first-name">First Name</label>
                    <input class="double-input" type="text" id="first-name" name="first-name" placeholder="John"  onblur = "validateNameField(this)" onfocus="onFocused(this)" required>
                </div>
                 <div class="modal-item">
                    <label for="last-name">Last Name</label>
                    <input class="double-input" type="text" id="last-name" name="last-name" placeholder="Doe"  onblur = "validateNameField(this)" onfocus="onFocused(this)" required>
                </div>
            </div>
            <div class="sign-up modal-item">
                <label for="email">Email</label>
                <input type="email" id="sign-up-email" name="email" placeholder="john.doe@example.com" onblur = "validateEmailField(this)" onfocus="onFocused(this)" required>
            </div>
            <div class="sign-up modal-item double">
                <div class="modal-item">
                    <label for="password">Password</label>
                    <input class="double-input" type="password" id="sign-up-password" name="password"
                            placeholder="Enter your password"  onblur = "validatePasswordField(this)" onfocus="onFocused(this)" required>
                </div>
                <div class="modal-item">
                    <label for="confirm-password">Confirm Password</label>
                    <input class="double-input" type="password" id="sign-up-confirm-password"
                            name="confirm-password" placeholder="Re-enter your password"  onblur = "validateNameField(this)" onfocus="onFocused(this)" required>
                </div>
            </div>
            <button type="submit" id="sign-up-submit" class="modal-submit">Submit</button>
            <p class="account-prompt sign-in">Already have an account? <a href="#">Sign in <u>here!</u></a></p>
        </form>
    </div>
    <a id="signed-in" href="#">SIGNED IN AS: NAME</a>
</header>
