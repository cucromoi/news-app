<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

$categories = [
    "Trang chủ" => "https://vnexpress.net/rss/tin-moi-nhat.rss",
    "Thế giới" => "https://vnexpress.net/rss/the-gioi.rss",
    "Kinh doanh" => "https://vnexpress.net/rss/kinh-doanh.rss",
    "Giải trí" => "https://vnexpress.net/rss/giai-tri.rss",
    "Thể thao" => "https://vnexpress.net/rss/the-thao.rss",
    "Pháp luật" => "https://vnexpress.net/rss/phap-luat.rss",
    "Khoa học" => "https://vnexpress.net/rss/khoa-hoc.rss",
];

$category = $_GET["category"] ?? "Trang chủ";
$url = $categories[$category] ?? $categories["Trang chủ"];

// Load RSS
$xml = @simplexml_load_file($url);

if (!$xml) {
    echo json_encode(["error" => "Không tải được RSS feed"]);
    exit;
}

$items = [];
foreach ($xml->channel->item as $item) {
    // Lấy ảnh từ phần mô tả (VnExpress nhúng <img>)
    $description = (string)$item->description;
    preg_match('/<img[^>]+src="([^"]+)"/', $description, $match);
    $image = $match[1] ?? '';

    // Loại bỏ thẻ HTML khỏi mô tả
    $cleanDesc = strip_tags($description);

    $items[] = [
        "title" => (string)$item->title,
        "link" => (string)$item->link,
        "description" => $cleanDesc,
        "image" => $image,
    ];
}

echo json_encode([
    "category" => $category,
    "news" => $items
], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
?>
