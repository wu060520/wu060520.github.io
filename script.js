function getCareerAdvice() {
    const adviceList = [
        "考虑实习机会，积累实际工作经验。",
        "参加职业规划讲座，了解行业动态。",
        "学习新技能，提升自己的竞争力。",
        "建立职业网络，与行业内的专业人士交流。",
        "考虑继续深造，提升学历和专业能力。"
    ];

    const randomAdvice = adviceList[Math.floor(Math.random() * adviceList.length)];
    document.getElementById('advice-text').innerText = randomAdvice;
}

document.getElementById('contact-form').addEventListener('submit', function(event) {
    event.preventDefault();
    alert('感谢您的留言，我们会尽快与您联系！');
});