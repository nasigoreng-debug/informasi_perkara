<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fitur Sedang Dikembangkan | PTA Bandung</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-dark: #1a2a6c;
            --secondary-dark: #2a4858;
            --bg-light: #f4f7fa;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-light);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            color: #2d3436;
        }

        .construction-card {
            background: white;
            border: none;
            border-radius: 40px;
            padding: 60px;
            text-align: center;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.05);
            max-width: 600px;
            width: 90%;
        }

        .icon-wrapper {
            position: relative;
            display: inline-block;
            margin-bottom: 40px;
        }

        .main-icon {
            font-size: 5rem;
            color: #e2e8f0;
        }

        .gear-icon {
            position: absolute;
            font-size: 2.5rem;
            color: var(--primary-dark);
            bottom: 0;
            right: -10px;
        }

        h1 {
            font-weight: 800;
            color: var(--primary-dark);
            margin-bottom: 20px;
            letter-spacing: -1px;
        }

        p {
            color: #636e72;
            line-height: 1.8;
            font-weight: 400;
            margin-bottom: 40px;
        }

        .progress {
            height: 10px;
            border-radius: 10px;
            background-color: #f1f5f9;
            margin-bottom: 40px;
        }

        .progress-bar {
            background: linear-gradient(90deg, var(--primary-dark), #4a90e2);
            border-radius: 10px;
        }

        .btn-back {
            background: var(--primary-dark);
            color: white;
            padding: 15px 40px;
            border-radius: 50px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.3s;
            display: inline-block;
            border: none;
        }

        .btn-back:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(26, 42, 108, 0.2);
            color: white;
        }
    </style>
</head>

<body>

    <div class="construction-card animate__animated animate__zoomIn">
        <div class="icon-wrapper">
            <i class="fas fa-tools main-icon"></i>
            <i class="fas fa-cog fa-spin gear-icon"></i>
        </div>

        <h1>Sedang Proses Pengembangan</h1>
        <p>
            Mohon maaf atas ketidaknyamanannya. Fitur <strong>Administrasi</strong> sedang dalam tahap pengembangan oleh Tim IT PTA Bandung untuk memberikan layanan yang lebih baik.
        </p>

        <div class="progress">
            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 65%"></div>
        </div>

        <a href="{{ route('welcome') }}" class="btn-back">
            <i class="fas fa-arrow-left me-2"></i> KEMBALI KE PORTAL
        </a>
    </div>

</body>

</html>