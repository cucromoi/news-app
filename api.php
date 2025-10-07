<?php
// api.php - Backend lấy tin từ VNExpress RSS + cache 5 phút (chạy ổn trên hosting)
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

$category = $_GET['category'] ?? 'trang-chu';

// Map RSS
$rssLinks = [
  'trang-chu'   => 'https://vnexpress.net/rss/tin-moi-nhat.rss',
  'the-gioi'    => 'https://vnexpress.net/rss/the-gioi.rss',
  'kinh-doanh'  => 'https://vnexpress.net/rss/kinh-doanh.rss',
  'giai-tri'    => 'https://vnexpress.net/rss/giai-tri.rss',
  'the-thao'    => 'https://vnexpress.net/rss/the-thao.rss',
  'phap-luat'   => 'https://vnexpress.net/rss/phap-luat.rss',
  'khoa-hoc'    => 'https://vnexpress.net/rss/khoa-hoc.rss'
];

$url = $rssLinks[$category] ?? $rssLinks['trang-chu'];

// Đường dẫn cache
$cacheDir = __DIR__ . '/cache';
$cacheFile = "$cacheDir/{$category}.json";
$cacheTime = 300; // 5 phút

if (!is_dir($cacheDir)) mkdir($cacheDir);

// Nếu có cache hợp lệ -> dùng
if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < $cacheTime)) {
  echo file_get_contents($cacheFile);
  exit;
}

// ---- Dùng cURL để lấy RSS ----
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // tránh lỗi SSL
$data = curl_exec($ch);
curl_close($ch);

if (!$data) {
  echo json_encode(["error" => "Không tải được RSS feed (cURL lỗi hoặc bị chặn)"]);
  exit;
}

// Parse XML thủ công
$rss = @simplexml_load_string($data);
if (!$rss) {
  echo json_encode(["error" => "RSS feed không hợp lệ"]);
  exit;
}

$news = [];
foreach ($rss->channel->item as $item) {
  $desc = (string)$item->description;
  preg_match('/<img[^>]+src="([^"]+)"/', $desc, $m);
  $image = $m[1] ?? '';
  $cleanDesc = trim(strip_tags($desc));

  $news[] = [
    'title' => (string)$item->title,
    'link' => (string)$item->link,
    'description' => mb_substr($cleanDesc, 0, 120) . '...',
    'image' => $image
  ];
}

// Lưu cache
file_put_contents($cacheFile, json_encode($news, JSON_UNESCAPED_UNICODE));
echo json_encode($news, JSON_UNESCAPED_UNICODE);
