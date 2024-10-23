<?php
session_start();
require_once 'config/db.php';

try {
$stmt = $conn->prepare("SELECT * FROM room_types");
$stmt->execute();
$roomtype =$stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e){
    echo "เกิข้อผิดพลาด".$e->getMessage();  
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบจัดการหอพัก</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar {
            margin-bottom: 20px;
        }
        .card {
            margin-bottom: 20px;
        }
    </style>
     <style>

.carousel-images {
    position: relative;
    width: 100%;
    height: 450px; /* เพิ่มความสูงเล็กน้อย */
    overflow: hidden;
    border-radius: 10px; /* ทำมุมให้โค้ง */
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2); /* เพิ่มเงาให้กับ Carousel */
}

.carousel-images img {
    position: absolute;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: opacity 0.5s ease-in-out;
    border-radius: 10px; /* ทำมุมให้โค้งเหมือนกัน */
}

.carousel-thumbnails {
    display: flex;
    justify-content: center;
    margin-top: 20px; /* เพิ่มระยะห่างให้ดูสบายตาขึ้น */
}

.carousel-thumbnails img {
    width: 100px; /* ปรับขนาดให้ใหญ่ขึ้นเล็กน้อย */
    height: 90px; /* ปรับขนาดให้ใหญ่ขึ้นเล็กน้อย */
    object-fit: cover;
    cursor: pointer;
    margin: 0 8px; /* เพิ่มระยะห่างระหว่าง thumbnails */
    border: 2px solid transparent; /* กำหนดกรอบให้กับ thumbnail */
    border-radius: 5px; /* ทำมุมให้โค้ง */
    transition: border-color 0.3s, transform 0.3s; /* เพิ่มการเปลี่ยนแปลงขนาดเมื่อ hover */
}

.carousel-thumbnails img:hover {
    border-color: #007bff; /* สีกรอบเมื่อ hover */
    transform: scale(1.05); /* ขยาย thumbnail เมื่อ hover */
}


.carousel-thumbnails img.active {
    border-color: #007bff;
}
.carousel-images img:first-child {
    opacity: 1;
}
.bgstyle { 
        background-color:#7FFFD4;
 }
 .bgstyle1{
    background-color:#7FFFD4;
 }
.navbar {
    height: 60px; /* ปรับความสูงของ navbar */
    padding: 0 20px; /* เพิ่ม padding ซ้ายและขวา */
}

.navbar-brand img {
    max-height: 100%; /* ทำให้โลโก้สูงไม่เกิน 100% ของ navbar */
}

.navbar-nav .nav-link {
    padding: 15px 20px; /* เพิ่ม padding ให้กับลิงก์ */
    font-size: 18px; /* ปรับขนาดฟอนต์ */
    transition: color 0.3s, background-color 0.3s; /* เพิ่มการเปลี่ยนสีที่นุ่มนวล */
}

.navbar-nav .nav-link:hover {
    color: #fff; /* สีเมื่อเลื่อนเมาส์ */
    background-color: rgba(255, 255, 255, 0.2); /* เพิ่มพื้นหลังเบา ๆ เมื่อเลื่อนเมาส์ */
    border-radius: 5px; /* เพิ่มมุมโค้งให้กับพื้นหลัง */
}

@media (max-width: 768px) {
    .navbar-brand {
        margin-right: auto; /* จัดเรียงโลโก้ให้ติดซ้ายเมื่อหน้าจอเล็ก */
    }
}
.navbar {
    background-color: rgba(0, 0, 0, 0.8); /* สีดำเงา */
}

.navbar-nav .nav-link {
    color: #28a745; /* สีเขียว */
}

.navbar-nav .nav-link:hover {
    color: #218838; /* สีเขียวเข้มเมื่อ hover */
}
		 .navbar {
    background-color: #167AB8; /* สีพื้นหลังเป็นสีดำเงา */
}

.navbar-nav .nav-link {
    color: #28a745; /* เปลี่ยนตัวหนังสือเป็นสีเขียว */
}

.navbar-nav .nav-link:hover {
    color: #218838; /* สีเขียวเข้มเมื่อ hover */
}

