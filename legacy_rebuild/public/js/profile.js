const Profile = {
    async init() {
        const form = document.getElementById('profile-form');
        if (!form) return;

        try {
            const response = await App.request('profile/show.php');
            const user = response.data;

            document.getElementById('name').value = user.name;
            document.getElementById('email').value = user.email;
            document.getElementById('student_id').value = user.student_id || 'N/A';
        } catch (error) {
            App.showToast('Failed to load profile', 'error');
        }

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = form.querySelector('button');
            btn.disabled = true;
            btn.innerText = 'Saving...';

            const formData = new FormData(form);
            const data = Object.fromEntries(formData.entries());

            try {
                await App.request('profile/update.php', 'POST', data);
                App.showToast('Profile updated.');
                // Update local storage user if needed
            } catch (error) {
                const msg = error.errors ? Object.values(error.errors)[0] : (error.message || 'Failed to update');
                App.showToast(msg, 'error');
            } finally {
                btn.disabled = false;
                btn.innerText = 'Save';
            }
        });
    }
};
