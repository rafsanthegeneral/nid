<?php
session_start();
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
if (!isset($_SESSION['alogin'])) {
    header('Location: login.php');
    exit(); // exit() is preferred over die() for readability
}
$servername = "localhost"; // ডাটাবেজ সার্ভারের নাম
$username = "root"; // ডাটাবেজ ইউজারনেম
$password = ""; // ডাটাবেজ পাসওয়ার্ড
$dbname = "nid"; 
$connn = mysqli_connect($servername, $username, $password, $dbname);

$user = $_SESSION['alogin'];
$sql = 'SELECT * FROM users where username = "'.$user.'"';
$result = mysqli_query($connn, $sql);
$row2 = $result->fetch_assoc();
$uid = $row2['uid'];
if ($uid != 0) {
    header('Location: login.php');
    exit(); // exit() is preferred over die() for readability
}

$conn = new mysqli($servername, $username, $password, $dbname);

// কানেকশন চেক করা
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// ইউজার অ্যাড করার প্রক্রিয়া
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // ফর্ম থেকে API username এবং Balance গ্রহণ করা
    if (isset($_POST['add_user'])) {
        $username = isset($_POST['username']) ? $_POST['username'] : '';
        $balance = isset($_POST['balance']) ? $_POST['balance'] : 0;

        // ইনপুট ভ্যালিডেশন
        if (!$username || !$balance) {
            $message = 'API username and Balance are required to add a user.';
            $message_type = 'error';
        } else {
            // ইউজার ডাটাবেজে অ্যাড করা
            $sql_insert = "INSERT INTO users (`username`, balance,uid) VALUES (?, ?, 1)";
            $stmt = $conn->prepare($sql_insert);
            $stmt->bind_param("si", $username, $balance);
            if ($stmt->execute()) {
                $message = 'User added successfully.';
                $message_type = 'success';
            } else {
                $message = 'Failed to add user.';
                $message_type = 'error';
            }
            $stmt->close();
        }
    }

    // API username দিয়ে ব্যালেন্স চেক করা
    if (isset($_POST['check_balance'])) {
        $username = isset($_POST['check_username']) ? $_POST['check_username'] : '';

        // ইনপুট ভ্যালিডেশন
        if (!$username) {
            $message = 'API username is required to check balance.';
            $message_type = 'error';
        } else {
            // 'username' দিয়ে ব্যালেন্স চেক করা
            $sql_balance = "SELECT balance FROM users WHERE `username` = ?";
            $stmt = $conn->prepare($sql_balance);
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result_balance = $stmt->get_result();

            if ($result_balance->num_rows == 0) {
                $message = 'Invalid username or username not found';
                $message_type = 'error';
            } else {
                // ব্যালেন্স ফেচ করা
                $row = $result_balance->fetch_assoc();
                $balance = $row['balance'];
                $message = 'Request Limit Available: $' . $balance;
                $message_type = 'success';
            }

            $stmt->close();
        }
    }

    // API username দিয়ে ব্যালেন্স আপডেট করা
    if (isset($_POST['update_balance'])) {
        $username = isset($_POST['update_username']) ? $_POST['update_username'] : '';
        $balance_change = isset($_POST['balance_change']) ? $_POST['balance_change'] : 0;

        // ইনপুট ভ্যালিডেশন
        if (!$username || !$balance_change) {
            $message = 'API username and Balance Change are required to update balance.';
            $message_type = 'error';
        } else {
            // ইউজারের ব্যালেন্স আপডেট করা
            $sql_balance = "SELECT balance FROM users WHERE `username` = ?";
            $stmt = $conn->prepare($sql_balance);
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result_balance = $stmt->get_result();

            if ($result_balance->num_rows == 0) {
                $message = 'Invalid username or username not found';
                $message_type = 'error';
            } else {
                $row = $result_balance->fetch_assoc();
                $current_balance = $row['balance'];

                // নতুন ব্যালেন্স হিসাব করা
                $new_balance = $current_balance + $balance_change;

                // ব্যালেন্স আপডেট করা
                $sql_update = "UPDATE users SET balance = ? WHERE `username` = ?";
                $stmt = $conn->prepare($sql_update);
                $stmt->bind_param("is", $new_balance, $username);
                if ($stmt->execute()) {
                    $message = 'Balance updated successfully.';
                    $message_type = 'success';
                } else {
                    $message = 'Failed to update balance.';
                    $message_type = 'error';
                }
            }
            $stmt->close();
        }
    }
}

// সমস্ত ইউজারের তালিকা ফেচ করা
$sql_users = "SELECT id, `username`, balance FROM users";
$result_users = $conn->query($sql_users);
$users = [];
if ($result_users->num_rows > 0) {
    while($row = $result_users->fetch_assoc()) {
        $users[] = $row;
    }
}

$conn->close();
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
        .user-list {
            margin-top: 30px;
            color: #fff;
            font-size: 16px;
        }
        .user-list ul {
            list-style-type: none;
            padding: 0;
        }
        .user-list li {
            margin-bottom: 10px;
            padding: 10px;
            background-color: #444;
            border-radius: 5px;
        }
        .user-list .username {
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="icon">
            <i class="fas fa-username"></i>
        </div>
        <h2>Balance Update and Add User</h2>

        <!-- Add User Form -->
        <form method="POST">
            <label for="username">Enter username to Add User:</label>
            <input type="text" id="username" name="username" placeholder="Enter Username" required><br>
            <label for="balance">Enter Balance:</label>
            <input type="number" id="balance" name="balance" placeholder="Balance" required><br>
            <button type="submit" name="add_user">Add User</button>
        </form>

        <!-- Update Balance Form -->
        <form method="POST" style="margin-top: 30px;">
            <label for="update_username">Enter username to Update Balance:</label>
            <input type="text" id="update_username" name="update_username" placeholder="Enter Username" required><br>
            <label for="balance_change">Enter Balance Change (+ or -):</label>
            <input type="number" id="balance_change" name="balance_change" placeholder="Balance Change" required><br>
            <button type="submit" name="update_balance">Update Balance</button>
        </form>

        <?php if (isset($message)): ?>
            <div class="message <?php echo $message_type; ?>">
                <i class="fas fa-info-circle"></i>
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <!-- Show All Users Below -->
        <div class="user-list">
            <h3>All Users</h3>
            <ul>
                <?php foreach ($users as $user): ?>
                    <li>
                        <span class="username">User: <?php echo $user['username']; ?></span><br>
                        Balance: $<?php echo $user['balance']; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

</body>
</html>
