<!-- navigation.php -->
<head>
  <title>SISTEM PENYELENGGARAAN ICT</title>
  <link rel="icon" type="image/png" href="ictlogo.png">

  <!-- CSS untuk sidebar bulat + icon + teks align center + garisan antara menu -->
  <style>
    .sidebar-menu {
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .sidebar-menu li {
        margin: 0;
        text-align: center;  

        /* INI TEMPAT LETAK LINE ANTARA MENU (termasuk bawah Log Keluar) */
        border-bottom: 1px solid rgba(255, 255, 255, 0.2); 
    }

    /* 
    Kalau TAK nak line di bawah menu terakhir (Log Keluar),
    boleh aktifkan balik code bawah ini:

    .sidebar-menu li:last-child {
        border-bottom: none;
    }
    */

    .sidebar-menu li a {
        display: flex;
        flex-direction: column;        /* icon atas, text bawah */
        align-items: center;           /* align center horizontal */
        justify-content: center;       /* center vertical dalam padding */
        padding: 14px 8px;
        text-decoration: none;
        color: inherit;
        text-align: center;
        width: 100%;                   /* ambil penuh lebar sidebar */
        box-sizing: border-box;
    }

    /* bulatan untuk icon di tengah */
    .sidebar-menu li a .menu-icon {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        border: 2px solid rgba(255, 255, 255, 0.4); /* boleh tukar warna ikut tema */
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        margin: 0 auto 6px auto;      /* pastikan betul-betul center */
        box-sizing: border-box;
        overflow: hidden;             /* penting untuk gambar dalam bulatan */
    }

    /* GAMBAR dalam menu-icon (contoh: report.png) */
    .sidebar-menu li a .menu-icon img {
        max-width: 70%;
        max-height: 70%;
        display: block;
    }

    /* text bawah icon */
    .sidebar-menu li a .menu-text {
        font-size: 14px;
        line-height: 1.2;
        white-space: normal;   /* benarkan wrap text */
        padding: 0 4px;        /* ruang sikit kiri kanan */
    }

    /* hover effect simple, TANPA zoom */
    .sidebar-menu li a:hover .menu-icon {
        background: rgba(255, 255, 255, 0.15);
        border-color: rgba(255, 255, 255, 0.8);
    }

    .sidebar-menu li a:hover .menu-text {
        text-decoration: none;
    }

    /* MATIKAN sebarang zoom / animation kalau ada dari style.css */
    .sidebar-menu li a,
    .sidebar-menu li a * {
        transition: none !important;
        transform: none !important;
        animation: none !important;
    }

    /* Kalau sidebar ada class collapsed, sorok teks, kekalkan icon bulat sahaja */
    .sidebar.collapsed .sidebar-menu li a .menu-text {
        display: none;
    }
  </style>
</head>

<!-- navigation.php -->
<link rel="stylesheet" href="style.css">

<div id="sidebar" class="sidebar collapsed">
    <div class="sidebar-header">
        <h3>eDK2</h3>
    </div>
    <ul class="sidebar-menu">
        <!-- index.php : Senarai Penyelenggaraan -->
        <li data-tooltip="Senarai Penyelenggaraan">
            <a href="index.php">
                <span class="menu-icon">🏠</span>
                <span class="menu-text">Senarai Penyelenggaraan</span>
            </a>
        </li>

        <!-- add_pilihan.php : Daftar Pengguna -->
        <li data-tooltip="Daftar Pengguna">
            <a href="add_pilihan.php">
                <span class="menu-icon">✚</span>
                <span class="menu-text">Daftar Pengguna</span>
            </a>
        </li>

        <!-- senarai_pengguna.php : Senarai Pengguna -->
        <li data-tooltip="Senarai Pengguna">
            <a href="senarai_pengguna.php">
                <span class="menu-icon">📋</span>
                <span class="menu-text">Senarai Pengguna</span>
            </a>
        </li>

        <!-- laporan.php : Laporan -->
        <li data-tooltip="Laporan">
            <a href="laporan.php">
                <span class="menu-icon">
                    <img src="report.png" alt="Laporan">
                </span>
                <span class="menu-text">Laporan</span>
            </a>
        </li>

        <!-- logout.php : Log Keluar -->
        <li data-tooltip="Log Keluar">
            <a href="logout.php">
                <span class="menu-icon">➜]</span>
                <span class="menu-text">Log Keluar</span>
            </a>
        </li>
    </ul>
</div>

<button id="toggleBtn" class="toggle-btn">&#9776;</button>
