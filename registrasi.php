<?php
require 'koneksi.php';
$pesan = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_lengkap = $_POST['nama_lengkap'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt_check = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt_check->bind_param("s", $username);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        $pesan = "<div class='alert alert-danger'>Username sudah digunakan, silakan pilih yang lain.</div>";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt_insert = $conn->prepare("INSERT INTO users (nama_lengkap, username, password) VALUES (?, ?, ?)");
        $stmt_insert->bind_param("sss", $nama_lengkap, $username, $hashed_password);

        if ($stmt_insert->execute()) {
            header('Location: login.php?status=sukses_registrasi');
            exit;
        } else {
            $pesan = "<div class='alert alert-danger'>Registrasi gagal, silakan coba lagi.</div>";
        }
        $stmt_insert->close();
    }
    $stmt_check->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi - Rate it Up</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f8f9fa;
        }

        .register-card {
            width: 100%;
            max-width: 500px;
        }
    </style>
</head>

<body>
    <div class="card register-card shadow-sm">
        <div class="card-body p-5">
            <h3 class="card-title text-center mb-4">Daftar Akun Baru</h3>
            <?php echo $pesan; ?>
            <form action="registrasi.php" method="POST">
                <div class="mb-3">
                    <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" required>
                </div>
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Daftar</button>
                </div>
            </form>
            <div class="text-center mt-3">
                <small>Sudah punya akun? <a href="login.php">Login di sini</a></small>
            </div>
        </div>
    </div>
</body>

</html>