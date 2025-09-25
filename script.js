// Utility Functions
function showAlert(type, message) {
    const alertHtml = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    $('#alert-container').html(alertHtml);
}

// Toggle Dark Mode
function toggleDarkMode() {
    const body = document.body;
    const navbar = document.querySelector('.navbar');
    const navLinks = document.querySelectorAll('.nav-link');
    const cards = document.querySelectorAll('.card');
    const buttons = document.querySelectorAll('.btn');

    body.classList.toggle('dark-mode');
    body.classList.toggle('light-mode');
    navbar.classList.toggle('navbar-dark-mode');
    navbar.classList.toggle('navbar-light-mode');

    navLinks.forEach(link => {
        link.classList.toggle('nav-link-dark-mode');
        link.classList.toggle('nav-link-light-mode');
    });

    cards.forEach(card => {
        card.classList.toggle('card-dark-mode');
        card.classList.toggle('card-light-mode');
    });

    buttons.forEach(button => {
        button.classList.toggle('btn-dark-mode');
        button.classList.toggle('btn-light-mode');
    });

    // Save the mode in localStorage
    if (body.classList.contains('dark-mode')) {
        localStorage.setItem('theme', 'dark');
    } else {
        localStorage.setItem('theme', 'light');
    }
}

// Load the saved theme from localStorage
document.addEventListener('DOMContentLoaded', () => {
    const savedTheme = localStorage.getItem('theme');
    const toggleDarkModeCheckbox = document.getElementById('toggleDarkMode');
    if (savedTheme) {
        if (savedTheme === 'dark') {
            document.body.classList.add('dark-mode');
            document.querySelector('.navbar').classList.add('navbar-dark-mode');
            document.querySelectorAll('.nav-link').forEach(link => link.classList.add('nav-link-dark-mode'));
            document.querySelectorAll('.card').forEach(card => card.classList.add('card-dark-mode'));
            document.querySelectorAll('.btn').forEach(button => button.classList.add('btn-dark-mode'));
            toggleDarkModeCheckbox.checked = true;
        } else {
            document.body.classList.add('light-mode');
            document.querySelector('.navbar').classList.add('navbar-light-mode');
            document.querySelectorAll('.nav-link').forEach(link => link.classList.add('nav-link-light-mode'));
            document.querySelectorAll('.card').forEach(card => card.classList.add('card-light-mode'));
            document.querySelectorAll('.btn').forEach(button => button.classList.add('btn-light-mode'));
            toggleDarkModeCheckbox.checked = false;
        }
    } else {
        document.body.classList.add('light-mode');
        document.querySelector('.navbar').classList.add('navbar-light-mode');
        document.querySelectorAll('.nav-link').forEach(link => link.classList.add('nav-link-light-mode'));
        document.querySelectorAll('.card').forEach(card => card.classList.add('card-light-mode'));
        document.querySelectorAll('.btn').forEach(button => card.classList.add('btn-light-mode'));
        toggleDarkModeCheckbox.checked = false;
    }
});

// Sample Code Functions
function toggleSampleCode() {
    const codeField = document.getElementById("sampleCode");
    const hiddenCode = "xcsadas%$$%&^23544";  // Store actual code in a constant
    const maskedCode = "************";
    
    codeField.value = (codeField.value === maskedCode) ? hiddenCode : maskedCode;
}

// Secret Code Handling
function submitSecretCode() {
    const code = $('#secretCode').val().trim();
    if (!code) {
        showAlert('danger', 'Please enter a secret code.');
        return;
    }

    $.ajax({
        url: 'secretCode.php',
        type: 'POST',
        data: {
            verify_code: true,
            secret_code: code
        },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                showAlert('success', response.message);
                updateCoinsDisplay(); // Update the coin count
            } else {
                showAlert('danger', response.message);
            }
        },
        error: function() {
            showAlert('danger', 'An error occurred. Please try again.');
        }
    });
}

// Helper Functions
function updateCoinsDisplay() {
    $.get('get_coins.php', function(coins) {
        $('.coin-count').text(coins);
    });
}

