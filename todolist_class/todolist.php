<?php

class ToDoList {
    private $file;

    public function __construct($file) {
        $this->file = $file;
    }

    public function tambahItem($item) {
        // Baca isi file untuk mengetahui nomor item terakhir
        $lines = file($this->file);
        $nomor = count($lines) + 1;

        // Tambahkan nomor dan item baru ke dalam file
        file_put_contents($this->file, "$nomor. $item" . PHP_EOL, FILE_APPEND);
    }

    public function hapusItem($index) {
        $lines = file($this->file);
        if (isset($lines[$index])) {
            unset($lines[$index]);
            file_put_contents($this->file, implode('', $lines));
        }
    }

    public function tampilkanList() {
        $lines = file($this->file);
        echo "<ul>";
        foreach ($lines as $key => $line) {
            echo "<li>$line <a href='?hapus=$key'>Hapus</a></li>";
        }
        echo "</ul>";
    }
}

// Inisialisasi objek ToDoList
$toDoList = new ToDoList('todo.txt');

// Proses penambahan item jika data POST disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['item'])) {
    $toDoList->tambahItem($_POST['item']);
    header("Location: {$_SERVER['PHP_SELF']}");
    exit;
}

// Proses penghapusan item jika ada parameter hapus di URL
if (isset($_GET['hapus'])) {
    $toDoList->hapusItem($_GET['hapus']);
    header("Location: {$_SERVER['PHP_SELF']}");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ToDo List</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>ToDo List</h2>
        <form method="post">
            <input type="text" name="item" placeholder="Tambahkan item">
            <button type="submit">Tambah</button>
        </form>
        <?php
        // Tampilkan daftar to-do
        $toDoList->tampilkanList();
        ?>
    </div>
</body>
</html>