.navbar-brand img {
    max-height: 100%; /* ทำให้โลโก้สูงไม่เกิน 100% ของ navbar */
}

		 .bgstyle1 {
    background-color: #167AB8; /* เปลี่ยนสีพื้นหลัง */
    border-radius: 10px; /* ทำมุมโค้ง */
    padding: 10px; /* เพิ่ม padding รอบๆ */
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); /* เพิ่มเงา */
}

.d-flex {
    margin-bottom: 10px; /* เพิ่มระยะห่างระหว่างแถว */
}

i {
    font-size: 24px; /* ขนาดไอคอน */
    color: #28a745; /* เปลี่ยนสีไอคอนเป็นสีเขียว */
}

		 .card {
    margin: 15px; /* เพิ่มระยะห่างระหว่างการ์ด */
    transition: transform 0.3s; /* เพิ่มเอฟเฟ็กต์การขยาย */
}

.card:hover {
    transform: scale(1.05); /* ขยายการ์ดเมื่อชี้เมาส์ */
}


     </style>

</head>
<body>
<div class="container">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">
            <img src="img/logo index.jfif" width="70" height="60" alt="Logo" class="d-inline-block align-text-top">
            ระบบจัดการหอพักหลิงหลิง
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php"><i class="fas fa-home me-2"></i>หน้าหลัก</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="signin.php"><i class="fas fa-sign-in-alt me-2"></i>เช้าสู่ระบบ</a>
                </li>
            </ul>
        </div>
    </div>
</nav>



<div style="height: 300px;">
    <img src="img/zone-a.jpg" height="350" width="100%" alt="">
</div>
<br>
	<br>

<?php
$pr=$_GET['pr'];
if($pr=="")
{
    ?>
<?php
try {

    $stmtA = $conn->prepare("SELECT * FROM room_types WHERE room_type_id = 1");
    $stmtA->execute();
    $zoneA = $stmtA->fetch(PDO::FETCH_ASSOC);

   
    $stmtB = $conn->prepare("SELECT * FROM room_types WHERE room_type_id = 2");
    $stmtB->execute();
    $zoneB = $stmtB->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "เกิดข้อผิดพลาด: " . $e->getMessage();
}
?>
  <br>  
<div class="bgstyle pt-2 ps-2 text-danger" style="height: 50px;">
    <h3>
        <i class="fas fa-bed me-2"></i>รายการห้องพัก
    </h3>
</div>

<br>

<div class="container mt-4">
   

    <ul class="list-inline text-center">
        <li class="list-inline-item">
            <div class="card" style="width: 500px;">
                <img src="uploads/<?php echo $zoneA['room_image']; ?>" class="card-img-top" alt="อาคาร A">
                <div class="card-body">
                    <h5 class="card-title">อาคาร A</h5>
                    <p class="card-text">มีจำนวน 10 ห้อง ราคา 3,500 บาท</p>
                    <a href="index.php?&&pr=1" class="btn btn-info">รายละเอียด</a>
                </div>
            </div>
        </li>

        <li class="list-inline-item">
            <div class="card" style="width: 500px;">
                <img src="uploads/<?php echo $zoneB['room_image']; ?>" class="card-img-top" alt="อาคาร B">
                <div class="card-body">
                    <h5 class="card-title">อาคาร B</h5>
                    <p class="card-text">มีจำนวน 5 ห้อง ราคา 3,000 บาท</p>
                    <a href="index.php?&&pr=2" class="btn btn-info">รายละเอียด</a>
                </div>
            </div>
        </li>
    </ul>
</div>


<?php
}
?>

