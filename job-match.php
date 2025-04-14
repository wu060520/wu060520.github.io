<?php
require 'config.php';

session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_SESSION['username'])) {
    die("请先登录！");
}

$username = $_SESSION['username'];
$matched_career = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $mbti = $_POST["MBTI"];
    $school = $_POST["school"];
    $major = $_POST["major"];
    $education = $_POST["education_background"];
    $place = $_POST["place"];
    $career_idea = $_POST["career_idea"];

    // 使用AI生成匹配职业
    $api_key = "0f509ce0d8e34ba0b61fde13c0226b2b.M7SJMe9N7m4uzuos";
    $api_url = "https://open.bigmodel.cn/api/paas/v4/chat/completions";

    $headers = [
        "Content-Type: application/json",
        "Authorization: Bearer $api_key"
    ];

    $prompt = "你是职业分析师，根据以下信息推荐一个最适合的职业，我不要你进行文字性质的输出分析，你只需在你的知识图谱里面分析最后只需输出一个职业或两个职业。要根据他的学校等级、专业、学历来进行推荐（不用文字性质的输出，只需要输出一个职业即可），一定要考虑用户个人的就职意向。你只需要输出一个职业即可，不用进行文字输出性质的分析，你只需要根据用户的这些信息，输出一个职业名词，其它任何文字都不要输出\n".
        "姓名：$name\nMBTI类型：$mbti\n学校：$school\n专业：$major\n学历：$education\n理想就业地：$place\n就业意向：$career_idea";
    $data = json_encode([
        "model" => "glm-4",
        "messages" => [
            ["role" => "system", "content" => "你是一个专业的职业推荐系统。"],
            ["role" => "user", "content" => $prompt]
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
    $career_result = "未知职业";

    if (curl_getinfo($ch, CURLINFO_HTTP_CODE) == 200) {
        $ai_response = json_decode($response, true);
        $career_result = $ai_response['choices'][0]['message']['content'] ?? "未能识别";
    }

    // 存入数据库
    $stmt = $conn->prepare("INSERT INTO user_career_info (username, name, mbti, school, major, education, place, career_idea, matched_career)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE 
                            name=VALUES(name), mbti=VALUES(mbti), school=VALUES(school), major=VALUES(major), 
                            education=VALUES(education), place=VALUES(place), career_idea=VALUES(career_idea), matched_career=VALUES(matched_career)");
    $stmt->bind_param("sssssssss", $username, $name, $mbti, $school, $major, $education, $place, $career_idea, $career_result);
    $stmt->execute();
    $stmt->close();

    // 跳转
    header("Location: match-later.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>大学生职业成长导航系统</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Noto+Sans+SC:wght@300;500&display=swap">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #A393EB;
            --background-color: #f8f9fa;
            --card-bg: #ffffff;
        }
        .hero h1 {
            font-size: 3rem; /* 放大标题字号 */
            font-weight: 600;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
            margin: 20px 0;
        }


        body {
            font-family: 'Noto Sans SC', sans-serif;
            background-color: var(--background-color);
            margin: 0;
            padding: 0;
        }

        .hero {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 80px 20px 40px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .user-info {
            position: absolute;
            top: 20px;
            right: 20px;
            background: rgba(255,255,255,0.9);
            padding: 8px 15px;
            border-radius: 20px;
            color: var(--primary-color);
            font-weight: 500;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .form-container {
            max-width: 800px;
            margin: 40px auto;
            background: var(--card-bg);
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.08);
            padding: 30px;
        }

        .form-section {
            margin: 25px 0;
            padding: 20px;
            border-left: 4px solid var(--secondary-color);
            background: #f8f9fa;
            border-radius: 6px;
        }

        .button-group {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }

        .button {
            flex: 1;
            padding: 12px 24px;
            background: linear-gradient(135deg, var(--secondary-color), #2980b9);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            text-align: center;
            text-decoration: none;
        }

        .button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(52,152,219,0.4);
        }

        form {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: var(--primary-color);
            font-weight: 500;
        }

        input, textarea {
            width: 100%;
            padding: 10px;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        input:focus, textarea:focus {
            border-color: var(--secondary-color);
            outline: none;
        }

        #loadingMessage {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.9);
            z-index: 9999;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .loader-container {
            text-align: center;
            padding: 20px;
            background: rgba(0, 0, 0, 0.8);
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.2);
        }

        .loader {
            width: 50px;
            height: 50px;
            margin: 0 auto 15px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #3498db;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        .loading-text {
            color: white;
            font-size: 18px;
            font-weight: bold;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
<section class="hero">
    <h1>匹配你的职业</h1>
    <div class="user-info">👤 当前用户: <?= htmlspecialchars($username) ?></div>
</section>

<div class="form-container">
    <section class="contact">
        <div class="form-section">
            <h3>1）人格测试</h3>
            <a href="https://www.16personalities.com/" target="_blank" class="button">
                📒 点击进行MBTI人格测试
            </a>
        </div>

        <div class="form-section">
            <h3>2）个人信息填写</h3>
            <form method="post" onsubmit="showLoading()">
                <div>
                    <label>姓名:</label>
                    <input type="text" name="name" required>
                </div>
                <div>
                    <label>MBTI:</label>
                    <input type="text" name="MBTI" required>
                </div>
                <div>
                    <label>学校:</label>
                    <input type="text" name="school" required>
                </div>
                <div>
                    <label>专业:</label>
                    <input type="text" name="major" required>
                </div>
                <div>
                    <label>学历:</label>
                    <input type="text" name="education_background" required>
                </div>
                <div>
                    <label>理想就职地:</label>
                    <input type="text" name="place" required>
                </div>
                <div style="grid-column: span 2">
                    <label>职业意向:</label>
                    <textarea name="career_idea" rows="4" required></textarea>
                </div>
                <div class="button-group" style="grid-column: span 2">
                    <button type="submit" class="button">提交信息</button>
                    <a href="welcome.php" class="button">返回首页</a>
                </div>
            </form>
        </div>
    </section>
</div>

<div id="loadingMessage" style="display:none;">
    <div class="loader-container">
        <div class="loader"></div>
        <div class="loading-text">AI正在分析中，请稍候...</div>
    </div>
</div>

<script>
    function showLoading() {
        document.getElementById("loadingMessage").style.display = "flex";
    }
    </script>
    </body>
    </html>