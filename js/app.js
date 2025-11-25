/* ===========================
   NAVBAR PROFILE + SEARCH LOGIC
=========================== */
//test
// Toggle profile dropdown
let profile = document.querySelector('.nav .flex .profile');
let search = document.querySelector('.nav .flex .search-form');

document.querySelector('#user-btn').onclick = () => {
    if (profile) {
        profile.classList.toggle('active');
    }
    if (search) {
        search.classList.remove('active');
    }
};

// Toggle search box
document.querySelector('#search-btn').onclick = () => {
    if (search) {
        search.classList.toggle('active');
    }
    if (profile) {
        profile.classList.remove('active');
    }
};

// Close menus on scroll
window.addEventListener('scroll', () => {
    if (profile) profile.classList.remove('active');
    if (search) search.classList.remove('active');
});

document.addEventListener("DOMContentLoaded", () => {

    const btn = document.getElementById("generateSummaryBtn");
    if (!btn) return;

    btn.addEventListener("click", () => {

        let contentId = btn.getAttribute("data-content-id");

        // 🔵 Show spinner while generating
        document.getElementById("summaryOutput").innerHTML = `
            <div class="ai-box">
                <div class="ai-spinner"></div>
                <div class="spinner-text">Generating summary & quiz...</div>
            </div>
        `;

        fetch("ai_summary.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: "content_id=" + contentId
        })
        .then(res => res.json())
        .then(data => {

            if (data.error) {
                alert(data.error);
                return;
            }

            // 🟣 Display final styled summary UI
            document.getElementById("summaryOutput").innerHTML = `
                <div class="ai-box">
                    <button class="ai-close-btn" onclick="document.getElementById('summaryOutput').innerHTML=''">×</button>

                    <h4 class="ai-label">SUMMARY</h4>
                    <p>${data.summary}</p>

                    <h4 class="ai-label">KEY POINTS</h4>
                    <ul>${data.key_points.map(p => `<li>${p}</li>`).join('')}</ul>

                    <h4 class="ai-label">QUIZ</h4>
                    <ol>${data.quiz.map(q => `<li>${q}</li>`).join('')}</ol>
                </div>
            `;
        })
        .catch(() => {
            alert("Something went wrong. Try again.");
        });

    });

});