<?php
if ($pr == "1") {
    try {
        $stmtA = $conn->prepare("SELECT * FROM room_types WHERE room_type_id = 1");
        $stmtA->execute();
        $ZoneA = $stmtA->fetch(PDO::FETCH_ASSOC);

      
        $gallery_images = explode(',', $ZoneA['gallery_images']);
    } catch (PDOException $e) {
        echo "เกิดข้อผิดพลาด: " . $e->getMessage();
    }
?>
<div class="bgstyle pt-2 ps-2 text-danger " style="height: 50px;"><h3>โซน A</h3></div>

<div class="container mt-5">
    <div class="row align-items-start">
        <div class="col-md-6">
            <div class="carousel">
              <div class="carousel-images" id="carousel-images">
                    <?php foreach ($gallery_images as $index => $image): ?>
                        <?php if (file_exists("uploads/" . trim($image)) && !empty(trim($image))) : ?>
                            <img src="uploads/<?php echo trim($image); ?>" alt="Image <?php echo $index + 1; ?>" width="194%" style="opacity: <?php echo ($index === 0) ? '1' : '0'; ?>;">
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>

                <div class="carousel-buttons mt-3">
                    <button id="prev">&lt;</button>
                    <button id="next">&gt;</button>
                </div>

              <div class="carousel-thumbnails" id="carousel-thumbnails">
                    <?php foreach ($gallery_images as $index => $image): ?>
                        <?php if (file_exists("uploads/" . trim($image)) && !empty(trim($image))) : ?>
                            <img src="uploads/<?php echo trim($image); ?>" alt="Thumbnail <?php echo $index + 1; ?>" data-index="<?php echo $index; ?>" class="<?php echo ($index === 0) ? 'active' : ''; ?>">
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>

            </div>
        </div>
       
        <div class="col-md-6">
            <h3><?php echo $ZoneA['room_description']; ?></h3>
        </div>
    </div>
</div>
<?php
}
?>


<?php
if ($pr == "2") {
    try {
        $stmtB = $conn->prepare("SELECT * FROM room_types WHERE room_type_id = 2");
        $stmtB->execute();
        $ZoneB = $stmtB->fetch(PDO::FETCH_ASSOC);

       
        $gallery_images_B = explode(',', $ZoneB['gallery_images']);

    } catch (PDOException $e) {
        echo "เกิดข้อผิดพลาด: " . $e->getMessage();
    }
?>
<div class="bgstyle pt-2 ps-2 text-danger " style="height: 50px;"><h3>โซน B</h3></div>
<div class="container mt-5">
    <div class="row align-items-start">
        <div class="col-md-6">
            <div class="carousel">
              <div class="carousel-images" id="carousel-images-B">
                    <?php foreach ($gallery_images_B as $index => $image): ?>
                        <?php if (file_exists("uploads/" . trim($image)) && !empty(trim($image))) : ?>
                            <img src="uploads/<?php echo trim($image); ?>" alt="Image <?php echo $index + 1; ?>" style="opacity: <?php echo ($index === 0) ? '1' : '0'; ?>;">
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>

                <div class="carousel-buttons mt-3">
                    <button id="prev-B">&lt;</button>
                    <button id="next-B">&gt;</button>
                </div>

              <div class="carousel-thumbnails" id="carousel-thumbnails-B">
                    <?php foreach ($gallery_images_B as $index => $image): ?>
                        <?php if (file_exists("uploads/" . trim($image)) && !empty(trim($image))) : ?>
                            <img src="uploads/<?php echo trim($image); ?>" alt="Thumbnail <?php echo $index + 1; ?>" data-index="<?php echo $index; ?>" class="<?php echo ($index === 0) ? 'active' : ''; ?>">
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>

            </div>
        </div>
       
        <div class="col-md-6">
            <h3><?php echo $ZoneB['room_description']; ?></h3>
        </div>
    </div>
</div>



<?php
}
?>


<br><br>


<p class="fst-italic fw-bold h3">แผนที่</p>
	
<div class="text-center h1 fw-bold" style="height: 600px; display: flex; justify-content: center; align-items: center;">
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2269.0663002572383!2d102.27436949640659!3d16.96965851869263!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3123cd258bf3972b%3A0x197217d5587ded80!2z4Lir4Lit4Lie4Lix4LiB4Lir4Lil4Li04LiH4Lir4Lil4Li04LiH!5e0!3m2!1sth!2sth!4v1722134228347!5m2!1sth!2sth" 
            style="border: 0; width: 100%; max-width: 900px; height: 500px;" 
            allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
    </iframe>
