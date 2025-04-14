<?php
require 'config.php';

session_start();

$username = $_SESSION['username'];
$advice_content = "";
$matched_career = "";

// 读取上次生成的职业
$result = $conn->query("SELECT matched_career FROM user_career_info WHERE username = '$username' LIMIT 1");
if ($result && $row = $result->fetch_assoc()) {
    $matched_career = $row['matched_career'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST" || !empty($matched_career)) {
    $career = $_POST["career"] ?? $matched_career;

    // 再次调用AI生成职业路径建议
    $api_key = "0f509ce0d8e34ba0b61fde13c0226b2b.M7SJMe9N7m4uzuos";
    $api_url = "https://open.bigmodel.cn/api/paas/v4/chat/completions";

    $headers = [
        "Content-Type: application/json",
        "Authorization: Bearer $api_key"
    ];

    $data = json_encode([
        "model" => "glm-4",
        "messages" => [
            ["role" => "system", "content" => "你是一个职业路径规划顾问，能详细分析如何从零开始成为某个职业，并给出具体步骤。"],
            ["role" => "user", "content" => "请告诉我如何成为：" . $career]
        ],
        "temperature" => 0.7,
        "top_p" => 0.9
    ]);

    $ch = curl_init($api_url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_POSTFIELDS => $data,
        CURLOPT_SSL_VERIFYPEER => false
    ]);

    $response = curl_exec($ch);

    if (curl_getinfo($ch, CURLINFO_HTTP_CODE) == 200) {
        $ai_response = json_decode($response, true);
        $advice_content = $ai_response['choices'][0]['message']['content'] ?? "未能生成建议";
    } else {
        $advice_content = "AI接口请求失败！";
    }
}
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>大学生职业成长导航系统</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2A2D72;
            --secondary-color: #5E63B6;
            --accent-color: #A393EB;
            --text-color: #333;
            --gradient: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: #f8f9fa;
            margin: 0;
            padding: 0;
            color: var(--text-color);
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            position: relative;
            overflow: hidden;
        }

        .container::before {
            content: '';
            position: absolute;
            top: -50px;
            right: -50px;
            width: 200px;
            height: 200px;
            background: var(--gradient);
            border-radius: 30px;
            transform: rotate(45deg);
            opacity: 0.1;
        }

        .return-btn {
            display: inline-flex;
            align-items: center;
            margin-bottom: 30px;
            text-decoration: none;
            color: #fff;
            background: var(--primary-color);
            padding: 12px 25px;
            border-radius: 8px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(42,45,114,0.2);
        }

        .return-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(42,45,114,0.3);
        }

        h1 {
            color: var(--primary-color);
            margin-bottom: 30px;
            font-weight: 600;
            position: relative;
            padding-bottom: 15px;
        }

        h1::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background: var(--accent-color);
        }

        form {
            margin: 40px 0;
        }

        input[type="text"] {
            width: 100%;
            padding: 15px 25px;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            font-size: 16px;
            transition: all 0.3s ease;
            margin: 15px 0;
        }

        input[type="text"]:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 15px rgba(163,147,235,0.2);
            outline: none;
        }

        button {
            padding: 15px 40px;
            border: none;
            background: var(--gradient);
            color: white;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 4px 15px rgba(42,45,114,0.3);
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(42,45,114,0.4);
        }

        .advice {
            background: #f9fafb;
            padding: 30px;
            border-radius: 15px;
            margin-top: 30px;
            position: relative;
            border-left: 4px solid var(--accent-color);
        }

        .advice h2 {
            color: var(--primary-color);
            margin-top: 0;
            font-weight: 600;
        }

        .advice p {
            line-height: 1.8;
            color: #555;
            font-size: 16px;
        }

        @media (max-width: 768px) {
            .container {
                margin: 20px;
                padding: 25px;
            }

            h1 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <div style="text-align:right; margin-bottom:10px;">👤 当前用户: <?= htmlspecialchars($username) ?></div>
    <a href="welcome.php" class="return-btn">
        ← 返回首页
    </a>
    <h1>职业成长路径规划系统</h1>
    <form method="post">
        <input type="text" name="career" value="<?= htmlspecialchars($matched_career) ?>" required>
        <button type="submit">生成建议</button>
    </form>
    <div class="advice">
        <h2>职业发展建议报告</h2>
        <p><?= nl2br(htmlspecialchars($advice_content)) ?></p>
    </div>
</div>
</body>
</html>
