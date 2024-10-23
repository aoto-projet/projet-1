<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background-color: #f0f8ff; /* Light background color */
            overflow: hidden; /* Prevent scroll bars due to falling leaves */
        }

        h3 {
            font-family: 'Arial', sans-serif; /* Font */
        }

        .btn-primary {
            background-color: #007bff; /* Button color */
            border: none; /* No border */
        }

        .btn-primary:hover {
            background-color: #0056b3; /* Hover color */
        }

        .leaf {
            position: absolute;
            top: -50px; /* Start above the screen */
            opacity: 0.7;
            animation: fall linear infinite;
        }

        @keyframes fall {
            0% {
                transform: translateY(0);
            }
            100% {
                transform: translateY(100vh); /* End position (bottom of viewport) */
            }
        }

        .leaf1 { animation-duration: 10s; }
        .leaf2 { animation-duration: 12s; }
        .leaf3 { animation-duration: 15s; }
        .leaf4 { animation-duration: 8s; }
		
		
		
    .container {
        background: rgba(255, 255, 255, 0.8); /* พื้นหลังโปร่งแสง */
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2); /* เงา */
        border-radius: 8px; /* มุมมน */
        padding: 10px; /* เพิ่ม padding */
    }

    </style>
</head>

<body>
    <div class="card">
        <img src="img/bglogin.jpg" class="card-img-top" alt="" style="width: 100%; height: auto;">

        <div class="card-body card-img-overlay">
            <div class="container" style="max-width:600px; margin-top: 10px;">
    <img src="img/logo index.jfif" alt="Logo" width="100" height="100" class="d-block mx-auto mb-3"> <!-- Logo -->
    <h3 class="mt-4 text-dark text-center">เข้าสู่ระบบ</h3> <!-- เปลี่ยนเป็นสีดำ -->
    <hr class="bg-light">

    <form action="signin_db.php" method="post">
        <?php if(isset($_SESSION['error'])) { ?>
        <div class="alert alert-danger" role="alert">
            <?php
            echo $_SESSION['error'];
            unset($_SESSION['error']);
            ?>
        </div>
        <?php } ?>

        <?php if(isset($_SESSION['success'])) { ?>
        <div class="alert alert-success" role="alert">
            <?php
            echo $_SESSION['success'];
            unset($_SESSION['success']);
            ?>
        </div>
        <?php } ?>

        <div class="mb-3">
            <label for="email" class="form-label text-dark">Email</label> <!-- เปลี่ยนเป็นสีดำ -->
            <input type="email" class="form-control" name="email" aria-describedby="email" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label text-dark">Password</label> <!-- เปลี่ยนเป็นสีดำ -->
            <input type="password" class="form-control" name="password" required>
        </div>

        <button type="submit" class="btn btn-primary w-100" name="signin">เข้าสู่ระบบ</button>
    </form>

    <hr class="bg-light">
    <p class="text-dark text-center">ยังไม่เป็นสมาชิก? <a href="signup.php" class="text-dark">สมัครสมาชิก</a></p> <!-- เปลี่ยนเป็นสีดำ -->
</div>



        </div>
    </div>

    <!-- Falling Leaves -->
    <div class="leaf leaf1" style="left: 10%;"><img src="img/leaf.png" alt="Leaf" width="50"></div>
    <div class="leaf leaf2" style="left: 30%;"><img src="img/leaf.png" alt="Leaf" width="50"></div>
    <div class="leaf leaf3" style="left: 50%;"><img src="img/leaf.png" alt="Leaf" width="50"></div>
    <div class="leaf leaf4" style="left: 70%;"><img src="img/leaf.png" alt="Leaf" width="50"></div>

    <script>
        // Function to create falling leaves
        function createLeaves() {
            const leafCount = 15; // Increase the number of leaves
            for (let i = 0; i < leafCount; i++) {
                let leaf = document.createElement('div');
                leaf.className = 'leaf';
                leaf.style.left = Math.random() * 100 + '%';
                leaf.style.animationDuration = (Math.random() * 3 + 5) + 's'; // Random duration between 5s to 8s
                leaf.style.animationDelay = Math.random() * 5 + 's'; // Random delay
                leaf.style.transform = `rotate(${Math.random() * 360}deg)`; // Random rotation
                leaf.innerHTML = '<img src="img/leaf.png" alt="Leaf" width="50">';
                document.body.appendChild(leaf);
            }
        }

        // Call the function to create leaves
        createLeaves();
    </script>
</body>
</html>
