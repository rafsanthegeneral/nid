<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('includes/config.php');

if (isset($_SESSION['alogin'])) {
    header('location:index.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];

    // ডাটাবেস থেকে username যাচাই
    $sql = "SELECT id, username, balance FROM users WHERE username = :username";
    $query = $dbh->prepare($sql);
    $query->bindParam(':username', $username, PDO::PARAM_STR);
    $query->execute();

    if ($query->rowCount() > 0) {
        $result = $query->fetch(PDO::FETCH_ASSOC);
        $_SESSION['alogin'] = $result['username'];
        $_SESSION['balance'] = $result['balance']; // ব্যালেন্স সেশন এ রাখছি
         
        header('location:index.php');
        if ($result['uid'] == 0)
        {
            header('location:add-user.php');
        }
    
    } else {
        echo "<script>alert('Invalid Username');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, height=device-height, user-scalable=no">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #1f1c2c, #928dab);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #ffffff;
        }
        .login {
            background: rgba(0, 0, 0, 0.8);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.5);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        h2 {
            margin-bottom: 25px;
            font-size: 28px;
            color: #00d2ff;
            text-shadow: 0 0 10px rgba(0, 210, 255, 0.5);
        }
        .input-group {
            display: flex;
            align-items: center;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid #444;
            border-radius: 8px;
            padding: 10px 15px;
            margin-bottom: 20px;
            transition: 0.3s ease;
        }
        .input-group i {
            color: #00d2ff;
            margin-right: 10px;
        }
        .input-group input {
            flex: 1;
            background: none;
            border: none;
            outline: none;
            color: #fff;
            font-size: 16px;
        }
        .input-group input::placeholder {
            color: #aaa;
        }
        button {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            background: linear-gradient(90deg, #00c6ff, #0072ff);
            border: none;
            border-radius: 8px;
            color: white;
            font-size: 18px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 118, 255, 0.4);
        }
        button:hover {
            background: linear-gradient(90deg, #0072ff, #00c6ff);
            box-shadow: 0 6px 20px rgba(0, 118, 255, 0.7);
            transform: translateY(-2px);
        }

        /* Join Channel Button */
        .join-btn {
            background: linear-gradient(90deg, #28a745, #00ff88);
            box-shadow: 0 4px 15px rgba(0, 255, 136, 0.4);
        }
        .join-btn:hover {
            background: linear-gradient(90deg, #00ff88, #28a745);
            box-shadow: 0 6px 20px rgba(0, 255, 136, 0.7);
        }

        /* Error Message Styling */
        .error-message {
            background: rgba(244, 67, 54, 0.8);
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 16px;
            box-shadow: 0 4px 15px rgba(244, 67, 54, 0.4);
        }
    </style>
</head>
<body>

<div class="login">
    <h2>Login</h2>

    <!-- Error Message Display -->
    <?php if (!empty($error_message)): ?>
        <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="input-group">
            <i class="fas fa-user"></i>
            <input type="text" name="username" placeholder="Enter Username" required>
        </div>

        <button type="submit">Login</button>
        <button type="button" class="join-btn" onclick="window.open('https://t.me/mjmodlab', '_blank')">Join Channel</button>
    </form>
</div>

</body>
</html>