/*
 * Copyright (C) <2024> <Hasan Beder> - <https://github.com/hasanbeder/eksisozlukentryapi>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 * How to Use:
 * 1. Include this script in your project.
 * 2. Make a GET request to the script with the following parameters:
 *    - `input`: The Ekşi Sözlük title or URL.
 *    - `start_page` (optional): The starting page number (default is 1).
 *    - `end_page` (optional): The ending page number (default is the last page).
 *    - `get_all` (optional): Set to `true` to retrieve all entries (overrides `end_page`).
 * 3. The script will return a JSON response with the following structure:
 *    ```json
 *    {
 *        "status": "success",
 *        "data": [
 *            {
 *                "id": "entry_id",
 *                "author": "author_username",
 *                "date": "entry_date",
 *                "date_link": "entry_permalink",
 *                "author_link": "author_profile_link",
 *                "content": "entry_content"
 *            },
 *            // ... more entries
 *        ]
 *    }
 *    ```
 *    In case of an error, the response will have a "status" of "error" and a "message" explaining the error.
 *
 * Example usage:
 * `https://your-domain.com/eksisozlukentryapi.php?input=example-title`
 * `https://your-domain.com/eksisozlukentryapi.php?input=example-title&start_page=2&end_page=5`
 * `https://your-domain.com/eksisozlukentryapi.php?input=https://eksisozluk.com/example-title?p=1`
 *
 */
<?php

ob_start(); // Start output buffering

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!isset($_GET['input']) || empty($_GET['input'])) {
        $response = array("status" => "error", "message" => "Please specify the 'input' parameter.");
        http_response_code(400);
        echo json_encode($response);
        exit;
    }

    $input = $_GET['input'];
    $startPage = isset($_GET['start_page']) ? max(1, intval($_GET['start_page'])) : 1;
    $endPage = !empty($_GET['end_page']) ? intval($_GET['end_page']) : null;
    $getAll = isset($_GET['get_all']);

    $slug = extractSlugFromUrl($input);
    $url = "https://eksisozluk.com/" . $slug;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Safari/537.36");
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    $html = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if ($html === false || $httpCode !== 200) {
        curl_close($ch);
        $response = array("status" => "error", "message" => "Title not found or an error occurred. HTTP Code: " . $httpCode);
        http_response_code(404);
        echo json_encode($response);
        exit;
    }

    $dom = new DOMDocument();
    @$dom->loadHTML($html);
    $xpath = new DOMXPath($dom);
    $pageCountNode = $xpath->query('//div[@class="pager"]/@data-pagecount')->item(0);
    $totalPages = $pageCountNode ? intval($pageCountNode->value) : 1;
    curl_close($ch);

    if ($getAll) {
        $endPage = $totalPages;
    } else if ($endPage === null || $endPage > $totalPages) {
        $endPage = $totalPages;
    }

    $allEntries = [];

    for ($page = $startPage; $page <= $endPage; $page++) {
        $pageUrl = $url . "?p=" . $page;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $pageUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Safari/537.36");
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $html = curl_exec($ch);

        if ($html === false) {
            curl_close($ch);
            continue;
        }

        curl_close($ch);

        $dom = new DOMDocument();
        @$dom->loadHTML($html);
        $xpath = new DOMXPath($dom);

        foreach ($xpath->query('//li[@id="entry-item" and @data-show="true"]') as $entryNode) {
            $entry = [];
            $entry['id'] = $entryNode->getAttribute('data-id');
            $entry['author'] = $entryNode->getAttribute('data-author');
            $dateNode = $xpath->query('.//a[@class="entry-date permalink"]', $entryNode)->item(0);
            $entry['date'] = $dateNode->textContent;
            $entry['date_link'] = "https://eksisozluk.com" . $dateNode->getAttribute('href');

            // Create author_link while preserving spaces
            $entry['author_link'] = "https://eksisozluk.com/biri/" . $entry['author'];

            $content = $xpath->query('.//div[@class="content"]', $entryNode)->item(0)->textContent;
            $entry['content'] = normalizeContent($content);
            $allEntries[] = $entry;
        }
    }

    $response = array("status" => "success", "data" => $allEntries);
    header('Content-Type: application/json; charset=utf-8');
    http_response_code(200);
    echo json_encode($response);
}

function normalizeContent($content) {
    $content = str_replace(["\r\n", "\r"], "\n", $content);
    $content = trim($content);
    $lines = explode("\n", $content);
    $lines = array_map('trim', $lines);
    $content = implode("\n", $lines);
    $content = preg_replace('/\n\s*\n/', "\n\n", $content);
    $content = preg_replace('/\s+/', ' ', $content);
    return trim($content);
}

function extractSlugFromUrl($input) {
    if (strpos($input, 'eksisozluk.com/') !== false) {
        $parts = parse_url($input);
        $path = $parts['path'] ?? '';
        $path = ltrim($path, '/');
        $path = explode('?', $path)[0];
        return $path;
    }
    return $input;
}

ob_end_flush(); // End output buffering

?>