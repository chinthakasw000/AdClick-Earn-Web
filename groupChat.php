<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
require_once 'db_connect.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Group Chat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background-color: #097969;
        }
        #chatBox {
            height: 60vh; /* Use viewport height for better responsiveness */
            overflow-y: scroll;
            border: 1px solid #ccc;
            padding: 10px;
            background-color: #fff;
        }
        #chatBox::-webkit-scrollbar {
            width: 0px;  /* Remove scrollbar space */
            background: transparent;  /* Optional: just make scrollbar invisible */
        }
        #chatBox {
            -ms-overflow-style: none;  /* Internet Explorer 10+ */
            scrollbar-width: none;  /* Firefox */
        }
        .chat-message {
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 10px;
            max-width: 80%; /* Adjust for smaller screens */
            background-color: #f8f9fa;
        }
        .chat-message .username {
            font-weight: bold;
        }
        .chat-message .timestamp {
            font-size: 0.8em;
            color: #999;
        }
        .current-user {
            background-color: #d1e7dd;
            align-self: flex-end;
            text-align: right;
            margin-left: auto;
        }
        .other-user {
            background-color: #f8d7da;
            align-self: flex-start;
            text-align: left;
            margin-right: auto;
        }
        /* Mobile-specific styles */
        @media (max-width: 767.98px) {
            .container {
                padding: 0;
            }
            .card {
                border-radius: 0;
            }
            #chatBox {
                height: 70vh; /* Increase height for better usability on mobile */
            }
            .chat-message {
                max-width: 90%; /* Allow more width for messages on mobile */
            }
            .card-header h1 {
                font-size: 1.5rem; /* Smaller heading for mobile */
            }
            .btn-success {
                padding: 8px 12px; /* Smaller button padding for mobile */
            }
        }
    </style>
</head>
<body>
    <div class="container mt-3 mt-md-5"> <!-- Adjusted margin for mobile -->
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6"> <!-- Adjusted column width for mobile -->
                <div class="card">
                    <div class="card-header text-center">
                        <h1>Group Chat</h1>
                        <a href="home.php" class="btn btn-success float-start">
                            <i class="bi bi-arrow-left-circle-fill text-success me-2"></i>Back to Home
                        </a>
                    </div>
                    <div class="card-body">
                        <div id="chatBox" class="mb-3 d-flex flex-column"></div>
                        <form id="chatForm">
                            <div class="input-group">
                                <input type="text" id="message" class="form-control" placeholder="Type your message..." required>
                                <div style="width: 5px;"></div>
                                <button type="submit" class="btn col-2 btn-success"><i class="bi bi-send"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
</body>
</html>