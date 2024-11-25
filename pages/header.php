<?php 
if (!(headers_sent())) {
    session_start();
}
$href = '#';
$text = 'SIGN IN';
$class = 'sign-in-link';
if (isset($_SESSION['loginID'])) {
    $href = '../server/logout.php';
    $name = htmlspecialchars($_SESSION['f_name']) . ' ' . htmlspecialchars($_SESSION['l_name']);
    $text = 'SIGNED IN AS: ' . $name;
    $class = 'signed-in-link';
}
?>

<link rel="stylesheet" type="text/css" href="../css/globals.css">
<script src="../scripts/global.js" defer></script>
<header class="header">
    <script src="../scripts/signup.js" defer></script>
    <div id="wordmark">
        <img src="../images/logo.svg" alt="Pizza Shop Logo">
        <h2>PIZZA SHOP</h2>
    </div>
        <a id='sign-in' class="<?php echo $class ?>" href="<?php echo $href ?>"><?php echo $text ?></a>
    <div class="modal user-modal">
        <form class="sign-in modal-content modal-form visible" aria-modal="true" action="../server/login.php" method="POST">
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
                <label for="sign-in-email">Email</label>
                <input type="email" id="sign-in-email" name="email" placeholder="john.doe@example.com">
            </div>
            <div class="sign-in modal-item">
                <label for="sign-in-password">Password</label>
                <input type="password" id="sign-in-password" name="password" placeholder="Enter your password">
            </div>
            <button type="submit" id="sign-in-submit" class="modal-submit">Sign In</button>
            <p class="account-prompt sign-up">No account? <a href="#">Make an account <u>here!</u></a></p>
        </form>
        <form class="sign-up modal-content modal-form" aria-modal="true" action = "../server/signup.php" method ="POST" onsubmit = "return validateSignup()">
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
                <label for="sign-up-email">Email</label>
                <input type="email" id="sign-up-email" name="email" placeholder="john.doe@example.com" onblur = "validateEmailField(this)" onfocus="onFocused(this)" required>
            </div>
            <div class="sign-up modal-item double">
                <div class="modal-item">
                    <label for="sign-up-password">Password</label>
                    <input class="double-input" type="password" id="sign-up-password" name="password"
                            placeholder="Enter your password"  onblur = "validatePasswordField(this)" onfocus="onFocused(this)" required>
                </div>
                <div class="modal-item">
                    <label for="sign-up-confirm-password">Confirm Password</label>
                    <input class="double-input" type="password" id="sign-up-confirm-password"
                            name="confirm-password" placeholder="Re-enter your password"  onblur = "validateNameField(this)" onfocus="onFocused(this)" required>
                </div>
            </div>
            <button type="submit" id="sign-up-submit" class="modal-submit">Submit</button>
            <p class="account-prompt sign-in">Already have an account? <a href="#">Sign in <u>here!</u></a></p>
        </form>
    </div>
</header>
