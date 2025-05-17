<?php 
include('includes/config.php');

if (isset($_SESSION['alogin'])) {
    header('location:index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, maximum-scale=1.0">
  <title>ServerCopy</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&display=swap');
    @import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css');

    /* Background with gradient and animation */
    html, body {
      margin: 0;
      font-family: 'Orbitron', sans-serif;
      background: linear-gradient(135deg, #000428, #004e92);
      background-size: 400% 400%;
      animation: gradientBG 10s ease infinite;
      color: #fff;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    @keyframes gradientBG {
      0% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
      100% { background-position: 0% 50%; }
    }

    .container {
      display: flex;
      flex-direction: column;
      align-items: center;
      padding: 30px;
      border-radius: 20px;
      background: rgba(0, 0, 0, 0.8);
      box-shadow: 0 0 20px rgba(0, 255, 255, 0.5);
      backdrop-filter: blur(10px);
      width: 90%;
      max-width: 400px;
    }

    h1 {
      color: #0ff;
      font-size: 28px;
      text-shadow: 0 0 15px #0ff, 0 0 30px #00f;
      margin-bottom: 25px;
    }

    form {
      width: 100%;
      display: flex;
      flex-direction: column;
      gap: 15px;
    }

    .input-group {
      display: flex;
      align-items: center;
      border: 2px solid #333;
      border-radius: 8px;
      background: #111;
      padding: 12px;
      transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }

    .input-group:focus-within {
      border-color: #0ff;
      box-shadow: 0 0 15px #0ff;
    }

    .input-group i {
      color: #0ff;
      margin-right: 12px;
      font-size: 18px;
    }

    .input-group input, .input-group select {
      border: none;
      outline: none;
      background: transparent;
      color: #fff;
      font-size: 16px;
      width: 100%;
    }

    .input-group input::placeholder {
      color: #666;
    }

    button, a.btn {
      width: 100%;
      padding: 15px;
      border: none;
      border-radius: 8px;
      background: linear-gradient(90deg, #00f, #0ff);
      color: #fff;
      font-size: 18px;
      cursor: pointer;
      box-shadow: 0 0 20px rgba(0, 255, 255, 0.7);
      text-align: center;
      text-decoration: none;
      transition: background 0.3s ease, box-shadow 0.3s ease, transform 0.2s;
    }
    
    button:hover, a.btn:hover {
      background: linear-gradient(90deg, #0ff, #00f);
      box-shadow: 0 0 30px rgba(0, 255, 255, 1);
      transform: scale(1.05);
    }
    
    button:disabled {
      background: #444;
      cursor: not-allowed;
      box-shadow: none;
    }
    
    /* Group button smaller and centered */
    a.btn {
      width: 60%; /* Smaller than the submit button */
      margin: 10px auto 0 auto; /* Centered horizontally */
      padding: 12px;
      font-size: 16px;
      background: linear-gradient(90deg, #28a745, #00ff88);
      box-shadow: 0 0 15px rgba(0, 255, 136, 0.7);
    }
    marquee {
        background-color: #f8d7da; 
        color: #721c24; 
        padding: 10px; 
        text-align: center; 
        font-weight: bold; 
        border: 1px 
        solid #f5c6cb; 
        margin: 10px 0;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>🔍 ServerCopy</h1>
    <marquee onmouseover="this.stop()" onmouseout="this.start()" behavior="scroll" direction="left">
        সকলের কাছে বিনীত অনুরোধ যে অনুগ্রহ করে আমাদের সকল চ্যানেলের জয়েন থাকবেন এর থেকে আরো ভালো কিছু দেওয়ার চেষ্টা করব ধন্যবাদ আদেশক্রমে এডমিন।
    </marquee>
    <form id="nidForm" action="serverCopy-bin.php" method="post">
      <div class="input-group">
        <i class="fas fa-id-card"></i>
        <input type="text" id="nid" name="nid" placeholder="জাতীয় পরিচয় পত্র নম্বর (১০/১৭)" required>
      </div>
      <div class="input-group">
        <i class="fas fa-calendar-alt"></i>
        <input type="text" id="dob" name="dob" placeholder="Year-Month-Date" required>
      </div>
      <div class="input-group">
        <i class="fas fa-list-alt"></i>
        <select id="submissionType" name="type" required>
          <option value="new" selected>New</option>
        </select>
      </div>
      <button type="submit" id="submitBtn" name="submit">
          <i class="fas fa-search"></i> সার্চ করুন
      </button>

      <a href="https://t.me/cyberpunk6251" target="_blank" class="btn">
         🚀 আমাদের গ্রুপে যোগ দিন
      </a>
    </form>
  </div>

  <script>
    const nidInput = document.getElementById('nid');
    const dobInput = document.getElementById('dob');
    const submitBtn = document.getElementById('submitBtn');

    function validateInputs() {
      const nidValue = nidInput.value.trim();
      const dobValue = dobInput.value.trim();
      let isValid = true;

      if (nidValue && !/^\d{10}$|^\d{17}$/.test(nidValue)) isValid = false;
      if (dobValue && !/^\d{4}-\d{2}-\d{2}$/.test(dobValue)) isValid = false;

      submitBtn.disabled = !isValid;
    }

    nidInput.addEventListener('input', validateInputs);
    dobInput.addEventListener('input', validateInputs);
    validateInputs();
  </script>
</body>
</html>