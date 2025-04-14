<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>大学生职业成长导航平台</title>
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+SC:wght@300;500;700&display=swap');
        body { font-family: 'Noto Sans SC', sans-serif; }
        .hero-bg { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .feature-card { transition: transform 0.3s, box-shadow 0.3s; }
        .feature-card:hover { transform: translateY(-5px); }
        .glass-effect { background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); }
        .feature-card:hover .fa-arrow-right {
            transform: translateX(3px);
            transition: transform 0.3s ease;
        }
    </style>
</head>

<body class="bg-gray-50">
<div id="app">
    <!-- 新版导航栏 -->
    <nav class="fixed w-full bg-white/80 shadow-sm backdrop-blur z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <!-- 左侧欢迎信息 -->
                <div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-2 text-gray-600">
                        <i class="fas fa-hand-wave text-sm"></i>
                        <span class="font-medium">欢迎，<?php echo $_SESSION['username']; ?>！</span>
                    </div>
                    <div class="h-5 w-px bg-gray-200"></div>
                    <a href="logout.php" class="text-indigo-500 hover:text-indigo-700 text-sm font-medium">
                        退出登录
                    </a>
                </div>

                <!-- 右侧个人中心 -->
                <div class="flex items-center space-x-6">
                    <a href="user-profile.php" class="flex items-center space-x-2 text-gray-600 hover:text-indigo-500">
                        <i class="fas fa-user-circle"></i>
                        <span>个人中心</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- 移除原来在hero部分的欢迎信息 -->
    <section class="hero-bg pt-24 pb-32 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-5xl font-bold mb-6 animate-fade-in-down">
                开启你的职业成长之旅
            </h1>
            <!-- 这里原来的欢迎信息已移除 -->
            <p class="text-xl text-indigo-100 mb-8">
                智能规划 × 精准匹配 × 沉浸式训练
            </p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-4xl mx-auto">
                <div class="glass-effect p-6 rounded-xl">
                    <i class="fas fa-brain text-3xl mb-4"></i>
                    <h3 class="text-lg font-semibold mb-2">AI智能分析</h3>
                    <p class="text-sm opacity-90">深度解析你的职业潜能</p>
                </div>
                <div class="glass-effect p-6 rounded-xl">
                    <i class="fas fa-chart-line text-3xl mb-4"></i>
                    <h3 class="text-lg font-semibold mb-2">成长路径规划</h3>
                    <p class="text-sm opacity-90">定制专属发展路线图</p>
                </div>
                <div class="glass-effect p-6 rounded-xl">
                    <i class="fas fa-users text-3xl mb-4"></i>
                    <h3 class="text-lg font-semibold mb-2">精英社区</h3>
                    <p class="text-sm opacity-90">与行业领袖直接对话</p>
                </div>
            </div>
        </div>
    </section>

    <!-- 核心功能模块 -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-16">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
            <a href="job-match.php" class="feature-card bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl">
                <div class="text-indigo-500 text-4xl mb-4">
                    <i class="fas fa-user-tie"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">职业匹配</h3>
                <p class="text-gray-600 mb-4">通过MBTI性格测试和AI算法，精准推荐最适合你的职业方向</p>
                <div class="flex items-center text-indigo-500">
                    <span>立即测试</span>
                    <i class="fas fa-arrow-right ml-2"></i>
                </div>
            </a>

            <a href="interview-choose.php" class="feature-card bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl">
                <div class="text-green-500 text-4xl mb-4">
                    <i class="fas fa-video"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">智能面试模拟</h3>
                <ul class="text-sm text-gray-600 space-y-2 mb-4">
                    <li>✔ 多行业面试题库</li>
                    <li>✔ AI面试官评估</li>
                    <li>✔ 实时表现分析</li>
                </ul>
                <div class="flex items-center text-green-500">
                    <span>开始模拟</span>
                    <i class="fas fa-arrow-right ml-2"></i>
                </div>
            </a>

            <a href="community.php" class="feature-card bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl">
                <div class="text-purple-500 text-4xl mb-4">
                    <i class="fas fa-comments"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">职业社区</h3>
                <p class="text-gray-600 mb-4">与行业精英交流，获取最新职场动态和求职经验分享</p>
                <div class="flex items-center text-purple-500">
                    <span>加入讨论</span>
                    <i class="fas fa-arrow-right ml-2"></i>
                </div>
            </a>
        </div>
        <!-- 在原有的三个功能模块之后继续添加 -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
            <!-- 原有三个模块... -->

            <!-- 新增简历测评模块 -->
            <a href="jianli.php" class="feature-card bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl">
                <div class="text-amber-500 text-4xl mb-4">
                    <i class="fas fa-file-signature"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">AI简历测评</h3>
                <div class="relative mb-4">
                    <div class="absolute inset-0 bg-gradient-to-r from-amber-100 to-amber-50 rounded-lg -rotate-1"></div>
                    <ul class="relative text-sm text-gray-600 space-y-2 p-4">
                        <li>✔ 智能内容评分</li>
                        <li>✔ 竞争力分析</li>
                        <li>✔ 一键优化建议</li>
                    </ul>
                </div>
                <div class="flex items-center text-amber-500">
                    <span>立即测评</span>
                    <i class="fas fa-arrow-right ml-2"></i>
                </div>
            </a>

            <!-- 新增证书认证模块 -->
            <a href="cailiao.php" class="feature-card bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl">
                <div class="text-cyan-500 text-4xl mb-4">
                    <i class="fas fa-award"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">证书认证中心</h3>
                <div class="flex flex-wrap gap-2 mb-4">
                    <span class="px-3 py-1 bg-cyan-50 text-cyan-600 rounded-full text-sm">技能认证</span>
                    <span class="px-3 py-1 bg-cyan-50 text-cyan-600 rounded-full text-sm">学历认证</span>
                    <span class="px-3 py-1 bg-cyan-50 text-cyan-600 rounded-full text-sm">项目认证</span>
                </div>
                <p class="text-sm text-gray-600">权威认证提升简历含金量，获得企业优先推荐</p>
                <div class="flex items-center text-cyan-500 mt-4">
                    <span>立即认证</span>
                    <i class="fas fa-arrow-right ml-2"></i>
                </div>
            </a>
        </div>

        <!--            &lt;!&ndash; 新增数据看板 &ndash;&gt;-->
        <!--            <section class="bg-indigo-50 rounded-2xl p-8 mb-16">-->
        <!--                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">-->
        <!--                    <div>-->
        <!--                        <div class="text-3xl font-bold text-indigo-600 mb-2">50W+</div>-->
        <!--                        <div class="text-gray-600">用户选择</div>-->
        <!--                    </div>-->
        <!--                    <div>-->
        <!--                        <div class="text-3xl font-bold text-indigo-600 mb-2">92%</div>-->
        <!--                        <div class="text-gray-600">求职成功率</div>-->
        <!--                    </div>-->
        <!--                    <div>-->
        <!--                        <div class="text-3xl font-bold text-indigo-600 mb-2">300+</div>-->
        <!--                        <div class="text-gray-600">合作企业</div>-->
        <!--                    </div>-->
        <!--                    <div>-->
        <!--                        <div class="text-3xl font-bold text-indigo-600 mb-2">10W+</div>-->
        <!--                        <div class="text-gray-600">面试真题</div>-->
        <!--                    </div>-->
        <!--                </div>-->
        <!--            </section>-->
    </main>

    <!-- 增强版底部 -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h4 class="text-lg font-semibold mb-4"></h4>
                    <p class="text-gray-400 text-sm">助力大学生职业发展的智能平台</p>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">产品服务</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="#" class="hover:text-white transition">职业测评</a></li>
                        <li><a href="#" class="hover:text-white transition">简历优化</a></li>
                        <li><a href="#" class="hover:text-white transition">在线课程</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">联系我们</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li>周一至周五 9:00-18:00</li>
                    </ul>
                </div>
                <!--                    <div>-->
                <!--                        <h4 class="text-lg font-semibold mb-4">关注我们</h4>-->
                <!--                        <div class="flex space-x-4">-->
                <!--                            <a href="#" class="text-2xl hover:text-indigo-400"><i class="fab fa-weixin"></i></a>-->
                <!--                            <a href="#" class="text-2xl hover:text-indigo-400"><i class="fab fa-weibo"></i></a>-->
                <!--                            <a href="#" class="text-2xl hover:text-indigo-400"><i class="fab fa-zhihu"></i></a>-->
                <!--                        </div>-->
                <!--                    </div>-->
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-sm text-gray-400">
            </div>
        </div>
    </footer>
</div>

<script>
    const { createApp } = Vue
    createApp({}).mount('#app')
</script>
</body>
</html>