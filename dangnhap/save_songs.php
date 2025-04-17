<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $playlist = $_SESSION['playlist'] ?? [];
        $currentSongIndex = $_SESSION['current_song_index'] ?? 0;

        if ($_POST['action'] === 'next') {
            $currentSongIndex = ($currentSongIndex + 1) % count($playlist); // Next song
        } elseif ($_POST['action'] === 'previous') {
            $currentSongIndex = ($currentSongIndex - 1 + count($playlist)) % count($playlist); // Previous song
        }

        $_SESSION['current_song_index'] = $currentSongIndex;
    }

    if (isset($_POST['index'])) {
        $_SESSION['current_song_index'] = (int) $_POST['index']; // Chọn bài hát từ danh sách
    }

    $currentSong = $_SESSION['playlist'][$_SESSION['current_song_index']] ?? null;

    if ($currentSong) {
        $_SESSION['current_song_name'] = $currentSong['ten_nhac'];
        $_SESSION['current_song_author'] = $currentSong['tac_gia'];
        $_SESSION['current_song_file'] = $currentSong['file_nhac'];
        $_SESSION['current_song_image'] = $currentSong['file_hinh'];
    }

    exit;
}
?>
