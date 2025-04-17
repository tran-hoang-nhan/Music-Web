<?php
// Initialize the search term variable
$search_term_music = '';

// Check if a search term is provided via POST
if (isset($_POST['search_music']) && !empty($_POST['search_music'])) {
    $search_term_music = $_POST['search_music'];
}

// Pagination logic
$results_per_page_music = 6;

// Adjust SQL query for counting total results, considering the search term
$query_music_count = "SELECT COUNT(*) as total FROM music WHERE ten_nhac LIKE '%$search_term_music%' OR tac_gia LIKE '%$search_term_music%'";
$result_music_count = $conn->query($query_music_count);
$row_music_count = $result_music_count->fetch_assoc();
$number_of_result_music = $row_music_count['total'];

$number_of_page_music = ceil($number_of_result_music / $results_per_page_music);

// Determine the current page
if (isset($_GET['page_music'])) {
    $page_music = $_GET['page_music'];
} else {
    $page_music = 1;
}

// Calculate the first result of the current page
$page_first_result_music = ($page_music - 1) * $results_per_page_music;

// Adjust the SQL query for fetching music records, considering the search term
$sql_music = "SELECT id, ten_nhac, tac_gia FROM music WHERE ten_nhac LIKE '%$search_term_music%' OR tac_gia LIKE '%$search_term_music%' LIMIT $page_first_result_music, $results_per_page_music";
$result_music = $conn->query($sql_music);
?>