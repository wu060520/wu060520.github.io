const questions = [
    "你最熟悉的编程语言是什么？能否举例说明你在项目中的应用？",
    "你如何处理内存管理和垃圾回收？",
    "你如何优化代码的性能？",
    "你熟悉哪些数据结构？它们的优缺点是什么？",
    "请解释一下哈希表的工作原理。"
];

let currentQuestionIndex = 0;
let score = 0;
let startTime; // 记录面试开始时间
let timerInterval; // 用于更新总计时器的 setInterval
let questionTimerInterval; // 用于更新每个问题的倒计时
const questionTimeLimit = 10 * 60; // 每个问题的限时（10 分钟，单位：秒）

const questionText = document.getElementById('question-text');
const answerInput = document.getElementById('answer-input');
const submitAnswerButton = document.getElementById('submit-answer');
const feedbackText = document.getElementById('feedback-text');
const scoreText = document.getElementById('score-text');
const nextQuestionButton = document.getElementById('next-question');
const feedbackContainer = document.getElementById('feedback-container');
const timerDisplay = document.createElement('div'); // 用于显示总计时器
const questionTimerDisplay = document.createElement('div'); // 用于显示问题倒计时
timerDisplay.id = 'timer';
questionTimerDisplay.id = 'question-timer';
document.querySelector('.container').prepend(questionTimerDisplay, timerDisplay); // 将计时器添加到页面顶部

// 开始总计时器
function startTimer() {
    startTime = Date.now();
    timerInterval = setInterval(updateTimer, 1000); // 每秒更新一次总计时器
}

// 更新总计时器显示
function updateTimer() {
    const elapsedTime = Math.floor((Date.now() - startTime) / 1000); // 计算经过的秒数
    const minutes = Math.floor(elapsedTime / 60);
    const seconds = elapsedTime % 60;
    timerDisplay.textContent = `总时间: ${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
}

// 开始问题倒计时
function startQuestionTimer() {
    let timeLeft = questionTimeLimit;
    updateQuestionTimer(timeLeft); // 初始化显示
    questionTimerInterval = setInterval(() => {
        timeLeft--;
        updateQuestionTimer(timeLeft);
        if (timeLeft <= 0) {
            clearInterval(questionTimerInterval); // 停止倒计时
            autoNextQuestion(); // 自动跳转到下一题
        }
    }, 1000); // 每秒更新一次
}

// 更新问题倒计时显示
function updateQuestionTimer(timeLeft) {
    const minutes = Math.floor(timeLeft / 60);
    const seconds = timeLeft % 60;
    questionTimerDisplay.textContent = `剩余时间: ${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
}

// 自动跳转到下一题
function autoNextQuestion() {
    feedbackText.textContent = "时间到！自动跳转到下一题。";
    feedbackContainer.style.display = 'block';
    setTimeout(() => {
        nextQuestion();
    }, 2000); // 2 秒后跳转
}

function displayQuestion() {
    questionText.textContent = questions[currentQuestionIndex];
    answerInput.value = '';
    feedbackContainer.style.display = 'none';
    startQuestionTimer(); // 开始问题倒计时
}

function submitAnswer() {
    const answer = answerInput.value.trim();
    if (answer) {
        // 停止当前问题的倒计时
        clearInterval(questionTimerInterval);

        // 简单的评分逻辑
        if (answer.length > 50) {
            score += 10;
            feedbackText.textContent = "回答得很好！";
        } else {
            feedbackText.textContent = "回答可以再详细一些。";
        }
        scoreText.textContent = `当前得分: ${score}`;
        feedbackContainer.style.display = 'block';
    } else {
        alert("请输入你的回答！");
    }
}

function nextQuestion() {
    currentQuestionIndex++;
    if (currentQuestionIndex < questions.length) {
        displayQuestion();
    } else {
        // 停止总计时器和问题倒计时
        clearInterval(timerInterval);
        clearInterval(questionTimerInterval);

        // 显示最终得分和用时
        const elapsedTime = Math.floor((Date.now() - startTime) / 1000);
        const minutes = Math.floor(elapsedTime / 60);
        const seconds = elapsedTime % 60;
        feedbackText.textContent = `面试结束！你的最终得分是: ${score}，总用时: ${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        scoreText.textContent = '';
        nextQuestionButton.style.display = 'none'; // 隐藏“下一题”按钮

        // 添加“确认”按钮
        const confirmButton = document.createElement('button');
        confirmButton.textContent = '确认';
        confirmButton.id = 'confirm-button';
        confirmButton.addEventListener('click', () => {
            // 跳转到另一个页面
            window.location.href = 'result.html'; // 修改为目标页面的URL
        });
        feedbackContainer.appendChild(confirmButton);
    }
}

submitAnswerButton.addEventListener('click', submitAnswer);
nextQuestionButton.addEventListener('click', nextQuestion);

// 初始化
displayQuestion();
startTimer(); // 开始总计时