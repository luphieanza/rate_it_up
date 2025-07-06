<?php
session_start();
require 'koneksi.php';

if (isset($_SESSION['user_id'])) {
  header('Location: dashboard.php');
  exit;
}

$pesan = '';

if (isset($_GET['status']) && $_GET['status'] == 'sukses_registrasi') {
  $pesan = "<div class='alert alert-success'>Registrasi berhasil! Silakan login.</div>";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST['username'];
  $password = $_POST['password'];

  $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();

    if (password_verify($password, $user['password'])) {
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['username'] = $user['username'];
      $_SESSION['role'] = $user['role'];

      header('Location: dashboard.php');
      exit;
    } else {
      $pesan = "<div class='alert alert-danger'>Username atau Password Salah!</div>";
    }
  } else {
    $pesan = "<div class='alert alert-danger'>Username atau Password Salah!</div>";
  }
  $stmt->close();
  $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Rate it Up</title>
  <link rel="icon" href="assets\img\iconLogo.png" type="image/x-icon">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      background-color: #f8f9fa;
    }

    .login-card {
      width: 100%;
      max-width: 400px;
    }
  </style>
</head>

<body>
  <div class="card login-card shadow-sm">
    <div class="card-body p-5">
      <h3 class="card-title text-center mb-4">Login to Rate it Up</h3>
      <?php echo $pesan; ?>
      <form action="login.php" method="POST">
        <div class="mb-3">
          <label for="username" class="form-label">Username</label>
          <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <div class="d-grid">
          <button type="submit" class="btn btn-primary">Login</button>
        </div>
      </form>
      <div class="text-center mt-3">
        <small>Belum punya akun? <a href="registrasi.php">Daftar di sini</a></small>
      </div>
    </div>
  </div>
</body>

</html>