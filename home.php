<?php
session_start();
if (!isset($_SESSION['username'])) {
  header('Location: login.html');
  exit();
}
$first_name = htmlspecialchars($_SESSION['first_name']);
$last_name = htmlspecialchars($_SESSION['last_name']);
$username = htmlspecialchars($_SESSION['username']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
  <script src="starry.js" defer></script>
  <style>
    .dashboard-card {
      background: rgba(20, 30, 48, 0.98);
      border-radius: 18px;
      box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
      padding: 2.5rem 2rem 2rem 2rem;
      max-width: 440px;
      margin: 4rem auto 2rem auto;
      display: flex;
      flex-direction: column;
      gap: 1.5rem;
      align-items: center;
    }
    .user-info {
      color: #fff;
      text-align: center;
      margin-bottom: 1.5rem;
    }
    .user-info h2 {
      margin-bottom: 0.5rem;
      font-size: 2rem;
      font-weight: 600;
    }
    .user-info p {
      margin: 0.2rem 0;
      color: #90caf9;
      font-size: 1.1rem;
    }
    .sha256-demo {
      background: #1e293b;
      border-radius: 12px;
      padding: 1.2rem;
      margin-bottom: 1.5rem;
      width: 100%;
      max-width: 370px;
      min-height: 220px;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }
    .sha256-demo h3 {
      color: #fff;
      margin-bottom: 0.5rem;
      font-size: 1.2rem;
    }
    .sha256-demo p, .sha256-demo ul {
      color: #90caf9;
      font-size: 1rem;
      margin-bottom: 0.7rem;
    }
    .sha256-demo input {
      width: 100%;
      padding: 0.7rem;
      border-radius: 8px;
      border: none;
      background: #222b3a;
      color: #fff;
      font-size: 1rem;
      margin-bottom: 0.7rem;
    }
    .sha256-demo .hash-output {
      word-break: break-all;
      color: #fff;
      font-family: monospace;
      font-size: 0.95rem;
      background: #263043;
      border-radius: 6px;
      padding: 0.5rem;
    }
    .slide-btns {
      display: flex;
      justify-content: flex-end;
      gap: 0.7rem;
      margin-top: 0.5rem;
    }
    .slide-btns button {
      background: #1e90ff;
      color: #fff;
      border: none;
      border-radius: 8px;
      padding: 0.5rem 1.2rem;
      font-size: 1rem;
      font-weight: 500;
      cursor: pointer;
      transition: background 0.2s;
    }
    .slide-btns button:hover {
      background: #1565c0;
    }
    .slide-img {
      display: block;
      margin: 0.5rem auto 0.7rem auto;
      max-width: 90%;
      border-radius: 8px;
      box-shadow: 0 2px 8px #0003;
    }
    .logout-btn {
      background: #1e90ff;
      color: #fff;
      border: none;
      border-radius: 8px;
      padding: 0.7rem 1.5rem;
      font-size: 1rem;
      font-weight: 500;
      cursor: pointer;
      margin-top: 1.2rem;
      transition: background 0.2s;
    }
    .logout-btn:hover {
      background: #1565c0;
    }
  </style>
</head>
<body>
  <div class="dashboard-card">
    <div class="user-info">
      <h2>Welcome, <?php echo $first_name; ?>!</h2>
      <p><i class="fa fa-user"></i> <?php echo $first_name . ' ' . $last_name; ?></p>
      <p><i class="fa fa-at"></i> <?php echo $username; ?></p>
    </div>
    <div class="sha256-demo" id="shaSlide">
      <!-- Slides will be injected here -->
    </div>
    <form method="post" action="logout.php">
      <button type="submit" class="logout-btn"><i class="fa fa-sign-out-alt"></i> Logout</button>
    </form>
  </div>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.1.1/crypto-js.min.js"></script>
  <script>
    // SHA-256 Slideshow slides
    const slides = [
      {
        title: 'What is SHA-256?',
        content: `<p>SHA-256 is a cryptographic hash function that converts any input into a fixed 256-bit (64-character) string. It's used to securely store passwords and verify data integrity.</p>` +
          `<input type='text' id='shaInput' placeholder='Type anything to see its SHA-256 hash...'>` +
          `<div class='hash-output' id='shaOutput'></div>`
      },
      {
        title: 'How does SHA-256 work?',
        content: `<img src='https://upload.wikimedia.org/wikipedia/commons/thumb/5/5b/SHA-2.svg/512px-SHA-2.svg.png' alt='SHA-256 Diagram (Wikimedia)' class='slide-img' onerror=\"this.style.display='none';this.insertAdjacentHTML('afterend','<div style=\'color:#90caf9;text-align:center;margin:1rem 0;\'>[Diagram unavailable]</div>')\">` +
          `<p>SHA-256 processes data in blocks, using bitwise operations and rounds to produce a unique hash. Even a tiny change in input gives a completely different output.</p>`
      },
      {
        title: 'Why is SHA-256 secure?',
        content: `<ul><li>One-way: Cannot reverse the hash to get the original input.</li><li>Collision-resistant: Extremely unlikely for two inputs to have the same hash.</li><li>Fast and efficient for computers.</li></ul>`
      },
      {
        title: 'Real-world uses of SHA-256',
        content: `<ul><li><i class='fa fa-lock'></i> Password storage</li><li><i class='fa fa-bitcoin'></i> Blockchain & cryptocurrencies</li><li><i class='fa fa-file-alt'></i> File integrity checks</li><li><i class='fa fa-shield-alt'></i> Digital signatures</li></ul>`
      }
    ];
    let currentSlide = 0;
    function renderSlide(idx) {
      const slide = slides[idx];
      document.getElementById('shaSlide').innerHTML =
        `<h3>${slide.title}</h3>${slide.content}` +
        `<div class='slide-btns'>` +
        (idx > 0 ? `<button onclick='prevSlide()'>Back</button>` : '') +
        (idx < slides.length - 1 ? `<button onclick='nextSlide()'>Next</button>` : '') +
        `</div>`;
      if (idx === 0) {
        // SHA-256 live demo
        const shaInput = document.getElementById('shaInput');
        const shaOutput = document.getElementById('shaOutput');
        shaInput.addEventListener('input', function() {
          if (shaInput.value) {
            shaOutput.textContent = CryptoJS.SHA256(shaInput.value).toString();
          } else {
            shaOutput.textContent = '';
          }
        });
      }
    }
    function nextSlide() {
      if (currentSlide < slides.length - 1) {
        currentSlide++;
        renderSlide(currentSlide);
      }
    }
    function prevSlide() {
      if (currentSlide > 0) {
        currentSlide--;
        renderSlide(currentSlide);
      }
    }
    // Initial render
    renderSlide(currentSlide);
  </script>
</body>
</html> 