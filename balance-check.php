<?php
// PHP কোড যা ফর্মের মাধ্যমে Username গ্রহণ করবে এবং ব্যালেন্স চেক করবে।
$balance = null;  // ব্যালেন্স স্টোর করার জন্য ভেরিয়েবল

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // ফর্ম থেকে 'username' গ্রহণ করা
    $username = isset($_POST['username']) ? $_POST['username'] : '';

    // ইনপুট ভ্যালিডেশন
    if (!$username) {
        $message = 'Username is required.';
        $message_type = 'error';
    } else {
        // ১. ডাটাবেজ কানেকশন তৈরি করা (উদাহরণ: MySQL)
        $servername = "localhost";
        $dbusername = "root";
        $password = "@#Rafsan123";
        $dbname = "nid";

        // কানেকশন তৈরি
        $conn = new mysqli($servername, $dbusername, $password, $dbname);

        // কানেকশন চেক করা
        if ($conn->connect_error) {
            $message = 'Database connection failed: ' . $conn->connect_error;
            $message_type = 'error';
        } else {
            // 'username' দিয়ে ব্যালেন্স চেক করা
            $sql = "SELECT balance FROM users WHERE `username` = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 0) {
                $message = 'Invalid username or user not found';
                $message_type = 'error';
            } else {
                // ব্যালেন্স ফেচ করা
                $row = $result->fetch_assoc();
                $balance = $row['balance'];
                $message = 'Request Limit Available: $' . $balance;
                $message_type = 'success';
            }

            $stmt->close();
            $conn->close();
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Balance Check Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #252525;
            color: #fff;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 50px auto;
            background-color: #2d2d2d;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            text-align: center;
        }
        h2 {
            color: #28a745;
            font-size: 24px;
            margin-bottom: 20px;
        }
        label {
            font-size: 16px;
            margin-bottom: 10px;
            display: block;
        }
        input {
            width: 80%;
            padding: 12px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 20px;
            background-color: #f2f2f2;
            color: #333;
        }
        button {
            background-color: #28a745;
            color: white;
            padding: 15px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
            width: 80%;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #218838;
        }
        .icon {
            font-size: 30px;
            color: #28a745;
            margin-bottom: 20px;
        }
        .message {
            font-size: 18px;
            margin-top: 20px;
            padding: 10px;
            border-radius: 5px;
        }
        .error {
            background-color: #dc3545;
        }
        .success {
            background-color: #28a745;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="icon">
            <i class="fas fa-key"></i>
        </div>
        <h2>Balance Check</h2>
        <form method="POST">
            <label for="key">Enter User Name:</label>
            <input type="text" id="username" name="username" value="" required>
            <button type="submit"><i class="fas fa-search"></i> Check Balance</button>
        </form>

        <?php if (isset($message)): ?>
            <div class="message <?php echo $message_type; ?>">
                <i class="fas fa-info-circle"></i>
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
    </div>

</body>
</html>