</div>


<div class="bgstyle1 text-center" style="height: 300px; padding-top: 20px;">
    <div>
        <img src="img/logo.png" alt="Logo" style="width: 100px; height: auto; margin-bottom: 10px;">
        <span class="fw-bold fs-2">ติดต่อ</span>
		
    </div>
    <div class="d-flex justify-content-center align-items-center mb-2">
        <i class="fas fa-phone-alt me-2"></i>
        <label class="fw-bold" style="min-width: 50px;">โทร</label>
        <span>062-490-8312 คุณเม่น</span>
    </div>
    <div class="d-flex justify-content-center align-items-center mb-2">
        <i class="fas fa-envelope me-2"></i>
        <label class="fw-bold" style="min-width: 50px;">Email</label>
        <span>puttipong.pr@outlook.com</span>
    </div>
    <div class="d-flex justify-content-center align-items-center mb-2">
        <i class="fab fa-facebook me-2"></i>
        <label class="fw-bold" style="min-width: 50px;">FB</label>
        <span>puttipong.pr@outlook.com</span>
    </div>
    <div class="d-flex justify-content-center align-items-center mb-2">
        <i class="fab fa-line me-2"></i>
        <label class="fw-bold" style="min-width: 50px;">Line</label>
        <span>Ment_AutoThec</span>
    </div>
</div>






<div class="bg-dark text-center text-light fst-italic fixed-bottom py-2">
    ระบบจัดการหอพักหลิงหลิง
</div>



  <script src="js/bootstrap.bundle.min.js"></script>
  <script>

function setupCarousel(carouselId, thumbnailsId, prevId, nextId) {
    let currentIndex = 0;
    const images = document.querySelectorAll(#${carouselId} img);
    const thumbnails = document.querySelectorAll(#${thumbnailsId} img);
    const totalImages = images.length;

    function showImage(index) {
        images[currentIndex].style.opacity = '0';
        thumbnails[currentIndex].classList.remove('active');
        currentIndex = index;
        images[currentIndex].style.opacity = '1';
        thumbnails[currentIndex].classList.add('active');
    }

    document.addEventListener('DOMContentLoaded', () => {
        showImage(currentIndex);
    });

    document.getElementById(nextId).addEventListener('click', () => {
        showImage((currentIndex + 1) % totalImages);
    });

    document.getElementById(prevId).addEventListener('click', () => {
        showImage((currentIndex - 1 + totalImages) % totalImages);
    });

    thumbnails.forEach(thumbnail => {
        thumbnail.addEventListener('click', () => {
            showImage(parseInt(thumbnail.getAttribute('data-index')));
        });
    });
}


setupCarousel('carousel-images', 'carousel-thumbnails', 'prev', 'next');

setupCarousel('carousel-images-B', 'carousel-thumbnails-B', 'prev-B', 'next-B');
</script>
<script>
function setupCarouselB() {
    let currentIndexB = 0;
    const imagesB = document.querySelectorAll("#carousel-images-B img");
    const thumbnailsB = document.querySelectorAll("#carousel-thumbnails-B img");
    const totalImagesB = imagesB.length;

    function showImageB(indexB) {
        imagesB[currentIndexB].style.opacity = '0';
        thumbnailsB[currentIndexB].classList.remove('active');
        currentIndexB = indexB;
        imagesB[currentIndexB].style.opacity = '1';
        thumbnailsB[currentIndexB].classList.add('active');
    }

    document.getElementById("next-B").addEventListener('click', () => {
        showImageB((currentIndexB + 1) % totalImagesB);
    });

    document.getElementById("prev-B").addEventListener('click', () => {
        showImageB((currentIndexB - 1 + totalImagesB) % totalImagesB);
    });

    thumbnailsB.forEach(thumbnail => {
        thumbnail.addEventListener('click', () => {
            showImageB(parseInt(thumbnail.getAttribute('data-index')));
        });
    });
}

document.addEventListener('DOMContentLoaded', setupCarouselB);
</script>
</div>
</body>
</html> 