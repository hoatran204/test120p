<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <?php
        session_start();
        class Database {
            private $host;
    private $username;
    private $password;
    private $dbname;
    private $conn;

    // Hàm khởi tạo
    public function __construct() {
        // Kiểm tra xem SESSION có giá trị không trước khi sử dụng
        if (isset($_SESSION['server'], $_SESSION['username'], $_SESSION['password'], $_SESSION['database'])) {
            $this->host = $_SESSION['server'];
            $this->username = $_SESSION['username'];
            $this->password = $_SESSION['password'];
            $this->dbname = $_SESSION['database'];
            $this->connect();
        } else {
            die("Thông tin kết nối cơ sở dữ liệu không có sẵn.");
        }
    }
            private function connect() {
                $this->conn = new mysqli($this->host, $this->username, $this->password, $this->dbname);
                if ($this->conn->connect_error) {
                    die("Kết nối thất bại: " . $this->conn->connect_error);
                }
            }
            public function getCourses() {
                $sql = "SELECT * FROM course";
                $result = $this->conn->query($sql);
        
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        // Hiển thị card cho mỗi khóa học
                        echo '
                        <div class="col">
                            <div class="card">
                                <img src="'. strtolower($row['ImageUrl']) . '" class="card-img-top" alt="' . $row['Tittle'] . '">
                                <div class="card-body">
                                    <h5 class="card-title">' . $row['Tittle'] . '</h5>
                                    <p class="card-text">' . $row['Description'] . '</p>
                                </div>
                            </div>
                        </div>';
                    }
                } else {
                    echo "Không có khóa học nào.";
                }
            }
            public function getCourses2() {
                $sql = "SELECT * FROM course";
                $result = $this->conn->query($sql);
        
                if ($result->num_rows > 0) {
                    $courses = [];
                    while ($row = $result->fetch_assoc()) {
                        $courses[] = $row;
                    }
                    return $courses;
                } else {
                    return [];
                }
            }
            public function close() {
                $this->conn->close();
            }
        }
        // Xử lý việc ghi file
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
            // Khởi tạo đối tượng Database và lấy danh sách khóa học
            $db = new Database();
            $courses = $db->getCourses2();
            $filename= $_POST['filename'];
            $db->close();

            // Kiểm tra nếu có khóa học
            if (empty($courses)) {
                echo "Không có khóa học nào để ghi.";
            } else {
                // Tạo nội dung để ghi vào file
                $content = "Danh sách Khóa Học:\n\n";
                foreach ($courses as $course) {
                    $content .= "Id: " . $course['Id'] . "\n";
                    $content .= "Tittle: " . $course['Tittle'] . "\n";
                    $content .= "Description: " . $course['Description'] . "\n";
                    $content .= "ImageUrl: " . $course['ImageUrl'] . "\n";
                    $content .= "---------------------\n";
                }

                // Đường dẫn file
                $file_path = "files/$filename.txt"; // Đổi tên file tại đây

                // Ghi vào file
                file_put_contents($file_path, $content);
            }
        }

    ?>
    <header>
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.php">PHP Example</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup"
                    aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                    <div class="navbar-nav">
                        <a class="nav-link active" aria-current="page" href="index.php">Home</a>
                        <a class="nav-link" href="connect.php">Connect MySQL</a>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <div class="container my-3">
        <nav class="alert alert-primary" style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Index</li>
            </ol>
        </nav>

        <div class="row row-cols-1 row-cols-md-2 g-4">
            <?php 
            // Khởi tạo đối tượng Database
            $Database = new Database();

            // Gọi phương thức getCourses để in ra danh sách khóa học
            $Database->getCourses();
            

            // Đóng kết nối khi không cần thiết
            $Database->close();
            ?>
            
        </div>

        
        <hr>
        <form class="row" method="POST" enctype="multipart/form-data">
            <div class="col">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="server" placeholder="File name" name="filename">
                    <label for="data">File name</label>
                    
                </div>
                <button type="submit" class="btn btn-primary" name="submit">Write file</button>
            </div>
            <div class="col">
            </div>
        </form>
    </div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>