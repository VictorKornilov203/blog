// Гамбургер-меню
document.addEventListener('DOMContentLoaded', function() {
    const hamburger = document.getElementById('hamburger');
    const navMenu = document.getElementById('nav-menu');
    
    if (hamburger && navMenu) {
        hamburger.addEventListener('click', function() {
            navMenu.classList.toggle('active');
        });
    }
    
    // Обработка формы комментария
    const commentForm = document.getElementById('comment-form');
    if (commentForm) {
        commentForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const messageDiv = document.getElementById('comment-message');
            
            try {
                const response = await fetch('ajax/add_comment.php', {
                    method: 'POST',
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                    // Добавляем комментарий в список
                    const commentsList = document.getElementById('comments-list');
                    const newComment = document.createElement('div');
                    newComment.className = 'comment';
                    newComment.id = `comment-${data.comment.id}`;
                    newComment.innerHTML = `
                        <div class="comment-header">
                            <strong>${data.comment.username}</strong>
                            <span>${data.comment.created_at}</span>
                        </div>
                        <div class="comment-content">${data.comment.content}</div>
                        <div class="comment-actions">
                            <button class="like-btn" data-comment-id="${data.comment.id}">
                                ❤️ <span class="likes-count">${data.comment.likes}</span>
                            </button>
                        </div>
                    `;
                    
                    commentsList.insertBefore(newComment, commentsList.firstChild);
                    
                    // Очищаем форму
                    document.getElementById('comment-content').value = '';
                    
                    messageDiv.innerHTML = '<div class="alert success">Комментарий добавлен</div>';
                    setTimeout(() => {
                        messageDiv.innerHTML = '';
                    }, 3000);
                } else {
                    messageDiv.innerHTML = `<div class="alert error">${data.message}</div>`;
                }
            } catch (error) {
                console.error('Error:', error);
                messageDiv.innerHTML = '<div class="alert error">Ошибка при отправке</div>';
            }
        });
    }
    
    // Обработка лайков
    document.addEventListener('click', async function(e) {
        if (e.target.classList.contains('like-btn') || e.target.parentElement.classList.contains('like-btn')) {
            const likeBtn = e.target.classList.contains('like-btn') ? e.target : e.target.parentElement;
            const commentId = likeBtn.dataset.commentId;
            
            try {
                const response = await fetch('ajax/like.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `comment_id=${commentId}`
                });
                
                const data = await response.json();
                
                if (data.success) {
                    const likesSpan = likeBtn.querySelector('.likes-count');
                    likesSpan.textContent = data.likes;
                }
            } catch (error) {
                console.error('Error:', error);
            }
        }
    });
});