function loadContent(page) {
    $("#mainContent").empty(); // Clear content first
    let pageName = page;
    
    // Convert 'adClick' to 'ads' for the correct file
    if (page === 'adClick') {
        pageName = 'ads';
    }
    
    $("#mainContent").load(`${pageName}.php`, function(response, status, xhr) {
        if (status === "error") {
            showAlert('danger', 'Error loading content. Please try again.');
            console.error("Error loading content:", xhr.status, xhr.statusText);
        } else {
            console.log(`${pageName}.php content loaded successfully.`);
        }
    });
}

// Document Ready Handler
$(document).ready(function() {
    // Load initial content
    loadContent('ads');
    
    // Navigation Button Click Handlers
    const pages = ['adClick', 'secretCode', 'buyProduct', 'watchVideo', 'download', 'users', 'groupChat'];
    
    pages.forEach(page => {
        $(`#${page}Btn`).on('click', function(e) {
            e.preventDefault();
            // Remove active class from all buttons
            $('.btn-outline-success').removeClass('active');
            // Add active class to clicked button
            $(this).addClass('active');
            // Load content
            loadContent(page);
        });
    });

    // Ensure event handlers persist after content changes
    $(document).on('click', '#submitCode', submitSecretCode);
    
    // Handle navigation errors
    $(window).on('error', function() {
        showAlert('danger', 'An error occurred. Please try again.');
    });

    // Check if the submit button should be hidden
    const lastSubmitTime = localStorage.getItem('lastSubmitTime');
    if (lastSubmitTime) {
        const now = new Date().getTime();
        const timeDiff = now - lastSubmitTime;
        const twoMinutes = 120000; // 2 minutes in milliseconds

        if (timeDiff < twoMinutes) {
            const submitButton = $('#submitCode');
            submitButton.addClass('d-none');

            // Show countdown for remaining time
            startCountdown(Math.floor((twoMinutes - timeDiff) / 1000), submitButton);
        }
    }

    // JavaScript to handle the Profile link click event
    $('#profileLink').on('click', function(e) {
        e.preventDefault();
        window.location.href = 'userProfile.php'; // Force reload the userProfile.php page
    });

    // Block right-click context menu
    document.addEventListener('contextmenu', function(e) {
        e.preventDefault();
    });

    // Block F12 and other developer tools shortcuts
    document.addEventListener('keydown', function(e) {
        if (e.key === 'F12' || (e.ctrlKey && e.shiftKey && (e.key === 'I' || e.key === 'J' || e.key === 'C')) || (e.ctrlKey && e.key === 'U')) {
            e.preventDefault();
        }
    });

    // Block text selection and copying
    document.addEventListener('selectstart', function(e) {
        e.preventDefault();
    });

    document.addEventListener('copy', function(e) {
        e.preventDefault();
    });

    // Group Chat Functionality
    function loadChatMessages() {
        $.get('loadChat.php', function(data) {
            $('#chatBox').html(data);
            // Comment out or remove the following line to disable auto-scroll
            // $('#chatBox').scrollTop($('#chatBox')[0].scrollHeight);
        });
    }

    $('#chatForm').on('submit', function(e) {
        e.preventDefault();
        const message = $('#message').val().trim();
        if (message) {
            $.post('saveChat.php', { message: message }, function(response) {
                if (response.success) {
                    $('#message').val('');
                    loadChatMessages();
                } else {
                    showAlert('danger', response.message);
                }
            }, 'json');
        }
    });

    // Load chat messages every 2 seconds
    setInterval(loadChatMessages, 2000);
    loadChatMessages();

    // Load group chat content when the button is clicked
    $('#groupChatBtn').on('click', function(e) {
        e.preventDefault();
        $('#mainContent').load('groupChat.php', function(response, status, xhr) {
            if (status === "error") {
                showAlert('danger', 'Error loading group chat. Please try again.');
                console.error("Error loading group chat:", xhr.status, xhr.statusText);
            } else {
                console.log("Group chat loaded successfully.");
            }
        });
    });

    // Add event listener for dark mode toggle
    $('#toggleDarkMode').on('change', function() {
        toggleDarkMode();
    });
});
