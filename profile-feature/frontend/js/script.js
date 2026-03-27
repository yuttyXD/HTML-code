$(document).ready(function() {
    $('#profileForm').on('submit', function(e) {
        e.preventDefault();
        
        // 修正：添加 /profile-feature 前缀（关键修复）
        $.ajax({
            url: '/profile-feature/backend/api.php',
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                console.log('Success:', response);
                alert('Profile created successfully!');
            },
            error: function(error) {
                console.error('Error:', error);
                alert('An error occurred while creating the profile.');
            }
        });
    });
});