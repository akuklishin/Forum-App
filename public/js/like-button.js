document.addEventListener('DOMContentLoaded', function () {

    const likeButtons = document.querySelectorAll('#like-button');

    likeButtons.forEach(likeButton => {
        const isLiked = likeButton.getAttribute('data-like') === 'true';
        const icon = likeButton.querySelector('i');

        setButtonIcon(icon, isLiked);

        likeButton.addEventListener('click', function (event) {
            event.preventDefault(); // prevent default form submission behavior to reload the page
            toggleLike.call(this);
            //toggleCommentLike.call(this);
        });

        

    });

    const commentlikeButtons = document.querySelectorAll('#comment-like-button');

    commentlikeButtons.forEach(commentlikeButtons => {
        const isLiked = commentlikeButtons.getAttribute('data-like') === 'true';
        const icon = commentlikeButtons.querySelector('i');

        setButtonIcon(icon, isLiked);

        commentlikeButtons.addEventListener('click', function (event) {
            event.preventDefault(); // prevent default form submission behavior to reload the page
            //toggleLike.call(this);
            toggleCommentLike.call(this);
        });

        

    });

    function setButtonIcon(icon, isLiked) {
        if (isLiked) {
            icon.classList.remove('bi-arrow-up-circle');
            icon.classList.add('bi-arrow-up-circle-fill');
        } else {
            icon.classList.remove('bi-arrow-up-circle-fill');
            icon.classList.add('bi-arrow-up-circle');
        }
    }

    function toggleLike() {

        const isLiked = this.getAttribute('data-like') === 'true';
        this.setAttribute('data-like', !isLiked);
        this.classList.toggle('liked');

        // also switch button icon
        const icon = this.querySelector('i');
        setButtonIcon(icon, !isLiked);

        // also 'fake' update score for additional feedback so we dont have to reload the page
        const scoreText = this.closest('.row').querySelector('.score-text');
        const scoreCount = parseInt(scoreText.innerText);

        if (isLiked) {
            scoreText.innerText = scoreCount - 1;
        } else {
            scoreText.innerText = scoreCount + 1;
        }

        // send a post request to the server to actually update the vote count
        const postId = this.getAttribute('data-post-id');
        fetch(`/votepost/${postId}`, {

            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }

        })

        .then(response => {
            console.log(response);
        })

        .catch(error => {
            console.error(error);
        });

    }

    function toggleCommentLike(){
        const isLiked = this.getAttribute('data-like') === 'true';
        this.setAttribute('data-like', !isLiked);
        this.classList.toggle('liked');

        // also switch button icon
        const icon = this.querySelector('i');
        setButtonIcon(icon, !isLiked);

        // also 'fake' update score for additional feedback so we dont have to reload the page
        const scoreText = this.closest('.row').querySelector('.score-text');
        const scoreCount = parseInt(scoreText.innerText);

        if (isLiked) {
            scoreText.innerText = scoreCount - 1;
        } else {
            scoreText.innerText = scoreCount + 1;
        }

    //     // send a post request to the server to actually update the comment vote count
        const commentId = this.getAttribute('data-comment-id');
        fetch(`/votecomment/${commentId}`, {

            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')

            }
        })

        .then(response => {
            console.log(response);
        })

        .catch(error => {
            console.error(error);
        });
    }
});
